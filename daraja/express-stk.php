<?php session_start();

include 'includes/config.php';

$errors  = array();
$errmsg  = '';

$config = array(
    "env"              => "sandbox",
    "BusinessShortCode"=> "6061164",
    "key"              => "6kq7YxghJsG2ugTBW7zl3QnVljwjR97yE8nuK82QhTpa9a0u", //Enter your consumer key here
    "secret"           => "VPU5QAL5AghIDZjW6dcLvf8yLkbzGg1nqoOaEI6c5yklzJjYAnqsZ0MZoGAUg8y2", //Enter your consumer secret here
    "username"         => "santi",
    "TransactionType"  => "CustomerPayBillOnline",
    "passkey"          => "lYDxNj8CTLIRM5gf2kb9n9ZZtO54WihFsFMWtu4/o0cZYsE7pxZFbWf6LNLW5NXsyQIMi3rGW1UX2Ge1dmuMVallDjIqi8m5m9GqdZDf+n+pyo/SkE55kYkP8aTvVw+B9Mh+MS4hsmgV+Ye9vwT9yL5UUYQfIIUQtHjapXGAz5yBjwPd4PvL5UDaDLmB+2TDp8yQ/lC/vWPbWaFWmFZHQ5G1qJXGV/3HAwTy4EGRPRXEtlaRt2R+ZdoZvj8Wgb8lNd4FoAdYFE7x7dAjNn3F1Kt1iwLBLf86CCeaWKBfK0yiBTHjrvrhP7PGitlmTdab9L3h/EPPWi9PrDFNwVsTFQ==", //Enter your passkey here
    "CallBackURL"      => "https://f899-41-90-64-220.ngrok.io/mpesa/callback.php", //When using localhost, Use Ngrok to forward the response to your Localhost
    "AccountReference" => "Santi Health LTD",
    "TransactionDesc"  => "Payment of".$_COOKIE['price'],
);

if (isset($_POST['phone_number'])) {
    $phone = $_POST['phone_number'];
    $orderNo = $_POST['orderNo'];
    $amount = $_COOKIE['price'];
    $phone = (substr($phone, 0, 1) == "+") ? str_replace("+", "", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "7") ? "254{$phone}" : $phone;
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

    if($result['ResponseCode'] === "0"){         //STK Push request successful

        $MerchantRequestID = $result['MerchantRequestID'];
        $CheckoutRequestID = $result['CheckoutRequestID'];

        //Saves your request to a database       
        try{
            $sql = "INSERT INTO santiorders (id, OrderNo, Amount, Phone, CheckoutRequestID, MerchantRequestID) 
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
                $errmsg .= $error . '<br />';
            } 
        }
    }catch(PDOException $e){
        $errmsg = "Internal Server error" . $e->getMessage();
    }
    }else{
        $errors['mpesastk'] = $result['errorMessage'];
        foreach($errors as $error) {
            $errmsg .= $error . '<br />';
        }
    }
} else {
    $errmsg = "Please type your phone number";
}
?>