<?php
namespace YoungMedia\Affiliate\Ajax;


class Earnings extends Ajax {

	public function __construct() {
		add_action( 'wp_ajax_earnings_list', array(&$this, 'ListEarnings') );
	}

	public function ListEarnings() {
		
		global $ymas;

		$adtraction = $this->FilterAdtraction($ymas->adtraction->api->transactions());
		$adrecord = $this->FilterAdrecord($ymas->adrecord->api->transactions());

		$output = array_merge($adtraction, $adrecord);

		wp_send_json(array(
			'status' => 'ok',
			'results' => $output
		));
	}

	public function FilterAdrecord($input) {

		$output = array();

		foreach ($input as $i) {

			if (!isset($i->program->name) OR 
				!isset($i->commissionName) OR
				!isset($i->type) OR
				!isset($i->click) OR
				!isset($i->changes[0]->date) OR
				!isset($i->commission))
				continue;

			$output[] = array(
				'name' => $i->program->name,
				'transaction' => $i->commissionName . ' - ' . $i->type,
				'click_date' => $this->parseDate($i->click),
				'event_date' => $this->parseDate($i->changes[0]->date),
				'commission' => $i->commission / 100,
				'currency' => 'SEK',
				'network' => 'Adrecord',
			);
		}

		return $output;
	}

	public function FilterAdtraction($input) {

		$output = array();

		foreach ($input as $i) {

			if (!isset($i->programName) OR 
				!isset($i->transactionName) OR
				!isset($i->clickDate) OR
				!isset($i->transactionDate) OR
				!isset($i->commission) OR
				!isset($i->currency))
				continue;

			$output[] = array(
				'name' => $i->programName,
				'transaction' => $i->transactionName,
				'click_date' => $this->parseDate($i->clickDate),
				'event_date' => $this->parseDate($i->transactionDate),
				'commission' => $i->commission,
				'currency' => $i->currency,
				'network' => 'Adtraction',
			);
		}

		return $output;
	}

	public function parseDate($date) {
		return date("Y-m-d H:i", strtotime($date));
	}

} 
