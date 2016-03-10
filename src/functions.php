<?php
namespace YoungMedia\Affiliate;


/**
 * Load plugin text domains 
*/
function LoadPluginTextdomain() {
  load_plugin_textdomain( 'ymas', false, YMAS_TEXTDOMAIN_PATH); 
}

/**
 * Group array results by date
*/
function group_by_date($input, $column) {

	$dates = array();

	foreach ($input as $date) {
		$index = date("Y-m-d", strtotime($date[$column]));
		$dates[$index][] = $date;
	}

	return $dates;
}

/**
 * Group date array by period range (today,week,month)
*/
function group_dates_by_range($input, $range) {

	global $ymas;

	$today = strtotime(date('Y-m-d 00:00'));
	
	$week = new \DateTime( "7 days ago", new \DateTimeZone($ymas->timezone));
	$week = strtotime($week->format("Y-m-d 00:00"));
	
	$month = new \DateTime( "30 days ago", new \DateTimeZone($ymas->timezone));
	$month = strtotime($month->format("Y-m-d 00:00"));

	$available_ranges = array(
		'today' => $today,
		'week' => $week,
		'month' => $month
	);

	if (!isset($available_ranges[$range]))
		return $input;

	$output = array();

	foreach ($input as $row) {
		if (strtotime($row['event_date']) >= $available_ranges[$range])
			$output[] = $row;
	}

	return $output;
}

/**
 * Group array results by network column
*/
function group_by_network($input) {

	$output = array();

	foreach ($input as $row) {
		$index = strtolower($row['network']);
		$output[$index][] = $row;
	}

	return $output;
}

/**
 * Get last week dates with chart labels
*/
function last_seven_days_labels() {

	global $ymas;
	
	$dates = array();
	foreach (last_seven_days() as $date) {
		$date = \date_i18n("D d/m", strtotime($date));
		array_push($dates, $date);
	}

	$dates[6] = __('yesterday', 'ymas');
	$dates[7] = __('today', 'ymas');

	return $dates;
}

/**
 * Get last week dates
*/
function last_seven_days() {

	global $ymas;
	
	$now = new \DateTime( "7 days ago", new \DateTimeZone($ymas->timezone));
	$interval = new \DateInterval( 'P1D'); // 1 Day interval
	$period = new \DatePeriod( $now, $interval, 7); // 7 Days

	$dates = array();
	foreach( $period as $day) {

	    $date = $day->format( 'Y-m-d');

	    array_push($dates, $date);
	}

	return $dates;
}

