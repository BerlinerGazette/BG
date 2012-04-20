<?php
/*
Plugin Name: BGTwitter Widget
Description: A Sidebar Twitter Widget Integration
Version: 1.0
Author: Marcel Eichner
Author URI: http://www.marceleichner.de
*/
class TwitterWidget extends WP_Widget
{
	/**
	 * Register widget with WordPress.
	 */
	public function __construct()
	{
		parent::__construct(
			'sidebar_twitter',
			'Twitter Widget',
			array(
				'description' => 'Display a Twitter Widget',
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
		$instance['search'] = strip_tags($new_instance['search']);
		$instance['height'] = (int) $new_instance['height'];
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
			$title = 'Titel';
		}
		if (isset($instance['search'])) {
			$search = $instance['search'];
		} else {
			$search = '#hashtag or term';
		}
		if (isset($instance['height'])) {
			$height = $instance['height'];
		} else {
			$height = 300;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _e('Text:'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
			<label for="<?php echo $this->get_field_id('search'); ?>">
				<?php _e('Suche:'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('search'); ?>" name="<?php echo $this->get_field_name('search'); ?>" type="text" value="<?php echo esc_attr($search); ?>" />
			</label>
			<label for="<?php echo $this->get_field_id('height'); ?>">
				<?php _e('Höhe:'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" />
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
		?>
		<li class="teaser twitterWidget">
			<script src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script>
			new TWTR.Widget({
			 version: 2,
			 type: 'search',
			 search: '<?php echo $instance['search']; ?>',
			 interval: 6000,
			 title: 'Twittermeldungen',
			 subject: '<?= $instance['title'] ?>',
			 width: 200,
			 height: <?php echo $instance['height']; ?>,
			 theme: {
			   shell: {
			     background: '#ebebeb',
			     color: '#0000'
			   },
			   tweets: {
			     background: '#ffffff',
			     color: '#444444',
			     links: '#1985b5'
			   }
			 },
			 features: {
			   scrollbar: false,
			   loop: true,
			   live: true,
			   hashtags: true,
			   timestamp: true,
			   avatars: true,
			   toptweets: true,
			   behavior: 'default'
			 }
			}).render().start();
			</script>
		</li>
		<?php
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'register_widget("TwitterWidget");'));