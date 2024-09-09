<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterNotification;
use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectQuotation;
use App\Models\QuotationTerm;


class ProjectContractExpiryNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contract:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Project Contract Expiry Notification';

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
        
        $projects = Project::join('project_quotations','project_quotations.id','=','projects.active_quotation_id')->join('quotation_terms','quotation_terms.quotation_id','=','project_quotations.id')->select('project_quotations.project_name','quotation_terms.expiry_date','project_quotations.created_by_id as employee_id')->get();
        if(isset($projects)){
        if(count($projects) > 0){
           foreach($projects as $project){
               
            $expiry_date = $project->expiry_date;
            $previous_date = date('d/m/Y', strtotime('-30 day', strtotime($expiry_date)));
            $current_date = date('d/m/Y');
            //check for date 
                if($current_date == $previous_date){
                    $get_notification_title = MasterNotification::find(15);
                    $notifications = new Notification;
                    $notifications->receiver_id = $project->employee_id;
                    $notifications->title = $get_notification_title->title;
                    $notifications->description = $project->project_name.' project contract is going to expiry on '.date('d/m/Y', strtotime($expiry_date));
                    $notifications->is_read = '0';
                    $notifications->redirect_path = 'projects';
                    $notifications->save();
                    
                    send_web_notification($notifications);
                }
               
           } 
        }
        }    //return 0;
    }
}
