<?php

/*
 * Developer: Ghanshyam Sharma
 * Date : 2021-10-26
 * Purpose : Generate Payroll
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use \App\Models\Project;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\EmployeeBonus;
use App\Models\PayrollCPFDetailSetting;
use App\Models\LoanDeduction;
use App\Models\EmployeeLoan;
use DB;

class GeneratePayroll extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate_payroll:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate payroll';

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
         * Get all employee list which come under this payroll
         */
        $month = date('Y-m', strtotime('-1 month', strtotime(date('Y-m-d'))));
        $title = "PAYROLL-" . date('F-Y', strtotime('-1 month', strtotime(date('Y-m-d'))));
        //$title = "PAYROLL-" . strtoupper(date("F-Y"));
        $payroll_exist = DB::table('payrolls')
                ->select('title')
                ->where('month', '=', $month)
                ->get();
        if (count($payroll_exist) > 0) {
            echo '<b> Payroll already generated</b>';
        } else {
            $attendance = DB::table('attendances as a')
                    ->join('attendance_details as ad', 'a.id', '=', 'ad.attendance_id')
                    ->join('users as e', 'e.id', '=', DB::raw(' (CASE WHEN ad.relief_employee_id IS NULL THEN ad.employee_id ELSE ad.relief_employee_id END) '))
                    ->select('agency', 'is_relief', 'relief_employee_id', DB::raw('(SELECT pq.project_name FROM projects p INNER JOIN project_quotations pq on pq.id=p.active_quotation_id WHERE p.id=a.project_id) as site_name'), 'a.project_id', 'e.id as employee_id', 'e.employee_type', 'e.first_name', 'e.last_name', DB::raw('concat(e.first_name," ",e.last_name) as name'), 'e.probation_period_type', 'e.probation_period', 'e.probation_start_date', 'e.probation_end_date', 'e.email', 'e.type', 'e.dob', 'e.bank_name', 'e.bank_account_no', 'e.hire_date', 'e.termination_resign_date', 'a.date', 'ad.employee_id', DB::raw('SUM(ad.employee_normal_duration) as employee_worked'), 'ad.employee_salary', 'ad.hourly_rate', DB::raw('SUM(ad.ot1_hours)as ot1_hrs'), DB::raw('MAX(ad.ot1_hourly_rate)as ot1_hourly_rate'), DB::raw('SUM(ad.ot2_hours) as ot2_hrs'), DB::raw('MAX(ad.ot2_hourly_rate) as ot2_hourly_rate'))
                    ->where('ad.work_status', '=', "2")
                    ->where(DB::raw('date_format(date,"%Y-%m")'), '=', $month)
                    ->groupBy("ad.employee_id")
                    ->get();
            /*
             * Start code to save data in main payroll table
             */

            $payroll = new Payroll;
            $payroll->title = $title;
            $payroll->status = '0';
            $payroll->month = $month;
            $payroll->save();
            $payrollId = $payroll->id;
            /*
             * Setup code for payroll details
             */
            $inc = 0;
            foreach ($attendance as $key => $val) {
                $inc++;
                $payrollDetails = new PayrollDetail;
                $payrollDetails->payroll_id = $payrollId;
                $emp_salary = $val->employee_salary;

                if ($val->is_relief != '1') {
                    $payrollDetails->employee_id = $val->employee_id;
                } else {
                    $val->employee_id = $val->relief_employee_id;
                    $payrollDetails->employee_id = $val->relief_employee_id;
                }
                $payrollDetails->project_id = $val->project_id;
                $payrollDetails->employee_name = $val->name;
                $payrollDetails->job_site = $val->site_name;
                $payrollDetails->type = $val->type;
                $payrollDetails->date_of_birth = $val->dob;
                $payrollDetails->start_date = $val->hire_date;
                $payrollDetails->end_date = $val->termination_resign_date;
                $payrollDetails->monthly_salary = $emp_salary;
                $payrollDetails->hourly_rate = $val->hourly_rate;
                $payrollDetails->bank_name = $val->bank_name;
                $payrollDetails->bank_account_no = $val->bank_account_no;
                $payrollDetails->remarks = "Payroll generate from cron.";
                $payrollDetails->ot_hrs = $val->ot1_hrs + $val->ot2_hrs;
                /*
                 * OT AMount
                 */
                $ot_amount_1 = $val->ot1_hrs * $val->ot1_hourly_rate;
                $ot_amount_2 = $val->ot2_hrs * $val->ot2_hourly_rate * $val->hourly_rate;
                $total_ot_amt = $ot_amount_1 + $ot_amount_2;
                $payrollDetails->ot_pay_1 = $ot_amount_1;
                $payrollDetails->ot_pay_2 = $ot_amount_2;
                /*
                 * END
                 */
                /*
                 * Check User probabation period
                 */
                $prorated_worked_hrs = 0;
                $leave_hrs = 0;
                $total_worked_hrs = 0;
                /*
                 * Get employee probabation period
                 */
                if (isset($val->probation_period_type) && $val->probation_period_type != '') {
                    if ($val->probation_period_type == '0' && $val->probation_period != '' && $val->termination_resign_date != '') {
                        $start_date = $val->termination_resign_date;
                        $end_date = date('Y-m-d', strtotime($val->termination_resign_date . "+" . $val->probation_period . " days"));
                    }
                    if ($val->probation_period_type == '1' && $val->probation_start_date != '' && $val->probation_end_date != '' && $val->termination_resign_date != '') {
                        $start_date = $val->probation_start_date;
                        $end_date = $val->probation_end_date;
                    }
                    if (isset($start_date)) {
                        $workedProb = DB::table('attendances as a')
                                ->join('attendance_details as ad', 'a.id', '=', 'ad.attendance_id')
                                ->select(DB::raw("count(ad.id) as works"))
                                ->where('a.date', '>=', $start_date)
                                ->where('a.date', '<=', $end_date);
                        if ($val->is_relief != '1') {
                            $workedProb = $workedProb->where('ad.employee_id', '=', $val->employee_id);
                        } else {
                            $workedProb = $workedProb->where('ad.relief_employee_id', '=', $val->employee_id);
                        }
                        $workedProb = $workedProb->first();
                        $fullLeave = isset($workedProb->works) && !empty($workedProb->works) ? $workedProb->works : 0;
                        $prorated_worked_hrs = $fullLeave * config('constants.hour_in_a_day');
                    }
                }
                /*
                 * END
                 */
                $leaveRecord = DB::table('employee_leaves')
                        ->select(DB::raw("SUM(no_of_leave) as leaves"))
                        ->where('leave_type', '=', 1)
                        ->where('is_deleted', '=', '0')
                        ->where('employee_id', '=', $val->employee_id)
                        ->where('status', '=', '1')
                        ->first();
                if (isset($leaveRecord->leaves)) {
                    $leave_hrs = $leave_hrs * config('constants.hour_in_a_day');
                }
                $payrollDetails->prorated_worked_hrs = $prorated_worked_hrs;
                $payrollDetails->leave_hrs = $leave_hrs;
                $payrollDetails->total_worked_hrs = $val->employee_worked;
                /*
                 * Basic salary=Monthly salary - AWOL
                 */
                $basic_salary = $val->employee_worked * $val->hourly_rate;
                $payrollDetails->basic_salary = $basic_salary;

                /*
                 * End
                 */
                /*
                 * Get Bonus
                 */
                $bonus = DB::table('employee_bonus as b')
                        ->join('employee_bonus_detail as bd', 'bd.bonus_id', '=', 'b.id')
                        ->select(DB::raw("SUM(bd.amount) as amt"))
                        ->where('bd.employee_id', '=', $val->employee_id)
                        ->where('b.status', '=', '1')
                        ->where('b.is_deleted', '=', '0')
                        ->where(DB::raw('date_format(bd.created_at,"%Y-%m")'), '=', $month)
                        ->first();
                $bonus_amount = 0;
                if (isset($bonus->bonus)) {
                    $bonus_amount = $bonus->bonus;
                }
                $payrollDetails->bonus = $bonus_amount;

                /*
                 * End
                 */
                /*
                 * Additional wages 
                 */
                $total_additional_wages = $bonus_amount + $total_ot_amt;
                $payrollDetails->total_additional_wages = $total_additional_wages;
                /*
                 * End
                 */
                /*
                 * Total Gross Pay: Basic Salary + OT Pay + Bonus  [Consider PH settings and OT settings]
                 */
                $total_gross_pay = $basic_salary + $total_ot_amt + $bonus_amount;
                $payrollDetails->total_gross_pay = $total_gross_pay;
                /*
                 * End
                 */
                /*
                 * Get employee type to exclude Foreigner
                 */
                $agency_fund = 0;   
                $calculated_employee_cpf = 0; 
                $empType = DB::table('users')
                        ->select('type')
                        ->where('id', '=', $payrollDetails->employee_id)
                        ->first();
                if ($empType->type != '2') {//Exclude for Foreigner
                    /*
                     * CPF
                     */
                    $diff = (date('Y') - date('Y', strtotime($val->dob)));
                    $payroll_cpf = DB::table('setting_payroll_cpf_detail')
                            ->select('employer_wages', 'employee_wages')
                            ->where('age_from', '<', $diff)
                            ->where('age_to', '>=', $diff)
                            ->first();
                    $employer_cpf = isset($payroll_cpf->employer_wages) ? $payroll_cpf->employer_wages : 0;
                    $employee_cpf = isset($payroll_cpf->employee_wages) ? $payroll_cpf->employee_wages : 0;
                    $calculated_employer_cpf = 0;
                    $calculated_employee_cpf = 0;
                    if ($emp_salary > 0 && $employer_cpf > 0) {
                        $calculated_employer_cpf = ($emp_salary * $employer_cpf) / 100;
                    }
                    if ($emp_salary > 0 && $employee_cpf > 0) {
                        $calculated_employee_cpf = ($emp_salary * $employee_cpf) / 100;
                    }
                    $payrollDetails->employer_cpf = $calculated_employer_cpf;
                    $payrollDetails->employee_cpf_on_basic_salary = $calculated_employee_cpf;

                    /*
                     * END
                     */
                    /*
                     * Employee CPF 20% of total_additional_wages
                     */
                    $employee_cpf_on_additional_wages = 0;
                    if ($total_additional_wages > 0) {
                        $employee_cpf_on_additional_wages = ($total_additional_wages * config('constants.cpf_additional_wages')) / 100;
                    }
                    $payrollDetails->employee_cpf_on_additional_wages = $employee_cpf_on_additional_wages;
                    /*
                     * END
                     */
                    /*
                     * Get agency details
                     */
                    $agency = DB::table('setting_payroll_agencies as asp')
                            ->join('master_agencies as a', 'a.id', '=', 'asp.agency_id')
                            ->select('asp.monthly_total_wages_from', 'asp.monthly_total_wages_to', 'a.title', 'asp.contribution')
                            ->where('asp.agency_id', '=', $val->agency)
                            ->where('asp.monthly_total_wages_from', '<', $emp_salary)
                            ->where('asp.monthly_total_wages_to', '>=', $emp_salary)
                            ->first();
                    $agency_fund = 0;
                    if (isset($agency->title) && !empty($agency->title)) {
                        $agency_fund = $agency->contribution;
                    }
                    $payrollDetails->agency_id = $val->agency;
                    $payrollDetails->agency_fund = $agency_fund;
                    /*
                     * END
                     */
                    /*
                     * SDL 
                     */
                    $payroll_sdl = DB::table('setting_payroll_sdl')
                            ->select('sdl_payable')
                            ->where('monthly_total_wages', '=', $emp_salary)
                            ->first();
                    $sdl = 0;
                    if (isset($payroll_sdl->sdl_payable)) {
                        $sdl = $payroll_sdl->sdl_payable;
                    }
                    $payrollDetails->sdl = $sdl;
                    /*
                     * END
                     */
                }
                /*
                 * Calculate Advance
                 */
                /* Over all advance */
                $advance = DB::table('employee_advance_detail as ead')
                        ->join('employee_advances as ea', 'ea.id', '=', 'ead.advance_id')
                        ->select(DB::raw("SUM(ead.amount) as amt"), DB::raw("SUM(ea.amount) as main"))
                        ->where('employee_id', '=', $val->employee_id)
                        ->where('status', '=', '1')
                        ->where('is_deleted', '=', '0')
                        ->where(DB::raw('date_format(ea.created_at,"%Y-%m")'), '=', $month)
                        ->where(DB::raw('date_format(ea.created_at,"%Y-%m-%d")'), '!=', $month . '-20')
                        ->first();
                /* For Advance on 20 */
                $additional_advanceAmt = DB::table('employee_advance_detail as ead')
                        ->join('employee_advances as ea', 'ea.id', '=', 'ead.advance_id')
                        ->select(DB::raw("SUM(ead.amount) as amt"), DB::raw("SUM(ea.amount) as main"))
                        ->where('employee_id', '=', $val->employee_id)
                        ->where('status', '=', '1')
                        ->where('is_deleted', '=', '0')
                        ->where(DB::raw('date_format(ea.created_at,"%Y-%m-%d")'), '=', $month . '-20')
                        ->first();
                $advance_amount = 0;
                $additional_advance = 0;
                if (isset($advance->amt) && !empty($advance->amt)) {
                    $advance_amount = $advance->amt;
                }
                if (isset($additional_advanceAmt->amt) && !empty($additional_advanceAmt->amt)) {
                    $additional_advance = $additional_advanceAmt->amt;
                }
                $total_advance_taken = $additional_advance + $advance_amount;
                $payrollDetails->advance = $advance_amount;
                $payrollDetails->additional_advance = $additional_advance;
                $payrollDetails->total_advance = $additional_advance + $advance_amount;
                /*
                 * END
                 */
                /*
                 * Calculate loan
                 */

                $before_net_pay = $total_gross_pay - ($agency_fund + $calculated_employee_cpf + $total_advance_taken);
                $loan = 0;
                if ($before_net_pay > 0) {
                    $loanData = DB::table('employee_loans as el')
                            ->select('id', 'monthly_deduction', 'deduction_start_month', 'amount', DB::raw("(SELECT IF(SUM(loan_amount) IS NULL or SUM(loan_amount) = '', 0, SUM(loan_amount)) as total FROM `loan_deduction` WHERE loan_id=el.id) as paid_amount"))
                            ->where('employee_id', '=', $val->employee_id)
                            ->where('amount', '>', DB::raw("(SELECT IF(SUM(loan_amount) IS NULL or SUM(loan_amount) = '', 0, SUM(loan_amount)) as total FROM `loan_deduction` WHERE loan_id=el.id)"))
                            ->where(DB::raw('REPLACE(deduction_start_month,"-","")'), '<=', str_replace('-', '', $month))
                            ->get();
                    foreach ($loanData as $loan_key => $loan_val) {
                        $loan_paid_amount = 0;
                        //Check remaining amount
                        $remaining = $loan_val->amount - $loan_val->paid_amount;
                        if ($remaining >= $loan_val->monthly_deduction) {
                            $loan_paid_amount = $loan_val->monthly_deduction;
                        } else {
                            $loan_paid_amount = $remaining;
                        }
                        // Check current month salary Amount
                        if ($before_net_pay >= $loan_paid_amount) {
                            $before_net_pay = $before_net_pay - $loan_paid_amount;
                        } else {
                            //Check now salary amount is remaining greater then 0
                            if ($before_net_pay > 0) {
                                //Then pay there partially amount of loan 
                                $loan_paid_amount = $loan_paid_amount - $before_net_pay;
                                $before_net_pay = 0;
                            }
                        }
                        /*
                         * Insert into loan deduction
                         */
                        if ($loan_paid_amount > 0) {
                            $loandeduction = new LoanDeduction;
                            $loan = $loan + $loan_paid_amount;
                            $loandeduction->employee_id = $val->employee_id;
                            $loandeduction->payroll_id = $payrollId;
                            $loandeduction->loan_id = $loan_val->id;
                            $loandeduction->loan_amount = $loan_paid_amount;
                            $loandeduction->month = $month;
                            $loandeduction->save();
                            /*
                             * employee_loans
                             */
                            $updateStatus = $loan_val->paid_amount + $loan_paid_amount;
                            if ($updateStatus == $loan_val->amount) {
                                $loandePaystatus = EmployeeLoan::find($loan_val->id);
                                $loandePaystatus->loan_pay_status = '1';
                                $loandePaystatus->save();
                            }
                        }
                    }
                }
                /*
                 * END
                 */
                $payrollDetails->loan_amount = $loan;
                $net_pay = $total_gross_pay - ($agency_fund + $calculated_employee_cpf + $total_advance_taken + $loan);
                $payrollDetails->net_pay = $net_pay;
                $payrollDetails->save();
            }
            echo '<b> Record inserted ' . $inc . "</b>";
        }
    }

}
