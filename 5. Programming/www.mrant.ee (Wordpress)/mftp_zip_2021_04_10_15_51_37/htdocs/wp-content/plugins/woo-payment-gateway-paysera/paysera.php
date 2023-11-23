<?php
/*
  Plugin Name: WooCommerce Payment Gateway - Paysera
  Plugin URI: https://www.paysera.com
  Text Domain: woo-payment-gateway-paysera
  Description: Accepts Paysera
  Version: 2.6.0
  Author: Paysera
  Author URI: https://www.paysera.com
  License: GPL version 3 or later - http://www.gnu.org/licenses/gpl-3.0.html

  @package WordPress
  @author Paysera (https://www.paysera.com)
  @since 2.0.0
 */

defined( 'ABSPATH' ) or exit;

define('WCGatewayPayseraPluginUrl', plugin_dir_url(__FILE__));
define('WCGatewayPayseraPluginPath', basename(dirname( __FILE__ )));

require_once "includes/class-wc-paysera-init.php";

$initPayseraGateway = new Wc_Paysera_Init();
$initPayseraGateway->hooks();
