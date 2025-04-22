<?php
include 'config.php';
$errors = array();
$errmsg = '';
$accref = strtoupper(uniqid());

if(isset($_POST['submit'])){

  $amount = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  date_default_timezone_set('Africa/Nairobi');
  $consumerKey = 'DrOZ7GTt6zFgKVtoi0N2tNPyYJpwCtg0Uv2h9l7dsCheGLH7';
  $consumerSecret = 'JvnGAzehENAVr6zuygbOeFE0PNhGh0EaCTp6MgLaapIFsZG68ZJ6GgAPQAcEG989';
  $credentials = base64_encode($consumerKey.':'.$consumerSecret);
  $BusinessShortCode = '6061162';
  $Passkey = '271cfa909e6f95c681c34b8276eed7fe932819ccb3bf8bc66d481efc355fa6d4';

 
if (empty($_POST['phone_number'])){
  $errmsg = "Phone number can't be empty";
}elseif(strlen($_POST['phone_number']) < 10){
  $errmsg = "Please type the correct format";
}else {
  $phone = $_POST['phone_number'];
  $phone = (substr($phone, 0, 1) == "+") ? str_replace("+", "", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "7") ? "254{$phone}" : $phone;
  $config = array(
    "env"              => "sandbox",
    "BusinessShortCode"=> "174379",
    "key"              => "y1aQEROSLch19tgHtjHT39iZwWGDcXqsDa73lD8OIPchNMJy", //Enter your consumer key here
    "secret"           => "JAvGWZfRDqcgp3ifIe6wFOKVU6DPOs3fGKN41owGNGcYddXCw2S3USRVuMLmN4Hv",
    "username"         => "Afraxshitote",
    "TransactionType"  => "CustomerPayBillOnline",
    "passkey"          => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919", //Enter your passkey here
    "CallBackURL"      => "https://5c75-102-135-169-167.ngrok-free.app/santi/daraja_x/callback.php", //When using Localhost, Use Ngrok to forward the response to your Localhost
    "AccountReference" => "SANTI HEALTH LTD",
    "TransactionDesc"  => "Payment of Consultation",
);

    $access_token = ($config['env']  == "live") ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"; 
    $credentials = base64_encode($config['key'] . ':' . $config['secret']);
        
    $ch = curl_init($access_token);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch);
    curl_close($ch);
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
    $data = array(
      'ShortCode' => '600988',
      'ResponseType' => 'Completed',
      'ConfirmationURL' => $config['CallBackURL'],
      'ValidationURL' => $config['CallBackURL']
    );
    $registerUrl = "https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl";
    $curlUrl = curl_init();
    curl_setopt($curlUrl, CURLOPT_URL, $registerUrl);
    curl_setopt($curlUrl, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer '.$token,
      'Content-Type: application/json'
    ]);
    curl_setopt($curlUrl, CURLOPT_POST, true);
    curl_setopt($curlUrl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curlUrl, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($curlUrl);
    curl_close($curlUrl);
    $endpoint = ($config['env'] == "live") ? "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest" : "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest"; 

    $ch = curl_init($endpoint );
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode(json_encode(json_decode($response), true), true);
    if(!preg_match('/^[0-9]{10}+$/', $phone) && array_key_exists('errorMessage', $result)){
        $errors['phone'] = $result["errorMessage"];
    }

    if($result['ResponseCode'] === "0"){
      $MerchantRequestID = $result['MerchantRequestID'];
      $CheckoutRequestID = $result['CheckoutRequestID'];
      $sql = $conn->prepare("INSERT INTO santiorders (OrderNo, Amount, Phone, CheckoutRequestID, MerchantRequestID) 
      VALUES (:OrderNo, :Amount, :Phone, :CheckoutRequestID, :MerchantRequestID);");
      $sql->bindParam(':OrderNo', $accref);
      $sql->bindParam(':Amount', $amount);
      $sql->bindParam(':Phone', $phone);
      $sql->bindParam(':CheckoutRequestID', $CheckoutRequestID);
      $sql->bindParam(':MerchantRequestID', $MerchantRequestID);
      if($sql->execute() == true){
           $_SESSION["MerchantRequestID"] = $MerchantRequestID;
            $_SESSION["CheckoutRequestID"] = $CheckoutRequestID;
            $_SESSION["phone"] = $phone;
            $_SESSION["orderNo"] = $accref;
            header('location:daraja_x/confirm-payment.php');
      }
    }else
        foreach($errors as $error) {
            $errmsg = !empty($error['errorMessage']) ? $error['errorMessage'] : 'Cannot complete the transaction at the moment.' . '<br />';
            // print_r($errmsg);     
        }
}
}

?>