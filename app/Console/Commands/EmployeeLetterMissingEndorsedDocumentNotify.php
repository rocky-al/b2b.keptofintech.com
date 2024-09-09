<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use App\Models\MasterNotification;
use App\Models\Notification;
use App\Models\Employee;
use App\Models\EmployeeOfferLetter;
use App\Models\EmployeeEmploymentLetter;
use App\Models\EmployeeWarningLetter;
use App\Models\EmployeeIncrementLetter;
use App\Models\MasterLetterSetting;



class EmployeeLetterMissingEndorsedDocumentNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missing_endorsed_document:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Employee Missing Endorse Document Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Cron is working fine!");
        

        //DB::enableQueryLog(); // Enable query log
        $offer_letters = EmployeeOfferLetter::select(['employee_id','created_by_id', 'approved_by_id', 'approved_at', DB::raw("'offer' as type")])->where([['status', '2'], ['is_delete', '0']])->WhereNull('endorsed_doc');
        $employment_letters = EmployeeEmploymentLetter::select(['employee_id','created_by_id', 'approved_by_id', 'approved_at', DB::raw("'employment' as type")])->where([['status', '2'], ['is_delete', '0']])->WhereNull('endorsed_doc');
        $warning_letters = EmployeeWarningLetter::select(['employee_id','created_by_id', 'approved_by_id', 'approved_at', DB::raw("'warning' as type")])->where([['status', '2'], ['is_delete', '0']])->WhereNull('endorsed_doc');
        $final_result = EmployeeIncrementLetter::select(['employee_id','created_by_id', 'approved_by_id', 'approved_at', DB::raw("'increment' as type")])->where([['status', '2'], ['is_delete', '0']])->WhereNull('endorsed_doc')
            ->union($offer_letters)
            ->union($employment_letters)
            ->union($warning_letters)
            ->get();
        //dd(DB::getQueryLog()); // Show results of log
       

        if (isset($final_result)) {
            if (count($final_result) > 0) {
                //get letter setting
                $letter_setting = MasterLetterSetting::select('endorse_missing_notification_to_superadmin','endorse_missing_notification_to_staff')->where([['id', '1']])->first();
                $hr_notification_day = (isset($letter_setting->endorse_missing_notification_to_staff) && !empty($letter_setting->endorse_missing_notification_to_staff) ? $letter_setting->endorse_missing_notification_to_staff:"2");
                $superadmin_notification_day = (isset($letter_setting->endorse_missing_notification_to_superadmin) && !empty($letter_setting->endorse_missing_notification_to_superadmin) ? $letter_setting->endorse_missing_notification_to_superadmin:"3");
                

                //offer letter template
                $get_offer_template = MasterNotification::find(16);
                //employment letter template
                $get_employment_template = MasterNotification::find(17);
                //warning letter template
                $get_warning_template = MasterNotification::find(18);
                //increment letter template
                $get_increment_template = MasterNotification::find(19); 
                //end
                foreach ($final_result as $final_result_value) {
                    //for get employee detail
                    $employeeDetail = Employee::select('id', 'first_name','last_name')->where([['id', $final_result_value->employee_id]])->first();
                    $employe_fullnamme = $employeeDetail->first_name.' '.$employeeDetail->last_name;
                    //end
                    $redirect_path ='';$title ='';$description ='';
                    if($final_result_value->type=='offer'){
                        $title =$get_offer_template->title;
                        $description =$get_offer_template->description;
                        $redirect_path ='employee/employee/view/'.$final_result_value->employee_id.'#employee-contract-tab';
                    }else if($final_result_value->type=='employment'){
                        $title =$get_employment_template->title;
                        $description =$get_offer_template->description;
                        $redirect_path ='employee/employee/view/'.$final_result_value->employee_id.'#employee-contract-tab';
                    }else if($final_result_value->type=='warning'){
                        $title =$get_warning_template->title;
                        $description =$get_offer_template->description;
                        $redirect_path ='employee/employee/view/'.$final_result_value->employee_id.'#correspondance-tab';
                    }else if($final_result_value->type=='increment'){
                        $title =$get_increment_template->title;
                        $description =$get_offer_template->description;
                        $redirect_path ='employee/employee/view/'.$final_result_value->employee_id.'#correspondance-tab';
                    }

                    $current_date = date('Y-m-d');
                    //send notification to superadmin                     
                    $notification_date_of_admin = date('Y-m-d', strtotime('+'.$superadmin_notification_day.' day', strtotime($final_result_value->approved_at)));
                    //check for date 
                    if ($current_date == $notification_date_of_admin) {                      
                        $notifications = new Notification;
                        $notifications->receiver_id = $final_result_value->approved_by_id;
                        $notifications->title = $title;
                        $notifications->description = str_replace("@Details",$employe_fullnamme, $description);
                        $notifications->is_read = '0';
                        $notifications->redirect_path = $redirect_path;
                        $notifications->save();
                        send_web_notification($notifications);
                    }


                    //send notification to hr
                    $notification_date_of_hr = date('Y-m-d', strtotime('+'.$hr_notification_day.' day', strtotime($final_result_value->approved_at)));
                    if ($current_date == $notification_date_of_hr) {                        
                        $notifications = new Notification;
                        $notifications->receiver_id = $final_result_value->created_by_id;
                        $notifications->title = $title;
                        $notifications->description = $description;
                        $notifications->is_read = '0';
                        $notifications->redirect_path = $redirect_path;
                        $notifications->save();
                        send_web_notification($notifications);
                    }
                }
            }
        }    //return 0;

    }
}
