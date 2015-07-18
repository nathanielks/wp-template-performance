<?php

namespace WP_Template_Performance;

class Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      WP_Template_Performance/Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function __construct() {

		$this->plugin_name = 'wp-template-performance';
		$this->version = '0.0.1';

		$this->load_dependencies();
		$this->profile_woocommerce();

	}

	/**
	 * Define autoloader for plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		spl_autoload_register(function ($class) {

			// project-specific namespace prefix
			$prefix = 'WP_Template_Performance\\';

			// base directory for the namespace prefix
			$base_dir = __DIR__ . '/';

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

		$this->loader = new Loader();

	}


	/**
	 * Add profiling functions to relevant WooCommerce hooks.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function profile_woocommerce(){

		add_action( 'woocommerce_before_template_part', function($template_name, $template_path, $located, $args){
			\WP_Template_Performance\Profile::start($located);
		}, 0, 4);

		add_action( 'woocommerce_after_template_part', function($template_name, $template_path, $located, $args){
			\WP_Template_Performance\Profile::end($located);
		}, 9999, 4);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since    0.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since    0.0.1
	 * @return    WP_Template_Performance/Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since    0.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
