<?php

/*
 * Developer: Ghanshyam Sharma
 * Date : 2021-11-10
 * Purpose : Generate Attendance calculations
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\AttendanceTiming;
use App\Models\AttendanceDetail;
use DB;

class GenerateAttendence extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate_attendance:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        /*
         * Check cron already execute if already execute in a day then no record will insert into attendance
         */
        $checkCronStatus = DB::table('attendances')
                ->select("id")
                ->where("date", "=", date("Y-m-d"))
                ->first();

        if (!isset($checkCronStatus->id)) {
            /*
             * GEt record from Quotation and Project quotation
             */
            echo '<pre>';
            $day = date("N");
            $day = $day == 7 ? 0 : $day; //Condition for sunday
            $dataQuotation = DB::table('quotation_deployment as qd')
                    ->join('project_quotations as pq', 'pq.id', '=', 'qd.quotation_id')
                    ->join('projects as p', 'pq.id', '=', 'p.active_quotation_id')
                    ->select("p.id as project_id", "project_name")
                    ->where("is_deleted", "=", '0')
                    ->where("is_converted", "!=", '0')
                    ->whereNotIn('pq.status', array('8', '9'))
                    ->groupBy("pq.project_id")
                    ->get();

            foreach ($dataQuotation as $key => $val) {
                /*
                 * Call function to check deployeement available or not
                 */
                $checkavailablity = $this->checkDeploymentAvailablity($val->project_id, $day);
                /*
                 * If value exist then insert record into attendance otherwise skip records
                 */
                if (!empty($checkavailablity)) {
                    /*
                     * Setup code for attendance table
                     */
                    $attendance = new Attendance;
                    $attendance->project_id = $val->project_id;
                    $attendance->date = date("Y-m-d");
                    $attendance->save();
                    $attendanceId = $attendance->id;
                    /*
                     * Get project according to type 
                     */
                    $dataInner = DB::table('quotation_deployment as qd')
                            ->join('project_quotations as pq', 'pq.id', '=', 'qd.quotation_id')
                            ->join('projects as p', 'pq.id', '=', 'p.active_quotation_id')
                            ->select(DB::raw('pq.project_id'), "project_name", "qd.id", "qd.quotation_id", "qd.type", "qd.days", "qd.start_time_1", "qd.end_time_1", "qd.manpower_1", "qd.start_time_2", "qd.end_time_2", "qd.manpower_2")
                            ->where("is_deleted", "=", '0')
                            ->where("pq.project_id", "=", $val->project_id)
                            ->whereRaw("CASE WHEN  frequency IS NULL then  find_in_set('$day',qd.days) ELSE 1=1 end")
                            ->get();
                    /*
                     * Loop for get project by service type
                     */
                    foreach ($dataInner as $value) {
                        /*
                         * Get record of employee
                         */
                        $dataSetEmployee = $this->getEmployee($val->project_id, $day, $value->type);
// '-=============== EMPLOYEE Wise data save==========';
                        if (count($dataSetEmployee) > 0) {
                            foreach ($dataSetEmployee as $valueEmp) {
                                /*
                                 * New code for skip employee,If not active Employee
                                 */
                                if (!empty($valueEmp->employee_id)) {
                                    $empInner = DB::table('users')
                                            ->select('first_name', "email", "id", "address", "status")
                                            ->where("is_deleted", "=", '0')
                                            ->where("id", "=", $valueEmp->employee_id)
                                            ->first();
                                    if (isset($empInner->status) && $empInner->status != '1') {
                                        continue;
                                    }
                                }
                                /*
                                 * END
                                 */
                                $refil = '';
                                if (!empty($valueEmp->employee_id)) {
                                    $refil = $this->employee_relif($valueEmp->employee_id);
                                }
                                $refil_status = '0';

                                $attendance_details = new AttendanceDetail();
                                $attendance_details->attendance_id = $attendanceId;
                                if (!empty($refil)) {
                                    $refil_status = '1';
                                    $attendance_details->relief_employee_id = $refil;
                                }
                                $attendance_details->is_relief = $refil_status;
                                $attendance_details->employee_id = $valueEmp->employee_id;
                                $attendance_details->deployment_type = $value->type;
                                $attendance_details->save();
//  }
                            }
                        }
// '=====================End  EMPLOYEE Wise data save============';
                        /*
                         * END
                         */
                        /*
                         * Check first deployeement time sloats available
                         */
                        if (!empty($value->start_time_1) && !empty($value->end_time_1) && !empty($value->manpower_1)) {

                            /*
                             * Setup code for attendance timing
                             */
                            $attendanceTiming = new AttendanceTiming;
                            $attendanceTiming->attendance_id = $attendanceId;
                            $attendanceTiming->type = $value->type;
                            $attendanceTiming->checkin_time = $value->start_time_1;
                            $attendanceTiming->checkout_time = $value->end_time_1;
                            /*
                             * Call function to calculate duration in hours
                             */
                            $duration = $this->calculateDuration($value->start_time_1, $value->end_time_1);
                            /*
                             * Calculate actual duration according to manpower
                             */
                            $attendanceTiming->duration = $duration * $value->manpower_1;
                            $attendanceTiming->save();
                            $attendanceTimingId = $attendanceTiming->id;
                        }
                        /*
                         * Check second deployeement available or not
                         */
                        if (!empty($value->start_time_2) && !empty($value->end_time_2) && !empty($value->manpower_2)) {
                            $duration = 0;
                            $attendanceTiming = new AttendanceTiming;
                            $attendanceTiming->attendance_id = $attendanceId;
                            $attendanceTiming->type = $value->type;
                            $attendanceTiming->checkin_time = $value->start_time_2;
                            $attendanceTiming->checkout_time = $value->end_time_2;
                            /*
                             * Call function to calculate duration in hours
                             */
                            $duration = $this->calculateDuration($value->start_time_2, $value->end_time_2);
                            /*
                             * Calculate actual duration according to manpower
                             */
                            $attendanceTiming->duration = $duration * $value->manpower_2;
                            $attendanceTiming->save();
                        }
                    }
                }
            }

            /*
             * End code
             */
            /*
             * Call function for add float attendance
             */
            $this->floatTeamAttendance();
        }
        /*
         * End code
         */
    }

    /*
     * Calculate Time in Hours
     * @param start time and end time
     * @return value in hours
     */

    public function calculateDuration($start_time, $end_time) {
        $start = strtotime($start_time);
        $end = strtotime($end_time);
        $mins = ($end - $start) / 60 / 60;
        return abs($mins);
    }

    /*
     * Get employee id 
     * @param Project id and by day
     * @return multiple employee
     */

    public function getEmployee($project_id, $day, $type = '') {
        $dataEmp = DB::table('stationed_schedules as ss')
                ->join('stationed_schedule_deployments as ssd', 'ssd.stationed_schedule_id', '=', 'ss.id')
                ->join('stationed_schedule_day_wise as ssdw', 'ssdw.stationed_schedule_deployment_id', '=', 'ssd.id')
                ->select('ss.service_type', 'ss.id', "ssd.employee_id", "ss.project_id", "ssdw.day", "ssdw.start_time", "ssdw.end_time")
                ->where('ss.status', '=', '1')
                ->where('ss.project_id', '=', $project_id)
                ->where('ssdw.day', '=', $day)
                ->where('ss.service_type', '=', "$type")
//                ->groupBy("ss.id")
                ->get();
        return $dataEmp;
    }

    /*
     * Check deployeement
     * @param Project id and by day
     * @return status is there deployeement available or not
     */

    public function checkDeploymentAvailablity($project_id, $day) {
        $dataEmp = DB::table('stationed_schedules as ss')
                ->join('stationed_schedule_deployments as ssd', 'ssd.stationed_schedule_id', '=', 'ss.id')
                ->join('stationed_schedule_day_wise as ssdw', 'ssdw.stationed_schedule_deployment_id', '=', 'ssd.id')
                ->select('ss.id')
                ->where('ss.status', '=', '1')
                ->where('ss.project_id', '=', $project_id)
                ->where('ssdw.day', '=', $day)
                ->groupBy("ss.id")
                ->first();
        return isset($dataEmp->id) ? 1 : 0;
    }

    /*
     * Add attendance for float team
     */

    public function floatTeamAttendance() {
        /*
         * GEt record from Quotation ,Projects and  Project quotation
         */
        $day = date("Y-m-d");
        $dataQuotation = DB::table('quotation_float_team_deployment as qd')
                ->join('project_quotations as pq', 'pq.id', '=', 'qd.quotation_id')
                ->join('projects as p', 'pq.id', '=', 'p.active_quotation_id')
                ->select(DB::raw('pq.project_id'), "project_name", "qd.type")
                ->where("is_deleted", "=", '0')
                ->where("is_converted", "!=", '0')
                ->whereNotIn('pq.status', array('8', '9'))
                ->groupBy("pq.project_id")
                ->get();
        /*
         * Loop for projects
         */
        foreach ($dataQuotation as $key => $val) {
            /*
             * Call function to check deployeement available or not
             */
            $checkavailablity = $this->checkDeploymentAvailablity_float($val->project_id, $day, $val->type);
            /*
             * If value exist then insert record into attendance otherwise skip records
             */

            if (!empty($checkavailablity)) {
                /*
                 * Setup code for attendance table
                 */
                $attendance = DB::table('attendances')
                        ->select("id")
                        ->where("date", date("Y-m-d"))
                        ->where("project_id", $val->project_id)
                        ->first();
                $attendanceId = isset($attendance->id) ? $attendance->id : '';
                /*
                 * Condition if attendance added from station deployement then get id and use that id
                 *  Otherwise insert new record into databse 
                 */
                if (empty($attendanceId)) {
                    $attendance = new Attendance;
                    $attendance->project_id = $val->project_id;
                    $attendance->date = $day;
                    $attendance->save();
                    $attendanceId = $attendance->id;
                }
                /*
                 * Get according to type
                 */
                $dataInner = DB::table('quotation_float_team_deployment as qd')
                        ->join('project_quotations as pq', 'pq.id', '=', 'qd.quotation_id')
                        ->join('projects as p', 'pq.id', '=', 'p.active_quotation_id')
                        ->select(DB::raw('pq.project_id'), "project_name", "qd.id", "qd.quotation_id", "qd.type", "qd.start_time", "qd.end_time", "qd.manpower")
                        ->where("is_deleted", "=", '0')
                        ->where("pq.project_id", "=", $val->project_id)
                        ->get();
                /*
                 * Get record of float team
                 */
                foreach ($dataInner as $value) {
                    $dataSetEmployee = $this->getFloatTeam($value->project_id, $day, $value->type);
                    $type = $value->type == '0' ? '2' : '3';
                    // '-=============== EMPLOYEE Wise data save==========';
                    $floatTeam = DB::table('float_teams')
                            ->select("*")
                            ->where("is_deleted", "=", '0')
                            ->where("id", "=", $dataSetEmployee->float_team_id)
                            ->first();
                    /*
                     * If Team deleted then skip that
                     */
                    if (isset($floatTeam->is_deleted) && $floatTeam->is_deleted == '0') {
                        if (isset($dataSetEmployee->float_team_id)) {
                            /*
                             * Get team members by team id
                             */
                            $day_c = date("N");
                            $day_c = $day_c == 7 ? 0 : $day_c; //Condition for sunday
                            $dataEmp = DB::table('float_team_employees as f')
                                    ->join('float_team_day_wise_schedule as td', 'f.id', '=', 'td.float_team_employee_id')
                                    ->select('employee_id')
                                    ->where('float_team_id', '=', $dataSetEmployee->float_team_id)
                                    ->where('is_deleted', '=', '0')
                                    ->where('day', '=', $day_c)
                                    ->get();
                            foreach ($dataEmp as $valueEmp) {
                                /*
                                 * New code for skip employee,If not active Employee
                                 */
                                if (!empty($valueEmp->employee_id)) {
                                    $empInner = DB::table('users')
                                            ->select('first_name', "email", "id", "address", "status")
                                            ->where("is_deleted", "=", '0')
                                            ->where("id", "=", $valueEmp->employee_id)
                                            ->first();
                                    if (isset($empInner->status) && $empInner->status != '1') {
                                        continue;
                                    }
                                }
                                /*
                                 * END
                                 */
                                $refil = '';

                                $refil_status = '0';
                                $attendance_details = new AttendanceDetail();
                                $attendance_details->attendance_id = $attendanceId;

                                $attendance_details->is_relief = $refil_status;
                                $attendance_details->deployment_type = $type;
                                $attendance_details->employee_id = $valueEmp->employee_id;
                                $attendance_details->save();
                            }
                        }
// '=====================End  EMPLOYEE Wise data save============';
                        /*
                         * END
                         */
                        /*
                         * Check first deployeement time sloats available
                         */
                        if (!empty($value->start_time) && !empty($value->end_time) && !empty($value->manpower)) {

                            /*
                             * Setup code for attendance timing
                             */

                            $attendanceTiming = new AttendanceTiming;
                            $attendanceTiming->attendance_id = $attendanceId;
                            $attendanceTiming->type = $type;
                            $attendanceTiming->checkin_time = $value->start_time;
                            $attendanceTiming->checkout_time = $value->end_time;
                            /*
                             * Call function to calculate duration in hours
                             */
                            $duration = $this->calculateDuration($value->start_time, $value->end_time);
                            /*
                             * Calculate actual duration according to manpower
                             */
                            $attendanceTiming->duration = $duration * $value->manpower;
                            $attendanceTiming->save();
                            $attendanceTimingId = $attendanceTiming->id;
                        }
                    }
                    /*
                     * END
                     */
                }
            }
        }
    }

    /*
     * Get Team id 
     * @param Project id and by day
     * @return multiple employee
     */

    public function getFloatTeam($project_id, $day, $type) {
        $dataEmp = DB::table('float_team_deployments as ftd')
                ->join('float_team_deployment_month_wise as ftdmw', 'ftdmw.float_team_deployment_id', '=', 'ftd.id')
                ->join('float_team_deployment_date_wise as ftddw', 'ftddw.float_team_deployment_month_wise_id', '=', 'ftdmw.id')
                ->select('float_team_id')
                ->where('ftd.project_id', '=', $project_id)
                ->where('ftddw.date', '=', $day)
                ->where('ftd.service_type', '=', $type)
                ->groupBy("ftddw.id")
                ->first();
        return $dataEmp;
    }

    /*
     * Check deployeement
     * @param Project id and by date
     * @return status is there deployeement available or not
     */

    public function checkDeploymentAvailablity_float($project_id, $day, $type) {
        $dataEmp = DB::table('float_team_deployments as ftd')
                ->join('float_team_deployment_month_wise as ftdmw', 'ftdmw.float_team_deployment_id', '=', 'ftd.id')
                ->join('float_team_deployment_date_wise as ftddw', 'ftddw.float_team_deployment_month_wise_id', '=', 'ftdmw.id')
                ->select('ftddw.id')
                ->where('ftd.project_id', '=', $project_id)
                ->where('ftddw.date', '=', $day)
//                ->where('ftd.service_type', '=', $type)
                ->groupBy("ftddw.id")
                ->first();
        return isset($dataEmp->id) ? 1 : 0;
    }

    /*
     * Function to get employee status for relif 
     */

    public function employee_relif($employee_id) {
        $dataEmp = DB::table('employee_leaves as el')
                ->join('employee_leave_details as eld', 'eld.employee_leave_id', '=', 'el.id')
                ->select('el.relief_personnel_id')
                ->where('el.employee_id', '=', $employee_id)
                ->first();
        return isset($dataEmp->relief_personnel_id) ? $dataEmp->relief_personnel_id : 0;
    }

}
