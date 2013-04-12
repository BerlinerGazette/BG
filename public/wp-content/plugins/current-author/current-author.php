<?php
/*
Plugin Name: Current Author Widget
Plugin URI: http://code.marceleichner.de/project/wp_current_author
Description: A small widget that shows author name, photo and description
Version: 1.1
Author: Marcel Eichner // Ephigenia <love@ephigenia.de>
Author URI: http://www.marceleichner.de
*/

/**
 * Current Author Widget
 * 
 * @author Marcel Eichner // Ephigenia <love@ephigenia.de>
 * @since 2010-02-28
 * @package wordpress.widget.randomAuthor
 * @subpackage wordpress.widget.randomAuthor
 */
class CurrentAuthor extends WP_Widget
{
	const NAME = 'Current Author';
	
	public function CurrentAuthor()
	{
		parent::WP_Widget(false, CurrentAuthor::NAME);
	}

	public function register()
	{
		register_sidebar_widget(CurrentAuthor::NAME, array(CurrentAuthor::NAME, 'widget'));
	}
	
	public function widget($args, $instance)
	{
		global $post;
		extract($args);
		// do nothing if no detail page or no author found
		if (empty($post)) {
			return;
		}
		if ($post->post_author <= 0) {
			return;
		}
		// do nothing if not on a detail page
		if (!is_single()) {
			return false;
		}
		if (isset($post->post_type) && $post->post_type == 'forum') {
			return false;
		}
		echo $before_widget;
		$author = get_userdata($post->post_author);
		$url = get_bloginfo('url', true).'/autor/'.$author->user_nicename.'/';
		$fullname = $author->first_name.' '.$author->last_name;
		if (function_exists('userphoto_thumbnail')) {
			echo '<a href="'.$url.'">';
			userphoto($author, null, null, array('class' => 'avatar'));
			echo '</a>';
		}
		echo '<h2><a href="'.$url.'">'.$fullname.'</a></h2>';
		if (!empty($author->description) && $showDescription) {
			echo '<p>'.truncate($author->description, 200, '…').'</p>';
		}
		echo '<a href="'.$url.'">Profil in der Berliner Gazette</a>';
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("CurrentAuthor");'));
