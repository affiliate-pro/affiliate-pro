<?php
namespace YoungMedia\Affiliate;

class Affiliate {

	public $titan;

	public $admin_dashboard;
	public $admin_settings_page;

	public $admin_settings_api_tab;
	public $admin_settings_advanced_tab;

	/**
	 * List of modules that should be loaded 
	*/
	public $modules = array(
		'adrecord', 'adtraction');

	public function __construct() {

		/**
		 * Check for required frameworks
		*/
		if (!class_exists('\TitanFramework')) 
			return;

		/**
		 * Prepare wordpress hooks
		*/
		add_action( 'admin_menu', array(&$this, 'RegisterMenus'));
		add_action( 'admin_enqueue_scripts', array(&$this, 'RegisterScriptsAndCss') );

		/**
		 * Create option pages (using Titan Framework)
		*/
		add_action(	'tf_create_options', array(&$this, 'RegisterTitanDashboard'));
		add_action( 'tf_create_options', array(&$this, 'RegisterTitanSettingsPages'));
		add_action( 'tf_create_options', array(&$this, 'RegisterTitanSettingsTabs'));


		/**
		 * Create a global instance of titan framework.
		*/
		$this->titan = \TitanFramework::getInstance( 'ymas' );

		/**
		 * Load modules.
		*/
		$this->InitModules();
	}

	/**
	 * Init Affiliate PRO.
	 * Create a new Affiliate instance and make it global.
	*/
	public static function InitAffiliatePlugin() {
		global $ymas;
		$ymas = new Affiliate();
	}

	/**
	 * Load modules.
	 * Load affiliate pro modules.
	*/
	public function InitModules() {

		$modules = $this->modules;

		foreach ($modules as $module) {

			$method_name = strtolower($module);			
			$module = ucfirst($module);		

			$class_name = "\YoungMedia\Affiliate\Modules\\{$module}";

			$this->$method_name = new $class_name;
			
		}
	}

	/**
	 * Register Menus
	 * Register admin menu navigations
	*/
	public function RegisterMenus() {

		\add_submenu_page('affiliate',
	        'Affiliate PRO > ' . __('Programs', 'ymas'),
	        __('Programs', 'ymas'),
	        'manage_options',
	        'affiliate-programs',
	        array(&$this, 'LoadViewPrograms')
        );

        \add_submenu_page('affiliate',
	        'Affiliate PRO > ' . __('Earnings', 'ymas'),
	        __('Earnings', 'ymas'),
	        'manage_options',
	        'affiliate-earnings',
	        array(&$this, 'LoadViewEarnings')
        );
	}

	/**
	 * Register Scripts and CSS
	 * Register Scripts and CSS on Wordpress pages
	*/
	public function RegisterScriptsAndCss($hook) {

		if (strpos($hook, 'affiliate_page_affiliate') === false)
			return;

		wp_enqueue_style( 'affiliate-style', YMAS_ASSETS . 'affiliate-style.css');
		wp_enqueue_script( 'angular', YMAS_ASSETS . 'js/angular.min.js');
		wp_enqueue_style( 'font-awesome', YMAS_ASSETS . 'css/font-awesome.min.css');
		wp_enqueue_script( 'angular-affiliatePro', YMAS_ASSETS . 'affiliatePro.js');
	}

	public function RegisterTitanDashboard() {
		$this->admin_dashboard = $this->titan->createAdminPanel( array(
			'name' => __('Affiliate', 'ymas'),
			'capability' => '',
		    'icon' => YMAS_ASSETS . 'menu_icon.png',
		));
	}

	public function RegisterTitanSettingsPages() {
		$this->admin_settings_page = $this->admin_dashboard->createAdminPanel( array(
		    'name' => __('Affiliate PRO', 'ymas'),
		    'title' => 'Affiliate PRO',
		));
	}

	public function RegisterTitanSettingsTabs() {

		$this->admin_settings_api_tab = $this->admin_settings_page->createTab( array(
		    'name' => __('Integrations', 'ymas'),
		    'slug' => 'integrations',
		));

		$this->admin_settings_advanced_tab = $this->admin_settings_page->createTab( array(
		    'name' => __('Advanced Settings', 'ymas'),
		    'slug' => 'advanced-settings',
		));
	}

	public function LoadViewPrograms() {
		global $ymas;
		$this->LoadView('programs');
	}

	public function LoadViewEarnings() {
		global $ymas;
		$this->LoadView('earnings');
	}

	public function LoadView( $view_name, $params = array() ) {

		global $ymas;
		extract($params);

		$titan = \TitanFramework::getInstance( 'ymas' );

		echo '<div ng-app="affiliatePro" id="ng-app">';
		require( YMAS_ROOT_DIR . 'views/' . $view_name . '.php');
		echo '</div>';
	}

}