<?php
namespace YoungMedia\Affiliate\Modules;


/**
 * Module
 * This is the base and example class for modules
*/
class AffiliateNetwork extends \YoungMedia\Affiliate\ModuleHelper {
	
	/** 
	 * Filter dates when loading transactions etc.
	*/
	public $from_date = "2016-01-01";
	public $to_date = "2016-12-31";

	/** 
	 * Set default name and slug for module
	*/
	public $name = 'Demo Network';
	public $slug = 'demonetwork';

	/**
	 * API token & Channel
	*/
	public $api_token;
	public $channelID;

	/*
	 * Init
	 * Calls on wp hook init
	*/
	public function _init() {
		
		global $ymas;

		/*
		 * Register API keys
		 * Load API token & channel ID from DB into variables.
		*/
		$this->api_token = $ymas->titan->getOption( $this->slug . '_api_token');
		$this->channelID = $ymas->titan->getOption( $this->slug . '_channel_id');

		$this->module_type = 'network';
		
	}
	
	/*
	 * Init
	 * Calls on wp hook admin_init
	*/
	public function _admin_init() {
		
	}

	/**
	 * Options 
	 * Create admin menu options for API tokens and channel ID
	*/
	public function _Options() {

		global $ymas;
		
		$ymas->admin_settings_api_tab->createOption( array(
		    'name' => $this->name,
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'API token',
			'id' => $this->slug . '_api_token',
			'type' => 'text',
			'placeholder' => __('Enter API-key (access token)', 'ymas'),
			'desc' => __('Learn more about this field in the', 'ymas') . ' <a href="#">' . __('documentation', 'ymas') . '</a>',
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'Channel ID',
			'id' => $this->slug . '_channel_id',
			'type' => 'text',
			'placeholder' => __('Enter your channel ID', 'ymas'),
			'desc' => __('Learn more about this field in the', 'ymas') . ' <a href="#">' . __('documentation', 'ymas') .'</a>',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'iframe',
		    'height' => 50,
		    'url' => YMAS_ASSETS . 'programs/' . $this->slug . '.html',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		));

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
		
		return array(
			array(
				'name' => 'Demo Program', 
				'category' => 'Demostration Services', 
				'tracking_url' => 'http://johndoe.com', 
				'network' => 'Demo Network'),
		);
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
		
		return array(
			array(
				'name' => 'Demo program',
				'transaction' => 'Demotransaction',
				'click_date' => $this->dateToString(date("Y-m-d H:i")),
				'event_date' => $this->dateToString(date("Y-m-d H:i")),
				'commission' => '120',
				'currency' => 'SEK',
				'network' => 'Demo Network',
			),
		);
	}

	/**
	 * Check if user has configuered API keys for this module
	*/
	public function isConfigured() {

		global $ymas;
		
		$api_token = $ymas->titan->getOption( $this->slug . '_api_token');
		$channelID = $ymas->titan->getOption( $this->slug . '_channel_id');
		
		if (empty($api_token) OR empty($channelID))
			return false;		

		return true;
	}


}