<?php
namespace YoungMedia\Affiliate;


/**
 * Load plugin text domains 
*/
function LoadPluginTextdomain() {
  load_plugin_textdomain( 'ymas', false, YMAS_TEXTDOMAIN_PATH); 
}

/**
 * Parse date and return it in same format EVERYWHER
*/
function ParseDate( $date ) {
	return date("Y-m-d H:i", strtotime($date));
}