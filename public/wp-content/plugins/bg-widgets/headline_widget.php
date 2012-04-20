<?php
/*
Plugin Name: Headline Widget
Description: A Sidebar Widget for creating Headline Deviders
Version: 1.0
Author: Marcel Eichner
Author URI: http://www.marceleichner.de
*/
class HeadlineWidget extends WP_Widget
{
	/**
	 * Register widget with WordPress.
	 */
	public function __construct()
	{
		parent::__construct(
			'sidebar_headline',
			'Headline',
			array(
				'description' => 'Display Headline Devider in Sidebar',
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
		if (isset($instance['text'])) {
			$title = $instance['text'];
		} else {
			$title = 'Headline';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _e('Text:'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
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
		echo $before_widget;
		echo '<h2>'.htmlentities($instance['title'], null, 'UTF-8').'</h2>';
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'register_widget("HeadlineWidget");'));