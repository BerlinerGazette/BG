<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="center">
	<?php
	if (!have_posts()) {
		echo 'Not Found';
	} else {
		
		/* Category Breadcrumb */
		if (is_category()) { ?>
			<ul class="breadcrumb">
				<li><a href="<?= bloginfo('url'); ?>" rel="index"><?= Startseite ?></a> &gt;</li>
				<?php
					$breadcrumb = array_filter(explode(',', get_category_parents($cat, true, ',')));
					foreach($breadcrumb as $index => $string) {
						if ($index < count($breadcrumb) - 1) {
							echo '<li>'.$string.' &gt;</li>'."\n";
						} else {
							echo '<li class="current">'.$string.'</li>'."\n";
						}
					}
				?>
			</ul>
			<?php
		}
		
		// Category & Tag Teaser
		$possibleTeaserPageNames = array();
		if (is_category()) {
			$category = get_category($cat);
			$possibleTeaserPageNames[] = $category->name.'_Center';
		}
		if (is_tag()) {
			$tagObj = get_term_by('slug', $tag, 'post_tag');
			$possibleTeaserPageNames[] = $tagObj->name.'_Center';
		}
		foreach($possibleTeaserPageNames as $possiblePageName) {
			if ($page = get_page_by_title($possiblePageName, false)) {
				?>
				<div class="page teaser">
					<div class="entry">
						<?= nl2br($page->post_content) ?>
					</div>
				</div>
				<?php
				break;
			}
		}
		?>
		
		<ul class="posts"><?php
			while (have_posts()) {
				require TEMPLATEPATH.'/elements/post.php';
			} ?>
		</ul>
		<?php require TEMPLATEPATH.'/elements/navigation.php';
	} ?>
</div>
<?php require TEMPLATEPATH.'/sidebar2.php'; ?>
<?php get_footer(); ?>