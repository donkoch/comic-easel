<?php

// actions
add_action('admin_menu', 'ceo_add_menu_pages');
add_action('wp_dashboard_setup', 'ceo_add_dashboard_widgets' );

// add_action( 'admin_notices', 'ceo_test_information' );


// INIT ComicPress Manager pages & hook activation of scripts per page.
function ceo_add_menu_pages() {
	$menu_location = 'edit.php?post_type=comic';
	$plugin_title = __('Comic Easel', 'comiceasel');
	$image_title = __('Image Manager', 'comiceasel');
	$chapter_title = __('Chapter Manager', 'comiceasel');
	$config_title = __('Config', 'comiceasel');
	$debug_title = __ ('Debug', 'comiceasel');
	
	// the ceo_pluginfo used here actually initiates it.
	$image_manager_hook = add_submenu_page($menu_location,  $plugin_title . ' - ' . $image_title, $image_title, 'edit_theme_options', 'comiceasel-image-manager', 'ceo_image_manager');
	$chapter_manager_hook = add_submenu_page($menu_location, $plugin_title . ' - ' . $chapter_title, $chapter_title, 'edit_theme_options', 'comiceasel-chapter-manager', 'ceo_chapter_manager');
	$config_hook = add_submenu_page($menu_location,   $plugin_title . ' - ' . $config_title, $config_title, 'edit_theme_options', 'comiceasel-config', 'ceo_manager_config');
	$debug_hook = add_submenu_page($menu_location,   $plugin_title . ' - ' . $debug_title, $debug_title, 'edit_theme_options', 'comiceasel-debug', 'ceo_debug');

	// Scripts for the chapter manager page.
	// Notice how its checking the _GET['page'], do this for the other areas
	// if you need to execute scripts on the particular areas
	if (isset($_GET['page'])) {
		switch ($_GET['page']) {
			case 'comiceasel-chapter-manager':
				add_action('admin_print_scripts-' . $chapter_manager_hook, 'ceo_load_scripts_chapter_manager');
				break;
			case 'comiceasel-image-manager':
				add_action('admin_print_scripts-' . $image_manager_hook, 'ceo_load_scripts_image_manager');
				add_action('admin_print_styles-' . $image_manager_hook, 'ceo_load_styles_image_manager');
				break;
		}
	}
}

function ceo_load_scripts_chapter_manager() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-sortable');
}

function ceo_load_scripts_image_manager() {
	wp_enqueue_script('comiceasel-fileuploader-script', ceo_pluginfo('plugin_url') .'/js/fileuploader.js');		
}

function ceo_load_styles_image_manager() {
	wp_enqueue_style('comiceasel-fileuploader-style', ceo_pluginfo('plugin_url') .'/css/fileuploader.css');
}

 


// This is done this way to *not* load pages unless they are called, self sufficient code, but since attached to the ceo-core it can use the library in core.
function ceo_image_manager() {
	require_once('ceo-image-manager.php');
}

function ceo_chapter_manager() {
	require_once('ceo-chapter-manager.php');
}

function ceo_manager_config() {
	require_once('ceo-config.php');
}

function ceo_debug() {
	require_once('ceo-debug.php');
}

function ceo_upload() {
	require_once('ceo-upload.php');
}

/**
 * This set of functions is for displaying the dashboard feed widget.
 *
 */
function ceo_dashboard_feed_widget() {
	wp_widget_rss_output('http://frumph.net/?feed=rss2', array('items' => 2, 'show_summary' => true));
} 

function ceo_add_dashboard_widgets() {
	wp_add_dashboard_widget('ceo_dashboard_widget', 'Frumph.NET News', 'ceo_dashboard_feed_widget');	
}




?>
