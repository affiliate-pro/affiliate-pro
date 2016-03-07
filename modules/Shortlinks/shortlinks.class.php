<?php
namespace YoungMedia\Affiliate\Modules;


class Shortlinks extends Module {

	/**
	 * Set name & slug for module
	 * This will be used as a variable for saving things like API keys.
	*/
	public $name = 'Shortlinks';
	public $slug = 'shortlinks';

	/*
	 * Init
	 * Calls on wp hook init
	*/
	public function init() {
		
		/**
		 * Register Rewrite Rules
		 * Create rewrite rule for affiliate short links.
		*/

		$slug = $this->getSlug();

		add_rewrite_rule(
			'^'.$slug.'/([a-zA-Z0-9]+)?',
			'index.php?affiliate_redirect=$matches[1]',
			'top'
		);

		flush_rewrite_rules();
	}

	/**
	 * Options 
	 * Create admin menu options for API tokens and channel ID
	*/
	public function Options() {

		global $ymas;
		
		$ymas->admin_settings_advanced_tab->createOption( array(
		    'name' => $this->name,
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_advanced_tab->createOption( array(
			'name' => __('Permalink (slug)', 'ymas'),
			'id' => 'affiliate_shortlink_slug',
			'type' => 'text',
			'default' => 'out',
			'desc' => __('Change to a custom URL slug for your affiliate links.<br><strong>The structure is:', 'ymas') . ' </strong>' . site_url() . '/&lt;slug&gt;/<program>',
		));

		$ymas->admin_settings_advanced_tab->createOption( array(
		    'type' => 'save',
		));

	}

	/**
	 * Get slug
	 * Get saved shortlink slug
	*/
	public function getSlug() {

		global $ymas;
		
		$slug = $ymas->titan->getOption('affiliate_shortlink_slug');
		
		if (isset($slug) AND !empty($slug))
			$ymas->slug = $ymas->titan->getOption('affiliate_shortlink_slug');
		else
			$ymas->slug = 'out';

		return $ymas->slug;

	}

}