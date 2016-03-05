<?php
namespace YoungMedia\Affiliate;


class Adrecord {
	
	public function __construct() {
		add_action(	'tf_create_options', array(&$this, 'RegisterTitanOptions'));
	}

	public function isConfigured() {

		global $ymas;
		
		$api_token = $ymas->titan->getOption('adrecord_api_token');
		$channelID = $ymas->titan->getOption('adrecord_channel_id');
		
		if (empty($api_token) OR empty($channelID))
			return false;		

		return true;
	}

	public function tracking_url( $program_id ) {

		global $ymas;

		$channel_id = $ymas->titan->getOption('adrecord_channel_id');
		
		return "http://click.adrecord.com?c={$channel_id}&p={$program_id}";
	}

	public function RegisterTitanOptions() {

		global $ymas;
		
		$ymas->admin_settings_api_tab->createOption( array(
		    'name' => 'Adrecord',
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'API key',
			'id' => 'adrecord_api_token',
			'type' => 'text',
			'placeholder' => 'b3q2OIVr4wmJIagsk',
			'desc' => 'Can be found at "<a href="https://www.adrecord.com/en/advanced/api" target="_new">Advanced &gt; API</a>" in your Adrecord Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'Channel ID',
			'id' => 'adrecord_channel_id',
			'type' => 'text',
			'placeholder' => '12345',
			'desc' => 'Can be found at "<a href="https://www.adrecord.com/en/channels" target="_new">Channels</a>" in your Adrecord Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'iframe',
		    'height' => 50,
		    'url' => YMAS_ASSETS . 'programs/adrecord.html',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'save',
		));

	}

}