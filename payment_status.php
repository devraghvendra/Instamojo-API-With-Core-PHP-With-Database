<?php 
    if(!empty($_REQUEST)){
        include 'config.php';
        $phone = $_REQUEST['phone'];
        $payment_id = $_REQUEST['payment_id'];
        $payment_status = $_REQUEST['payment_status'];
        $transaction_id = $_REQUEST['transaction_id'];
        $order_id = $_REQUEST['order_id'];
        $sql = "INSERT INTO `payment_status` (`payment_id`,`payment_status`,`transaction_id`,`order_id`,`phone`) VALUE ('$payment_id','$payment_status','$transaction_id','$order_id','$phone')";
        $query = mysqli_query($con,$sql);
        $result = array('status' => 200, 'message' => 'Success');
    }else{
        $result = array('status' => 500, 'message' => 'Missing Required Params');
    }
    die(json_encode($result));  
?>