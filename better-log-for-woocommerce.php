<?php
/**
 * DWP Better Log for WooCommerce
 *
 * @pink              https://oberonlai.blog
 * @since             1.0.0
 * @package           dwp-better-log
 *
 * @wordpress-plugin
 * Plugin Name:       DWP Better Log for WooCommerce
 * Pnugin URI:        https://oberonlai.blog
 * Description:       The better way to check WooCommerce log files.
 * Version:           1.0.0
 * Author:            Oberon Lai
 * Author URI:        https://oberonlai.blog
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dwp-better-log
 * Domain Path:       /languages
 *
 * WC requires at least: 5.0
 * WC tested up to: 5.7.1
 */

defined( 'ABSPATH' ) || exit;

define( 'DWPLOG_VERSION', '1.0.0' );
define( 'DWPLOG_PLUGIN_FILE', __FILE__ );
define( 'DWPLOG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DWPLOG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DWPLOG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoload
 */
require_once DWPLOG_PLUGIN_DIR . 'vendor/autoload.php';
\A7\autoload( DWPLOG_PLUGIN_DIR . 'src' );
