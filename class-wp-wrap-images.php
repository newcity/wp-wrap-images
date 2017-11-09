<?php

/**
 *
 * Functions for wrapping images
 *
 * @since      0.1.0
 *
 * @package    WPWrapImages
 * @author     NewCity <geeks@insidenewcity.com>
 */

class WPWrapImages {

	public function __construct() {
		add_filter( 'img_caption_shortcode', array( $this, 'replacement_caption_shortcode' ), 10, 3 );
		add_filter( 'the_content', array( $this, 'wrap_img_with_figure' ) );
		add_filter( 'acf_the_content', array( $this, 'wrap_img_with_figure' ) );
	}

	private function shift_align_class( $matches ) {
		$img = $matches[1];
		$classes = $matches[2];
		$width = $matches[3];
	
		$align_pattern = '/align[a-z]*/i';
		$id_pattern = '/wp-image-(\d+)/';
		preg_match_all( $id_pattern, $classes, $img_id );
		preg_match_all( $align_pattern, $classes, $align_classes );
	
		$img_id = $img_id[1][0];
		$align_classes = implode( ' ', $align_classes[0] );
	
		$img = preg_replace( $align_pattern, '', $img );
		return '[caption id="' . $img_id . '" align="' . $align_classes . '" width="' . $width . '" class="none"]' . $img . '[/caption]';
	}

	public function wrap_img_with_figure( $content ) {

		$p_wrapper_pattern = '/<p>(<img([^>]*)>)<\/p>/i';
		$p_wrapper_replacement = '$1';

		$content = preg_replace( $p_wrapper_pattern, $p_wrapper_replacement, $content );

		$p_content_pattern = '/<p>(<img[^>]*>)([\s\S]+?)<\/p>/i';
		$p_content_replacement = "$1\n<p>$2</p>";

		$content = preg_replace( $p_content_pattern, $p_content_replacement, $content );
		
		$br_remover_pattern = '/<p><br \/>\s*/i';
		$br_remover_replacement = '<p>';
		
		$content = preg_replace( $br_remover_pattern, $br_remover_replacement, $content );

		$img_pattern = '/[^\]](<img.+?class="([^"]*)"[^<]+?width="([^"]*)"[^<]+?>)/i';

		$content = preg_replace_callback( $img_pattern, array( $this, 'shift_align_class' ), $content );


		return $content;
	}

	/**
	 * Replacement for Wordpress caption shortcode
	 *
	 * This code was copied from the Wordpress Core version of the `img_caption_shortcode`
	 * function located in `wp-includes/media.php` on 11/8/2017
	 * 
	 * It was modified to act as a replacement shortcode filter using the `img_caption_shortcode`
	 * hook by adding `$empty` to its argument list.
	 * 
	 * The primary purpose of the modifications is to allow the shortcode to operate on images
	 * that do not have captions assigned to them.
	 *
	 * @since 0.1.0
	 *
	 * @see img_caption_shortcode()
	 *
	 * @param string $empty    Empty string (required)
	 * @param array  $attr     Attributes of the caption shortcode.
	 * @param string $content  The image element, possibly wrapped in a hyperlink.
	 */
	public function replacement_caption_shortcode( $empty, $attr, $content = null ) {
		// New-style shortcode with the caption inside the shortcode with the link and image tags.
		if ( ! isset( $attr['caption'] ) ) {
			if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
				$content = $matches[1];
				$attr['caption'] = trim( $matches[2] );
			}
		} elseif ( strpos( $attr['caption'], '<' ) !== false ) {
			$attr['caption'] = wp_kses( $attr['caption'], 'post' );
		}
		/**
		 * Filters the default caption shortcode output.
		 *
		 * If the filtered output isn't empty, it will be used instead of generating
		 * the default caption template.
		 *
		 * @since 2.6.0
		 *
		 * @see img_caption_shortcode()
		 *
		 * @param string $output  The caption output. Default empty.
		 * @param array  $attr    Attributes of the caption shortcode.
		 * @param string $content The image element, possibly wrapped in a hyperlink.
		 */

		$atts = shortcode_atts( array(
			'id'	  => '',
			'align'	  => 'alignnone',
			'width'	  => '',
			'caption' => '',
			'class'   => '',
		), $attr, 'caption' );

		$atts['width'] = (int) $atts['width'];


		if ( $atts['width'] < 1 )
			return $content;
		if ( ! empty( $atts['id'] ) )
			$atts['id'] = 'id="' . esc_attr( sanitize_html_class( $atts['id'] ) ) . '" ';
		
		$class = trim( $atts['align'] . ' ' . $atts['class'] );
		if ( $atts['caption'] ) {
			$class = trim( 'wp-caption ' . $class );
		}

		$html5 = current_theme_supports( 'html5', 'caption' );
		// HTML5 captions never added the extra 10px to the image width
		$width = $html5 ? $atts['width'] : ( 10 + $atts['width'] );
		/**
		 * Filters the width of an image's caption.
		 *
		 * By default, the caption is 10 pixels greater than the width of the image,
		 * to prevent post content from running up against a floated image.
		 *
		 * @since 3.7.0
		 *
		 * @see img_caption_shortcode()
		 *
		 * @param int    $width    Width of the caption in pixels. To remove this inline style,
		 *                         return zero.
		 * @param array  $atts     Attributes of the caption shortcode.
		 * @param string $content  The image element, possibly wrapped in a hyperlink.
		 */
		$caption_width = apply_filters( 'img_caption_shortcode_width', $width, $atts, $content );

		$style = '';

		if ( $caption_width ) {
			$style = 'style="max-width: ' . (int) $caption_width . 'px" ';
		}

		if ( $html5 ) {
			$html = '<figure ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">'
			. do_shortcode( $content );

			if ( $atts['caption'] ) {
				$html .= '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption>';
			}

			$html .= '</figure>';
		} else {
			$html = '<div ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">'
			. do_shortcode( $content ) . '<p class="wp-caption-text">' . $atts['caption'] . '</p></div>';
		}
		return $html;
	}

}
