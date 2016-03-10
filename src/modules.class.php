<?php
namespace YoungMedia\Affiliate;


/**
 * Module Helper
 * This is the base and example class for modules
*/
class ModuleHelper {

	public $api; 
	public $slug;
	public $module_path;
	public $module_type = 'module';

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

		$this->generateModulePath();

	}


	/**
     * View
	 * Loads a view into custom titan option fields.
	 * @return html
	*/
	public function view( $view = '' ) {
		
		$file = $this->module_path . 'views/' . $view . '.php';

		if (!file_exists($file))
			return __('View not found', 'ymas') . ':<br>' . $file;

		ob_start();
		require_once($file);
		return ob_get_clean();
	}

	/**
	 * Find out what path module is using and put it into $this->module_path
	 * @return void
	*/
	public function generateModulePath() {
		$this->module_path = trailingslashit(YMAS_ROOT_DIR . 'modules/' . $this->slug);
	}


	/**
	 * Get option
	 * Returns option value for module.
	 * @return any
	*/
	public function getOption( $value ) {

		global $ymas;
		return $ymas->titan->getOption( $this->slug . '_' . $value );
		
	}

	/**
	 * Is enabled options
	 * Returns true/false depending on if choosen module option checkbox is set.
	 * @return bool
	*/
	public function isEnabledOption( $value ) {

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

	/**
	 * Convert any date to readable Y-m-d H:i string
	 * @return date
	*/
	public function dateToString( $input_date ) {

		$timestamp = strtotime( $input_date );
		return date("Y-m-d H:i", $timestamp);
	}


}