<?php 
	include '../lib/Bkash.php';

	use Bkash\Checkout\Bkash;

	$search_transaction = new Bkash();

	echo $search_transaction->searchTransaction("8GK204UIR8");

?>