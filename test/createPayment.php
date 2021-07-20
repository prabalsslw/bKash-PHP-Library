<?php 
	include '../lib/Bkash.php';

	use Bkash\Checkout\Bkash;

	$create_payment = new Bkash();

	$request_body = json_decode(file_get_contents('php://input'), true);

	$post_data = array();

	$post_data = [
		'amount' => $request_body['amount'],
		'merchantInvoiceNumber' => strtoupper(uniqid())
	];

	echo $create_payment->createPayment($post_data);

?>