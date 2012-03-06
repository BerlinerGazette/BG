<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="center">
	<?php
	// search users
	$authorIds = $wpdb->get_col('SELECT ID FROM '.$wpdb->users.' WHERE user_status = 0
	AND (
			display_name = \''.$wpdb->escape($s).'\'
		OR	display_name LIKE \''.$wpdb->escape($s).'\'
	)
	LIMIT 0, 10');
	if (!empty($authorIds)) foreach($authorIds as $authorId) {
		$authors[] = get_userdata($authorId);
	}
	
	// search for posts
	if (!have_posts() && empty($authors)) { ?>
		<h1><?= sprintf(__('Suche nach <q>%s</q>'), $s); ?></h1>
		<p class="error">
			Die Suche nach <q><?= $s; ?></q> verlief leider ergebnislos.
		</p>
		<?php
	} else {
		// authors found
		if (!empty($authors)) { ?>
			<h1><?= __('Gefundene Autoren') ?></h1>
			<?php foreach($authors as $author) {
				$authorUrl = get_bloginfo('url').'/author/'.$author->user_nicename.'/';
				echo '<a href="'.$authorUrl.'">'.$author->first_name.' '.$author->last_name.'</a><br />';
			}
			?>
			<br />
			<?php
		} ?>
		
		<?php
		// found posts
		if (have_posts()) { ?>
		<h1><?= sprintf(__('Suchbegriff <q>%s</q> in Beiträgen'), $s) ?></h1>
		<ul class="posts">
			<?php while (have_posts()) {
				require TEMPLATEPATH.'/elements/post.php';
			} ?>
		</ul>
		<?php require TEMPLATEPATH.'/elements/navigation.php';
		} // have_posts
		
	} // if ?>
</div>
<?php require TEMPLATEPATH.'/sidebar2.php'; ?>
<?php get_footer(); ?>