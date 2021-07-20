<?php 
	
	return [
		"sandboxBaseUrl" => "https://checkout.sandbox.bka.sh/v1.2.0-beta",
		"sliveBaseUrl" => "",
		"grantTokenUrl" => "/checkout/token/grant",
		"createPaymentUrl" => "/checkout/payment/create",
		"executePaymentUrl" => "/checkout/payment/execute/",
		"queryUrl" => "/checkout/payment/query/",
		"capturePaymentUrl" => "/checkout/payment/capture/",
		"voidUrl" => "/checkout/payment/void/",
		"refundUrl" => "/checkout/payment/refund",
		"refundStatusUrl" => "/checkout/payment/refund",
		"searchTranUrl" => "/checkout/payment/search/",
		"jsScriptUrl" => "https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js",
		"app_key" => "5tunt4masn6pv2hnvte1sb5n3j",
		"app_secret" => "1vggbqd4hqk9g96o9rrrp2jftvek578v7d2bnerim12a87dbrrka",
		"username" => "sandboxTestUser",
		"password" => "hWD@8vtzw0",
		"proxy" => "",
		"is_sandbox" => true, 	# true - sandbox, false - live
		"is_capture" => true 	# true - authorization, false - sale
	];