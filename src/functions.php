<?php
namespace YoungMedia\Affiliate;


/**
 * Load Plugin Textdomain
*/
function LoadPluginTextdomain() {
  load_plugin_textdomain( 'ymas', false, YMAS_ROOT_DIR . 'languages' ); 
}