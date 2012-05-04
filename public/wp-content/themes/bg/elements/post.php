<?php the_post() ?>
<li id="post<?php the_ID(); ?>" class="post">
	<h1>
		<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link zu <?php the_title(); ?>"><?php the_title(); ?></a>
	</h1>
	<div class="text">
		<?php
		// blog post content
		$content = get_the_content('weiterlesen &raquo;');
		
		if (!is_single()) {
			// replace first image link with permalink
			$content = preg_replace('@<a href="([^"]+)"><img (.+)>@si', '<a href="'.get_permalink($post->ID).'"><img $2>', $content);
		}
		
		// replace old >quote content< notation
		$content = preg_replace('@([\s,.(]|^)>([^<]+)<([\s,.!?)]|$)@', '$1<q>$2</q>$3', $content);
		// apply wordpress filters 
		$content = apply_filters('the_content', $content);
		// replace [] which are
		$content = preg_replace('@(\s+|^)\[([^\]]+)\]@', '$1($2)', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		echo $content;
		?>
	</div>
	<div class="meta">
		<span class="comments"><?php comments_popup_link('Keine Kommentare', '1 Kommentar', '% Kommentare'); ?></span>
		<cite class="author"><?php the_author_posts_link() ?></cite> &middot;
		<a class="time" href="<?php the_permalink(); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_time('d.m.Y') ?></a><br />
		<?php 
		$categories = get_the_category();
		foreach($categories as $index => $category) {
			$url = get_category_link($category->cat_ID);
			echo '<a href="'.$url.'">'.$category->cat_name.'</a>';
			if ($index < count($categories) - 1) {
				echo ' &gt; ';
			}
		}
		?>
		<span class="flattr"><?php the_flattr_permalink(); ?></span>
		<?php
		if (is_single()) {
			require TEMPLATEPATH.'/elements/addthis.php';
		} ?>
	</div>
</li>