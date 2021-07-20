<?php 
	namespace Bkash\Checkout;

	require_once(__DIR__."/BkashAbstract.php");

	class Bkash extends BkashAbstract
	{
	    protected $secretdata = [];
	    protected $data = [];
	    protected $config = [];

		public function __construct() {

	        $this->config = include(__DIR__.'/../config/bkash.php');

	        $this->setAppkey($this->config['app_key']);
	        $this->setAppsecret($this->config['app_secret']);
	        $this->setUsername($this->config['username']);
	        $this->setPassword($this->config['password']);

	        if($this->config['is_sandbox']) {
	        	$this->setEnv($this->config['sandboxBaseUrl']);
	        } else {
	        	$this->setEnv($this->config['sliveBaseUrl']);
	        }

	        if($this->config['is_capture']) {
	        	$this->setCapture('authorization');
	        } else {
	        	$this->setCapture('sale');
	        }

	        $token_response = json_decode($this->grantToken(), true);
	        $this->setToken($token_response['id_token']);
	    }

	    public function grantToken() {
	    	$this->secretdata['app_key'] = $this->getAppkey();
	    	$this->secretdata['app_secret'] = $this->getAppsecret();
	    	$this->setApiurl($this->getEnv().$this->config['grantTokenUrl']);

	    	$header = [
				'Content-Type:application/json',
				'password:'.$this->getPassword(),                                                               
		        'username:'.$this->getUsername()                                                          
		    ];	

	    	$response = $this->Post($this->secretdata, $header);

	    	return $response;
	    }

	    public function createPayment($postdata) {
	    	$this->readyParameter($postdata);
	    	$this->setApiurl($this->getEnv().$this->config['createPaymentUrl']);

	    	$header = [ 
		        'Content-Type:application/json',
		        'authorization:'.$this->getToken(),
		        'x-app-key:'.$this->getAppkey()                                                   
		    ];

		    $response = $this->Post($this->data, $header);
		    $status = json_decode($response, true);

		    if(isset($status['transactionStatus']) && $status['transactionStatus'] == "Initiated") {
				return $response;
		    } else {
		    	return "Unable to create payment! Reason: ". $status['errorCode']." - ".$status['errorMessage'];
		    }
	    }

	    public function executePayment($payment_id) {
	    	$this->setApiurl($this->getEnv().$this->config['executePaymentUrl'].$payment_id);

	    	$header = [ 
		        'Content-Type:application/json',
		        'authorization:'.$this->getToken(),
		        'x-app-key:'.$this->getAppkey()                                                   
		    ];

		    $response = $this->Post("", $header);
		    $status = json_decode($response, true);

		    if(isset($status['transactionStatus']) && ($status['transactionStatus'] == "Completed" || $status['transactionStatus'] == "Authorized")) {
				return $response;
		    } else {
		    	return "Unable to execute payment! Reason: ".$status['errorCode']." - ".$status['errorMessage'];
		    }
	    }

	    public function queryPayment($payment_id) {
	    	$this->setApiurl($this->getEnv().$this->config['queryUrl'].$payment_id);

	    	$header = [ 
		        'Content-Type:application/json',
		        'authorization:'.$this->getToken(),
		        'x-app-key:'.$this->getAppkey()                                                   
		    ];

		    $response = $this->Get($header);

	    	return $response;
	    }

	    public function searchTransaction($trxid) {
	    	$this->setApiurl($this->getEnv().$this->config['searchTranUrl'].$trxid);

	    	$header = [ 
		        'Content-Type:application/json',
		        'authorization:'.$this->getToken(),
		        'x-app-key:'.$this->getAppkey()                                                   
		    ];

		    $response = $this->Get($header);

	    	return $response;
	    }

	    public function refundTransaction($postdata) {
	    	$this->readyRefundParameter($postdata);
	    	$this->setApiurl($this->getEnv().$this->config['refundUrl']);

	    	$header = [ 
		        'Content-Type:application/json',
		        'authorization:'.$this->getToken(),
		        'x-app-key:'.$this->getAppkey()                                                   
		    ];

		    $response = $this->Post($this->data, $header);

	    	return $response;
	    }

	    public function refundStatus($postdata) {
	    	$this->readyRefundStatusParameter($postdata);
	    	$this->setApiurl($this->getEnv().$this->config['refundStatusUrl']);

	    	$header = [ 
		        'Content-Type:application/json',
		        'authorization:'.$this->getToken(),
		        'x-app-key:'.$this->getAppkey()                                                   
		    ];

		    $response = $this->Post($this->data, $header);

	    	return $response;
	    }

	    public function capturePayment($payment_id) {
	    	if($this->config['is_capture']) {
	    		$this->setApiurl($this->getEnv().$this->config['capturePaymentUrl'].$payment_id);

		    	$header = [ 
			        'Content-Type:application/json',
			        'authorization:'.$this->getToken(),
			        'x-app-key:'.$this->getAppkey()                                                   
			    ];

			    $response = $this->Post("", $header);
			    $status = json_decode($response, true);

			    if(isset($status['transactionStatus']) && $status['transactionStatus'] == "Completed") {
					return $response;
			    } else {
			    	return "Unable to capture payment! Reason: ". $status['errorCode']." - ".$status['errorMessage'];
			    }
	    	} else {
	    		return "Trying to capture payment in sale mode!";
	    	}
	    	
	    }

	    public function readyParameter(array $param) {
	    	$this->data['amount'] = (isset($param['amount'])) ? $param['amount'] : null;
	    	$this->data['currency'] = "BDT";
	    	$this->data['intent'] = $this->getCapture();
	    	$this->data['merchantInvoiceNumber'] = (isset($param['merchantInvoiceNumber'])) ? $param['merchantInvoiceNumber'] : null;
	    	$this->data['merchantAssociationInfo'] = (isset($param['merchantAssociationInfo'])) ? $param['merchantAssociationInfo'] : null;

	    	return $this->data;
	    }

	    public function readyRefundParameter(array $param) {
	    	$this->data['paymentID'] = (isset($param['paymentID'])) ? $param['paymentID'] : null;
	    	$this->data['amount'] = (isset($param['amount'])) ? $param['amount'] : null;
	    	$this->data['trxID'] = (isset($param['trxID'])) ? $param['trxID'] : null;
	    	$this->data['sku'] = (isset($param['sku'])) ? $param['sku'] : null;
	    	$this->data['reason'] = (isset($param['reason'])) ? $param['reason'] : null;

	    	return $this->data;
	    }

	    public function readyRefundStatusParameter(array $param) {
	    	$this->data['paymentID'] = (isset($param['paymentID'])) ? $param['paymentID'] : null;
	    	$this->data['trxID'] = (isset($param['trxID'])) ? $param['trxID'] : null;

	    	return $this->data;
	    }
	}