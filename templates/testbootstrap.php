<?php
/**
 * PHPUnit bootstrap file.
 *
 * @author 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.14
 */

require_once __DIR__ . '/../vendor/autoload.php';

$phpunit_json = __DIR__ . '/../phpunit.json';
if ( file_exists( $phpunit_json ) ) {
    $phpunit_json = json_decode( file_get_contents( $phpunit_json ) ); 
} else {
    $phpunit_json = null;
}

define( 'WP_TESTS_DIR', $phpunit_json ? $phpunit_json->wp_test_dir : null );
define( 'WP_TESTS_CONFIG_FILE_PATH', $phpunit_json ? $phpunit_json->wp_tests_config_path : null );

$_tests_dir = defined( 'WP_TESTS_DIR' ) ? WP_TESTS_DIR : getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
    $_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
    echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_wpmvc_project() {
    // Load WordPress MVC project
    require dirname( dirname( __FILE__ ) ) . '/{0}';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_wpmvc_project' );

/**
 * Manually load 3rd party plugins that are dependencies.
 */
function _manually_load_external_plugins() {
    // ----------------------------
    //
    // Load plugin dependencies here, example below:
    //
    // require_once WP_CONTENT_DIR . '/plugins/woocommerce/woocommerce.php';
    //
    // ----------------------------
}

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

_manually_load_external_plugins();