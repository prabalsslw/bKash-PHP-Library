<?php 
	include '../lib/Bkash.php';

	use Bkash\Checkout\Bkash;

	$query_payment = new Bkash();

	// $request_body = json_decode(file_get_contents('php://input'), true);
	// $payment_id = $request_body['paymentID'];8GK204UIR8

	echo $query_payment->queryPayment("92V730H1626752910560");

?>