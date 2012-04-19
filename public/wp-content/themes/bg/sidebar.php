<div class="sidebar leftSidebar">
	<ul>
	<?php
		
		// get category id when in single post view
		if (is_single()) {
			$category = get_the_category();
			$cat = $category[0]->cat_ID;
		}
		if (function_exists('dynamic_sidebar')) {
			
			// show project sidebars
			// Liquid Writing Sidebar
			if (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$liquidwriting['category_id']) {
				dynamic_sidebar('liquidwriting');
				?>
				<?php require TEMPLATEPATH.'/elements/twitterWidget.php'; ?>
				<li class="teaser feeds">
					<h2>RSS-Feeds</h2>
					<ul class="list last">
						<li class="rss"><a rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo get_category_link($cat).'feed'?>">Artikel aus dieser Rubrik</a></li>
					</ul>
				</li>
				<?php
			// Lebenskünstler
			} elseif (
			 	   (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$lebenskuenstler['category_id'])
				|| (is_tag() && in_array($tag, BGProjectConfig::$lebenskuenstler['tags']))
				) {
				dynamic_sidebar('lebenskuenstler-left');
				?>
				<li class="teaser feeds">
					<h2>RSS-Feeds</h2>
					<ul class="list last">
						<li class="rss"><a rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo bloginfo('url'); ?>/lebenskuenstler/feed/">Artikel aus dieser Rubrik</a></li>
					</ul>
				</li>
				<?php
			// L311 / Symposium Learning from Fukushima
			} elseif (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$l311['category_id']) {
				dynamic_sidebar('l311-left');
				?>
				<?php 
				$TwitterWidgetConfig = array(
					'search' => '#fukushima',
					'subject' => 'Fukushima',
				);
				require TEMPLATEPATH.'/elements/twitterWidget.php';
			// BQV (2012)
			} elseif (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$bqv['category_id']) {
				$displayYearlyTopics = true;
				dynamic_sidebar('bqv-left');
				$TwitterWidgetConfig = array(
					'search' => '#bqv',
					'subject' => 'BGV',
				);
				require TEMPLATEPATH.'/elements/twitterWidget.php';
			// Everything else
			} else { ?>
				<?php dynamic_sidebar('main_sidebar_left_top'); ?>
				<?php
				dynamic_sidebar('main_sidebar_left_btm');
			}
		}
		
		// Jahresthemen
		if (!isset($displayYearlyTopics) || $displayYearlyTopics) {
			require TEMPLATEPATH.'/elements/yearlyTopics.php';
		}

		?>
	</ul>
</div>