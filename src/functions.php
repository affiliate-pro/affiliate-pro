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
function group_by_date($input_dates, $column) {

	$dates = array();

	foreach ($input_dates as $date) {
		$index = date("Y-m-d", strtotime($date[$column]));
		$dates[$index][] = $date;
	}

	return $dates;
}

/**
 * Get last week dates with chart labels
*/
function last_seven_days_labels() {

	global $ymas;
	
	$dates = array();
	foreach (last_seven_days() as $date) {
		$date = \date("D d/m", strtotime($date));
		array_push($dates, $date);
	}

	$dates[6] = $dates[6] . ' (' . __('Yesterday', 'ymas') . ')';
	$dates[7] = $dates[7] . ' (' . __('Today', 'ymas') . ')';

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

