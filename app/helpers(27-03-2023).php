<?php

//time format



function date_time_format($in_time = '', $format = '')
{
    if ($in_time == '' || $format == '') {
        return null;
    } else {
        $in_time = str_replace('/', '-', $in_time);
        return date($format, strtotime($in_time));
    }
}

//For convert number to words
function conver_number_to_words($number)
{
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety'
    );
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else
            $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
        "." . $words[$point / 10] . " " .
        $words[$point = $point % 10] : '';
    // $result . "Dollars  " . $points . " Paise";
    return strtoupper($result . "Dollars");
}
// create full word ordinal of number
function createFullWordOrdinal($number)
{
    $ord1     = array(1 => "first", 2 => "second", 3 => "third", 5 => "fifth", 8 => "eight", 9 => "ninth", 11 => "eleventh", 12 => "twelfth", 13 => "thirteenth", 14 => "fourteenth", 15 => "fifteenth", 16 => "sixteenth", 17 => "seventeenth", 18 => "eighteenth", 19 => "nineteenth");
    $num1     = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eightteen", "nineteen");
    $num10    = array("zero", "ten", "twenty", "thirty", "fourty", "fifty", "sixty", "seventy", "eighty", "ninety");
    $places   = array(2 => "hundred", 3 => "thousand", 6 => "million", 9 => "billion", 12 => "trillion", 15 => "quadrillion", 18 => "quintillion", 21 => "sextillion", 24 => "septillion", 27 => "octillion");

    $number = array_reverse(str_split($number));

    if ($number[0] == 0) {
        if ($number[1] >= 2)
            $out = str_replace("y", "ieth", $num10[$number[1]]);
        else
            $out = $num10[$number[1]] . "th";
    } else if (isset($number[1]) && $number[1] == 1) {
        $out = $ord1[$number[1] . $number[0]];
    } else {
        if (array_key_exists($number[0], $ord1))
            $out = $ord1[$number[0]];
        else
            $out = $num1[$number[0]] . "th";
    }

    if ((isset($number[0]) && $number[0] == 0) || (isset($number[1]) && $number[1] == 1)) {
        $i = 2;
    } else {
        $i = 1;
    }

    while ($i < count($number)) {
        if ($i == 1) {
            $out = $num10[$number[$i]] . " " . $out;
            $i++;
        } else if ($i == 2) {
            $out = $num1[$number[$i]] . " hundred " . $out;
            $i++;
        } else {
            if (isset($number[$i + 2])) {
                $tmp = $num1[$number[$i + 2]] . " hundred ";
                $tmpnum = $number[$i + 1] . $number[$i];
                if ($tmpnum < 20)
                    $tmp .= $num1[$tmpnum] . " " . $places[$i] . " ";
                else
                    $tmp .= $num10[$number[$i + 1]] . " " . $num1[$number[$i]] . " " . $places[$i] . " ";

                $out = $tmp . $out;
                $i += 3;
            } else if (isset($number[$i + 1])) {
                $tmpnum = $number[$i + 1] . $number[$i];
                if ($tmpnum < 20)
                    $out = $num1[$tmpnum] . " " . $places[$i] . " " . $out;
                else
                    $out = $num10[$number[$i + 1]] . " " . $num1[$number[$i]] . " " . $places[$i] . " " . $out;
                $i += 2;
            } else {
                $out = $num1[$number[$i]] . " " . $places[$i] . " " . $out;
                $i++;
            }
        }
    }
    return $out;
}
// Display numbers with ordinal suffix
function ordinal($number)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number . 'th';
    else
        return $number . $ends[$number % 10];
}

// Get Ago time from current date time 
function get_time_ago($datetime,$full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'y',
        'm' => 'month',
        'w' => 'w',
        'd' => 'd',
        'h' => 'h',
        'i' => 'm',
        's' => 's',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ''.$v.($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]); 
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . '' : '1s';
    
   // return $string ? implode(', ', $string) . ' ago' : '1s';
    /*$time_difference =  $time - strtotime(date('Y-m-d H:i:s'));
    //echo $time_difference; die;
    if ($time_difference < 1) {
        return '1s';
    }
    $condition = array(
        12 * 30 * 24 * 60 * 60 =>  'y',
        30 * 24 * 60 * 60       =>  'm',
        24 * 60 * 60            =>  'd',
        60 * 60                 =>  'h',
        60                      =>  'm',
        1                       =>  's'
    );

    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;

        if ($d >= 1) {
            $t = round($d);
            return  $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
        }
    } */
}

// get the image from S3 server and public folder 
function get_file_url($source_file_name)
{
    if (config('constants.storage_type') == 's3') {
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();

        $command = $client->getCommand('GetObject', [
            'Bucket' => 'ghdev-upload',
            'Key' => $source_file_name  // file name in s3 bucket which you want to access
        ]);

        $request = $client->createPresignedRequest($command, '+20 minutes');

        $image = (string) $request->getUri();
        return $image;
    } else {
        return Storage::url($source_file_name);
    }
}

