<?php
namespace YoungMedia\Affiliate;


class Adtraction {

	public $api; 

	public function __construct() {
		add_action(	'tf_create_options', array(&$this, 'RegisterTitanOptions'));
	}

	public function isConfigured() {

		global $ymas;
		
		$api_token = $ymas->titan->getOption('adtraction_api_token');
		$channelID = $ymas->titan->getOption('adtraction_channel_id');
		
		if (empty($api_token) OR empty($channelID))
			return false;		

		return true;
	}

	public function RegisterTitanOptions() {

		global $ymas;
		
		$ymas->admin_settings_api_tab->createOption( array(
		    'name' => 'Adtraction',
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'API token',
			'id' => 'adtraction_api_token',
			'type' => 'text',
			'placeholder' => '63ECEB5B9AE3230262838CEE1C679DD152DFC0',
			'desc' => 'Can be found at "<a href="https://secure.adtraction.com/affiliate/editaffiliate.htm" target="_new">Account > Settings</a>" in your Adtraction Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'Channel ID',
			'id' => 'adtraction_channel_id',
			'type' => 'text',
			'placeholder' => '23423423234',
			'desc' => 'Can be found at "<a href="https://secure.adtraction.com/affiliate/viewwebsites.htm" target="_new">Account > My Channels</a>" in your Adtraction Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'iframe',
		    'height' => 50,
		    'url' => YMAS_ASSETS . 'programs/adtraction.html',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'save',
		));

	}

}