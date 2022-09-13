# PointerPlus
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)
![Downloads](https://img.shields.io/packagist/dt/wpbp/pointerplus.svg)    

Super pointer creation for WP Admin

## Install

`composer require wpbp/pointerplus:dev-master`

## Features

* Pointer autoposition by selector (WP-Pointer API)
* Remember the Pointer  (WP-Pointer API)
* Show by Post Type
* Show by page (ex: users.php)
* Custom dashicon (https://developer.wordpress.org/resource/dashicons)
* Hide feature
* Custom button with JS callback
* Custom PHP Code to execute at loading of the page
* Reset the pointer by prefix (by user id)
* Wait the available selector
* Create a wizard-like system

## Example

```php
$pointerplus = new PointerPlus( array( 'prefix' => 'your-pointer-domain' ) );
// With this line of code you can reset all the pointer with your prefix
// $pointerplus->reset_pointer();

function custom_initial_pointers( $pointers, $prefix ) {
	/*
	  Default parameters:
	  $defaults = array(
	  'class' => 'pointerplus',
	  'width' => 300, //fixed value
	  'align' => 'middle',
	  'edge' => 'left',
	  'post_type' => array(),
	  'pages' => array(),
	  'next' => 'the id of the pointer to jump on Next button click',
	  // Or a custom js solution
	  'jsnext' => '' //empty [t = pointer instance, $ = jQuery]
	  'phpcode' => function() //executed on admin_notices action
	  'show' => 'open' //default,
	  'next' => 'id' // On close the pointer show to that pointer
	  );
	 */

	return array_merge( $pointers, array(
		$prefix . '_settings' => array(
			'selector' => '#menu-settings',
			'title' => __( 'PointerPlus Test', 'your-pointer-domain' ),
			'text' => __( 'The plugin is active and ready to start working.', 'your-pointer-domain' ),
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
			'title' => __( 'PointerPlus for Posts', 'your-pointer-domain' ),
			'text' => __( 'One more pointer.', 'your-pointer-domain' ),
			'post_type' => array( 'post' ),
			'icon_class' => 'dashicons-admin-post',
			'width' => 350,
		),
		$prefix . '_pages' => array(
			'selector' => '#menu-pages',
			'title' => __( 'PointerPlus Pages', 'your-pointer-domain' ),
			'text' => __( 'A pointer for pages.', 'your-pointer-domain' ),
			'post_type' => array( 'page' ),
			'icon_class' => 'dashicons-admin-post'
		),
		$prefix . '_users' => array(
			'selector' => '#menu-users',
			'title' => __( 'PointerPlus Users', 'your-pointer-domain' ),
			'text' => __( 'A pointer for users.', 'your-pointer-domain' ),
			'pages' => array( 'users.php' ),
			'icon_class' => 'dashicons-admin-users'
		),
		$prefix . '_settings_tab' => array(
			'selector' => '#show-settings-link',
			'title' => __( 'PointerPlus Help', 'your-pointer-domain' ),
			'text' => __( 'A pointer with action.', 'your-pointer-domain' ),
			'edge' => 'top',
			'align' => 'right',
			'icon_class' => 'dashicons-welcome-learn-more',
			'next' => $prefix . '_contextual_tab'
		),
		$prefix . '_contextual_tab' => array(
			'selector' => '#contextual-help-link',
			'title' => __( 'PointerPlus Help', 'your-pointer-domain' ),
			'text' => __( 'A pointer for help tab.<br>Go to Posts, Pages or Users for other pointers.', 'your-pointer-domain' ),
			'edge' => 'top',
			'align' => 'right',
			'icon_class' => 'dashicons-welcome-learn-more',
			'show' => 'close'
		)
			) );
}
// Your prefix
add_filter( 'your-pointer-domain-pointerplus_list', 'custom_initial_pointers', 10, 2 );

function custom_phpcode_thickbox() {
	add_thickbox();
	echo '<div id="menu-popup" style="display:none;">
			<p style="text-align: center;">
				 This is my hidden content! It will appear in ThickBox when the link is clicked.
				 <iframe width="560" height="315" src="https://www.youtube.com/embed/EaWfDuXQfo0" frameborder="0" allowfullscreen></iframe>
			</p>
		</div>';
}
```

