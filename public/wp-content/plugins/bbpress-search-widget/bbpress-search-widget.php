<?php
/**
 * Main plugin file.
 * This Plugin adds a search widget for the bbPress 2.x forum plugin post types independent from the regular WordPress search.
 *
 * @package   bbPress Search Widget
 * @author    David Decker
 * @link      http://twitter.com/#!/deckerweb
 * @author    Daniel Hüsken
 * @link      http://twitter.com/#!/danielhuesken
 * @copyright Copyright 2011-2012, David Decker - DECKERWEB
 *
 * Plugin Name: bbPress Search Widget
 * Plugin URI: http://genesisthemes.de/en/wp-plugins/bbpress-search-widget/
 * Description: This Plugin adds a search widget for the bbPress 2.x forum plugin post types independent from the regular WordPress search.
 * Version: 1.2
 * Author: David Decker - DECKERWEB
 * Author URI: http://deckerweb.de/
 * License: GPLv2 or later
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: bbpress-search-widget
 * Domain Path: /languages/
 *
 * Copyright 2011-2012 David Decker - DECKERWEB
 *
 *     This file is part of bbPress Search Widget,
 *     a plugin for WordPress.
 *
 *     bbPress Search Widget is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     bbPress Search Widget is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Setting constants
 *
 * @since 1.0
 * @version 1.1
 */
/** Plugin directory */
define( 'BBPSW_PLUGIN_DIR', dirname( __FILE__ ) );

/** Plugin base directory */
define( 'BBPSW_PLUGIN_BASEDIR', dirname( plugin_basename( __FILE__ ) ) );


add_action( 'init', 'ddw_bbpsw_init', 1 );
/**
 * Load the text domain for translation of the plugin.
 * Load admin helper functions - only within 'wp-admin'.
 * 
 * @since 1.0
 * @version 1.2
 */
function ddw_bbpsw_init() {

	/** First look in WordPress' "languages" folder = custom & update-secure! */
	load_plugin_textdomain( 'bbpress-search-widget', false, BBPSW_PLUGIN_BASEDIR . '/../../languages/bbpress-search-widget/' );

	/** Then look in plugin's "languages" folder = default */
	load_plugin_textdomain( 'bbpress-search-widget', false, BBPSW_PLUGIN_BASEDIR . '/languages/' );

	/** If 'wp-admin' include admin helper functions */
	if ( is_admin() ) {
		require_once( BBPSW_PLUGIN_DIR . '/includes/bbpsw-admin.php' );
	}

	/** Add "Widgets Page" link to plugin page */
	if ( is_admin() && current_user_can( 'edit_theme_options' ) ) {
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) , 'ddw_bbpsw_widgets_page_link' );
	}

	/** Define helper constant for removing search label */
	if ( ! defined( 'BBPSW_SEARCH_LABEL_DISPLAY' ) ) {
		define( 'BBPSW_SEARCH_LABEL_DISPLAY', TRUE );
	}

}  // end of function ddw_bbpsw_init


add_filter( 'bbp_get_theme_compat_templates', 'ddw_bbp_get_theme_compat_templates' );
/**
 * Change Post Permalink
 *
 * @since 1.1
 *
 * @param $templates
 * @return string $templates
 */
function ddw_bbp_get_theme_compat_templates( $templates ) {

	/** Let bbPress use the current theme's native search.php for displaying the search results */
	if ( isset( $_GET['s'] ) )
		array_unshift( $templates, 'search.php' );
	return $templates;
}


/**
 * Change Post Permalink
 *
 * @since 1.1
 *
 * @param $post_link, $post, $leavename, $sample
 * @return string $post_link
 */
if ( isset( $_GET['s'] ) )
	add_filter( 'post_type_link', 'ddw_bbpress_change_post_permalink', 20, 4 );
	
function ddw_bbpress_change_post_permalink( $post_link, $post, $leavename, $sample ) {
	if ( $post->post_type == 'reply' ) {
		$post_link = bbp_get_reply_url( $post->ID );
		$post->comment_status = "closed";
	} elseif ( $post->post_type == 'topic' ) {
		$post->comment_status = "closed";		
	} elseif ( $post->post_type == 'forum' ) {
		$post->comment_status = "closed";
	}
	return $post_link;
}


/**
 * The main plugin class - creating the bbPress search widget
 *
 * @since 1.0
 * @version 1.1
 */
class bbPress_Forum_Plugin_Search extends WP_Widget {

	/**
	 * Constructor
	 * 
	 * Setup the widget with the available options
	 *
	 * @since 1.0
	 */
	public function __construct() {
	
		$options = array(
			'description' => sprintf( __( 'Search box for the bbPress 2.x forum plugin. Search in forum topics and replies only. (No mix up with regular WordPress search!)', 'bbpress-search-widget' ) ),
		);
		
		/** Create the widget */
		parent::__construct( 'bbpress_search', sprintf( __( 'bbPress Forum Search', 'bbpress-search-widget' ) ), $options );
	}

