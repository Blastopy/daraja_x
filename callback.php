<?php

include 'config.php';
$content = file_get_contents('php://input'); //Receives the JSON Result from safaricom
$res = json_decode($content, true); //Convert the json to an array

$dataToLog = array(
    date("Y-m-d H:i:s"), //Date and time
	" MerchantRequestID: ".$res['Body']['stkCallback']['MerchantRequestID'],
	" CheckoutRequestID: ".$res['Body']['stkCallback']['CheckoutRequestID'],
    " ResultCode: ".$res['Body']['stkCallback']['ResultCode'],
	" ResultDesc: ".$res['Body']['stkCallback']['ResultDesc'],
	"Mpesa number: ".$res['Body']['stkCallback']['CallbackMetadata']['Item']['MpesaReceiptNumber'],
);

if(isset($res['Body']['stkCallback']['CallbackMetadata']['Item'])){
	foreach($res['Body']['stkCallback']['CallbackMetadata']['Item'] as $item) {
		if ($item['Name'] === "MpesaReceiptNumber") {
			$receiptNumber = $item['Value'];
			break;
		}
	}
}

echo json_encode(['ReceiptNumber' => $receiptNumber ?? 'Not found']);
$data = implode(" - ", $dataToLog);
$data .= PHP_EOL;
file_put_contents('transaction_log', $data, FILE_APPEND); //Logs the results to our log file

//Saves the result to the database
$stmt = $conn->prepare("SELECT * FROM santiorders ORDER BY ID DESC LIMIT 1");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
	$ID = $row['ID'];

	if($res['Body']['stkCallback']['ResultCode'] == '1032'){
		$sql = $conn->query("UPDATE santiorders SET Status = 'CANCELLED' WHERE santiorders.ID = :ID");
		$sql->bindParam(':ID', $ID);
		$sql->execute();
		$rs = $sql->execute();
	 }else{
		$sql = $conn->query("UPDATE santiorders SET Status = 'SUCCESS' WHERE santiorders.ID = :ID");
        $sql->bindParam(':ID', $ID);
		$rs = $sql->execute();
	 }

	if($rs){
		file_put_contents('error_log', "Records Inserted", FILE_APPEND);;
	}else{
		file_put_contents('error_log', "Failed to insert Records", FILE_APPEND);
	}
}

?>
