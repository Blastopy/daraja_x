<?php

$errors = array();
$errmsg = '';

if(isset($_POST['submit'])){

  date_default_timezone_set('Africa/Nairobi');
  $amount = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  # access token
  $consumerKey = 'mw3tGurOnfyg9f8HseGjCoteTd9T48EXnSn2AA2tcI3F5Erb'; //Fill with your app Consumer Key
  $consumerSecret = 'j4eKxE2WuS0dOzBx541iHEYkupYEH3D4TLPZhSdcUq6k2htBT4F4tlOHzrSGrvPs'; // Fill with your app Secret

  # define the variables
  # provide the following details, this part is found on your test credentials on the developer account
  $BusinessShortCode = '6061164';
  $Passkey = 'P6iirpmDcjhJfNia9jR4pUDRLY7Ba+i3++6CbLVljGwvpKWoVPgFywESqYYQvRdvqt5kjjCED68qpcLOBLDOCwKChppJnDxMxtLa/p8vdi6CHpWK5YrMQBmjKPzavN/n6JvvJepA39zSuN3BrWgW6hbDlY8nsXA9ZReVzF21C9VkouNXXZbdIp1o5Y4AOV5kNSyjUUgMhNzg/DP/CKwXDj7K/1Efvh4LtOFBCaFQdFh41HsIgtQj5dw79YWSA1yxAIIoi4EUjtj1oTbjQk7V+/zgOGjSVf7/IeInz4SmjekC6iK1PT/MgGLSedCKcP2iwCJH83xT+Oc8nKYwxWupMQ==';  

  /*
    This are your info, for
    $PartyA should be the ACTUAL clients phone number or your phone number, format 2547********
    $AccountRefference, it maybe invoice number, account number etc on production systems, but for test just put anything
    TransactionDesc can be anything, probably a better description of or the transaction
    $Amount this is the total invoiced amount, Any amount here will be 
    actually deducted from a clients side/your test phone number once the PIN has been entered to authorize the transaction. 
    for developer/test accounts, this money will be reversed automatically by midnight.
  */
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
  $AccountReference = '2255';
  $TransactionDesc = 'consultation Payment';
  $Amount = $amount;

  # Get the timestamp, format YYYYmmddhms -> 20181004151020
  $Timestamp = date('YmdHis');

  # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

  # header for access token
  $headers = ['Content-Type:application/json; charset=utf8'];

    # M-PESA endpoint urls
  $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
  $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  # callback url
  $CallBackURL = 'https://morning-forest-72309.herokuapp.com/callback_url.php';

  $curl = curl_init($access_token_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
  $result = curl_exec($curl);
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $result = json_decode(json_encode(json_decode($result)), true);  $access_token = $result['access_token'];
  curl_close($curl);

  # header for stk push
  $stkheader = ['Content-Type:application/json','Authorization: Basic '.$access_token];

  # initiating the transaction
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => $CallBackURL,
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  $curl_response = curl_exec($curl);
  $qurl = json_decode(json_encode(json_decode($curl_response)), true);
  $errors['mpesastk'] = $qurl;
        foreach($errors as $error) {
            $errmsg = $error['errorMessage'] . '<br />';
        }

}};
?>