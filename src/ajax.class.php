<?php
namespace YoungMedia\Affiliate\Ajax;

class Ajax {
	
	public $earnings;

	public function __construct() {
		add_action( 'wp_ajax_programs_list', array(&$this, 'ListPrograms') );
		add_action( 'wp_ajax_earnings_list', array(&$this, 'ListEarnings') );
	}

	public function parseDate($date) {
		return date("Y-m-d H:i", strtotime($date));
	}

	public function ListPrograms() {
		
		global $ymas;

		$adtraction = $ymas->adtraction->programs();
		$adrecord = $ymas->adrecord->programs();

		$output = array_merge($adtraction, $adrecord);

		wp_send_json(array(
			'status' => 'ok',
			'results' => $output
		));
	}

	public function ListEarnings() {
		
		global $ymas;

		$adtraction = $ymas->adtraction->transactions();
		$adrecord = $ymas->adrecord->transactions();

		$output = array_merge($adtraction, $adrecord);

		wp_send_json(array(
			'status' => 'ok',
			'results' => $output
		));
	}

} new Ajax();