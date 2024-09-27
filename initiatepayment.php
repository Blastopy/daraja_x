<?php

date_default_timezone_set('Africa/Nairobi');
include 'includes/config.php';
// payment_reason, meeting_priority, payment_date, patient_email, payment_status, paid_amount, doctors_email, confirmation_code
// Errors
$reasonErr = $meeting_priorityErr = $payment_dateErr = $reasonErr1 = $patient_emailErr = $payment_statusErr =$payment_phoneErr = $paid_amountErr = $doctors_emailErr = $confirmation_codeErr  = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['payment_reason'])){
			$reasonErr = 'Reason of payment cannot be empty';
		}else{
			$payment_reason = test_inputs($_POST['payment_reason']);
			$payment_reason = filter_input(INPUT_POST, 'payment_reason', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['meeting_priority'])){
			$meeting_priorityErr = 'Meeting priority cannot be empty';
		}else{
			$meeting_priority = test_inputs($_POST['meeting_priority']);
			$meeting_priority = filter_input(INPUT_POST, 'meeting_priority', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['payment_date'])){
			$payment_dateErr = 'Reason of payment cannot be empty';
		}else{
			$payment_date = test_inputs($_POST['payment_date']);
			$payment_date = filter_input(INPUT_POST, 'payment_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['patient_email'])){
			$patient_emailErr = 'Patient email cannot be empty';
		}else{
			$payment_email = filter_input(INPUT_POST, 'patient_email', FILTER_SANITIZE_EMAIL);
			try{
				$query = $conn -> prepare("SELECT email FROM members WHERE email=:email");
				$query->bindParam(':email', $payment_email);
				$query->execute();
				$result = $query -> fetch(PDO::FETCH_ASSOC);
				if(empty($result['email'])){
					$patient_emailErr = 'Patient not registered';
				}else{
					$payment_email = test_inputs($_POST['patient_email']);
					$payment_email = filter_input(INPUT_POST, 'patient_email', FILTER_SANITIZE_EMAIL);
				}
		}catch(PDOException){
			$patient_emailErr = 'Email does not exist';
		}
		}
	}

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['payment_status'])){
			$reasonErr = 'Reason of payment cannot be empty';
		}else{
			$payment_status = test_inputs($_POST['payment_status']);
			$payment_status = filter_input(INPUT_POST, 'payment_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['paid_amount'])){
			$paid_amountErr = 'Amount cannot be empty';
		}else{
			$paid_amount = test_inputs($_POST['paid_amount']);
			$paid_amount = filter_input(INPUT_POST, 'paid_amount', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['doctors_email'])){
			$doctors_emailErr = 'Doctor\'s email cannot be empty';
		}else{
			$doctors_email = filter_input(INPUT_POST, 'doctors_email', FILTER_SANITIZE_EMAIL);
			try{
					$query = $conn -> prepare("SELECT email FROM santi_data WHERE email=:email");
					$query->bindParam(':email', $doctors_email);
					$query->execute();
					$result = $query -> fetch(PDO::FETCH_ASSOC);
					if(empty($result['email'])){
						$doctors_emailErr = 'Doctor not registered';
					}else{
						$doctors_email = test_inputs($_POST['doctors_email']);
						$doctors_email = filter_input(INPUT_POST, 'doctors_email', FILTER_SANITIZE_EMAIL);
					}
			}catch(PDOException){
				$doctors_emailErr = 'Email does not exist';
			}
			}
		}

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['confirmation_code'])){
			$confirmation_codeErr = 'Confirmation code cannot be empty';
		}else{
			if (strlen($_POST['confirmation_code']) !== 10){
				$confirmation_codeErr = 'Wrong confirmation code';
			}else {
				$confirmation_code = test_inputs($_POST['confirmation_code']);
				$confirmation_code = filter_input(INPUT_POST, 'confirmation_code', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$confirmation_code = strtoupper($confirmation_code);
			}
		}
	}

	if(isset($_POST['approve_payment'])){
		if (empty($_POST['payment_phone'])){
			$payment_phoneErr = 'Payment phone cannot be empty';
		}else{
				$payment_phone = test_inputs($_POST['payment_phone']);
				$payment_phone = filter_input(INPUT_POST, 'payment_phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (empty($reasonErr) && empty($meeting_priorityErr) && empty($payment_dateErr) && empty($payment_phoneErr) && empty($patient_emailErr) && empty($payment_statusErr) && empty($paid_amountErr) && empty($doctors_emailErr) && empty($confirmation_codeErr))
	{
		try{
			$orderNo = strtoupper(uniqid());

			$query = $conn -> prepare("SELECT CheckoutRequestID FROM santiorders");
					$query->execute();
					$resulting = $query -> fetch(PDO::FETCH_ASSOC);
					if(!empty($resulting['CheckoutRequestID'])){
						if(strcmp($resulting['CheckoutRequestID'], $confirmation_code) === 0){
							$confirmation_codeErr = 'Payment already made';
						}else{
							$stmt = "INSERT INTO santiorders (OrderNo, Amount , Phone, doc_email, patient_email, CheckoutRequestID, payment_status)
										VALUES (:orderNo, :amount, :phone, :doc_email, :patient_email, :CheckoutRequestID, :payment_status)";
							$statement = $conn->prepare($stmt);
							$statement->bindParam(':orderNo', $orderNo);
							$statement->bindParam(':amount', $paid_amount);
							$statement->bindParam(':phone', $payment_phone);
							$statement->bindParam(':doc_email', $doctors_email);
							$statement->bindParam(':patient_email', $payment_email);
							$statement->bindParam(':CheckoutRequestID', $confirmation_code);
							$statement->bindParam(':payment_status', $payment_status);
							if($statement->execute() == TRUE){
								$booking = '<div class="alert success"><span class="closebtn">Payment initiated succeffully</span></div>';
							}else {
								$booking = '<div class="alert warning"><span class="closebtn">Payment not initiated</span></div>';
							}
					}
				}
		}catch (PDOException $e){
			$reasonErr1 = 'Internal server error';
		}
	}
}
?>