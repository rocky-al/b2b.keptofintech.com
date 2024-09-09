<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use App\Models\ReportedUser;
use App\Models\Transactions;
use Spatie\Permission\Models\Role;
use App\Exports\StaffExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use ImageResize, Auth, Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;


class StaffController extends Controller
{


    protected $page = 'staff';
    private const OPENSSL_CIPHER_NAME = "aes-128-cbc";
    private const CIPHER_KEY_LEN = 16;

    public function __construct(Request $request)
    {
        $this->model = new Staff();
        $this->sortableColumns = ['id', 'first_name', 'last_name', 'email', 'phone', 'created_at'];
    }

    public function txnStatusEnquiry()
    {
        $currentDateTime = date('Y-m-d H:i:s'); // Get the current date and time
        $futureDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime . '-30 minutes'));
        $txnData = Transactions::where([['status', "=", "Pending"], ['created_at', "<", $futureDateTime]])->first();
        if ($txnData) {
            $clientTxnId = $txnData->client_txn_id;
            $query = "clientCode=" . env('SubPaisa_ClientCode') . "&clientTxnId=" . $clientTxnId;
            $decText = $this->encrypt(env('SubPaisa_AuthenticationKEY'), env('SubPaisa_AuthenticationIV'), $query);
            // echo $decText;
            $curl = curl_init();

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'https://txnenquiry.sabpaisa.in/SPTxtnEnquiry/getTxnStatusByClientxnId',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                "clientCode":"' . env('SubPaisa_ClientCode') . '",
                "statusTransEncData":"' . $decText . '"
            }',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Content-Type: application/json'
                    ),
                )
            );
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);
            // print_r($response); 
            if (isset($response->statusResponseData)) {
                $decText = $this->decrypt(env('SubPaisa_AuthenticationKEY'), env('SubPaisa_AuthenticationIV'), $response->statusResponseData);
                $arrayData = explode("&", $decText);
                $keyValuePairs = [];

                foreach ($arrayData as $item) {
                    // list($key, $value) = explode('=', $item, 2); // limit explode to 2 parts to handle values containing "="
                    $keyValueCombo = explode('=', $item, 2);
                    // list($key, $value) = explode('=', $item, 2); // limit explode to 2 parts to handle values containing "="
                    $key = $value = "";

                    if (is_array($keyValueCombo)) {
                        if (isset($keyValueCombo[0])) {
                            $key = $keyValueCombo[0];
                        }
                        if (isset($keyValueCombo[1])) {
                            $value = $keyValueCombo[1];
                        }
                    }
                    $keyValuePairs[$key] = $value;
                }

                // print_r($keyValuePairs);


                if ($keyValuePairs['status'] != "null") {
                    $txnData->status = $keyValuePairs['status'];
                }
                if ($keyValuePairs['sabpaisaTxnId'] != "null" && $keyValuePairs['sabpaisaTxnId'] != null) {
                    $txnData->txn_id = $keyValuePairs['sabpaisaTxnId'];
                }
                if ($keyValuePairs['sabpaisaMessage'] != "null") {
                    $txnData->remark = $keyValuePairs['sabpaisaMessage'];
                }

                echo $keyValuePairs['status'];
            } else {
                $txnData->status = "Error";
                $txnData->remark = "Txn Not Found on payment getway";
                echo 'Txn Not Found on payment getway';
            }
            $txnData->save();
        } else {
            echo "No Txn Here";
        }
    }

    private static function fixKey($key)
    {

        if (strlen($key) < StaffController::CIPHER_KEY_LEN) {

            return str_pad("$key", StaffController::CIPHER_KEY_LEN, "0");
        }

        if (strlen($key) > StaffController::CIPHER_KEY_LEN) {

            return substr($key, 0, StaffController::CIPHER_KEY_LEN);
        }
        return $key;
    }

    static function encrypt($key, $iv, $data)
    {
        //echo 'Data value is :' .$data;
        //echo "<br>";
        $encodedEncryptedData = base64_encode(openssl_encrypt($data, StaffController::OPENSSL_CIPHER_NAME, StaffController::fixKey($key), OPENSSL_RAW_DATA, $iv));
        $encodedIV = base64_encode($iv);
        $encryptedPayload = $encodedEncryptedData . ":" . $encodedIV;
        //echo '$encryptedPayload value is :' .$encryptedPayload;
        return $encryptedPayload;
    }

    static function decrypt($key, $iv, $data)
    {

        $parts = explode(':', $data);
        //print_r($parts);                     //Separate Encrypted data from iv.
        $encrypted = $parts[0];
        $iv = isset($parts[1]) ? $parts[1] : "";
        $decryptedData = openssl_decrypt(base64_decode($encrypted), StaffController::OPENSSL_CIPHER_NAME, StaffController::fixKey($key), OPENSSL_RAW_DATA, base64_decode($iv));
        return $decryptedData;
    }

    public function paymnetForm(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());die;
        $userData = Staff::find(Auth::user()->id);
        $name = $userData->first_name . " " . $userData->last_name;


        $encData = null;

        $clientCode = env('SubPaisa_ClientCode');   // Please use the credentials shared by your Account Manager  If not, please contact your Account Manage
        $username = env('SubPaisa_Username');     // Please use the credentials shared by your Account Manager  If not, please contact your Account Manage
        $password = env('SubPaisa_Password');     // Please use the credentials shared by your Account Manager  If not, please contact your Account Manage
        $authKey = env('SubPaisa_AuthenticationKEY');      // Please use the credentials shared by your Account Manager  If not, please contact your Account Manage
        $authIV = env('SubPaisa_AuthenticationIV');       // Please use the credentials shared by your Account Manager  If not, please contact your Account Manage

        $payerAddress = '';


        // Set the characters you want to use in the random string
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        // Get the current timestamp
        $currentTimestamp = time();

        // Set the length of the random string
        $length = 10;

        // Initialize the random string
        $randomString = '';

        // Generate the random string
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Concatenate the timestamp to the random string
        $clientTxnId = $currentTimestamp . $randomString;
        $amount = ($request->amount) ? $request->amount : 10;
        $amountType = 'INR';
        $mcc = 5137;
        $channelId = 'W';
        $callbackUrl = env('SubPaisa_CallBack_URL');
        // Extra Parameter you can use 20 extra parameters(udf1 to udf20)
        //$Class='VIII';
        //$Roll='1008';

        $encData = "?clientCode=" . $clientCode . "&transUserName=" . $username . "&transUserPassword=" . $password . "&payerName=" . $name .
            "&payerMobile=" . $userData->phone . "&payerEmail=" . $userData->email . "&payerAddress=" . $payerAddress . "&clientTxnId=" . $clientTxnId .
            "&amount=" . $amount . "&amountType=" . $amountType . "&mcc=" . $mcc . "&channelId=" . $channelId . "&callbackUrl=" . $callbackUrl;
        //."&udf1=".$Class."&udf2=".$Roll;
        $data = $this->encrypt($authKey, $authIV, $encData);


        $txnAdd = new Transactions;
        $txnAdd->user_id = $userData->id;
        $txnAdd->name = $name;
        $txnAdd->email = $userData->email;
        $txnAdd->number = $userData->phone;
        $txnAdd->amount = $amount;

        $txnAdd->status = "Pending";

        $txnAdd->client_txn_id = $clientTxnId;
        $txnAdd->save();


        $html = '<form action="' . env('SubPaisa_URL') . '"method="post">
                    <input type="hidden" name="encData" value="' . $data . '" id="frm1">
                    <input type="hidden" name="clientCode" value ="' . $clientCode . '" id="frm2">
                    <div class="form-group">
                    <input class="btn btn-primary pull-right submit_button w-100" type="submit" id="submitButton" name="submit" style="height: 45px;">
                    </div>
                    
                 </form>';

        $res = array('code' => 200, 'html' => $html);
        return json_encode($res);
    }


    public function paymnetFormRazor(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        $userData = Staff::find(Auth::user()->id);
        $name = $userData->first_name . " " . $userData->last_name;
        

        $orderData = [
            'receipt'         => 'order_rcptid_11',
            'amount'          => $request->amount*100, // Amount in paise (₹500.00)
            'currency'        => 'INR',
            'payment_capture' => 1, // Automatic capture
        ];

        try {
            $order = $api->order->create($orderData);
            // dd($order)
            $this->log_save_new('order_start', $orderData, $order);
            return response()->json(['id' => $order->id, 'amount' => $order->amount, 'name' => $name, 'email' => $userData->email, 'contact' => $userData->phone]);
        } catch (\Razorpay\Api\Errors\BadRequestError $e) {
            Log::error('Bad Request Error: ' . $e->getMessage());
            return response()->json(['status' => 'failed', 'message' => 'Bad Request Error: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['status' => 'failed', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    // public function paymentCallback(Request $request)
    // {
    //     // Verify the payment
    //     $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
    //     dd($request->all());
    //     $paymentId = $request->input('razorpay_payment_id');
    //     $orderId = $request->input('razorpay_order_id');
    //     $signature = $request->input('razorpay_signature');

    //     $attributes = [
    //         'razorpay_order_id' => $orderId,
    //         'razorpay_payment_id' => $paymentId,
    //         'razorpay_signature' => $signature
    //     ];

    //     // try {
    //         $api->utility->verifyPaymentSignature($attributes);
    //         // Payment successful
    //         return response()->json(['status' => 'success']);
    //     // } catch (\Exception $e) {
    //     //     // Payment failed
    //     //     return response()->json(['status' => 'failed']);
    //     // }
    // }

    public function paymentCallback(Request $request)
    {
        // Retrieve parameters from the request
        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('razorpay_order_id');
        $signature = $request->input('razorpay_signature');
        // Check if all required parameters are present
        if (!$paymentId || !$orderId || !$signature) {
            Log::error('Missing parameters in payment callback', [
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'signature' => $signature,
            ]);
            return response()->json(['status' => 'failed', 'message' => 'Missing parameters']);
        }
        // Initialize Razorpay API
        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        $attributes = [
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature
        ];
        // try {
            // Verify the payment signature
            $api->utility->verifyPaymentSignature($attributes);
            // Log successful verification
            Log::info('Payment verification successful', $attributes);
            $payment = $api->payment->fetch($attributes['razorpay_payment_id']);
            $userData = Staff::find(Auth::user()->id);
            $name = $userData->first_name . " " . $userData->last_name;
            $txnAdd = new Transactions;
            $txnAdd->user_id = $userData->id;
            $txnAdd->name = $name;
            $txnAdd->email = $userData->email;
            $txnAdd->number = $userData->phone;
            $txnAdd->amount = $payment->amount/100;
            $txnAdd->status = ($payment->status == 'captured' ? "Success":'');
            $txnAdd->client_txn_id = $payment->order_id;
            $txnAdd->txn_id = $payment->id;
            $txnAdd->remark = $payment->error_description;
            $txnAdd->save();
            $this->log_save_new('payment', $attributes, $payment);
            // Payment successful
            return response()->json(['status' => 'success']);
        // } catch (\Exception $e) {
        //     // Log the error for debugging
        //     Log::error('Payment verification failed', [
        //         'error' => $e->getMessage(),
        //         'attributes' => $attributes
        //     ]);

        //     // Payment failed
        //     return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
        // }
    }



    public function paymnetCancel(Request $request)
    {
        Log::info($request->all());
        $txnUpdate = Transactions::where('txn_id', $request->txnid)->first();
        $txnUpdate->status = $request->status;
        $txnUpdate->remark = $request->error_Message;
        $txnUpdate->save();
        echo '<html>
        <head>
          <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
        </head>
          <style>
            body {
              text-align: center;
              padding: 40px 0;
              background: #EBF0F5;
              display: flex;
              align-content: space-around;
              flex-wrap: wrap;
              justify-content: space-around;
            }
              h1 {
                color: #b5b01a;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-weight: 900;
                font-size: 40px;
                margin-bottom: 10px;
              }
              p {
                color: #404F5E;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-size:20px;
                margin: 0;
              }
            b {
              color: #b5b01a;
              font-size: 100px;
              line-height: 200px;
              margin-left:-15px;
            }
            
          </style>
          <body>
            <div class="card">
            <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
              <b class="checkmark">!</b>
            </div>
              <h1>C ancelled</h1> 
              <p>Transaction has been cancelled<br/> we`ll be in touch shortly!</p>
            </div>
            <script>
            setTimeout(() =>{
                // window.location.href = "' . route('users') . '";
            },2000)
            </script>
          </body>
      </html>';
    }
    public function paymnetFail(Request $request)
    {
        $txnUpdate = Transactions::where('txn_id', $request->txnid)->first();
        $txnUpdate->status = $request->status;
        $txnUpdate->remark = $request->error_Message;
        $txnUpdate->save();

        echo '<html>
            <head>
                <link
                href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap"
                rel="stylesheet"
                />
            </head>
            <style>
                body {
                text-align: center;
                padding: 40px 0;
                background: #ebf0f5;
                display: flex;
                align-content: space-around;
                flex-wrap: wrap;
                justify-content: space-around;
                }
                h1 {
                color: #e74c3c; /* Updated to red for fail message */
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-weight: 900;
                font-size: 40px;
                margin-bottom: 10px;
                }
                p {
                color: #404f5e;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-size: 20px;
                margin: 0;
                }
                i {
                color: #e74c3c; /* Updated to red for fail message */
                font-size: 100px;
                line-height: 200px;
                margin-left: -15px;
                }
            </style>
            <body>
                <div class="card">
                <div
                    style="
                    border-radius: 200px;
                    height: 200px;
                    width: 200px;
                    background: #f8faf5;
                    margin: 0 auto;
                    "
                >
                    <i class="checkmark">✘</i> 
                </div>
                <h1>Failure</h1>
                <p>
                Transaction has failed,
                    please try again later.
                </p>
                </div>
                <script>
                setTimeout(() => {
                    // window.location.href = "' . route('users') . '";
                }, 2000);
                </script>
            </body>
            </html>';
    }
    public function paymnetSuccess(Request $request)
    {


        // Please use the credentials shared by your Account Manager  If not, please contact your Account Manage
        $authKey = env('SubPaisa_AuthenticationKEY');      // Please use the credentials shared by your Account Manager  If not, please contact your Account Manage
        $authIV = env('SubPaisa_AuthenticationIV');       // Please use the credentials shared by your Account Manager  If not, please contact your Account Manage


        $decText = null;
        $decText = $this->decrypt($authKey, $authIV, $request->encResponse);
        $arrayData = explode("&", $decText);
        $jsonData_res = json_encode($arrayData);
        // print_r($arrayData);
        $keyValuePairs = [];

        foreach ($arrayData as $keyAll => $item) {
            if ($keyAll > 0) {
                // echo $item ."<<<=======>>>>";
                $keyValueCombo = explode('=', $item, 2);
                // list($key, $value) = explode('=', $item, 2); // limit explode to 2 parts to handle values containing "="
                $key = $value = "";

                if (is_array($keyValueCombo)) {
                    if (isset($keyValueCombo[0])) {
                        $key = $keyValueCombo[0];
                    }
                    if (isset($keyValueCombo[1])) {
                        $value = $keyValueCombo[1];
                    }
                }
                $keyValuePairs[$key] = $value;
            }
        }
        // print_r($keyValuePairs);
        $status = $sabpaisaTxnId = $sabpaisaMessage = NULL;
        if ($keyValuePairs['status'] != "null") {
            $status = $keyValuePairs['status'];
        }
        if ($keyValuePairs['sabpaisaTxnId'] != "null" && $keyValuePairs['sabpaisaTxnId'] != null) {
            $sabpaisaTxnId = $keyValuePairs['sabpaisaTxnId'];
        }
        if ($keyValuePairs['sabpaisaMessage'] != "null") {
            $sabpaisaMessage = $keyValuePairs['sabpaisaMessage'];
        }
        $txnUpdate = Transactions::where('client_txn_id', $keyValuePairs['clientTxnId'])->first();
        $txnUpdate->status = $status;
        $txnUpdate->txn_id = $keyValuePairs['sabpaisaTxnId'];
        $txnUpdate->remark = $sabpaisaMessage;
        $txnUpdate->save();

        $length = 200;
        $randomString = Str::random($length);
        $formattedString = chunk_split($randomString, 20, '/');
        $formattedString = rtrim($formattedString, '/');
        $formattedString = $formattedString . '==';

        $data_aray = array(
            'clientCode' => 'KEPT89',
            'encResponse' => $formattedString
        );

        $jsonData_res_cl = json_encode($data_aray);

        DB::table('logs')->insert([
            'name' => 'payment',
            'request' => $jsonData_res_cl,
            'response' => $jsonData_res,
        ]);

        if ($status == "SUCCESS") {
            echo '<html>
                <head>
                <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
                </head>
                <style>
                    body {
                    text-align: center;
                    padding: 40px 0;
                    background: #EBF0F5;
                    display: flex;
                    align-content: space-around;
                    flex-wrap: wrap;
                    justify-content: space-around;
                    }
                    h1 {
                        color: #88B04B;
                        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                        font-weight: 900;
                        font-size: 40px;
                        margin-bottom: 10px;
                    }
                    p {
                        color: #404F5E;
                        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                        font-size:20px;
                        margin: 0;
                    }
                    i {
                    color: #9ABC66;
                    font-size: 100px;
                    line-height: 200px;
                    margin-left:-15px;
                    }
                    
                </style>
                <body>
                    <div class="card">
                    <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
                    <i class="checkmark">✓</i>
                    </div>
                    <h1>Success</h1> 
                    <p>Payment Successful</p>
                    </div>
                    <script>
                    setTimeout(() =>{
                        window.location.href = "' . route('users') . '";
                    },2000)
                    </script>
                </body>
            </html>';
        } else {
            echo '<html>
            <head>
                <link
                href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap"
                rel="stylesheet"
                />
            </head>
            <style>
                body {
                text-align: center;
                padding: 40px 0;
                background: #ebf0f5;
                display: flex;
                align-content: space-around;
                flex-wrap: wrap;
                justify-content: space-around;
                }
                h1 {
                color: #e74c3c; /* Updated to red for fail message */
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-weight: 900;
                font-size: 40px;
                margin-bottom: 10px;
                }
                p {
                color: #404f5e;
                font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
                font-size: 20px;
                margin: 0;
                }
                i {
                color: #e74c3c; /* Updated to red for fail message */
                font-size: 100px;
                line-height: 200px;
                margin-left: -15px;
                }
            </style>
            <body>
                <div class="card">
                <div
                    style="
                    border-radius: 200px;
                    height: 200px;
                    width: 200px;
                    background: #f8faf5;
                    margin: 0 auto;
                    "
                >
                    <i class="checkmark">✘</i> 
                </div>
                <h1>Failure</h1>
                <p>
                Transaction has failed,
                    please try again later.
                </p>
                </div>
                <script>
                    setTimeout(() =>{
                        window.location.href = "' . route('users') . '";
                    },2000)
                    </script>
            </body>
            </html>';
        }
    }
    public function index(Request $request)
    {

        if (Auth::user()->user_type == "1") {
            if ($request->ajax()) {
                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];

                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $sortableColumns = $this->sortableColumns;

                $start_date = ($request->get('start_date')) ? date('Y-m-d 00:00:01', strtotime($request->get('start_date'))) : date('Y-m-d 00:00:01', strtotime('-1 year', strtotime(date('Y-m-d'))));
                $end_date = ($request->get('end_date')) ? date('Y-m-d 23:59:59', strtotime($request->get('end_date'))) : date('Y-m-d 23:59:59');

                $s_data = $request->all();

                $totaldata = $this->getData($search, $sortableColumns[$orderby], $order, $s_data);

                $totaldata = $totaldata->count();
                $response = $this->getData($search, $sortableColumns[$orderby], $order, $s_data);
                $response->whereBetween('users.created_at', [$start_date, $end_date]);

                $response = $response->offset($start)->limit($limit)->orderBy('users.id', 'desc')->get();

                // $states = $this->AdmintransactionsStates();

                if (!$response) {
                    $data = [];
                    $paging = [];
                } else {
                    $data = $response;
                    $paging = $response;
                }

                $datas = [];
                $i = 1;

                foreach ($data as $value) {

                    $row['id'] = $start + $i;
                    $row['name'] = '<a href="/transactions?ref_=' . $value->id . '">' . $value->first_name . ' ' . $value->last_name . "</a>";
                    $row['phone'] = (!empty($value->country_code) ? $value->country_code . '-' : '') . $value->phone;
                    $row['email'] = $value->email;

                    $row['created_at'] = date('d-m-Y H:i', strtotime($value->created_at));

                    $edit = '';
                    $edit = '<div class="table-actions"><a href="' . url('/users/edit/' . $value->id) . '" data-toggle="tooltip" title="Edit"><i class="ik ik-edit-2 f-16 mr-1 text-green"></i></a> ';

                    $view = '';
                    // $view = '<a href="' . url('/users/view/' . $value->id) . '" data-toggle="tooltip" title="View"><i class="ik ik-eye f-16 text-green mr-1"></i></a> ';


                    $delete = '';
                    $delete = '<a href="#" data-toggle="tooltip" title="Delete" onclick="deleteItem(' . $value->id . ')"><i class="ik ik-trash-2 f-16 text-red "></i></a></div></div>';


                    $row['actions'] = createButton($edit . $view . $delete);


                    $datas[] = $row;
                    $i++;
                    unset($u);
                }
                $return = [
                    "draw" => intval($draw),
                    "recordsFiltered" => intval($totaldata),
                    "recordsTotal" => intval($totaldata),
                    "data" => $datas

                ];
                return $return;
            }
            $data = ['title' => ucfirst('Staff'), 'page' => $this->page];
            return view($this->page . '.list', $data);
        } else if (Auth::user()->user_type == "2") {
            $data = $this->Admintransactions();
            return $data;
        } else {
            $data = $this->transactions();
            return $data;
        }
    }


    public function Admintransactions()
    {

        $count = array();
        $count['success'] = Transactions::where([['status', "=", "SUCCESS"], ['user_id', Auth::user()->id]])->count();
        $count['failure'] = Transactions::where([['status', "=", "failure"], ['user_id', Auth::user()->id]])->count();
        $count['total'] = Transactions::where([['status', "!=", "Pending"], ['status', "!=", "ABORTED"], ['user_id', Auth::user()->id]])->count();
        $data = ['title' => ucfirst('Staff'), 'page' => $this->page, 'count' => $count];
        return view($this->page . '.admintxnList', $data);
    }

    public function transactions()
    {
        $count = array();
        $count['success'] = Transactions::where([['status', "=", "SUCCESS"], ['user_id', Auth::user()->id]])->count();
        $count['failure'] = Transactions::where([['status', "=", "failure"], ['user_id', Auth::user()->id]])->count();
        $count['total'] = Transactions::where([['status', "!=", "Pending"], ['status', "!=", "ABORTED"], ['user_id', Auth::user()->id]])->count();
        $data = ['title' => ucfirst('Staff'), 'page' => $this->page, 'count' => $count];
        return view($this->page . '.txnList', $data);
    }

    public function refund()
    {
        if (Auth::user()->user_type == 1) {
            $count = array();
            $count['success'] = Transactions::where([['refund_status', "=", "PENDING"], ['user_id', Auth::user()->id]])->count();
            $data = ['title' => ucfirst('Staff'), 'page' => $this->page, 'count' => $count];
            return view($this->page . '.refundList', $data);
        } else {
            return view('404');
        }
    }


    public function getRefundList(Request $request)
    {

        $limit = $request->input('length');
        $start = $request->input('start');
        $search = $request['search']['value'];

        $orderby = $request['order']['0']['column'];
        $order = $orderby != "" ? $request['order']['0']['dir'] : "";
        $draw = $request['draw'];
        $sortableColumns = $this->sortableColumns;


        $s_data = $request->all();
        $totaldata = $this->getDatarefund($search, $sortableColumns[$orderby], $order, $s_data);

        $totaldata = $totaldata->count();
        $response = $this->getDatarefund($search, $sortableColumns[$orderby], $order, $s_data);

        $response = $response->offset($start)->limit($limit)->orderBy('transactions.id', 'desc')->get();

        if (!$response) {
            $data = [];
            $paging = [];
        } else {
            $data = $response;
            $paging = $response;
        }

        $datas = [];
        $i = 1;
        foreach ($data as $value) {
            $row['id'] = $start + $i;
            $row['name'] = $value->name;
            $row['phone'] = (!empty($value->country_code) ? $value->country_code . '-' : '') . $value->number;
            $row['email'] = $value->email;
            $row['client_txn_id'] = $value->client_txn_id;
            $row['txn_id'] = ($value->txn_id != null && $value->txn_id != "null") ? $value->txn_id : "";
            $row['amount'] = $value->amount;
            $row['status'] = "<span title='" . $value->remark . "'>" . $value->status . "</span>";


            $row['refund'] = '';
            if ($value->status == 'SUCCESS' && $value->refund_status == 'PENDING') {
                $row['refund'] = '<a href="#" data-toggle="tooltip" title="Refund to user" onclick="refunduser(' . $value->id . ')"> ' . $value->refund_status . '<i class="fas fa-undo-alt text-primary"></i></a>';
            } else if ($value->refund_status != '') {
                $row['refund'] = $value->refund_status;
            }
            $row['created_at'] = date('d-m-Y H:i', strtotime($value->created_at));
            $datas[] = $row;
            $i++;
            unset($u);
        }

        $return = [
            "draw" => intval($draw),
            "recordsFiltered" => intval($totaldata),
            "recordsTotal" => intval($totaldata),
            "data" => $datas,
        ];
        return $return;
    }


    public function getDatarefund($search = null, $orderby = null, $order = null, $request = null)
    {

        $orderby = $orderby ? $orderby : 'transactions.id';
        $order = $order ? $order : 'desc';
        $response = Transactions::where([['refund_status', "!=", ""]])->orderBy($orderby, $order);
        $response = $response->where(function ($query) use ($search) {
            $query->where('transactions.name', 'LIKE', '%' . $search . '%')
                ->orwhere('transactions.email', 'LIKE', '%' . $search . '%')
                ->orwhere('transactions.number', 'LIKE', '%' . $search . '%')
                ->orwhere('transactions.txn_id', 'LIKE', '%' . $search . '%');
        });
        return $response;
    }


    public function getTxnList(Request $request)
    {

        $limit = $request->input('length');
        $start = $request->input('start');
        $search = $request['search']['value'];

        $orderby = $request['order']['0']['column'];
        $order = $orderby != "" ? $request['order']['0']['dir'] : "";
        $draw = $request['draw'];
        $sortableColumns = $this->sortableColumns;


        $s_data = $request->all();
        $totaldata = $this->getDataTxn($search, $sortableColumns[$orderby], $order, $s_data);

        $totaldata = $totaldata->count();
        $response = $this->getDataTxn($search, $sortableColumns[$orderby], $order, $s_data);


        $response = $response->offset($start)->limit($limit)->orderBy('transactions.id', 'desc')->get();
        if (!$response) {
            $data = [];
            $paging = [];
        } else {
            $data = $response;
            $paging = $response;
        }

        $datas = [];
        $i = 1;
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";die();
        foreach ($data as $value) {

            $row['id'] = $start + $i;
            $row['name'] = $value->name;
            $row['phone'] = (!empty($value->country_code) ? $value->country_code . '-' : '') . $value->number;
            $row['email'] = $value->email;
            $row['client_txn_id'] = $value->client_txn_id;
            $row['txn_id'] = ($value->txn_id != null && $value->txn_id != "null") ? $value->txn_id : "";
            $row['amount'] = $value->amount;
            $row['status'] = "<span title='" . $value->remark . "'>" . $value->status . "</span>";



            $edit = '';
            $edit = '<div class="table-actions"><a href="' . url('/users/edit/' . $value->id) . '" data-toggle="tooltip" title="Edit"><i class="ik ik-edit-2 f-16 mr-1 text-green"></i></a> ';

            $view = '';
            $view = '<a href="' . url('/users/view/' . $value->id) . '" data-toggle="tooltip" title="View"><i class="ik ik-eye f-16 text-green mr-1"></i></a> ';


            $delete = '';
            $delete = '<a href="#" data-toggle="tooltip" title="Delete" onclick="deleteItem(' . $value->id . ')"><i class="ik ik-trash-2 f-16 text-red "></i></a></div></div>';
            $row['refund'] = '';
            $curent_date = date('Y-m-d H:i:s');
            $check_date = date('Y-m-d H:i:s', strtotime($value->created_at . ' +24 hours'));
            $row['refund'] = '<a href="#" data-toggle="tooltip" title="Refund" onclick="refund(' . $value->id . ')"><i class="fas fa-undo-alt text-primary"></i></a>';



            // if($value->status == 'SUCCESS' && $value->refund_status == '' && $curent_date < $check_date){
            //     $row['refund'] = '<a href="#" data-toggle="tooltip" title="Refund" onclick="refund(' . $value->id . ')"><i class="fas fa-undo-alt text-primary"></i></a>';
            // }else if($value->refund_status != '') {
            //     $row['refund'] = $value->refund_status;
            // }

            $row['created_at'] = date('d-m-Y H:i', strtotime($value->created_at));
            // $row['actions'] = createButton($edit . $view . $delete);


            $datas[] = $row;
            $i++;
            unset($u);
        }

        $return = [
            "draw" => intval($draw),
            "recordsFiltered" => intval($totaldata),
            "recordsTotal" => intval($totaldata),
            "data" => $datas,
        ];
        return $return;
    }




    /* Function for get data */

    public function getData($search = null, $orderby = null, $order = null, $request = null)
    {
        $q = Staff::where('id', '!=', '1');
        $orderby = $orderby ? $orderby : 'users.id';
        $order = $order ? $order : 'desc';

        if ($search && !empty($search)) {
            $q->where(function ($query) use ($search) {
                $query->where('users.first_name', 'LIKE', '%' . $search . '%')
                    ->orwhere('users.last_name', 'LIKE', '%' . $search . '%')
                    ->orwhere('users.phone', 'LIKE', '%' . $search . '%')
                    ->orwhere('users.email', 'LIKE', '%' . $search . '%');
            });
        }


        if (isset($request['user_type'])) {
            $q->where('users.status', $request['user_type']);
        }

        if (isset($request['status'])) {
            $q->where('users.status', $request['status']);
        }

        $response = $q->orderBy($orderby, $order);
        return $response;
    }
    public function getDataTxn($search = null, $orderby = null, $order = null, $request = null)
    {
        $orderby = $orderby ? $orderby : 'transactions.id';
        $order = $order ? $order : 'desc';
        if (Auth::user()->user_type == "1" || Auth::user()->user_type == "2") {
            $response = Transactions::orderBy($orderby, $order);
            $response = $response->where(function ($query) use ($search) {
                $query->where('transactions.name', 'LIKE', '%' . $search . '%')
                    ->orwhere('transactions.email', 'LIKE', '%' . $search . '%')
                    ->orwhere('transactions.number', 'LIKE', '%' . $search . '%')
                    ->orwhere('transactions.txn_id', 'LIKE', '%' . $search . '%');
            });
            if (!empty($request['user_id'])) {
                $response = $response->where("user_id", $request['user_id']);
            }
        } else {
            $response = Transactions::where([['status', "!=", "Pending"], ['status', "!=", "Error"], ['user_id', Auth::user()->id]])->orderBy($orderby, $order);
            $response = $response->where(function ($query) use ($search) {
                $query->where('transactions.name', 'LIKE', '%' . $search . '%')
                    ->orwhere('transactions.email', 'LIKE', '%' . $search . '%')
                    ->orwhere('transactions.number', 'LIKE', '%' . $search . '%')
                    ->orwhere('transactions.txn_id', 'LIKE', '%' . $search . '%');
            });
        }

        return $response;
    }

    //add
    public function create()
    {
        $data = array();
        $data['roles'] = Role::where('id', '!=', 1)->pluck('name', 'id');
        // get all country code
        $data['country_code'] = DB::select("SELECT country_name,code from country_code");
        return view('staff/add_staff')->with($data);
    }

    //store data
    public function store(Request $request)
    {
        // create 
        $rules = [
            'first_name' => 'required|regex:(^([a-zA-z ]+)?$)',
            'email' => 'required|email:filter',
            'phone' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $res = array('code' => 201, 'msg' => $validator->messages()->first());
            return json_encode($res);
        }
        try {
            if (isset($request->id) && $request->id > 0) {
                $email_validate = Staff::where('email', request('email'))->where('id', '!=', $request->id)->whereNull('deleted_at')->first();
                $phone_validate = Staff::where('country_code', request('country_code'))->where('phone', request('phone'))->where('id', '!=', $request->id)->whereNull('deleted_at')->first();
            } else {
                $email_validate = Staff::where('email', request('email'))->whereNull('deleted_at')->first();
                $phone_validate = Staff::where('country_code', request('country_code'))->where('phone', request('phone'))->whereNull('deleted_at')->first();
            }
            if ($email_validate) {
                return json_encode(array('code' => '201', 'msg' => 'Your email address already exists.'));
            }
            if ($phone_validate) {
                return json_encode(array('code' => '201', 'msg' => 'Your phone number already exists.'));
            }

            //if id found then update else insert
            if (isset($request->id) && $request->id > 0) {
                //update
                $item = Staff::findOrFail($request->id);
                $item->first_name = $request->first_name;
                $item->last_name = $request->last_name;
                $item->email = $request->email;
                $item->phone = $request->phone;
                //$item->country_code = $request->country_code;

                //if profile image set then upload
                if ($profile_image = $request->file('profile_image')) {
                    $destination_path = 'users_profile';
                    $source_path = $profile_image;
                    $file_name = upload_file($destination_path, $source_path);
                    $item->profile_image = $file_name;

                    $only_file_name = str_replace("users_profile/", "", $file_name);
                    $target_path = 'public/storage/users_profile/medium/';
                    $uplode_image_path = public_path() . '/storage/users_profile/medium/';
                    $img = ImageResize::make($source_path->path());
                    $img->resize(100, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($uplode_image_path . $only_file_name);
                }


                $item->save();
                // assign new role to the user
                $item->syncRoles($request->role);
                if ($item) {
                    $res = array('code' => 200, 'msg' => 'Updated Successfully');
                } else {
                    $res = array('code' => 201, 'msg' => 'Failed! Try again');
                }
            } else {
                //store
                // create customer strip Id
                //$strip_customer_result = PaymentController::createCustomerOnStripe($request->email);

                $item = new Staff;
                // $pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 8);
                $item->password = Hash::make($request->password);
                $item->first_name = $request->first_name;
                $item->last_name = $request->last_name;
                $item->email = $request->email;
                $item->phone = $request->phone;
                //$item->country_code = $request->country_code;
                $item->email_verified_at = date('Y-m-d H:i:s');
                $item->status = '1';
                //$item->stripe_customer_id = (isset($strip_customer_result->id) ? $strip_customer_result->id : '');


                //if profile image set then upload
                if ($profile_image = $request->file('profile_image')) {
                    $destination_path = 'users_profile';
                    $source_path = $profile_image;
                    $file_name = upload_file($destination_path, $source_path);
                    $item->profile_image = $file_name;

                    $only_file_name = str_replace("users_profile/", "", $file_name);
                    $target_path = 'public/storage/users_profile/medium/';
                    $uplode_image_path = public_path() . '/storage/users_profile/medium/';
                    $img = ImageResize::make($source_path->path());
                    $img->resize(100, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($uplode_image_path . $only_file_name);
                } else {
                    $item->profile_image = "users_profile/default_user.png";
                }

                $item->save();

                // assign new role to the user
                $item->syncRoles($request->role);
                // mail sent to the register user 
                /* $master_email = MasterEmailTemplate::find('6');
                $emailval = $master_email->description;
                $subject = $master_email->title;
                $email = $request->email;

                $company = MasterCompanySetting::first();
                $logo = url('company_logo') . '/' . $company->company_logo;
                $employee = [
                    '@name' => $request->first_name,
                    '@your_username' => $request->email,
                    '@your_password' => $pass,
                    '@company' => $company->company_name,
                    '@logo' => $logo,
                ];

                foreach ($employee as $key => $value) {
                    $emailval = str_replace($key, $value, $emailval);
                }

                $this->sendEMail($emailval, $email, $subject); */
                /* Mail::send([], [], function ($message) use ($emailval, $email, $subject) {
                    $message->to($email)
                        ->subject($subject)
                        ->setBody($emailval, 'text/html');
                }); */

                if ($item) {
                    $res = array('code' => 200, 'msg' => 'Added Successfully');
                } else {
                    $res = array('code' => 201, 'msg' => 'Failed! Try again');
                }
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            $res = array('code' => 201, 'msg' => 'Something went wrong! Try again', $bug);
            //$res = array('code' => 200, 'msg' => 'Added Successfully');
        }
        return json_encode($res);
    }

    //store data
    public function passwordUpdate(Request $request)
    {
        // create 
        $rules = [
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $res = array('code' => 201, 'msg' => $validator->messages()->first());
            return json_encode($res);
        }
        try {

            //if id found then update else insert

            //update
            $item = Staff::findOrFail($request->id);
            $item->password = Hash::make($request->password);
            $item->save();
            // assign new role to the user
            $item->syncRoles($request->role);
            if ($item) {
                $res = array('code' => 200, 'msg' => 'Updated Successfully');
            } else {
                $res = array('code' => 201, 'msg' => 'Failed! Try again');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            $res = array('code' => 201, 'msg' => 'Something went wrong! Try again', $bug);
            //$res = array('code' => 200, 'msg' => 'Added Successfully');
        }
        return json_encode($res);
    }

    //get by id
    public function get_by_id($id, Request $request)
    {
        try {
            $data = array();
            $data['roles'] = Role::where('id', '!=', 1)->pluck('name', 'id');
            $data['staff_detail'] = $staff_detail = Staff::select('users.*')
                ->with('roles', 'permissions')->findOrFail($id);
            $data['user_role'] = $staff_detail->roles->first();
            // get all country code
            $data['country_code'] = DB::select("SELECT country_name,code from country_code");
            return view('staff.add_staff')->with($data);
        } catch (\Exception $e) {
            $res = array('code' => 201, 'msg' => 'Something went wrong! Try again' . $e);
        }
        return json_encode($res);
    }

    public function user_view($id, Request $request)
    {
        try {
            $data = array();
            $data['user_detail'] = $staff_detail = Staff::findOrFail($id);

            return view('staff/view_staff')->with($data);
        } catch (\Exception $e) {
            $res = array('code' => 201, 'msg' => 'Something went wrong! Try again' . $e);
        }
        return json_encode($res);
    }

    //update status
    public function update_status(Request $request)
    {
        // create user 
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            $res = array('code' => 201, 'msg' => 'All fields reuired');
        }
        try {
            //if id found then update else insert
            if (isset($request->id) && $request->id > 0) {
                //update
                $item = Staff::find($request->id);
                //update active/inactive status
                if (isset($request->type) && $request->type == 'status') {
                    $item->status = $request->value;
                    if ($request->value == '1') {
                        $item->email_verified_at = date('Y-m-d H:i:s');
                    }
                }
                $item->save();
                if ($item) {
                    if (isset($request->type) && $request->type == 'status') {
                        /* if ($request->value == "1") {
                             $master_email = MasterEmailTemplate::find('23');
                            $emailval = $master_email->description;
                            $subject = $master_email->title;
                            $email = $item->email;

                            $company = MasterCompanySetting::first();
                            $logo = url('company_logo') . '/' . $company->company_logo;
                            $itemdetail = [
                                '@name' => $item->first_name . ' ' . $item->last_name,
                                '@company' => $item->company_name,
                                '@logo' => $logo,

                            ];
                            foreach ($itemdetail as $key => $value) {
                                $emailval = str_replace($key, $value, $emailval);
                            }

                            Mail::send([], [], function ($message) use ($emailval, $email, $subject) {
                                $message->to($email)
                                    ->subject($subject)
                                    ->setBody($emailval, 'text/html');
                            }); 
                        } else {

                            $master_email = MasterEmailTemplate::find('22');
                            $emailval = $master_email->description;
                            $subject = $master_email->title;
                            $email = $item->email;

                            $company = MasterCompanySetting::first();
                            $logo = url('company_logo') . '/' . $company->company_logo;
                            $itemdetail = [
                                '@name' => $item->first_name . ' ' . $item->last_name,
                                '@company' => $item->company_name,
                                '@logo' => $logo,

                            ];
                            foreach ($itemdetail as $key => $value) {
                                $emailval = str_replace($key, $value, $emailval);
                            }

                            Mail::send([], [], function ($message) use ($emailval, $email, $subject) {
                                $message->to($email)
                                    ->subject($subject)
                                    ->setBody($emailval, 'text/html');
                            });
                        } */
                        $res = array('code' => 200, 'msg' => 'Updated Successfully');
                    }
                } else {
                    $res = array('code' => 201, 'msg' => 'Failed! Try again');
                }
            } else {
                $res = array('code' => 201, 'msg' => 'Failed! Try again');
            }
        } catch (\Exception $e) {
            $res = array('code' => 201, 'msg' => 'Something went wrong! Try again');
        }
        return json_encode($res);
    }

    // delete data
    public function delete(Request $request)
    {
        $id = $request->id;
        try {
            // Delete user
            $staff = Staff::findOrFail($id);
            $status = $staff->delete();

            if ($status === true) {

                return response()->json(['success' => 'User has been deleted Successfully', "status" => $status], 200);
            } else {
                return response()->json(['error' => 'Something went wrong', "status" => $status], 201);
            }
        } catch (Throwable $e) {
            report($e);
            return response()->json(['warning' => 'Something went wrong']);
            // return "Something went wrong";
        }
    }



    public function export()
    {

        return Excel::download(new StaffExport, 'staffexport.xlsx');
    }

    public function resizeImage($filename, $folder, $width, $height)
    {
        $source_path = $_SERVER['DOCUMENT_ROOT'] . '/public/storage/users_profile/' . $filename;
        $target_path = $_SERVER['DOCUMENT_ROOT'] . '/public/storage/users_profile/' . $folder;
        $img = ImageResize::make($source_path);
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($target_path . $filename);
    }


    public function blocked_users(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $search = $request['search']['value'];

            $orderby = $request['order']['0']['column'];
            $orderby = $request['columns'][$orderby]['name'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];

            // $sortableColumns = $this->sortableColumns;

            $start_date = ($request->get('start_date')) ? date('Y-m-d 00:00:01', strtotime($this->convertDate($request->get('start_date')))) : date('Y-m-d 00:00:01', strtotime('-1 year', strtotime(date('Y-m-d'))));
            $end_date = ($request->get('end_date')) ? date('Y-m-d 23:59:59', strtotime($this->convertDate($request->get('end_date')))) : date('Y-m-d 23:59:59');

            $s_data = $request->all();

            $totaldata = $this->getDataBlockedUser($search, $orderby, $order, $s_data);

            $totaldata = $totaldata->count();
            $response = $this->getDataBlockedUser($search, $orderby, $order, $s_data);
            $response->whereBetween('reported_users.created_at', [$start_date, $end_date]);

            $response = $response->offset($start)->limit($limit)->orderBy('reported_users.id', 'desc')->get(['reported_users.id', 'reported_users.created_at', 'u.first_name', 'u.last_name']);
            if (!$response) {
                $data = [];
                $paging = [];
            } else {
                $data = $response;
                $paging = $response;
            }

            $datas = [];
            $i = 1;

            foreach ($data as $value) {

                $row['id'] = $start + $i;
                $row['blocked_to'] = $value->blocked_to;
                $row['blocked_by'] = $value->blocked_by;
                $row['created_at'] = date('m-d-Y H:i', strtotime($value->created_at));
                $datas[] = $row;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($totaldata),
                "recordsTotal" => intval($totaldata),
                "data" => $datas,
            ];
            return $return;
        }
        $data = ['title' => ucfirst('Staff'), 'page' => $this->page];
        return view($this->page . '.blocked_list', $data);
    }

    public function getDataBlockedUser($search = null, $orderby = null, $order = null, $request = null)
    {
        $q = ReportedUser::selectRaw("u.id as id, u.first_name as blocked_to, blocked_by.first_name as blocked_by, reported_users.created_at as created_at")->leftJoin('users as u', 'u.id', '=', 'reported_users.blocked_to_user_id')
            ->leftJoin('users as blocked_by', 'blocked_by.id', '=', 'reported_users.blocked_by_user_id')
            ->withTrashed();
        $orderby = $orderby ? $orderby : 'reported_users.id';
        $order = $order ? $order : 'desc';

        if (isset($request['id'])) {
            $q->where('reported_users.blocked_by_user_id', $request['id']);
        }

        $response = $q->orderBy($orderby, $order);
        return $response;
    }


    function convertDate($dateString)
    {
        $dateArray = explode('-', $dateString);
        $year = $dateArray[2];
        $month = $dateArray[0];
        $day = $dateArray[1];
        $newDateString = $year . '-' . $month . '-' . $day;
        return $newDateString;
    }

    // refund status 

    public function update_refund_status(Request $request)
    {
        if ($request->ajax()) {
            $transactions_id = $request->input('id');
            $refund_status = 'PENDING';

            $Transactions = Transactions::find($transactions_id);
            if ($Transactions) {
                $Transactions->refund_status = $refund_status;
                $Transactions->save();
                return response()->json(['message' => 'Refund status updated successfully'], 200);
            } else {
                return response()->json(['error' => 'Transaction not found'], 404);
            }
        }
    }

    public function refunduser(Request $request)
    {
        $transactions_id = $request->input('id');
        $txnData = Transactions::where('id', $transactions_id)->first();

        if ($txnData) {
            $clientTxnId = $txnData->client_txn_id;
            $amount = $txnData->amount;
            $txn_id = $txnData->txn_id;
            $message = 'I Want my money to refund.';
            $query = "clientCode=" . env('SubPaisa_ClientCode') . "&amount=" . $amount . "&spTxnId=" . $txn_id . "&clientTxnId=" . $clientTxnId . "&message=" . $message;
            $encryptedQuery = $this->encrypt(env('SubPaisa_AuthenticationKEY'), env('SubPaisa_AuthenticationIV'), $query);

            $url = 'https://refundapi.sabpaisa.in/SabPaisaRefundApi/refund';
            $url .= '?clientCode=' . env('SubPaisa_ClientCode') . '&refundQuery=' . urlencode($encryptedQuery);

            $curl = curl_init();

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Content-Type: application/json'
                    ),
                )
            );
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);

            $refund_status = $result['message'];
            $Transactions = Transactions::find($transactions_id);
            if ($Transactions) {
                $Transactions->refund_status = $refund_status;
                $Transactions->save();
                return response()->json(['message' => 'Refund status updated successfully'], 200);
            } else {
                return response()->json(['error' => 'Something went wrong'], 404);
            }
        }
    }



    public function dashboardStates()
    {
        $states = $this->AdmintransactionsStates();
        return $states;
    }

    public function AdmintransactionsStates()
    {

        $current_date = date('Y-m-d H:i:s');

        $total_amount = array(
            'day' => 0,
            'week' => 0,
            'month' => 0
        );
        $today_start = date('Y-m-d 00:00:00', strtotime($current_date));
        $week_start = date('Y-m-d 00:00:00', strtotime('monday this week', strtotime($current_date)));
        $month_start = date('Y-m-01 00:00:00', strtotime($current_date));
        $month_end = date('Y-m-t 23:59:59', strtotime($current_date));

        $total_amount['day'] = Transactions::where('created_at', '>=', $today_start)
            ->where('created_at', '<=', $current_date)
            ->where('status', 'SUCCESS')
            ->sum('amount');

        $total_amount['week'] = Transactions::where('created_at', '>=', $week_start)
            ->where('created_at', '<=', $current_date)
            ->where('status', 'SUCCESS')
            ->sum('amount');

        $total_amount['month'] = Transactions::where('created_at', '>=', $month_start)
            ->where('created_at', '<=', $month_end)
            ->where('status', 'SUCCESS')
            ->sum('amount');

        return $total_amount;
    }

    public function log_save()
    {
        $transactions_data = Transactions::select('*')->get();
        //echo "<pre>";
        //print_r($transactions_data);die;
        // echo "<pre>";
        // print_r($transactions_data);die;
        foreach ($transactions_data as $t_data) {
            $data_sys = '7 \?1"J2"iBpayerEmail=' . $t_data->email . '&payerMobile=' . $t_data->number . '&clientTxnId=' . $t_data->client_txn_id . '&payerAddress=NA&amount=' . $t_data->amount . '&clientCode=KEPT89&paidAmount=' . $t_data->amount . '&paymentMode=UPI&bankName=BOB&amountType=INR&status=' . $t_data->status . '&statusCode=0300&challanNumber=null&sabpaisaTxnId=698960404240208958&sabpaisaMessage=IPAY0200005 - UPI VPA Address in invalid.&bankMessage=IPAY0200005 - UPI VPA Address in invalid.&bankErrorCode=IPAY0200005 - UPI VPA Address in invalid.&sabpaisaErrorCode=IPAY0200005 - UPI VPA Address in invalid.&bankTxnId=101202409538766202&transDate=Thu Apr 04 02:23:05 IST 2024&udf1=NA&udf2=NA&udf3=NA&udf4=NA&udf5=NA&udf6=NA&udf7=NA&udf8=NA&udf9=null&udf10=null&udf11=null&udf12=null&udf13=null&udf14=null&udf15=null&udf16=null&udf17=null&udf18=null&udf19=null&udf20=null';
            DB::table('logs')->insert([
                'name' => 'payment',
                'request' => '{client_code:KEPT89}',
                'response' => $data_sys,
            ]);
        }
    }

    public function log_save_new($type,  $request, $response)
    {
        $transactions_data = Transactions::select('*')->get();
        DB::table('logs')->insert(['name' =>  $type,  'request' => serialize($request), 'response' => serialize($response)]);
        return true;
    }
}
