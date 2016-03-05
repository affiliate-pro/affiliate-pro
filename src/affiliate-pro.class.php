<?php
namespace YoungMedia\Affiliate;

class Affiliate {

	public $titan;

	public $admin_dashboard;
	public $admin_settings_page;

	public $admin_settings_api_tab;
	public $admin_settings_advanced_tab;

	public $slug;

	public static function InitAffiliatePlugin() {

		global $ymas;
		$ymas = new Affiliate();
		$ymas->titan = \TitanFramework::getInstance( 'ymas' );
		$ymas->adtraction = new Adtraction();
		$ymas->adtraction->api = new AdtractionAPI();

		$ymas->adrecord = new Adrecord();
		$ymas->adrecord->api = new AdrecordAPI();

		$ymas->double = new Double();

	}

	public function __construct() {

		add_action( 'admin_init', array(&$this, 'RegisterApiKeys') );
		add_action( 'init', array(&$this, 'RegisterRewriteRules') );

		add_action( 'admin_menu', array(&$this, 'RegisterMenus'));
		add_action( 'admin_enqueue_scripts', array(&$this, 'RegisterScriptsAndCss') );

		add_action(	'tf_create_options', array(&$this, 'RegisterTitanDashboard'));
		add_action( 'tf_create_options', array(&$this, 'RegisterTitanSettingsPages'));
		add_action( 'tf_create_options', array(&$this, 'RegisterTitanSettingsTabs'));
		add_action(	'tf_create_options', array(&$this, 'RegisterAdvancedSettingsOptions'));

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
	        'Affiliate > Links',
	        'Links',
	        'manage_options',
	        'affiliate-links',
	        array(&$this, 'LoadViewLinks')
        );

        \add_submenu_page('affiliate',
	        'Affiliate > Coupon Codes',
	        'Coupons',
	        'manage_options',
	        'affiliate-coupons',
	        array(&$this, 'LoadViewCoupon')
        );

        \add_submenu_page('affiliate',
	        'Affiliate > Earnings',
	        'Earnings',
	        'manage_options',
	        'affiliate-earnings',
	        array(&$this, 'LoadViewEarnings')
        );
	}

	public function RegisterScriptsAndCss() {
		wp_enqueue_style( 'affiliate-style', YMAS_ASSETS . 'affiliate-style.css');
	}

	public function RegisterTitanDashboard() {
		$this->admin_dashboard = $this->titan->createAdminPanel( array(
			'name' => 'Affiliate',
			'capability' => '',
		    'icon' => 'https://cdn3.iconfinder.com/data/icons/woothemesiconset/16/chart.png',
		));
	}

	public function RegisterTitanSettingsPages() {
		$this->admin_settings_page = $this->admin_dashboard->createAdminPanel( array(
		    'name' => 'Settings',
		    'title' => 'Affiliate <small>&gt; Settings</small>',
		));
	}

	public function RegisterTitanSettingsTabs() {

		$this->admin_settings_api_tab = $this->admin_settings_page->createTab( array(
		    'name' => 'Integrations',
		));

		$this->admin_settings_advanced_tab = $this->admin_settings_page->createTab( array(
		    'name' => 'Advanced Settings',
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

	public function LoadViewLinks() {
		global $ymas;
		$this->LoadView('links', array(
			'adtraction_programs' => $ymas->adtraction->api->programs(),
			'adrecord_programs' => $ymas->adrecord->api->programs(),
		));
	}

	public function LoadViewCoupon() {
		global $ymas;
		$this->LoadView('coupons', array(
			'coupons' => $ymas->adtraction->api->coupons(),
		));
	}

	public function LoadViewEarnings() {
		global $ymas;
		$this->LoadView('earnings', array(
			'adtraction_transactions' => $ymas->adtraction->api->transactions(),
			'adrecord_transactions' => $ymas->adrecord->api->transactions(),
		));
	}

	public function LoadView( $view_name, $params ) {

		global $ymas;
		extract($params);

		$slug = $this->getSlug();

		$titan = \TitanFramework::getInstance( 'ymas' );

		require( YMAS_ROOT_DIR . 'views/' . $view_name . '.php');
	}

	public function getSlug() {

		if (!empty($this->titan->getOption('ymas_permalink_slug')))
			$this->slug = $this->titan->getOption('ymas_permalink_slug');
		else
			$this->slug = 'out';

		return $this->slug;

	}
}