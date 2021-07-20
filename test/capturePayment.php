<?php 
	include '../lib/Bkash.php';

	use Bkash\Checkout\Bkash;

	$capture_payment = new Bkash();

	$request_body = json_decode(file_get_contents('php://input'), true);
	$payment_id = $request_body['paymentID'];

	echo $capture_payment->capturePayment($payment_id);

?>