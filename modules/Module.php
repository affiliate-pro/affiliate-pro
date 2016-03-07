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

		if (method_exists($this, 'init')) {
			$this->init();
		}
	}

	/**
	 * Convert date into ISO8601 format
	 * @return date
	*/
	public function dateToISO8601( $input_date ) {
		$date = new \DateTime($input_date);
		return $date->format(\DateTime::ISO8601);
	}

	public function dateToString( $input_date ) {

		$timestamp = strtotime( $input_date );
		return date("Y-m-d H:i", $timestamp);
		
	}

}