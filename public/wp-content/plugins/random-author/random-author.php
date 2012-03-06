<?php
/*
Plugin Name: Random Author Widget
Plugin URI: http://code.marceleichner.de/project/wp_random_author_widget
Description: A small widget that shows author name and description by random
Version: 1.0
Author: Marcel Eichner // Ephigenia <love@ephigenia.de>
Author URI: http://www.marceleichner.de
*/

/**
 * Random Author Widget
 * 
 * @author Marcel Eichner // Ephigenia <love@ephigenia.de>
 * @since 2010-02-28
 * @package wordpress.widget.randomAuthor
 * @subpackage wordpress.widget.randomAuthor
 */
class RandomAuthor extends WP_Widget
{
	const NAME = 'Random Author';
	
	public function RandomAuthor()
	{
		parent::WP_Widget(false, RandomAuthor::NAME);
	}

	public function register()
	{
		register_sidebar_widget(RandomAuthor::NAME, array(RandomAuthor::NAME, 'widget'));
	}
	
	public function widget($args, $instance)
	{
		global $wpdb; extract ($args);
		// get through random users
		$count = 1;
		$showDescription = false;
		$userLevel = array(1, 10);
		//
		$query = 'SELECT users.ID
			FROM '.$wpdb->users.' users
			JOIN '.$wpdb->usermeta.' usermeta ON
					usermeta.user_id = users.ID
				AND usermeta.meta_key = "bgazette_user_level"
				AND usermeta.meta_value IN ('.implode(',', $userLevel).')
			JOIN '.$wpdb->usermeta.' usermeta2 ON
					usermeta2.user_id = users.ID
				AND usermeta2.meta_key = "description"
				AND usermeta2.meta_value <> ""
			WHERE
				users.user_status = 0
			ORDER BY RAND()
			LIMIT 0, '.$count;
		$userIds = $wpdb->get_col($query);
		if (!$userIds) {
			return false;
		}
		echo $before_widget;
		foreach($userIds as $userId) {
			$author = get_userdata($userId);
			$url = get_bloginfo('url', true).'/autor/'.$author->user_nicename.'/';
			$fullname = $author->first_name.' '.$author->last_name;
			if (function_exists('userphoto_thumbnail')) {
				userphoto($author, null, null, array('class' => 'avatar'));
			}
			echo '<h2><a href="'.$url.'">'.$fullname.'</a></h2>';
			if (!empty($author->description) && $showDescription) {
				echo '<p>'.truncate($author->description, 200, '…').'</p>';
			}
			echo '<a href="'.$url.'">Profil in der Berliner Gazette</a>';
		}
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("RandomAuthor");'));
