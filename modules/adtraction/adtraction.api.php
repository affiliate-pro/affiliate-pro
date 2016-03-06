<?php
namespace YoungMedia\Affiliate;

class AdtractionAPI {

	private $api_token;
	private $channelID;

	public function setApiKeys($api_token, $channelID) {
		$this->api_token = $api_token;
		$this->channelID = $channelID;
	}

	public function programs() {
		return $this->getRequest('programs', array(
			'channelId' => $this->channelID,
			'approvalStatus' => 1
		));
	}

	public function coupons() {
		return $this->getRequest('couponcodes', array(
			'channelId' => $this->channelID,
		));
	}

	public function transactions( $from_date, $to_date ) {
		return $this->getRequest('transactions', array(
			'channelId' => $this->channelID,
			'fromDate' => $from_date,
			'toDate' => $to_date,
		));
	}

	public function getRequest( $command, $post ) {

		$url = 'https://api.adtraction.com/v1/affiliate/' . $command;
	 
		$headers = array(
			"X-Token: " . $this->api_token,
			"Content-Type: application/json",
			"Accept: application/json",
			"Access-Control-Request-Method: POST",
			"Access-Control-Request-Headers: content-type,X-Token"
		);
			 
		$payload = json_encode($post);

		$payload_md5 = md5($payload);
	     
     	$handle = curl_init(); 
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true);
	 
		$data = curl_exec($handle);
	 
		if ($data === false) {
			$info = curl_getinfo($handle);
			curl_close($handle);
			die('error occurred during curl exec. Additional info: ' . var_export($info));
		}
	 
		curl_close($handle);
	 
		return json_decode($data);
	}
}