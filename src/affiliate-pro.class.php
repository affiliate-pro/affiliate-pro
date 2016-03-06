<?php
namespace YoungMedia\Affiliate;

class Affiliate {

	public $titan;

	public $admin_dashboard;
	public $admin_settings_page;

	public $admin_settings_api_tab;
	public $admin_settings_advanced_tab;

	public $slug;

	public function __construct() {

		/**
		 * Check for required frameworks
		*/
		if (!class_exists('\TitanFramework')) 
			return;

		add_action( 'admin_init', array(&$this, 'RegisterApiKeys') );
		add_action( 'init', array(&$this, 'RegisterRewriteRules') );

		add_action( 'admin_menu', array(&$this, 'RegisterMenus'));
		add_action( 'admin_enqueue_scripts', array(&$this, 'RegisterScriptsAndCss') );

		add_action(	'tf_create_options', array(&$this, 'RegisterTitanDashboard'));
		add_action( 'tf_create_options', array(&$this, 'RegisterTitanSettingsPages'));
		add_action( 'tf_create_options', array(&$this, 'RegisterTitanSettingsTabs'));
		add_action(	'tf_create_options', array(&$this, 'RegisterAdvancedSettingsOptions'));

		$this->titan = \TitanFramework::getInstance( 'ymas' );
		$this->ajax = new Ajax\Ajax();

		$this->adtraction = new Adtraction();
		$this->adrecord = new Adrecord();
		//$this->double = new Double();

	}

	public static function InitAffiliatePlugin() {
		global $ymas;
		$ymas = new Affiliate();
	}

	public function RedirectToLocation( $program_slug ) {

	}

	public function RegisterRewriteRules( $slug = 'out' ) {

		add_rewrite_rule(
			'^'.$slug.'/([a-zA-Z0-9]+)?',
			'index.php?affiliate_redirect=$matches[1]',
			'top'
		);

		flush_rewrite_rules();
	}

	public function RegisterApiKeys() {

		global $ymas;
		
		/**
		 * Adtraction
		*/
		$api_token = $ymas->titan->getOption('adtraction_api_token');
		$channelID = $ymas->titan->getOption('adtraction_channel_id');
		
		$ymas->adtraction->api->setApiKeys($api_token, $channelID);

		/**
		 * Adrecord
		*/
		$api_token = $ymas->titan->getOption('adrecord_api_token');
		$channelID = $ymas->titan->getOption('adrecord_channel_id');
		
		$ymas->adrecord->api->setApiKeys($api_token, $channelID);
	}	

	public function RegisterMenus() {

		if ($this->adtraction->isConfigured() !== true)
			return; // @todo: This should check for every program.

		\add_submenu_page('affiliate',
	        'Affiliate > ' . __('Programs', 'ymas'),
	        __('Programs', 'ymas'),
	        'manage_options',
	        'affiliate-programs',
	        array(&$this, 'LoadViewPrograms')
        );

        \add_submenu_page('affiliate',
	        'Affiliate > ' . __('Coupon Codes', 'ymas'),
	        __('Coupons', 'ymas'),
	        'manage_options',
	        'affiliate-coupons',
	        array(&$this, 'LoadViewCoupon')
        );

        \add_submenu_page('affiliate',
	        'Affiliate > ' . __('Earnings', 'ymas'),
	        __('Earnings', 'ymas'),
	        'manage_options',
	        'affiliate-earnings',
	        array(&$this, 'LoadViewEarnings')
        );
	}

	public function RegisterScriptsAndCss() {
		wp_enqueue_style( 'affiliate-style', YMAS_ASSETS . 'affiliate-style.css');
		wp_enqueue_script( 'angular', '//ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js');
		wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
		wp_enqueue_script( 'angular-affiliatePro', YMAS_ASSETS . 'affiliatePro.js');
	}

	public function RegisterTitanDashboard() {
		$this->admin_dashboard = $this->titan->createAdminPanel( array(
			'name' => __('Affiliate', 'ymas'),
			'capability' => '',
		    'icon' => 'https://cdn3.iconfinder.com/data/icons/woothemesiconset/16/chart.png',
		));
	}

	public function RegisterTitanSettingsPages() {
		$this->admin_settings_page = $this->admin_dashboard->createAdminPanel( array(
		    'name' => __('Settings', 'ymas'),
		    'title' => 'Affiliate <small>&gt; Settings</small>',
		    'slug' => 'settings',
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

	public function RegisterAdvancedSettingsOptions() {

		$this->admin_settings_advanced_tab->createOption( array(
		    'name' => 'Short links',
		    'type' => 'heading',
			'toggle' => true,
		));

		$this->admin_settings_advanced_tab->createOption( array(
			'name' => 'Permalink (slug)',
			'id' => 'ymas_permalink_slug',
			'type' => 'text',
			'default' => 'out',
			'desc' => 'Change to a custom URL slug for your affiliate links.<br><strong>The structure is:</strong>' . site_url() . '/&lt;slug&gt;/<program>',
		));

		$this->admin_settings_advanced_tab->createOption( array(
		    'type' => 'save',
		));
	}

	public function LoadViewPrograms() {
		global $ymas;
		$this->LoadView('programs');
	}

	public function LoadViewCoupon() {
		global $ymas;
		$this->LoadView('coupons', array(
			'coupons' => $ymas->adtraction->api->coupons(),
		));
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

	public function getSlug() {
		
		@$slug = $this->titan->getOption('ymas_permalink_slug');
		
		if (isset($slug) AND !empty($slug))
			$this->slug = $this->titan->getOption('ymas_permalink_slug');
		else
			$this->slug = 'out';

		return $this->slug;

	}
}