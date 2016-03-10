<?php
namespace YoungMedia\Affiliate\Ajax;

class Ajax {
	
	public $earnings;

	public function __construct() {
		add_action( 'wp_ajax_programs_list', array(&$this, 'ListPrograms') );
		add_action( 'wp_ajax_earnings_list', array(&$this, 'ListEarnings') );
	}

	public function ListPrograms() {
		
		global $ymas;

		$output = $this->CombineLists('programs');

		wp_send_json(array(
			'status' => 'ok',
			'results' => $output
		));
	}

	public function ListEarnings() {
		
		global $ymas;

		$output = $this->CombineLists('transactions');

		wp_send_json(array(
			'status' => 'ok',
			'results' => $output
		));
	}

	public function CombineLists( $list_type ) {

		global $ymas;

		$output = array();
		
		foreach ($ymas->modules as $module) {

			$module_name = strtolower($module);

			$module = $ymas->$module_name;

			if (method_exists($module, $list_type) AND 
				is_array($module->$list_type()) AND
				$module->isConfigured() === true)
					$output = array_merge($output, $module->$list_type());

		}

		return $output;
	}

	public function parseDate($date) {
		return date("Y-m-d H:i", strtotime($date));
	}

} new Ajax();