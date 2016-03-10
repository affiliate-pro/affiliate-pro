<?php 
namespace YoungMedia\Affiliate;


/**
 * Plugin Name: Affiliate PRO
 * Description: Import affiliate data and statistics to your Wordpress site. More info at: https://affiliate-pro.github.io
 * Plugin URL: https://affiliate-pro.github.io/
 * Author: Rasmus Kjellberg
 * Author URI: https://rasmuskjellberg.se
 * Tags: affiliate, adtraction, adrecord, clean links, affiliate api
 * Keywords: affiliate, adtraction, adrecord, clean links, affiliates api
 * Version: 1.3.1
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
require_once('src/affiliate-pro.class.php');
require_once('src/functions.php');
require_once('src/ajax.class.php');
require_once('src/modules.class.php');


/**
 * Require Module Wrappers
*/
require_once('modules/Module.php');
require_once('modules/AffiliateNetwork.php');

//require_once('modules/shortlinks/shortlinks.class.php');

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
require_once('src/frameworks/titan-framework/titan-framework-embedder.php');


add_action('after_setup_theme', array('\YoungMedia\Affiliate\Affiliate', 'InitAffiliatePlugin'));
