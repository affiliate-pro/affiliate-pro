<?php 
namespace YoungMedia\Affiliate;


/**
 * Plugin Name: Affiliate PRO
 * Description: Sync your wordpress site with your affiliate data. Adds short links and displays sales data to your WordPress dashboard. Currently supports: Adtraction
 * Author: Rasmus Kjellberg
 * Author URI: https://rasmuskjellberg.se
 * Tags: affiliate, adtraction, adrecord, affiliate api
 * Keywords: affiliate, adtraction, adrecord, affiliates api
 * Version: 1.1
 * Textdomain: ymas
 * Domain Path: /languages
*/

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) 
	exit; 


/**
 * Require plugin source files
*/
require_once('src/apis/adtraction.api.php');
require_once('src/apis/adrecord.api.php');
require_once('src/affiliate-pro.class.php');
require_once('src/adtraction.class.php');
require_once('src/adrecord.class.php');
require_once('src/double.class.php');
require_once('src/functions.php');
require_once('src/ajax.class.php');

/** 
 * Define default constants
*/
define('YMAS_ASSETS', plugins_url( 'static/', __FILE__ ));
define('YMAS_ROOT_DIR', trailingslashit(__DIR__));
define('YMAS_TEXTDOMAIN_PATH', plugin_basename( dirname( __FILE__ ) ) . '/languages' );


/**
 * Load plugin textdomain 
*/
add_action( 'plugins_loaded', 'YoungMedia\Affiliate\LoadPluginTextdomain' );


/**
 * Checks if Titan Framework is installed and activated
 * Initialize plugin only if TitanFramework is installed and activated
*/
require_once('titan-framework-checker.php');

if (class_exists('TitanFramework')) {

	add_action('after_setup_theme', array('\YoungMedia\Affiliate\Affiliate', 'InitAffiliatePlugin'));
}