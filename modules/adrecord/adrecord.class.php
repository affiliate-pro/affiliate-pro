<?php
namespace YoungMedia\Affiliate;


/**
 * Require API class
*/
require_once('adrecord.api.php');


/**
 * Adrecord Module
 * Connection with Adtraction API
*/
class Adrecord {
	
	public function __construct() {
		add_action(	'tf_create_options', array(&$this, 'RegisterTitanOptions'));
		$this->api = new AdrecordAPI();
	}

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

		$api_response = $this->api->transactions();

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
				'click_date' => ParseDate($i->click),
				'event_date' => ParseDate($i->changes[0]->date),
				'commission' => $i->commission / 100,
				'currency' => 'SEK',
				'network' => 'Adrecord',
			);
		}

		return $output;
	}

	public function isConfigured() {

		global $ymas;
		
		$api_token = $ymas->titan->getOption('adrecord_api_token');
		$channelID = $ymas->titan->getOption('adrecord_channel_id');
		
		if (empty($api_token) OR empty($channelID))
			return false;		

		return true;
	}

	public function tracking_url( $program_id ) {

		global $ymas;

		$channel_id = $ymas->titan->getOption('adrecord_channel_id');
		
		return "http://click.adrecord.com?c={$channel_id}&p={$program_id}";
	}

	public function RegisterTitanOptions() {

		global $ymas;
		
		$ymas->admin_settings_api_tab->createOption( array(
		    'name' => 'Adrecord',
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'API key',
			'id' => 'adrecord_api_token',
			'type' => 'text',
			'placeholder' => 'b3q2OIVr4wmJIagsk',
			'desc' => 'Can be found at "<a href="https://www.adrecord.com/en/advanced/api" target="_new">Advanced &gt; API</a>" in your Adrecord Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'Channel ID',
			'id' => 'adrecord_channel_id',
			'type' => 'text',
			'placeholder' => '12345',
			'desc' => 'Can be found at "<a href="https://www.adrecord.com/en/channels" target="_new">Channels</a>" in your Adrecord Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'iframe',
		    'height' => 50,
		    'url' => YMAS_ASSETS . 'programs/adrecord.html',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'save',
		));

	}

}