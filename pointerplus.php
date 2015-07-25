<?php

/**
 * Plugin Name: Simple Pointers with PointerPlus
 * Plugin URI: https://github.com/Mte90/pointerplus
 * Description: Facilitates the creation of single pointers for WP Admin.
 * Author: Mte90 & QueryLoop
 * Author URI: http://mte90.net
 * Version: 1.0.0
 * Text Domain: your-domain
 * Domain Path: /languages
 */

if ( !defined( 'WPINC' ) ) {
	die;
}

// Load and initialize class. If you're loading the PointerPlus class in another plugin or theme, this is all you need.
require_once 'class-pointerplus.php';
$pointerplus = new PointerPlus( array( 'prefix' => 'your-domain' ) );
// With this line of code you can reset all the pointer with your prefix
// $pointerplus->reset_pointer();

/**
 *  Everything after this point is only for pointerplus configuration
 */

/**
 * Initialize localization routines. If you're already doing it in your plugin or theme dismiss this.
 *
 * @since 1.0.0
 */
function pointerplus_load_localization() {
	load_plugin_textdomain( 'your-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'pointerplus_load_localization' );

/**
 * Add pointers.
 *
 * @param $pointers
 * @param $prefix for your pointers
 *
 * @return mixed
 */
function custom_initial_pointers( $pointers, $prefix ) {
	/*
	 * Default parameters:
	  $defaults = array(
	  'class' => 'pointerplus',
	  'width' => 300, //fixed value
	  'align' => 'middle',
	  'edge' => 'left',
	  'post_type' => array(),
	  'pages' => 'array(),
	  'jsnext' => '' //empty [t = pointer instance, $ = jQuery]
	  'phpcode' => function() //executed on admin_notices action
	  'show' => 'open' //default
	  );
	 */

	return array_merge( $pointers, array(
		$prefix . '_settings' => array(
			'selector' => '#menu-settings',
			'title' => __( 'PointerPlus Test', 'your-domain' ),
			'text' => __( 'The plugin is active and ready to start working.', 'your-domain' ),
			'width' => 260,
			'icon_class' => 'dashicons-admin-settings',
			'jsnext' => "button = jQuery('<a id=\"pointer-close\" class=\"button action thickbox\" href=\"#TB_inline?width=700&height=500&inlineId=menu-popup\">" . __( 'Open Popup' ) . "</a>');
                    button.bind('click.pointer', function () {
                        t.element.pointer('close');
                    });
                    return button;",
			'phpcode' => custom_phpcode_thickbox()
		),
		$prefix . '_posts' => array(
			'selector' => '#menu-posts',
			'title' => __( 'PointerPlus for Posts', 'your-domain' ),
			'text' => __( 'One more pointer.', 'your-domain' ),
			'post_type' => array( 'post' ),
			'icon_class' => 'dashicons-admin-post',
			'width' => 350,
		),
		$prefix . '_pages' => array(
			'selector' => '#menu-pages',
			'title' => __( 'PointerPlus Pages', 'your-domain' ),
			'text' => __( 'A pointer for pages.', 'your-domain' ),
			'post_type' => array( 'page' ),
			'icon_class' => 'dashicons-admin-post'
		),
		$prefix . '_users' => array(
			'selector' => '#menu-users',
			'title' => __( 'PointerPlus Users', 'your-domain' ),
			'text' => __( 'A pointer for users.', 'your-domain' ),
			'pages' => array( 'users.php' ),
			'icon_class' => 'dashicons-admin-users'
		),
		$prefix . '_settings_tab' => array(
			'selector' => '#show-settings-link',
			'title' => __( 'PointerPlus Help', 'your-domain' ),
			'text' => __( 'A pointer with action.', 'your-domain' ),
			'edge' => 'top',
			'align' => 'right',
			'icon_class' => 'dashicons-welcome-learn-more',
			'jsnext' => "button = jQuery('<a id=\"pointer-close\" class=\"button action\">" . __( 'Next' ) . "</a>');
                    button.bind('click.pointer', function () {
                        t.element.pointer('close');
						jQuery('#contextual-help-link').pointer('open');
                    });
                    return button;"
		),
		$prefix . '_contextual_tab' => array(
			'selector' => '#contextual-help-link',
			'title' => __( 'PointerPlus Help', 'your-domain' ),
			'text' => __( 'A pointer for help tab.<br>Go to Posts, Pages or Users for other pointers.', 'your-domain' ),
			'edge' => 'top',
			'align' => 'right',
			'icon_class' => 'dashicons-welcome-learn-more',
			'show' => 'close'
		)
			) );
}
// Your prefix
add_filter( 'your-domain' . 'pointerplus_list', 'custom_initial_pointers', 10, 2 );

/**
 * Function created for support PHP => 5.2
 * You can use the anonymous function that are not supported by PHP 5.2
 * 
 * @since 1.0.0
 */
function custom_phpcode_thickbox() {
	add_thickbox();
	echo '<div id="menu-popup" style="display:none;">
			<p style="text-align: center;">
				 This is my hidden content! It will appear in ThickBox when the link is clicked.
				 <iframe width="560" height="315" src="https://www.youtube.com/embed/EaWfDuXQfo0" frameborder="0" allowfullscreen></iframe>
			</p>
		</div>';
}
