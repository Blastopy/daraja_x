<?php

date_default_timezone_set('Africa/Nairobi');
include 'includes/config.php';

$errors  = array();
$errmsg  = '';

$config = array(
    "env"              => "sandbox",
    "BusinessShortCode"=> "6061162",
    "key"              => "mw3tGurOnfyg9f8HseGjCoteTd9T48EXnSn2AA2tcI3F5Erb", //Enter your consumer key here
    "secret"           => "j4eKxE2WuS0dOzBx541iHEYkupYEH3D4TLPZhSdcUq6k2htBT4F4tlOHzrSGrvPs", //Enter your consumer secret here
    "username"         => "Afraxshitote",
    "TransactionType"  => "CustomerBuyGoodsOnline",
    "passkey"          => "Te3TFShJcQLaFnrYOlTzyp8bZd/cCT+LfY23yCA+RdBWtMibJp5XRqcBorgiPDvfEI+8Zr7d5EE2Srytja36SLQ4ACgpFLjTl3jFZTJYtz8g+EYoCtEtr92t3SapQYWadiAOHP8dQfquhyMwH36leXtJOzCBQk83+hBeGCe1cQ2JUWRxvx4BqHUIKins3kCJ64xYPKWoQw1dNzG7Z9punZEn2uhiMZeHf++fSQX/IqvYoRm8BpqWa/mJQM9/rJR/QL54vChpgMXkKw8hWTjZNzANkg7WQyP2a3YuvpzAgqQ8pvNA20YC2aZq1IDe9WcDzMxsvu7dCwd5qCL1rx+lfQ==", //Enter your passkey here
    "CallBackURL"      => "https://7d0f-105-163-157-137.ngrok-free.app/santi/callback.php", //When using localhost, Use Ngrok to forward the response to your Localhost
    "AccountReference" => "SANTI HEALTH LTD",
    "TransactionDesc"  => "Payment for consultation",
);

if (isset($_POST['submit'])) {
    if(!empty($_POST['phone_number'])){
    $amount = $_POST['price'];
    $phone = $_POST['phone_number'];
    $orderNo = $_POST['orderNo'];
    $phone = (substr($phone, 0, 1) == "+") ? str_replace("+", "", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "7") ? "254{$phone}" : $phone;
    $access_token = ($config['env']  == "live") ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"; 
    $credentials = base64_encode($config['key'] . ':' . $config['secret']);
    $ch = curl_init($access_token);
    curl_setopt($ch, CURLOPT_URL, $access_token);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $response = curl_exec($ch);
    $result = json_decode($response); 
    $token = isset($result->{'access_token'}) ? $result->{'access_token'} : "N/A";
    $timestamp = date("YmdHis");
    $password  = base64_encode($config['BusinessShortCode'] . "" . $config['passkey'] ."". $timestamp);
    $curl_post_data = array(
        "BusinessShortCode" => $config['BusinessShortCode'],
        "Password" => $password,
        "Timestamp" => $timestamp,
        "TransactionType" => $config['TransactionType'],
        "Amount" => $amount,
        "PartyA" => $phone,
        "PartyB" => $config['BusinessShortCode'],
        "PhoneNumber" => $phone,
        "CallBackURL" => $config['CallBackURL'],
        "AccountReference" => $config['AccountReference'],
        "TransactionDesc" => $config['TransactionDesc'],
    ); 
    $data_string = json_encode($curl_post_data);

    $endpoint = ($config['env'] == "live") ? "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest" : "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest"; 

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response  = curl_exec($ch);
    curl_close($ch);

    $result = json_decode(json_encode(json_decode($response)), true);

    if(!preg_match('/^[0-9]{10}+$/', $phone) && array_key_exists('errorMessage', $result)){
        $errors['phone'] = $result["errorMessage"];
    }
if (!empty($result['ResponseCode'])){
    if($result['ResponseCode'] === "0"){         //STK Push request successful
        $MerchantRequestID = $result['MerchantRequestID'];
        $CheckoutRequestID = $result['CheckoutRequestID'];

        //Saves your request to a database       
        try{
            $sql = "INSERT INTO santiorders (OrderNo, Amount, Phone, CheckoutRequestID, MerchantRequestID) 
            VALUES (:OrderNo, :Amount, :Phone, :CheckoutRequestID, :MerchantRequestID)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":OrderNo", $orderNo);
            $stmt->bindParam(":Amount", $amount);
            $stmt->bindParam(":Phone", $phone);
            $stmt->bindParam(":CheckoutRequestID", $CheckoutRequestID);
            $stmt->bindParam(":MerchantRequestID", $MerchantRequestID);     
        if ($stmt->execute() === TRUE){
            $_SESSION["MerchantRequestID"] = $MerchantRequestID;
            $_SESSION["CheckoutRequestID"] = $CheckoutRequestID;
            $_SESSION["phone"] = $phone;
            $_SESSION["orderNo"] = $orderNo;
            header('location:confirm-payment.php');
        }else{
            $errors['database'] = "Unable to initiate your order: ".$conn;  
            foreach($errors as $error) {
                $errmsg = $error . '<br />';
            } 
        }
    }catch(PDOException $e){
        $errmsg = "Internal Server error" . $e->getMessage();
    }
    }else{
        $errors['mpesastk'] = $result['errorMessage'];
        foreach($errors as $error) {
            $errmsg = $error . '<br />';
        }
    }
}else {
    $errmsg = "Merchant does not exist";
}
} else {
    $errmsg = "Please enter your phone number";
}
}
?>