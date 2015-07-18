# WP Template Performance #
**Contributors:** (nathanielks)  
**Tags:** template, theme, performance, profiling, debug  
**Requires at least:** 3.0.1  
**Tested up to:** 3.4.2  
**Stable tag:** 0.0.1  
**License:** MIT  
**License URI:** http://opensource.org/licenses/MIT  

A simple profiling tool used to determine which templates take a while to load. PHP >= 5.3.0, WIP

## Description ##

Note: This plugin is currently intended for developers as it requires modification of WP Core. Read on to find out why this is necessary and why you shouldn't modify Core.

In order to determine load time of WordPress-loaded templates, modification of the `[load_template](https://codex.wordpress.org/Function_Reference/load_template)` function is required. Unfortunately there aren't any hooks available to do this, so modification of WP Core is required. As this plugin is meant to be used in a non-production environment, doing so in a development/staging environment isn't the worst thing in the world. I absolutely, postively, whole-heartedly do _not_ recommend doing this in a production environment\*. Do so at your own risk.

\* Ideally you'll have as least three stages of development: local/development, staging, and production. Your staging environment should be identical to production, which is why you should not use this in production and should be used in the aforementioned stages.

## Installation ##

1. Upload `wp-template-performance` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Modify the `load_template` function located in `wp-includes/template.php`. Place `\WP_Template_Performance\Profile::start($_template_file);` before the following lines of code and `\WP_Template_Performance\Profile::end($_template_file);` after it.

	```
	if ( $require_once )
		require_once( $_template_file );
	else
		require( $_template_file );
	```

	Your new `load_template` function should look like this (as of WP 4.2.2):
	```
	function load_template( $_template_file, $require_once = true ) {
		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		if ( is_array( $wp_query->query_vars ) )
			extract( $wp_query->query_vars, EXTR_SKIP );

		if(class_exists('WP_Template_Performance')){
			\WP_Template_Performance\Profile::start($_template_file);
		}
		if ( $require_once )
			require_once( $_template_file );
		else
			require( $_template_file );
		if(class_exists('WP_Template_Performance')){
			\WP_Template_Performance\Profile::end($_template_file);
		}
	}
	```

1. The heaviest template will appear at the top of the list at the bottom of whatever page you're viewing, with how long it took to load as well as some other valuable statistics.
