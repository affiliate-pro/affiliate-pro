<?php
namespace YoungMedia\Affiliate\Modules;


class Double extends AffiliateNetwork {

	/**
	 * Set name & slug for module
	 * This will be used as a variable for saving things like API keys.
	*/
	public $name = 'Double';
	public $slug = 'double';

	/**
	 * Connect with service API and 
	 * return an array of programs.
	 * 
	 * @string: name
	 * @string: category
	 * @string: tracking_url
	 * @string: network 
	 * @return array
	*/
	public function programs() {

		global $ymas;

		$output = array();

		$channel_id = $ymas->titan->getOption( $this->slug . '_channel_id');

		$api_response = $this->getRequest('programs');

		foreach ($api_response as $i) {
				
			if (!isset($i->title) OR 
				!isset($i->category->title) OR 
				!isset($i->allowed_channels))
				continue;

			$allowed_channels = $i->allowed_channels;


			$tracking_url = $this->tracking_url($channel_id, $i->id);


			foreach ($allowed_channels as $key => $value) {

				if ($value == $channel_id) {
					$output[] = array(
						'name' => trim($i->title),
						'category' => $i->category->title,
						'tracking_url' => $tracking_url,
						'network' => 'Double');
				}
			}


			
		}

		return $output;
	}

	
	/**
	 * Tracking URL
	 * Generate Adrecorc tracking url from program id and channel id
	*/
	public function tracking_url( $channel_id, $program_id ) {

		return "http://track.double.net/click/?channel={$channel_id}&program={$program_id}";

	}

	/**
	 * Get Request
	 * Download API data with CURL
	*/
	public function getRequest( $command ) {

		$api_key = $this->api_token;

		$url = 'https://www.double.net/api/publisher/v2/' . $command . '/?format=json&affiliated=affiliation-allowed&apikey=' . $api_key;

		$headers = array(
			"Authorization: Token " . $api_key,
			"Content-Type: application/json",
			"Accept: application/json",
			"Access-Control-Request-Headers: content-type,X-Token"
		);
			 	     
     	$handle = curl_init(); 
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
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