// upload the image in the s3 folder and public folder
function upload_file($destination_path, $source_path)
{
    if (config('constants.storage_type') == 's3') {
        $res = Storage::disk('s3')->put(
            $destination_path,
            $source_path
        );
        return $res;
    } else {
        $res = Storage::disk('public')->put($destination_path, $source_path);
        return $res;
    }
}

// send web notifications
function send_web_notification($notifications)
{

    $notification_data = array('sender_id' => $notifications->receiver_id, 'message' => $notifications->title);
    $curl = curl_init();
    $socket_url = config('constants.socket_url') . '/send_notification';
    curl_setopt_array($curl, array(
        CURLOPT_URL => $socket_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($notification_data),
        CURLOPT_HTTPHEADER => array(
            // Set here requred headers
            "accept: */*",
            "accept-language: en-US,en;q=0.8",
            "content-type: application/json",
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
}

//create company info hearder
function company_info_header($company, $type = 'normal')
{
    if ($type == 'pdf') {
        $margin_left = "margin-left:15%;";
    } else {
        $margin_left = "";
    }
    return '<div class="company-header" style="' . $margin_left . 'font-size:14px;width: 100%;text-align: center;">
                    <div style="display:inline-block;margin-right:10px;">
                        <img src="' . asset('company_logo/') . '/' . $company->report_logo . '" style="max-height: 90px;display:inline-block"><br>
                        Reg No:: ' . $company->reg_no . '
                    </div>
                    <div style="display:inline-block;">
                        <span style="font-size:27px;font-weight:600;">' . $company->company_name . '</span><br>
                            <b>(' . $company->company_service . ')</b><br>' . $company->address . ' <br>
                                Tel: +' . $company->phone . '<br>
                                Email: ' . $company->email . ' <br>
                        </div>
                  </div>';
}




// send notification to android 

function send_android_notification_FCM($title, $description, $token, $redirection = "",  $notification_id = "", $show_action_button = "no",$other_data=array())
{
    if (!is_array($token)) {
        $token = array($token);
    }
    $SERVER_API_KEY = "AAAAexNgOn0:APA91bEp7CYOf_vONsIhInJ3QBuzde2IoD4tofMjz3N1j9DoE4psbtaz5dCtLZupWKerCHPb4JR1gCfUzZ3kZEVxQs73c_5-XBxTqwANCDBa2RmvOzO6vN5id_v6fU496uqT8DgkqFwR";
    /* $data = [
        "registration_ids" => $token,
        "data" => [
            "title" => $title,
            "body" => $description,
            "redirection" => $redirection,
            "group_id" => $group_id,
        ]
    ]; */

    $data = [
        "registration_ids" => $token,
        "notification" => [
            "title" => $title,
            "body" => $description,
            "sound" => "default",
            "description" => $description,
            "largeIcon" => "notification_icon",
            "smallIcon" => "ic_notification",
            "show_in_foreground" => true,
            "content_available" => true,
            "priority" => "high",
            "userInteraction" => false,
        ],
        "data" => [
            "description" => $description,
            "largeIcon" => "notification_icon",
            "smallIcon" => "ic_notification",
            "show_in_foreground" => true,
            "content_available" => true,
            "priority" => "high",
            "userInteraction" => false,
            "redirection" => $redirection,
            "notification_id" => $notification_id,
            "show_action_button" => $show_action_button,
            "order_product_id" => (isset($other_data['order_product_id']) ? $other_data['order_product_id'] : ''), 
            'sender_name'=> (isset($other_data['sender_name']) ? $other_data['sender_name'] : ''),
            'phone'=> (isset($other_data['phone']) ? $other_data['phone'] : ''),
            'sender_id'=> (isset($other_data['sender_id']) ? $other_data['sender_id'] : ''),
            'sender_image'=> (isset($other_data['sender_image']) ? $other_data['sender_image'] : ''),
            
        ],
    ];
    // "<pre>"; print_r($data); die;

    $dataString = json_encode($data);
    //echo  $dataString; die;
    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $rest = curl_exec($ch);

    // Close connection
    curl_close($ch);
    return $rest;
}


// CREATE BUTTON FUNCTION 
function createButton($buttons)
{
    return  $buttons;
}




//EDIT BUTTON FUNCTION 
function editButton($route, $parms)
{
    return '<li><a class="dropdown-item model_open"  type="button" url="' . customeRoute($route, $parms) . '"> Edit </a></li>';
}

//VIEW BUTTON FUNCTION 
function viewButton($route, $parms)
{
    return '<li><a class="dropdown-item model_open"  type="button" url="' . customeRoute($route, $parms) . '"> View </a></li>';
}

function customeRoute($route = null, $params = null)
{
    return route($route, $params);
}


// image upload 

function uploadSmallImage($image, $path, $height, $width)
{
    $image_data = $image;
    $name = $image_data->getClientOriginalName();
    $filename = time() . "-" .$name;
    $filename = str_replace(' ', '_', $filename);
    $filename = str_replace('-', '_', $filename);
    if (!file_exists($path)) { //Verify if the directory exists
        mkdir($path, 777, true); //create it if do not exists
    }
    $image_data_resize = \Image::make($image_data);
    $image_data_resize->resize($width,$height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path.'/' . $filename);



}







