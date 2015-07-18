<?php
/**
 * Plugin Name: WP Template Performance
 * Text Domain: wtp
 * Author:      Nathaniel Schweinberg
 * Version:     0.0.1
 * Author URI:  http://twitter.com/nathanielks
 * License:     MIT
 *
 *
 **/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'includes/Plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_wp_template_performance() {
	$plugin = new \WP_Template_Performance\Plugin();
	$plugin->run();
}
run_wp_template_performance();

add_action('shutdown', function(){
	var_dump(\WP_Template_Performance\Profile::get_statistics(true));
});
