<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use SendGrid;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function sendEMail($emailval, $email_id, $subject)
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom(getenv('SENDGRID_SENDER_ID'), "KEPTO FINTECH");
        $email->setSubject($subject);
        $email->addTo($email_id,"User");
        $email->addContent(
            "text/html", $emailval
        );

        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
 
        try {
            $response = $sendgrid->send($email,false, ['debug_mode' => false]);
            return response()->json("Email sent successfully");
 
        } catch (Exception $e) {
            return response()->json( 'Caught exception: '. $e->getMessage() ."\n");
        }
 
    }
}
