<?php 
    if(!empty($_POST)){
        include 'config.php';
        $request_id = $_POST['request_id'];
        $access_token = $_POST['access_token'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.instamojo.com/v2/gateway/orders/payment-request/');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer '.$access_token,
        ));
        $payload = array(
            'id' => $request_id
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        curl_close($ch);
        $decodedText = html_entity_decode($response);
        $myArray = array(json_decode($response, true));
        $id = $myArray[0]["order_id"];
        $phone = $myArray[0]["phone"];
        $sql = "INSERT INTO `order_request` (`order_id`,`phone`) VALUE ('$id','$phone')";
        $query = mysqli_query($con,$sql);
        $result = array('status' => 200, 'message' => 'Success', 'order_id' =>$id);
    }else{
        $result = array('status' => 500, 'message' => 'Missing Required Params');
    }
    die(json_encode($result));  
?>