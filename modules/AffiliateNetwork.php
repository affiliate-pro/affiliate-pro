<?php
namespace YoungMedia\Affiliate;


/**
 * Module
 * This is the base and example class for modules
*/
class AffiliateNetwork extends Module {
	
	/** 
	 * Filter dates when loading transactions etc.
	*/
	public $from_date = "2016-01-01 00:00";
	public $to_date = "2016-12-31 23:59";

	/**
	 * Options 
	 * Create admin menu options
	*/
	public function Options() {

		global $ymas;
		
		$ymas->admin_settings_api_tab->createOption( array(
		    'name' => 'Demo Network API',
		    'type' => 'heading',
		    'toggle' => true,
		));

		$ymas->admin_settings_api_tab->createOption( array(
		    'type' => 'save',
		));
	}

	/**
	 * Connect with service API and 
	 * return an array of programs.
	 * 
	 * @string: name
	 * @string: category
	 * @string: tracking_url
	 * @string: network 
	 * @return array
	*/
	public function programs() {
		
		return array(
			array(
				'name' => 'Demo Program', 
				'category' => 'Demostration Services', 
				'tracking_url' => 'http://johndoe.com', 
				'network' => 'Demo Network'),
		);
	}

	/**
	 * Connect with service API and 
	 * return an array of transactions.
	 * 
	 * @string: name
	 * @int: transaction
	 * @date: click_date
	 * @date: event_date
	 * @int: commission
	 * @string: currency
	 * @string: network 
	 * @return array
	*/
	public function transactions() {
		
		return array(
			array(
				'name' => 'Demo program',
				'transaction' => 'Demotransaction',
				'click_date' => ParseDate(strtotime(date("Y-m-d H:i"))),
				'event_date' => ParseDate(strtotime(date("Y-m-d H:i"))),
				'commission' => '120',
				'currency' => 'SEK',
				'network' => 'Demo Network',
			),
		);
	}

}