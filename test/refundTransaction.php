<?php 
	include '../lib/Bkash.php';

	use Bkash\Checkout\Bkash;

	$refund_transaction = new Bkash();

	$post_data = array();

	$post_data = [
		'paymentID' => "3VF7CXX1626763710076",
		'amount' => "10",
		'trxID' => "8GK404UJ2E",
		'sku' => "SK-M2323",
		'reason' => "Faulty"
	];

	echo $refund_transaction->refundTransaction($post_data);

?>