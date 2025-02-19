<?php

$errors = array();
$errmsg = '';
$accref = strtoupper(uniqid());

if(isset($_POST['submit'])){

  $amount = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  date_default_timezone_set('Africa/Nairobi');
  $consumerKey = 'DrOZ7GTt6zFgKVtoi0N2tNPyYJpwCtg0Uv2h9l7dsCheGLH7';
  $consumerSecret = 'JvnGAzehENAVr6zuygbOeFE0PNhGh0EaCTp6MgLaapIFsZG68ZJ6GgAPQAcEG989'; 
  $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
  $BusinessShortCode = '6061162';
  $Passkey = '271cfa909e6f95c681c34b8276eed7fe932819ccb3bf8bc66d481efc355fa6d4';

 
if (empty($_POST['phone_number'])){
  $errmsg = "Phone number can't be empty";
}elseif(strlen($_POST['phone_number']) < 10){
  $errmsg = "Please type the correct format";
}else {
  $PartyA = trim($_POST['phone_number']);
  $PartyA = htmlspecialchars($_POST['phone_number']);
  $PartyA = stripcslashes($_POST['phone_number']);
  $PartyA = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $orderNo = $_POST['orderNo'];
  $amount = $amount;
  $PartyA = (substr($PartyA, 0, 1) == "+") ? str_replace("+", "", $PartyA) : $PartyA;
  $PartyA = (substr($PartyA, 0, 1) == "0") ? preg_replace("/^0/", "254", $PartyA) : $PartyA;
  $PartyA = (substr($PartyA, 0, 1) == "7") ? "254{$PartyA}" : $PartyA;
  $AccountReference = $accref;
  $TransactionDesc = 'Consultation Payment';
  $Amount = $amount;

  $Timestamp = date('YmdHis');

  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

  # header for access token
  $headers = ['Authorization: Basic '. $credentials,
              'Content-Type: application/json'];

    # M-PESA endpoint urls
  $access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
  $initiate_url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  # callback url
  $CallBackURL = 'https://santihealth.co.ke/callback.php';

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $access_token_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $result = curl_exec($curl);
  if (curl_errno($curl)) {
    $errmsg = 'Request Error:'. curl_error($curl);
  }
  curl_close($curl);
  $result = json_decode($result, true);
  if (!isset($result['access_token'])) {
    die('Failed to retrieve access token. Response '.print_r($result));
  } else $accessToken = $result['access_token'];


  # header for stk push
  $stkheader = [
                'Authorization: Bearer '.$accessToken,
                'Content-Type: application/json',
              ];

  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerBuyGoodsOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => $CallBackURL,
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );
              
  # initiating the transaction
  $c_url = curl_init();
  curl_setopt($c_url, CURLOPT_URL, $initiate_url);
  curl_setopt($c_url, CURLOPT_HTTPHEADER, $stkheader); //setting custom header
  curl_setopt($c_url, CURLOPT_POST, true);
  curl_setopt($c_url, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
  curl_setopt($c_url, CURLOPT_RETURNTRANSFER, true);
  $curl_response = curl_exec($c_url);
  $qurl = json_decode(json_encode(json_decode($curl_response)), true);
  $errors['mpesastk'] = $qurl;
        foreach($errors as $error) {
            $errmsg = !empty($error['errorMessage']) ? $error['errorMessage'] : 'Cannot complete the transaction at the moment.' . '<br />';
        }
}};
?>