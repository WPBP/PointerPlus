<?php

/**
 * @package   PointerPlus
 * @author    QueryLoop & Mte90
 * @license   GPL-3.0+
 * @link      https://daniele.tech
 * @copyright 2014-2022 GPL
 */

/**
 * Super pointer creation for WP Admin
 */
class PointerPlus {

	/**
	* Prefix strings like styles, scripts and pointers IDs
	* @var string
	*/
	var $prefix = 'pointerplus';
	var $pointers = array();

	/**
	* Construct the class parameter
	*
	* @param array $args Parameters of class.
	* @return void
	*/
	function __construct( $args = array() ) {
		if ( isset( $args[ 'prefix' ] ) ) {
			$this->prefix = $args[ 'prefix' ];
		}
		add_action( 'current_screen', array( $this, 'maybe_add_pointers' ) );
	}

	/**
	* Set pointers and its options
	*
	* @since 1.0.0
	*/
	function initial_pointers() {
		global $pagenow;
		$defaults = array(
			'class' => '',
			'width' => 300, //only fixed value
			'align' => 'middle',
			'edge' => 'left',
			'post_type' => array(),
			'pages' => array(),
			'icon_class' => ''
		);
		$screen = \get_current_screen();
		$current_post_type = isset( $screen->post_type ) ? $screen->post_type : false;
		$search_pt = false;

		$pointers = \apply_filters( $this->prefix . '-pointerplus_list', array(
			// Pointers are added through the 'initial_pointerplus' filter
			), $this->prefix );

		foreach ( $pointers as $key => $pointer ) {
			$pointers[ $key ] = wp_parse_args( $pointer, $defaults );
			$search_pt = false;
			// Clean from null ecc
			$pointers[ $key ][ 'post_type' ] = array_filter( $pointers[ $key ][ 'post_type' ] );
			if ( !empty( $pointers[ $key ][ 'post_type' ] ) ) {
				if ( !empty( $current_post_type ) ) {
					if ( is_array( $pointers[ $key ][ 'post_type' ] ) ) {
						// Search the post_type
						foreach ( $pointers[ $key ][ 'post_type' ] as $value ) {
							if ( $value === $current_post_type ) {
								$search_pt = true;
							}
						}
						if ( $search_pt === false ) {
							unset( $pointers[ $key ] );
						}
					} else {
						new \WP_Error( 'broke', \__( 'PointerPlus Error: post_type is not an array!' ) );
					}
					// If not in CPT view remove all the pointers with post_type
				} else {
					unset( $pointers[ $key ] );
				}
			}
			// Clean from null ecc
			if ( isset( $pointers[ $key ][ 'pages' ] ) ) {
				if ( is_array( $pointers[ $key ][ 'pages' ] ) ) {
					$pointers[ $key ][ 'pages' ] = array_filter( $pointers[ $key ][ 'pages' ] );
				}

				if ( !empty( $pointers[ $key ][ 'pages' ] ) ) {
					if ( is_array( $pointers[ $key ][ 'pages' ] ) ) {
						// Search the page
						foreach ( $pointers[ $key ][ 'pages' ] as $value ) {
							if ( $pagenow === $value ) {
								$search_pt = true;
							}
						}

						if ( $search_pt === false ) {
							unset( $pointers[ $key ] );
						}
					} else {
						new \WP_Error( 'broke', __( 'PointerPlus Error: pages is not an array!' ) );
					}
				}
			}
		}

		return $pointers;
	}

	/**
	* Check that pointers haven't been dismissed already. If there are pointers to show, enqueue assets.
	*
	* @since 1.0.0
	*/
	function maybe_add_pointers() {
		// Get default pointers that we want to create
		$default_keys = $this->initial_pointers();

		// Get pointers dismissed by user
		$dismissed = explode( ',', \get_user_meta( \get_current_user_id(), 'dismissed_wp_pointers', true ) );

		// Check that our pointers haven't been dismissed already
		$diff = array_diff_key( $default_keys, array_combine( $dismissed, $dismissed ) );

		// If we have some pointers to show, save them and start enqueuing assets to display them
		if ( !empty( $diff ) ) {
			$this->pointers = $diff;
			\add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

			foreach ( $diff as $pointer ) {
				if ( isset( $pointer[ 'phpcode' ] ) ) {
					\add_action( 'admin_notices', $pointer[ 'phpcode' ] );
				}
			}
		}
		$this->pointers[ 'l10n' ] = array( 
			'next' => __( 'Next' ),
			'dismiss' => __( 'Dismiss' ),
		);
	}

	/**
	* Enqueue pointer styles and scripts to display them.
	*
	* @since 1.0.0
	*/
	function admin_enqueue_assets() {
		$base_url = \plugins_url( '', __FILE__ );
		\wp_enqueue_style( $this->prefix, $base_url . '/pointerplus.css', array( 'wp-pointer' ) );
		\wp_enqueue_script( $this->prefix, $base_url . '/pointerplus.js?var=' . str_replace( '-', '_', $this->prefix ) . '_pointerplus', array( 'wp-pointer' ) );
		\wp_localize_script( $this->prefix, str_replace( '-', '_', $this->prefix ) . '_pointerplus', \apply_filters( $this->prefix . '_pointerplus_js_vars', $this->pointers ) );
	}

	/**
	* Reset pointer
	*
	* @since 1.0.0
	*/
	function reset_pointer() {
		\add_action( 'current_screen', array( $this, '_reset_pointer' ), 0 );
	}

	/**
	* Reset pointer in hook
	*
	* @since 1.0.0
	*/
	function _reset_pointer( $id = 'me' ) {
		if ( $id === 'me' ) {
			$id = \get_current_user_id();
		}

		$pointers = explode( ',', \get_user_meta( $id, 'dismissed_wp_pointers', true ) );
		foreach ( $pointers as $key => $pointer ) {
			if ( strpos( $pointer, $this->prefix ) === 0 ) {
			unset( $pointers[ $key ] );
			}
		}

		$meta = implode( ',', $pointers );
		\update_user_meta( \get_current_user_id(), 'dismissed_wp_pointers', $meta );
	}

}
