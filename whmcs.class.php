<?php

class WHMCS {
	public $url;
	public $username;
	public $password;
	public $accesskey;

	public function __construct($url = 'http://whmcs.com/include/api.php', $username = 'username', $password = 'password', $accesskey = ''){
		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
		$this->accesskey = $accesskey;
	}

	public function authenticate($username, $password){
		$response = $this->api("validatelogin", array("email" => $username, "password2" => $password));
		if($response->userid){
			return true;
		}

		return false;
	}

	public function getDomains($uid = 0, $domainId = 0, $domain = '', $start = 0, $limit = 0){
		if($limit <= 0){
			$limit = 9999;
		}

		$params['limitnum'] = $limit;
		$params['limitstart'] = $start;

		if($uid > 0){
			$params['clientid'] = $uid;
		}

		if($domainId > 0){
			$params['domainid'] = $domainId;
		}

		if($domain){
			$params['domain'] = $domain;
		}

		$response = $this->api("getclientsdomains", $params);

		if($response->result == 'error'){
			throw new WhmcsException("WHMCS complained: ".$response->message);
		}

		return $response;
	}

	public function getServices($uid = 0, $serviceId = 0, $domain = '', $productId = 0, $serviceUsername = '', $start = 0, $limit = 0){
		if($limit <= 0){
			$limit = 9999;
		}

		$params['limitnum'] = $limit;
		$params['limitstart'] = $limitstart;

		if($uid > 0){
			$params['clientid'] = $uid;
		}

		if($serviceId > 0){
			$params['serviceid'] = $serviceId;
		}

		if($domain){
			$params['domain'] = $domain;
		}

		if($productId){
			$params['pid'] = $productId;
		}

		if($serviceUsername){
			$params['username2'] = $serviceUsername;
		}

		$response = $this->api("getclientsproducts", $params);

		if($response->result == 'error'){
			throw new WhmcsException("WHMCS complained: ".$response->message);
		}

		return $response;
	}

	public function getTransactions($uid = 0, $invoiceId = 0, $transactionId = 0){
		if($uid > 0){
			$params['clientid'] = $uid;
		}

		if($invoiceId > 0){
			$params['invoiceid'] = $invoiceId;
		}

		if($transactionId > 0){
			$params['transid'] = $transactionId;
		}

		$response = $this->api("gettransactions", $params);

		if($response->result == 'error'){
			throw new WhmcsException("WHMCS complained: ".$response->message);
		}

		return $response;
	}

	public function getEmails($uid, $filter = '', $filterdate = '', $start = 0, $limit = 0){
		$params['clientid'] = $uid;

		if($filter){
			$params['subject'] = $filter;
		}

		if($filterdate){
			$params['date'] = $filterdate;
		}

		if(!$limit <= 0){
			$limit = 9999;
		}

		$params['limitnum'] = $limit;
		$params['limitstart'] = $start;

		$response = $this->api("getemails", $params);

		if($response->result == 'error'){
			throw new WhmcsException("WHMCS complained: ".$response->message);
		}

		return $response;
	}

	public function getCredits($uid){
		return $this->api("getcredits", array("clientid" => $uid));
	}

	public function getClient($uid = 0, $email = ''){
		if($uid > 0){
			$params = array("clientid" => $uid);
		}elseif($email){
			$params = array("email" => $email);
		}else{
			return false;
		}

		$params['stats'] = true;

		$response = $this->api("getclientsdetails", $params);

		if($response->result == 'error'){
			throw new WhmcsException("WHMCS complained: ".$response->message);
		}

		return $response;
	}
	private function api($action, $params){
		$postfields = array();
		$postfields['username'] = $this->username;
		$postfields['password'] = md5($this->password);
		$postfields['responsetype'] = 'json';
		$postfields['action'] = $action;

		if($this->accesskey != ''){
			$postfields['accesskey'] = $this->accesskey;
		}

		foreach($params as $k => $v){
			$postfields[$k] = $v;
		}

		$queryString = "";
		foreach($postfields as $k => $v){
			$queryString .= $k."=".urlencode($v)."&";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$response = curl_exec($ch);
		if(curl_error($ch)){ throw new Exception(curl_error($ch)); }
		curl_close($ch);

		return(json_decode($response));
	}
}

class WhmcsException extends Exception {}