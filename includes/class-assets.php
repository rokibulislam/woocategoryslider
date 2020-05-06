<?php

namespace WCCS;

class Assets {

	public function __construct() {
		if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'register' ), 5 );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 5 );
        }
	}

	public function enqueue_admin_scripts( $hook ) {
        wp_enqueue_script( 'wccs-admin' );
        $localize_script = $this->get_admin_localized_scripts();
        wp_localize_script( 'wccs-admin', 'wccs', $localize_script );
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $styles = $this->get_styles();
        $scripts = $this->get_scripts();

        $this->register_scripts( $scripts );
        $this->register_styles( $styles );

        do_action( 'wccs_register_scripts' );
    }


    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : WCCS_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, WCCS_VERSION );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $scripts = [
            'wccs-admin' => [
                'src'       => WCCS_ASSETS . '/js/admin.js',
                'deps'      => [ 'jquery' ],
                'in_footer' => true
            ],
            'wccs-front' => [
                'src'       => WCCS_ASSETS . '/js/script.js',
                'deps'      => [ 'jquery' ],
                'in_footer' => true
            ],
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {
        $styles = [];
        return $styles;
    }


    public function get_admin_localized_scripts() {

        return apply_filters( 'wccs_admin_localize_script', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'wccs_plugin' ),
            'rest'    => array(
                'root'    => esc_url_raw( get_rest_url() ),
                'nonce'   => wp_create_nonce( 'wp_rest' ),
                'version' => 'wccs_plugin/v1',
            ),
        ]);
    }
}