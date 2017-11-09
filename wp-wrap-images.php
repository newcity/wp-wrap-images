<?php
/**
 * WP Wrap Images
 *
 *
 * @since             0.1.0
 * @package           WPWrapImages
 *
 * @wordpress-plugin
 * Plugin Name:       WP Wrap Images
 * Description:       Wrap all images inserted into the WYSIWYG in a figure tag,
 *                    even if no caption is present
 * Version:           0.1.0
 * Author:            NewCity  <geeks@insidenewcity.com>
 * Author URI:        http://insidenewcity.com
 * License:           GPL-2.0
 */


 // If this file is called directly, abort.
 if ( ! defined( 'WPINC' ) ) {
     die;
 }

require_once( dirname( __FILE__ ) . '/class-wp-wrap-images.php');

function wrap_images() {
	$image_wrapper = new WPWrapImages();
}

wrap_images();
