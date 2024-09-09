<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterNotification;
use App\Models\Notification;
use App\Models\EmployeePermit;

class EmployeePermitNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permit:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Employee Permit Expiry Notification';

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
        \Log::info("Cron is working fine Permit!");
        
        $employeePermits = EmployeePermit::join('users','users.id','employee_permits.employee_id')->join('employee_permit_details','employee_permit_details.id','employee_permits.active_permit_id')->select('employee_permits.employee_id','employee_permit_details.expiry_date','users.first_name','users.last_name','users.employee_type')->get();
        
        if(count($employeePermits) > 0){
          foreach($employeePermits as $employeePermit){
              
            $expiry_date = $employeePermit->expiry_date;
            $previous_date = date('d/m/Y', strtotime('-1 day', strtotime($expiry_date)));
            $current_date = date('d/m/Y');
            $date_flag = '';
                if($current_date == $previous_date){
                    $get_notification_title = MasterNotification::find(14);
                    $notifications = new Notification;
                    $notifications->receiver_id = '1';
                    $notifications->title = $get_notification_title->title;
                    $notifications->description = 'Permit Document of '.$employeePermit->first_name.' '.$employeePermit->last_name.' is going to expiry tomorrow';
                    $notifications->is_read = '0';
                    
                    if($employeePermit->employee_type == '1'){
                       $redirect_path = 'employee/employee/view/'.$employeePermit->employee_id.'#employee-permit-tab';
                    }else{
                        $redirect_path = 'employee/staff/view/'.$employeePermit->employee_id.'#employee-permit-tab';
                    }
                    $notifications->redirect_path = $redirect_path;
                    $notifications->save(); 
                    
                    send_web_notification($notifications);
                }
              
          }  
        }
        
        
        //return 0;
    }
}
