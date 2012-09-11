<?php
/**
 * Helper functions for the admin - plugin links and help tabs.
 *
 * @package    bbPress Search Widget
 * @subpackage Admin
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright 2011-2012, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link       http://genesisthemes.de/en/wp-plugins/bbpress-search-widget/
 * @link       http://twitter.com/#!/deckerweb
 *
 * @since 1.0
 * @version 1.1
 */

/**
 * Setting helper links constant
 *
 * @since 1.2
 */
define( 'BBPSW_URL_PLUGIN',		__( 'http://genesisthemes.de/en/wp-plugins/bbpress-search-widget/', 'bbpress-search-widget' ) );
define( 'BBPSW_URL_DONATE',		__( 'http://genesisthemes.de/en/donate/', 'bbpress-search-widget' ) );
define( 'BBPSW_URL_TRANSLATE',		'http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/bbpress-search-widget' );
define( 'BBPSW_URL_WPORG_FAQ',		'http://wordpress.org/extend/plugins/bbpress-search-widget/faq/' );
define( 'BBPSW_URL_WPORG_FORUM',	'http://wordpress.org/support/plugin/bbpress-search-widget' );


/**
 * Add "Widgets Page" link to plugin page
 *
 * @since 1.0
 *
 * @param  $bbpsw_links
 * @param  $bbpsw_widgets_link
 * @return strings widgets link
 */
function ddw_bbpsw_widgets_page_link( $bbpsw_links ) {

	$bbpsw_widgets_link = sprintf( '<a href="%s" title="%s">%s</a>' , admin_url( 'widgets.php' ) , __( 'Go to the Widgets settings page', 'bbpress-search-widget' ) , __( 'Widgets', 'bbpress-search-widget' ) );
	
	array_unshift( $bbpsw_links, $bbpsw_widgets_link );

	return $bbpsw_links;

}  // end of function ddw_bbpsw_widgets_page_link


add_filter( 'plugin_row_meta', 'ddw_bbpsw_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page
 *
 * @since 1.0
 *
 * @param  $bbpsw_links
 * @param  $bbpsw_file
 * @return strings plugin links
 */
function ddw_bbpsw_plugin_links( $bbpsw_links, $bbpsw_file ) {

	if ( ! current_user_can( 'install_plugins' ) )
		return $bbpsw_links;

	if ( $bbpsw_file == BBPSW_PLUGIN_BASEDIR . '/bbpress-search-widget.php' ) {
		$bbpsw_links[] = '<a href="' . BBPSW_URL_WPORG_FAQ . '" target="_new" title="' . __( 'FAQ', 'bbpress-search-widget' ) . '">' . __( 'FAQ', 'bbpress-search-widget' ) . '</a>';
		$bbpsw_links[] = '<a href="' . BBPSW_URL_WPORG_FORUM . '" target="_new" title="' . __( 'Support', 'bbpress-search-widget' ) . '">' . __( 'Support', 'bbpress-search-widget' ) . '</a>';
		$bbpsw_links[] = '<a href="' . BBPSW_URL_TRANSLATE . '" target="_new" title="' . __( 'Translations', 'bbpress-search-widget' ) . '">' . __( 'Translations', 'bbpress-search-widget' ) . '</a>';
		$bbpsw_links[] = '<a href="' . BBPSW_URL_DONATE . '" target="_new" title="' . __( 'Donate', 'bbpress-search-widget' ) . '">' . __( 'Donate', 'bbpress-search-widget' ) . '</a>';
	}

	return $bbpsw_links;

}  // end of function ddw_bbpsw_plugin_links


add_action( 'sidebar_admin_setup', 'ddw_bbpsw_widgets_help' );
/**
 * Load plugin help tab after core help tabs on Widget admin page.
 *
 * @since 1.2
 *
 * @global mixed $pagenow
 */
function ddw_bbpsw_widgets_help() {

	global $pagenow;

	add_action ( 'admin_head-' . $pagenow, 'ddw_bbpsw_widgets_help_content' );

}  // end of function ddw_bbpsw_widgets_help


/**
 * Create and display plugin help tab content.
 *
 * @since 1.2
 *
 * @global mixed $bbpsw_widgets_screen
 */
function ddw_bbpsw_widgets_help_content() {

	global $bbpsw_widgets_screen;

	$bbpsw_widgets_screen = get_current_screen();

	/** Display help tabs only for WordPress 3.3 or higher */
	if( ! class_exists( 'WP_Screen' ) || ! $bbpsw_widgets_screen || ! class_exists( 'bbPress' ) )
		return;

	/** Content: bbPress Search Widget plugin */
	$bbpsw_widget_area_help =
		'<h3>' . __( 'bbPress Search Widget', 'bbpress-search-widget' ) . '</h3>' .		
		'<p>' . sprintf( __( 'Added Widget by the plugin: %s', 'bbpress-search-widget' ), '<em>' . __( 'bbPress Forum Search', 'bbpress-search-widget' ) . '</em>' ) . '</p>' .
		'<p>' . sprintf( __( 'It searches only in the post types %s and %s and outputs the results formatted like the other search results (of WordPress).', 'bbpress-search-widget' ), '<em>' . __( 'Topic', 'bbpress-search-widget' ) . '</em>', '<em>' . __( 'Reply', 'bbpress-search-widget' ) . '</em>' ) .
			'<br />' . __( 'Please note: This plugin does not mix up its displayed search results with WordPress built-in search. It is limited to the bbPress forum post types. For enhanced styling of the widget and/or the search results please have a look on the FAQ page linked below.', 'bbpress-search-widget' ) . '</p>' .
		'<p><strong>' . __( 'Important plugin links:', 'bbpress-search-widget' ) . '</strong>' . 
		'<br /><a href="' . BBPSW_URL_PLUGIN . '" target="_new" title="' . __( 'Plugin Homepage', 'bbpress-search-widget' ) . '">' . __( 'Plugin Homepage', 'bbpress-search-widget' ) . '</a> | <a href="' . BBPSW_URL_WPORG_FAQ . '" target="_new" title="' . __( 'FAQ', 'bbpress-search-widget' ) . '">' . __( 'FAQ', 'bbpress-search-widget' ) . '</a> | <a href="' . BBPSW_URL_WPORG_FORUM . '" target="_new" title="' . __( 'Support', 'bbpress-search-widget' ) . '">' . __( 'Support', 'bbpress-search-widget' ) . '</a> | <a href="' . BBPSW_URL_TRANSLATE . '" target="_new" title="' . __( 'Translations', 'bbpress-search-widget' ) . '">' . __( 'Translations', 'bbpress-search-widget' ) . '</a> | <a href="' . BBPSW_URL_DONATE . '" target="_new" title="' . __( 'Donate', 'bbpress-search-widget' ) . '">' . __( 'Donate', 'bbpress-search-widget' ) . '</a></p>';

	/** Add the new help tab */
	$bbpsw_widgets_screen->add_help_tab( array(
		'id'      => 'bbpsw-widgets-help',
		'title'   => __( 'bbPress Search Widget', 'bbpress-search-widget' ),
		'content' => apply_filters( 'bbpsw_help_tab', $bbpsw_widget_area_help, 'bbpsw-widgets-help' ),
	) );

}  // end of function ddw_bbpsw_widgets_help_content
