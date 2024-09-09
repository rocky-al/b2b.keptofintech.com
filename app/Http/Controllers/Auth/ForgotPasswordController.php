<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\MasterEmailTemplate;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon; 
use Mail;
use App\Models\Staff;
use App\Models\MasterCompanySetting;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    

    use SendsPasswordResetEmails;
    
    public function submitForgetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users,email,deleted_at,"NULL",status,"1"',
          ]);
          
          
          
          $email = $request->email;
          $user = Staff::where('email',$email)->where('status','1')->first();
         //create token
          $password_broker = app(PasswordBroker::class);
            //create reset password token 
          $token = $password_broker->createToken($user); 
          //store in password reset
          DB::table('password_resets')->insert([
              'email' => $request->email, 
              'token' => $token, 
              'created_at' => Carbon::now()
            ]);
         $company_detail = MasterCompanySetting::select('company_name','company_logo')->first();
         $logo = url('company_logo') . '/' . $company_detail->company_logo;
         $master_email = MasterEmailTemplate::find('5');
                $emailval = $master_email->description;
                $subject = $master_email->title;
                $employee = [
                    '@name' => $user->first_name,
                    '@url' => url('password/reset/'.$token.'?email='.$email),
                    '@logo' => $logo,
                    '@company' => $company_detail->company_name,
                    
                ];

                foreach ($employee as $key => $value) {
                        $emailval = str_replace($key, $value, $emailval);
                    }

                $this->sendEMail($emailval, $email, $subject);

                /* Mail::send([], [], function ($message) use ($emailval, $email, $subject) {
                        $message->to($email)
                                ->subject($subject)
                                ->setBody($emailval, 'text/html');
                    });
                */
                
          return back()->with('status', 'We have e-mailed your password reset link!');
      }
}
