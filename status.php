<?php

/*Call function with these configurations*/
    $env="sandbox";
    $shortcode = '600988'; 
    $type = '4';
    $key = "y1aQEROSLch19tgHtjHT39iZwWGDcXqsDa73lD8OIPchNMJy"; //Put your key here
    $secret = "JAvGWZfRDqcgp3ifIe6wFOKVU6DPOs3fGKN41owGNGcYddXCw2S3USRVuMLmN4Hv";  //Put your secret here
    $initiatorName = "testapi";
    $initiatorPassword = "Safaricom978!";
    $results_url = "https://5c75-102-135-169-167.ngrok-free.app/santi/daraja_x/callback.php"; //Endpoint to receive results Body
    $timeout_url = "https://5c75-102-135-169-167.ngrok-free.app/santi/daraja_x/callback.php"; //Endpoint to to go to on timeout
/*End  configurations*/

/*Ensure transaction code is entered*/
    // if (!isset($_GET["transactionID"])) {
    //     echo "Technical error";
    //     exit();
    // }
/*End transaction code validation*/

    //$transactionID = $_GET["transactionID"]; 
    $transactionID = "QCS2FC258A";
    $command = "TransactionStatusQuery";
    $remarks = "Transaction Status Query"; 
    $occasion = "Transaction Status Query";
    $callback = null ;

    if (isset($_POST['phone_number'])) {
        $access_token = ($env == "live") ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"; 
        $credentials = base64_encode($key . ':' . $secret); 
        
        $ch = curl_init($access_token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true); 
    
        //echo $result->{'access_token'};
        
        $token = isset($result->{'access_token'}) ? $result->{'access_token'} : "N/A";
    
        $publicKey = file_get_contents(__DIR__ . "/SandboxCertificate.cer"); 
        $isvalid = openssl_public_encrypt($initiatorPassword, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING); 
        $password = base64_encode($encrypted);
    
        //echo $token;
    
        $curl_post_data = array( 
            "Initiator" => $initiatorName, 
            "SecurityCredential" => $password, 
            "CommandID" => $command, 
            "TransactionID" => $transactionID, 
            "PartyA" => $shortcode, 
            "IdentifierType" => $type, 
            "ResultURL" => $results_url, 
            "QueueTimeOutURL" => $timeout_url, 
            "Remarks" => $remarks, 
            "Occasion" => $occasion,
        ); 
    
        $data_string = json_encode($curl_post_data);
    
        //echo $data_string;
    
        $endpoint = ($env == "live") ? "https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query" : "https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query"; 
    
        $ch2 = curl_init($endpoint);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        $response     = curl_exec($ch2);
        curl_close($ch2);
    
        echo "Response: ". $response;
    
        $result = json_decode($response, true); 
    
        var_dump($result);
        print_r($result);
        
        $verified = $result->{'ResponseCode'};
        if($verified === "0"){
            echo "Transaction Verification request Sent SUCCESSFULLY";
        }else{
            echo "Verification Request UNSUCCESSFUL";
        }
    }
?>