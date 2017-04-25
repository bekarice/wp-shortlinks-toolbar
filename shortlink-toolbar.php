<?php
/**
 * Plugin Name: Shortlink Toolbar
 * Plugin URI: http://www.bekarice.com/plugins/
 * Description: Adds a menu to the admin toolbar to get the shortlink for a post or share the shortlink via Twitter, Buffer.
 * Author: Beka Rice
 * Author URI: http://www.bekarice.com/
 * Version: 1.1.0
 * Text Domain: shortlink-toolbar
 *
 * Copyright: (c) 2015 Yoast and 2015-2017 Beka Rice
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * 
 * This plugin is a derivative work of the Bit.ly Shortlinks plugin
 * Credit: Joost de Valk
 * http://yoast.com/wordpress/bitly-shortlinks/
 *
 * @package   Shortlink-Toolbar
 * @author    Beka Rice
 * @category  Admin
 * @copyright Copyright (c) 2015 Yoast, 2015-2017 Beka Rice
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

defined( 'ABSPATH' ) or exit;

/**
 * Plugin Description
 *
 * Adds a menu to the admin toolbar to get the shortlink for a post; 
 * if wp.me shortlinks are enabled via Jetpack, this will be used instead
 *
 */
function shortlink_toolbar_menu() {
	global $wp_admin_bar, $post;

	if ( ! isset( $post->ID ) ) {
		return;
	}

	$short_url    = esc_url( wp_get_shortlink( $post->ID, 'query' ) );
	$post_title   = str_replace( '+', '%20', urlencode( $post->post_title ) ) . ' - ';
	$twitter_link = "https://twitter.com/intent/tweet?text={$post_title}{$short_url}&source=webclient";
	$buffer_link  = "https://buffer.com/add?url={$short_url}&source=admin&text={$post_title}";

	// covers posts, pages, custom post types
	if ( is_singular() ) {

		$wp_admin_bar->add_node( array( 'id' => 'shortlink', 'title' => __( 'Shortlinks', 'shortlink-toolbar' ), 'href' => '#', 'meta' => array( 'onclick' => 'javascript:prompt("Here\'s your short link!", "' . $short_url . '");' ) ) );
		$wp_admin_bar->add_node( array( 'parent' => 'shortlink', 'id' => 'shortlink_shortened-link', 'title' => __( 'Get Shortlink', 'shortlink-toolbar' ), 'href' => '#', 'meta' => array( 'onclick' => 'javascript:prompt("Here\'s your short link!", "' . $short_url . '");' ) ) );
		$wp_admin_bar->add_node( array( 'parent' => 'shortlink', 'id' => 'shortlink_twitterlink', 'title' => __( 'Share on Twitter', 'shortlink-toolbar' ), 'href' => esc_url( $twitter_link ), 'meta' => array( 'target' => '_blank' ) ) );
		$wp_admin_bar->add_node( array( 'parent' => 'shortlink', 'id' => 'shortlink_bufferlink', 'title' => __( 'Share via Buffer', 'shortlink-toolbar' ), 'href' => esc_url( $buffer_link ),  'meta' => array( 'target' => '_blank' ) ) );
	}
}
add_action( 'admin_bar_menu', 'shortlink_toolbar_menu', 95 );