<?php 
	include '../lib/Bkash.php';

	use Bkash\Checkout\Bkash;

	$refund_query = new Bkash();

	$post_data = array();

	$post_data = [
		'paymentID' => "3VF7CXX1626763710076",
		'trxID' => "8GK404UJ2E"
	];

	echo $refund_query->refundStatus($post_data);

?>