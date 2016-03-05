<?php
namespace YoungMedia\Affiliate;


/**
 * Load plugin text domains 
*/
function LoadPluginTextdomain() {
  load_plugin_textdomain( 'ymas', false, YMAS_TEXTDOMAIN_PATH); 
}