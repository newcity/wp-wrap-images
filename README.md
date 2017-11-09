# Wrap WYSIWYG Images

## Description

Wraps all images inserted into a WYSIWYG field from the Add Media interface with a
`<figure>` element, even if no caption is assigned to the image. Compatible with the standard
Wordpress tinyMCE editor and the [Advanced Custom Fields](https://www.advancedcustomfields.com/)
WYSIWYG field type.

## Installation Instructions
To install this plugin manually:
1. Download the plugin .zip file
2. Unzip
3. Copy the Wrap WYSIWYG Images directory to your /wp-content/plugins directory
4. Go to the plugin management page and activate the plugin

This plugin has no settings to configure -- once it is activated, it starts doing it's work immediately.

## Why this plugin exists

Wordpress is inconsistent in its handling of images inserted into WYSIWYG editor fields.
If the image has a caption, Wordpress wraps it in a shortcode that in turn wraps the `<img>` tag in
a `<figure>` tag with an included `<figcaption>` tag.

**Example Image with Caption Output**

```HTML
<figure id="attachment_508" style="width: 300px" class="wp-caption alignnone">
  <img ... />
  <figcaption class="wp-caption-text">This is a caption</figcaption>
</figure>
```

If no caption is present, the image is unceremoniously dumped into the page with no wrapper.
This presents a challenge for theme designers, since targeting the `<figure>` tag or the `<img>` tag
with CSS will yield different results.

## How it works

This plugin uses the `the_content` and `acf_the_content` hooks to wrap images without captions in the `[caption]` shortcode
before the page is built. It also replaces Wordpress' default caption shortcode function with a slightly
modified version that does not ignore captionless images. The result is that uncaptioned images receive
exactly the same markup wrappers as captioned images, but without the `<figcaption>` element.

## Possible conflicts
This plugin adds its replacement `[caption]` filter to `img_caption_shortcode`, and will conflict with any custom
caption shortcode you may have already assigned via `img_caption_shortcode`. This plugin's filter is assigned with
a priority of `10`, so if you want to use this plugin in combination with a different caption override filter you should
assign the other filter a priority value greater than `10`.

## Support
This plugin is provided without warranty or formal support. To ask questions of the developer, send an email
to geeks@insidenewcity.com.

Please submit bugs, patches, and feature requests to  
https://github.com/newcity/wp-wrap-images)