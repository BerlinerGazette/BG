<?php
/*
Plugin Name: Category Dropdown Widget
Description: A Sidebar Widget that displays sub-categories of a parent_category
Version: 1.0
Author: Marcel Eichner
Author URI: http://www.marceleichner.de
*/
class CategoryDropdownWidget extends WP_Widget
{
	/**
	 * Register widget with WordPress.
	 */
	public function __construct()
	{
		parent::__construct(
			'sidebar_category_dropdown',
			'Category Dropdown',
			array(
				'description' => 'Display Dropdown with sub-categories of a parent category',
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
		$instance['parent_category_id'] = strip_tags($new_instance['parent_category_id']);
		$instance['ignore_category_ids'] = preg_replace('/[^\d,]/', '', $new_instance['ignore_category_ids']);
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
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = 'Titel';
		}
		if (isset($instance['parent_category_id'])) {
			$parent_category_id = $instance['parent_category_id'];
		} else {
			$parent_category_id = 0;
		}
		if (isset($instance['ignore_category_ids'])) {
			$ignore_category_ids = $instance['ignore_category_ids'];
		} else {
			$ignore_category_ids = '';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _e('Titel:'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('parent_category_id'); ?>">
				<?php _e('Parent Kategorie ID:'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('parent_category_id'); ?>" name="<?php echo $this->get_field_name('parent_category_id'); ?>" type="text" value="<?php echo esc_attr($parent_category_id); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('ignore_category_ids'); ?>">
				<?php _e('Ignore Categories (id,id,id ...):'); ?>
				<input calss="widefat" id="<?php echo $this->get_field_id('ignore_category_ids'); ?>" name="<?php echo $this->get_field_name('ignore_category_ids'); ?>" type="text" value="<?php echo esc_attr($ignore_category_ids); ?>" />
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
		
		$topics = get_categories(array(
			'child_of' => $instance['parent_category_id'],
			'pad_counts' => 0,
			'hide_empty' => 0
		));
		
		// ignore category ids
		$ignore_category_ids = explode(',', $instance['ignore_category_ids']);

		$current_category_id = 0;
		if ($current_categories = get_the_category()) {
			$current_category_id = $current_categories[0]->cat_ID;
		}
		echo $before_widget;
		?>
		<?php if (!empty($instance['title'])) { ?><h2><?php echo $instance['title']; ?></h2><?php } ?>
		<form action="#" method="post" accept-charset="UTF-8">
			<fieldset>
				<select name="cat" size="1" class="autoLink">
					<option value=""><?= __('Auswählen …'); ?></option>
					<?php foreach($topics as $subCategory) {
					if (in_array($subCategory->cat_ID, $ignore_category_ids)) {
						continue;
					}
					$selected = $subCategory->cat_ID == $current_category_id;
					?>
					<option value="<?= get_category_link($subCategory->term_id) ?>"<?php
						if ($selected) echo ' class="selected" selected="selected"';
						?>>
						<?= $subCategory->name; ?>
					</option>
					<?php } ?>
				</select>
			</fieldset>
		</form>
		<?php
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'register_widget("CategoryDropdownWidget");'));