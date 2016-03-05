<?php
namespace YoungMedia\Affiliate;

/**
 * Plugin Name: Affiliate PRO
 * Plugin URI: https://rasmuskjellberg.se
 * Description: Sync your wordpress site with your affiliate data. Adds short links and displays sales data to your WordPress dashboard. Currently supports: Adtraction
 * Author: Rasmus Kjellberg
 * Author URI: https://rasmuskjellberg.se
 * Tags: affiliate, adtraction, affiliate api
 * Keywords: affiliate, adtraction, affiliates api
 * Version: 1.0
 * Textdomain: ymas
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) 
	exit; 


if (isset($_GET['ymas_redirect']))
	die(print_r($_GET));

/** 
 * Define default constants
*/
define('YMAS_ASSETS', plugins_url( 'static/', __FILE__ ));
define('YMAS_ROOT_DIR', trailingslashit(__DIR__));

// Require Titan Framework Checker
require_once('titan-framework-checker.php');

// Require source files
require_once('src/apis/adtraction.api.php');
require_once('src/apis/adrecord.api.php');
require_once('src/affiliate-pro.class.php');
require_once('src/adtraction.class.php');
require_once('src/adrecord.class.php');
require_once('src/double.class.php');

/**
 * Initialize plugin 
*/
if (class_exists('TitanFramework')) {
	add_action('after_setup_theme', array('\YoungMedia\Affiliate\Affiliate', 'InitAffiliatePlugin'));
}