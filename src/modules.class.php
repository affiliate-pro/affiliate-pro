<?php
namespace YoungMedia\Affiliate;


/**
 * Module Helper
 * This is the base and example class for modules
*/
class ModuleHelper {

	public $api; 
	public $slug;

	public function __construct() {


		if (method_exists($this, '_Options')) {
			add_action(	'tf_create_options', array(&$this, '_Options'));
		}

		if (method_exists($this, '_init')) 
			add_action( 'init', array(&$this, '_init') );

		if (method_exists($this, '_admin_init')) 
			add_action( 'admin_init', array(&$this, '_admin_init') );

		if (method_exists($this, '_wp_footer')) 
			add_action( 'wp_footer', array(&$this, '_wp_footer') );


		if (method_exists($this, 'Options')) {
			add_action(	'tf_create_options', array(&$this, 'Options'));
		}
		
		if (method_exists($this, 'init')) 
			add_action( 'init', array(&$this, 'init') );

		if (method_exists($this, 'admin_init')) 
			add_action( 'admin_init', array(&$this, 'admin_init') );

		if (method_exists($this, 'wp_footer')) 
			add_action( 'wp_footer', array(&$this, 'wp_footer') );

	}

	public function isEnabled( $value ) {

		global $ymas;
		return $ymas->titan->getOption( $this->slug . '_enabled_' . $value );
		
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