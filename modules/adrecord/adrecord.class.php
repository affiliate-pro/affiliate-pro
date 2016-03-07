<?php
namespace YoungMedia\Affiliate\Modules;


/**
 * Adrecord Module
 * Connection with Adtraction API
*/
class Adrecord extends AffiliateNetwork {
	
	/**
	 * Set name & slug for module
	 * This will be used as a variable for saving things like API keys.
	*/
	public $name = 'Adrecord';
	public $slug = 'adrecord';


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

		$api_response = $this->getRequest('statistics')->result;

		foreach ($api_response as $i) {

			if (!isset($i->program->name))
				continue;

			$tracking_url = $this->tracking_url($i->program->id);

			$output[] = array(
				'name' => trim($i->program->name),
				'category' => '-',
				'tracking_url' => $tracking_url,
				'network' => 'Adrecord',
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

		$api_response = $this->getRequest('transactions')->result;

		foreach ($api_response as $i) {

			if (!isset($i->program->name) OR 
				!isset($i->commissionName) OR
				!isset($i->type) OR
				!isset($i->click) OR
				!isset($i->changes[0]->date) OR
				!isset($i->commission))
				continue;

			$output[] = array(
				'name' => $i->program->name,
				'transaction' => $i->commissionName . ' - ' . $i->type,
				'click_date' => $this->dateToString($i->click),
				'event_date' => $this->dateToString($i->changes[0]->date),
				'commission' => $i->commission / 100,
				'currency' => 'SEK',
				'network' => 'Adrecord',
			);
		}

		return $output;
	}

	/**********************************
	 * 
	 * CUSTOM FUNCTION
	 * CUSTOM FUNCTIONS RELATED TO THIS SPECIFIC NETWORK
	 *
	 **********************************/


	/**
	 * Tracking URL
	 * Generate Adrecorc tracking url from program id and channel id
	*/
	public function tracking_url( $program_id ) {

		global $ymas;

		$channel_id = $ymas->titan->getOption( $this->slug . '_channel_id');
		
		return "http://click.adrecord.com?c={$channel_id}&p={$program_id}";
	}

	public function getRequest( $command ) {

		$api_key = $this->api_token;

		$url = 'https://api.adrecord.com/v1/' . $command . '?apikey=' . $api_key;
	 
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

		return json_decode($data);
	}

}