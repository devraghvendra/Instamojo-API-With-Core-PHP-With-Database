<?php 
include 'config.php';
if(!empty($_POST)){
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.instamojo.com/oauth2/token/');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $payload = array(
    'grant_type' => 'client_credentials',
    'client_id' => 'ojmu9nOO53seHWsJABWVkmD8qgOPOHmUO7qpgory',
    'client_secret' => 'icWRp1KWooJxuwl57y4Gr7Xzos2L3LfkuVxfBDh2uDjCjiTANUtmKXlJlBaIeXeQIraUZ53e0VFmVaj3ctEen5I6Z163gXwu0E3NrEuTXwvMpxoF93izK5vIrXD5BmNJ',
);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    $response = curl_exec($ch);
    curl_close($ch);
    $decodedText = html_entity_decode($response);
    $myArray = array(json_decode($response, true));
    if(isset($myArray[0]['error'])){
        $result = array('status' => 500, 'message' => $myArray[0]['error']);
        die(json_encode($result));
    }else{
        $access_token = $myArray[0]["access_token"];
        if(!empty($access_token)){
            $insertQuery = "insert into access_token (mobile,access_token) value ('$phone','$access_token')";
            $insertResult = mysqli_query($con,$insertQuery);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.instamojo.com/v2/payment_requests/');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$access_token,
            ));
            $payload = array(
            'purpose' => 'Wonder Women',
            'phone' => $phone,
            'amount' => $amount,
            'buyer_name' => $name,
            'send_email' => false,
            'send_sms' => false,
            'email' => $email,
            'allow_repeated_payments' => false,
        );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            $decodedText = html_entity_decode($response);
            $myArray = array(json_decode($response, true));
            if(isset($myArray[0]['message'])){
                $result = array('status' => 500, 'message' => $myArray[0]['message']);
                die(json_encode($result));
            }else{
                $id =  $myArray[0]['id'];
               $phone=  $myArray[0]['phone'];
                $email = $myArray[0]['email'];
                $buyer_name = $myArray[0]['buyer_name'];
                $amount=$myArray[0]['amount'];
                $purpose =$myArray[0]['purpose'];
                $status = $myArray[0]['status'];
                $longurl =$myArray[0]['longurl'];
                $sql = "INSERT INTO `order_details` (`request_id`,`phone`,`email`,`buyer_name`,`amount`,`purpose`,`status`,`longurl`) VALUE ('$id','$phone','$email','$buyer_name','$amount','$purpose','$status','$longurl')";
                $query = mysqli_query($con,$sql);
                $result = array('status' => 200,  'message' => 'Payment Details', 'access_token'=>$access_token, 'Data'=>$myArray);
            }
            }
    }
    }else{
        $result = array('status' => 500, 'message' => 'Missing Required Params');
    }
    die(json_encode($result));  
?>