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

		/**
		 * Prepare wordpress hooks
		*/
		add_action( 'init', array(&$this, 'RegisterRewriteRules') );
		add_action( 'admin_init', array(&$this, 'RegisterApiKeys') );
		add_action( 'admin_menu', array(&$this, 'RegisterMenus'));
		add_action( 'admin_enqueue_scripts', array(&$this, 'RegisterScriptsAndCss') );


		/**
		 * Create option pages (using Titan Framework)
		*/
		add_action(	'tf_create_options', array(&$this, 'RegisterTitanDashboard'));
		add_action( 'tf_create_options', array(&$this, 'RegisterTitanSettingsPages'));
		add_action( 'tf_create_options', array(&$this, 'RegisterTitanSettingsTabs'));
		add_action(	'tf_create_options', array(&$this, 'RegisterAdvancedSettingsOptions'));


		/**
		 * Create a global instance of titan framework.
		*/
		$this->titan = \TitanFramework::getInstance( 'ymas' );


		/**
		 * Load modules. This should be done some other way.
		 * Probably automatically without beeing a security issue.
		*/
		$this->adtraction = new Adtraction();
		$this->adrecord = new Adrecord();
		$this->double = new Double();

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
	 * Register Rewrite Rules
	 * Create rewrite rule for affiliate short links.
	*/
	public function RegisterRewriteRules( $slug = 'out' ) {

		add_rewrite_rule(
			'^'.$slug.'/([a-zA-Z0-9]+)?',
			'index.php?affiliate_redirect=$matches[1]',
			'top'
		);

		flush_rewrite_rules();
	}

	/**
	 * Register API keys
	 * Register api keys for each plugin.
	 * @todo: This must be moved to AffiliateNetwork class.
	*/
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

	/**
	 * Register Menus
	 * Register admin menu navigations
	*/
	public function RegisterMenus() {

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