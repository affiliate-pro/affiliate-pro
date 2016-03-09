<?php
namespace YoungMedia\Affiliate\Modules;


/**
 * Adtraction Module
 * Connection with Adtraction API
*/
class Adtraction extends AffiliateNetwork {

	/**
	 * Set name & slug for module
	 * This will be used as a variable for saving things like API keys.
	*/
	public $name = 'Adtraction';
	public $slug = 'adtraction';

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

		$output = array();

		$api_response = $this->getRequest('programs', array(
			'channelId' => $this->channelID,
			'approvalStatus' => 1
		));

		foreach ($api_response as $i) {

			if (!isset($i->programName) OR 
				!isset($i->category) OR
				!isset($i->trackingURL))
				continue;

			$output[] = array(
				'name' => trim($i->programName),
				'category' => trim($i->category),
				'tracking_url' => trim($i->trackingURL),
				'network' => 'Adtraction',
			);
		}

		return $output;
	}

	/**
	 * Connect with service API and 
	 * return an array of transactions.
	 * 
	 * @string: name
	 * @int: transaction
	 * @date: click_date
	 * @date: event_date
	 * @int: commission
	 * @string: currency
	 * @string: network 
	 * @return array
	*/
	public function transactions() {

		$output = array();

		$from_date = $this->dateToISO8601($this->from_date);
		$to_date = $this->dateToISO8601($this->to_date);

		$api_response = $this->getRequest('transactions', array(
			'channelId' => $this->channelID,
			'fromDate' => $from_date,
			'toDate' => $to_date,
		));


		if (!isset($api_response))
			return $output;

		foreach ($api_response as $i) {

			if (!isset($i->programName) OR 
				!isset($i->transactionName) OR
				!isset($i->clickDate) OR
				!isset($i->transactionDate) OR
				!isset($i->commission) OR
				!isset($i->currency))
				continue;

			$output[] = array(
				'name' => $i->programName,
				'transaction' => $i->transactionName,
				'click_date' => $this->dateToString($i->clickDate),
				'event_date' => $this->dateToString($i->transactionDate),
				'commission' => $i->commission,
				'currency' => $i->currency,
				'network' => 'Adtraction',
			);
		}

		return $output;
	}

	/**
	 * Get Request
	 * Download API data with CURL
	*/
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