	/**
	 * Widget
	 * 
	 * Display the widget in the sidebar
	 *
	 * @since 1.0
	 * @version 1.1
	 */
	public function widget( $args, $instance ) {
	
		/** Extract the widget arguments */
		extract( $args );

		/** Set up the arguments */
		$args = array(
			'intro_text' => $instance['intro_text'],
			'outro_text' => $instance['outro_text']
		);

		/** Set the widget title */
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'bbPress Forum Search', 'bbpress-search-widget' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		
		/** Output the widget wrapper and title */
		echo $before_widget;
		echo $before_title . $title . $after_title;

		/** Display widget intro text if it exists */
		if ( !empty( $instance['intro_text'] ) ) {
			echo '<p class="'. $this->id . '-intro-text bbpsw-intro-text">' . $instance['intro_text'] . '</p>';
		}

		/** Set filters for various strings */
		$bbpsw_label_string = apply_filters( 'bbpsw_filter_label_string', __( 'Search forum in topics and replies for:', 'bbpress-search-widget' ) );
		$bbpsw_placeholder_string = apply_filters( 'bbpsw_filter_placeholder_string', __( 'Search the forums', 'bbpress-search-widget' ) );
		$bbpsw_search_string = apply_filters( 'bbpsw_filter_search_string', __( 'Search', 'bbpress-search-widget' ) );

		/** Construct the search form */
		$form = '<div id="bbpsw-form-wrapper"><form role="search" method="get" id="searchform" class="searchform bbpsw-search-form" action="' . home_url() . '">';
		$form .= '<div class="bbpsw-form-container">';
			if ( BBPSW_SEARCH_LABEL_DISPLAY ) {
				$form .= '<label class="screen-reader-text bbpsw-label" for="s">' . esc_attr__( $bbpsw_label_string ) . '</label>';
				$form .= '<br />';
			}
			$form .= '<input type="hidden" name="post_type[]" value="topic" />';
			$form .= '<input type="hidden" name="post_type[]" value="reply" />';
			$form .= '<input type="text" value="' . get_search_query() . '" name="s" id="s" class="s bbpsw-search-field" placeholder="' . esc_attr__( $bbpsw_placeholder_string ) . '" />';
			$form .= '<input type="submit" id="searchsubmit" class="searchsubmit bbpsw-search-submit" value="' . esc_attr__( $bbpsw_search_string ) . '" />';

		$form .= '</div>';
		$form .= '</form></div>';

		/** Apply filter to allow for additional fields */
		echo apply_filters( 'bbpress_forum_plugin_search_form', $form, $instance, $this->id_base );

		/** Display widget outro text if it exists */
		if ( ! empty( $instance['outro_text'] ) ) {
			echo '<p class="'. $this->id . '-outro_text bbpsw-outro-text">' . $instance['outro_text'] . '</p>';
		}
		
		/** Output the closing widget wrapper */
		echo $after_widget;
	}

	/**
	 * Update
	 * 
	 * Handles the processing of information entered in the WordPress admin area
	 *
	 * @since 1.0
	 * @version 1.1
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['intro_text'] = $new_instance['intro_text'];
		$instance['outro_text'] = $new_instance['outro_text'];
		return $instance;
	}

	/**
	 * Form
	 * 
	 * Displays the form for the WordPress admin
	 *
	 * @since 1.0
	 * @version 1.1
	 */
	public function form( $instance ) {

		/** Get values from instance */
		$title = ( isset( $instance['title'] ) ) ? esc_attr( $instance['title'] ) : null;
		$intro_text = ( isset( $instance['intro_text'] ) ) ? esc_textarea( $instance['intro_text'] ) : null;
		$outro_text = ( isset( $instance['outro_text'] ) ) ? esc_textarea( $instance['outro_text'] ) : null;
	
		/** Widget title */
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'title' ) . '">' . _e( 'Title:', 'bbpress-search-widget' ) . '</label>';
		echo '<input type="text" class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" value="' . $title . '" />';
	   	echo '</p>';

		/** Optional intro text */
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'intro_text' ) . '">' . _e( 'Optional intro text:', 'bbpress-search-widget' ) . '';
		echo '<br /><small>' . __( 'For example add some additional forum/search info etc.', 'bbpress-search-widget' ) . '';
		echo '<br />(' . __( 'Just leave blank to not use at all.', 'bbpress-search-widget' ) . ')</small>';
		echo '<textarea name="' . $this->get_field_name( 'intro_text' ) . '" id="' . $this->get_field_id( 'intro_text' ) . '" rows="4" class="widefat">' . $intro_text . '</textarea>';
		echo '</label>';
		echo '</p>';

		/** Optional outro text */
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'outro_text' ) . '">' . _e( 'Optional outro text:', 'bbpress-search-widget' ) . '';
		echo '<br /><small>' . __( 'For example add some additional user instructions etc.', 'bbpress-search-widget' ) . '';
		echo '<br />(' . __( 'Just leave blank to not use at all.', 'bbpress-search-widget' ) . ')</small>';
		echo '<textarea name="' . $this->get_field_name( 'outro_text' ) . '" id="' . $this->get_field_id( 'outro_text' ) . '" rows="4" class="widefat">' . $outro_text . '</textarea>';
		echo '</label>';
		echo '</p>';
	}

}  // end of main class bbPress_Forum_Plugin_Search


/**
 * Register the widget
 * 
 * @since 1.0
 */
function ddw_bbpsw_register_widgets() {
	register_widget( 'bbPress_Forum_Plugin_Search' );
}
add_action( 'widgets_init', 'ddw_bbpsw_register_widgets' );
