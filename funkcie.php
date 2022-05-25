<?php
/**
 * Plugin Name: Funkcie
 * Plugin URI: https://andrejsrna.sk
 * Description: Dodatočné funkcie pre tento web
 * Version: 1.0
 * Author: Andrej Srna
 * Author URI: https://andrejsrna.sk
 * License: GPL2
 */
 
/*  Copyright 2022  Andrej Srna  (email : ahoj@andrejsrna.sk)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Security snippets

add_action( 'user_profile_update_errors', 'set_user_nicename_to_nickname', 10, 3 );
function set_user_nicename_to_nickname( &$errors, $update, &$user ) {
 if ( ! empty( $user->nickname ) ) {
  $user->user_nicename = sanitize_title( $user->nickname, $user->display_name );
 }
}

define('DISALLOW_FILE_EDIT', true);

function deregister_qjuery() { 
 if ( !is_admin() ) {
 wp_deregister_script('jquery-migrate');
 }
} 
add_action('wp_enqueue_scripts', 'deregister_qjuery');

function wpdocs_dequeue_dashicon() {
        if (current_user_can( 'update_core' )) {
            return;
        }
        wp_deregister_style('dashicons');
}
add_action( 'wp_enqueue_scripts', 'wpdocs_dequeue_dashicon' );

define('WP_POST_REVISIONS', 20);

define('EMPTY_TRASH_DAYS', 7);

add_action( 'init', 'stop_heartbeat', 1 );
function stop_heartbeat() {
wp_deregister_script('heartbeat');
}

add_action( 'add_attachment', 'my_set_image_meta_upon_image_upload' );

function my_set_image_meta_upon_image_upload( $post_ID ) {
// Check if uploaded file is an image, else do nothing
if ( wp_attachment_is_image( $post_ID ) ) {
$my_image_title = get_post( $post_ID )->post_title;

		// Sanitize the title: remove hyphens, underscores & extra
		// spaces:
		$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',
		$my_image_title 
	);
	// Sanitize the title: capitalize first letter of every word
	// (other letters lower case):
	$my_image_title = ucwords( strtolower( $my_image_title ) );
	// Create an array with the image meta (Title, Caption,
	// Description) to be updated
	// Note: comment out the Excerpt/Caption or Content/Description
	// lines if not needed
	$my_image_meta = array(
	// Specify the image (ID) to be updated
		'ID' => $post_ID,
		// Set image Title to sanitized title
		'post_title' => $my_image_title,
		// Set image Caption (Excerpt) to sanitized title
		'post_excerpt' => $my_image_title,
		// Set image Description (Content) to sanitized title
		'post_content' => $my_image_title,
		);
	// Set the image Alt-Text
	update_post_meta( $post_ID, '_wp_attachment_image_alt',
	$my_image_title );
	// Set the image meta (e.g. Title, Excerpt, Content)
	wp_update_post( $my_image_meta );
	}
}

function Head(){
	if (!is_page() &&is_single()) {   
		if (!is_singular() ) {return; 
		}
		elseif (!empty( $post->post_excerpt)) {
			$meta = $post->post_excerpt ;
			echo ''; 
			echo '';
			echo '';
		} 
		else {
			$meta = apply_filters('the_content', $post->post_content); 
			$meta = strip_tags($meta); 
			$meta = strip_shortcodes($meta );$meta = strip_tags($meta);
			$meta = strip_shortcodes($meta );
			$meta = str_replace(array("\n", "\r", "\t"), ' ', $meta);
			$meta = substr($meta, 0, 175);
			echo ''; 
			echo '';
			echo '';
		}
	}
}
add_action('wp_head', 'Head');
