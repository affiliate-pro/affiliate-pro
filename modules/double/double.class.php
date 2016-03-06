<?php
namespace YoungMedia\Affiliate;


class Double {
	
	public function __construct() {
		add_action(	'tf_create_options', array(&$this, 'RegisterTitanOptions'));
	}

	public function isConfigured() {

		global $ymas;
		
		$api_token = $ymas->titan->getOption('double_api_token');
		$channelID = $ymas->titan->getOption('double_channel_id');
		
		if (empty($api_token) OR empty($channelID))
			return false;		

		return true;
	}

	public function RegisterTitanOptions() {

		global $ymas;
		
		$ymas->admin_settings_api_tab->createOption( array(
		    'name' => 'Double',
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'API token',
			'id' => 'double_api_token',
			'type' => 'text',
			'placeholder' => '5a5366b8m6as9c43d3ea8b27asd7c61c8be97616db',
			'desc' => 'Can be found at "<a href="https://www.double.net/panel/publisher/api-token/" target="_new">Integration &gt; API-token</a>" in your Double Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
			'name' => 'Channel ID',
			'id' => 'double_channel_id',
			'type' => 'text',
			'placeholder' => '12345',
			'desc' => 'Can be found at "<a href="https://www.double.net/panel/publisher/channels/" target="_new">Channels</a>" in your Double Dashboard',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'iframe',
		    'height' => 50,
		    'url' => YMAS_ASSETS . 'programs/double.html',
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'save',
		));

	}

}