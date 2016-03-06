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

		if (method_exists($this, 'RegisterOptions')) {
			add_action(	'tf_create_options', array(&$this, 'RegisterOptions'));
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

		/* 
		return array(
			array(
				'name' => '',
				'transaction' => '',
				'click_date' => ParseDate(''),
				'event_date' => ParseDate(''),
				'commission' => '',
				'currency' => '',
				'network' => '',
			),
		); */
		
		return array();
	}

}