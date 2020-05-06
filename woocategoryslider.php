<?php

/**
 * Plugin Name: WC Category Slider
 * Description: Description
 * Plugin URI: http://#
 * Author: Author
 * Author URI: http://#
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: text-domain
 * Domain Path: domain/path
 */

/*
    Copyright (C) Year  Author  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class WC_Category_Slider {

	private $version   = '1.0.0';
	private $container = [];

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_classes();

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

		add_action( 'woocommerce_loaded', array( $this, 'init_plugin' ) );
	}

	public function define_constants() {
		define( 'WCCS_VERSION', $this->version );
        define( 'WCCS_FILE', __FILE__ );
        define( 'WCCS_PATH', dirname( WCCS_FILE ) );
        define( 'WCCS_INCLUDES', WCCS_PATH . '/includes' );
        define( 'WCCS_URL', plugins_url( '', WCCS_FILE ) );
        define( 'WCCS_ASSETS', WCCS_URL . '/assets' );
	}

	public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }


	public static function init() {
		static $instance = false;

		if( !$instance ) {
			$instance = new Self();
		}

		return $instance;
	}

	public function init_classes() {
		if ( is_admin() ) {
			$this->container['assets']    = new WCCS\Assets();
			$this->container['installer'] = new WCCS\Installer();
			$this->container['metabox']   = new WCCS\Metabox();
			$this->container['shortcode'] = new WCCS\Shortcode();
        }
	}

	public function includes() {
		if ( is_admin() ) {
            require_once WCCS_INCLUDES . '/class-assets.php';
            require_once WCCS_INCLUDES . '/class-custom-post.php';
            require_once WCCS_INCLUDES . '/class-installer.php';
            require_once WCCS_INCLUDES . '/class-metabox.php';
        }

        require_once WCCS_INCLUDES . '/class-shortcode.php';
        require_once WCCS_INCLUDES . '/functions.php';

	}

	public function activate() {

	}

	public function deactivate() {

	}

	public function init_plugin() {
		add_action( 'init', array( $this, 'localization_setup' ) );
	}

	public function localization_setup() {
		load_plugin_textdomain( 'wccs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

function WCCS() {
	return WC_Category_Slider::init();
}

WCCS();