=== WP Wrap Images ===
Contributors: jajanowiak
Tags: wysiwyg, images, media, shortcodes
Requires at least: 4.6
Tested up to: 4.8.3
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Wraps all images inserted into a WYSIWYG field from the Add Media interface with the
[caption] shortcode, even if no caption is assigned to the image.

== Description ==

Wraps all images inserted into a WYSIWYG field from the Add Media interface with the `[caption]` shortcode, even if no caption is assigned to the image. The end result is that all images will be wrapped with a `<figure>` element, even if no caption is assigned to the image. Compatible with the standard Wordpress tinyMCE editor and the [Advanced Custom Fields](https://www.advancedcustomfields.com/) WYSIWYG field type.

For bleeding-edge updates, or to report issues, visit the [WP Wrap Images Github repository](https://github.com/newcity/wp-wrap-images)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. This plugin has no settings to configure -- once it is activated, it starts doing it's work immediately

== Frequently Asked Questions ==

= What if I'm already replacing the `[caption]` shortcode in my theme or another plugin? =

This plugin adds a replacement `[caption]` filter to the `img_caption_shortcode` hook, using the default filter priority of 10. This will interact unpredictably with other filters using the same hook at the same priority, and will override any similar filters using a lower priority.

If you want to use this plugin in combination with a different caption override filter, and you have control over the priority setting of that filter, you should assign your filter a priority value greater than `10`.

This plugin will be useless with any different `[caption]` shortcode replacement that ignores images without captions.


== Changelog ==

= 0.1.0 =
* First release of the plugin