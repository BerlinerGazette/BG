<?php
/*
Plugin Name: BGCategory Feed Widget
Description: A Sidebar Twitter Widget Integration
Version: 1.0
Author: Marcel Eichner
Author URI: http://www.marceleichner.de
*/
class CurrentCategoryFeedWidget extends WP_Widget
{
	/**
	 * Register widget with WordPress.
	 */
	public function __construct()
	{
		parent::__construct(
			'sidebar_current_category_feed',
			'Current Category Feed',
			array(
				'description' => 'Display a Category Feed Box',
			)
		);
	}
	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['linktitle'] = strip_tags($new_instance['linktitle']);
		return $instance;
	}
	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = 'RSS-Feeds';
		}
		if (isset($instance['linktitle'])) {
			$linktitle = $instance['linktitle'];
		} else {
			$linktitle = 'Artikel aus dieser Rubrik';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _e('Text:'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
			<label for="<?php echo $this->get_field_id('linktitle'); ?>">
				<?php _e('Linktitel:'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('linktitle'); ?>" name="<?php echo $this->get_field_name('linktitle'); ?>" type="text" value="<?php echo esc_attr($linktitle); ?>" />
			</label>
		</p>
		<?php
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		extract($args);
		$current_category_id = 0;
		if ($current_categories = get_the_category()) {
			$current_category_id = $current_categories[0]->cat_ID;
		}
		$href = get_category_link($current_category_id).'feed';
		
		echo $before_widget;
		if (!empty($instance['title'])) {
			echo '<h2>'.htmlentities($instance['title'], null, 'UTF-8').'</h2>';
		}
		?>
		<ul class="list last">
			<li class="rss">
				<a rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo $href; ?>" class="icon-feed">
					<?php echo $instance['linktitle']; ?>
				</a>
			</li>
		</ul>
		<?php
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'register_widget("CurrentCategoryFeedWidget");'));