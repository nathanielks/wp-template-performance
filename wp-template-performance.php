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

/**
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'WP_Template_Performance\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/includes/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

add_action('shutdown', function(){
	var_dump(\WP_Template_Performance\Profile::get_statistics(true));
});
