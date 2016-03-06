<?php
namespace YoungMedia\Affiliate;


/**
 * Module
 * This is the base and example class for modules
*/
class Module {

	public $api; 
	public $slug;

	public function __construct() {

		if (method_exists($this, 'Options')) {
			add_action(	'tf_create_options', array(&$this, 'Options'));
		}

		$this->api = new AdtractionAPI();

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

		/* 
		return array(
			array(
				'name' => '', 
				'category' => '', 
				'tracking_url' => '', 
				'network' => ''),
			array(
				'name' => '', 
				'category' => '', 
				'tracking_url' => '', 
				'network' => ''),
		); */
		
		return array();
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
				'click_date' => ParseDate(strtotime(date("Y-m-d H:i"))),
				'event_date' => ParseDate(strtotime(date("Y-m-d H:i"))),
				'commission' => '120',
				'currency' => 'SEK',
				'network' => 'Demo Network',
			),
		);
	}

	/**
	 * Options 
	 * Create admin menu options
	*/
	public function Options() {

		global $ymas;
		
		$ymas->admin_settings_advanced_tab->createOption( array(
		    'name' => 'My Unnamed Module',
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_advanced_tab->createOption( array(
		    'type' => 'save',
		));
	}

}