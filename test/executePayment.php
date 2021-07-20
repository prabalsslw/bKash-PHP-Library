<?php 
	include '../lib/Bkash.php';

	use Bkash\Checkout\Bkash;

	$executive_payment = new Bkash();

	$request_body = json_decode(file_get_contents('php://input'), true);
	$payment_id = $request_body['paymentID'];

	echo $executive_payment->executePayment($payment_id);

?>