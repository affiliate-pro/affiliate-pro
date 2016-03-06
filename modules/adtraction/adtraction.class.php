<?php
namespace YoungMedia\Affiliate;


/**
 * Require API class
*/
require_once('adtraction.api.php');


/**
 * Adtraction Module
 * Connection with Adtraction API
*/
class Adtraction extends Module {

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

		$api_response = $this->api->programs();

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

		$api_response = $this->api->transactions();

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
				'click_date' => ParseDate($i->clickDate),
				'event_date' => ParseDate($i->transactionDate),
				'commission' => $i->commission,
				'currency' => $i->currency,
				'network' => 'Adtraction',
			);
		}

		return $output;
	}

	public function isConfigured() {

		global $ymas;
		
		$api_token = $ymas->titan->getOption('adtraction_api_token');
		$channelID = $ymas->titan->getOption('adtraction_channel_id');
		
		if (empty($api_token) OR empty($channelID))
			return false;		

		return true;
	}

	public function RegisterOptions() {

		global $ymas;
		
		$ymas->admin_settings_api_tab->createOption( array(
		    'name' => 'Adtraction',
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'API token',
			'id' => 'adtraction_api_token',
			'type' => 'text',
			'placeholder' => '63ECEB5B9AE3230262838CEE1C679DD152DFC0',
			'desc' => 'Can be found at "<a href="https://secure.adtraction.com/affiliate/editaffiliate.htm" target="_new">Account > Settings</a>" in your Adtraction Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'Channel ID',
			'id' => 'adtraction_channel_id',
			'type' => 'text',
			'placeholder' => '23423423234',
			'desc' => 'Can be found at "<a href="https://secure.adtraction.com/affiliate/viewwebsites.htm" target="_new">Account > My Channels</a>" in your Adtraction Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'iframe',
		    'height' => 50,
		    'url' => YMAS_ASSETS . 'programs/adtraction.html',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'save',
		));

	}

}