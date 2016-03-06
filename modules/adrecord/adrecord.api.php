<?php
namespace YoungMedia\Affiliate;

class AdrecordAPI {

	private $api_token;
	private $channelID;

	public function setApiKeys($api_token, $channelID) {
		$this->api_token = $api_token;
		$this->channelID = $channelID;
	}

	public function transactions() {
		return $this->getRequest('transactions')->result;
	}

	public function program($program_id) {
		return $this->getRequest("programs/{$program_id}")->result;
	}
	public function programs() {
		return $this->getRequest('statistics')->result;
	}

	public function getRequest( $command ) {

		$api_key = $this->api_token;

		$url = 'https://api.adrecord.com/v1/' . $command . '?apikey=' . $api_key;
	 
		$payload_md5 = md5($url);

		//if ( false === ( $data = get_transient( 'ymas_api_req_' . $payload_md5 ) ) ) {
	     
	     	$handle = curl_init(); 
			curl_setopt($handle, CURLOPT_URL, $url);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true);
		 
			$data = curl_exec($handle);
		 
			if ($data === false) {
				$info = curl_getinfo($handle);
				curl_close($handle);
				die('error occurred during curl exec. Additional info: ' . var_export($info));
			}
		 
			curl_close($handle);

	    	//set_transient( 'ymas_api_req_' . $payload_md5, $data, 600 );
		//}
	 
		return json_decode($data);
	}

}