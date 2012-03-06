<?php

/**
 * Most Recents Comments
 * @author Marcel Eichner // Ephigenia <love@ephigenia.de>
 * @since 2010-03-07
 */

$minCommentsCount = 3;

if (is_home()) {
	$comments = get_comments('number=10&status=approve');
	$label = 'Kommentare';
} else {
	$allNewComments = get_comments('number=100&status=approve');
	$comments = array();
	foreach ($allNewComments as $comment) {
		if (!in_category($cat, $comment->comment_post_ID)) continue;
		$comments[] = $comment;
		if (count($comments) == 5) break;
	}
	$label = 'Kommentare';
}

// only show comments if there are enough
if (count($comments) >= $minCommentsCount) { ?>
<li class="teaser">
	<h2><?php echo $label ?></h2>
	<ul class="comments last">
	<?php
	$posts = array();
	foreach($comments as $comment) {
		if (!isset($posts[$comment->comment_post_ID])) {
			$posts[$comment->comment_post_ID] = get_post($comment->comment_post_ID);
		}
		$post = $posts[$comment->comment_post_ID];
		?>
		<li class="comment <?= $comment->comment_type ?>" id="comment-<?php comment_ID() ?>">
			<div class="meta">
				<a href="<?php the_permalink(); ?>#comment-<?php comment_ID() ?>" title="Permalink zu diesem Kommentar" rel="bookmark">
					<cite class="author"><?php
						comment_author()
					?></cite> &middot;
					<span class="time" title="<?= comment_date('c') ?>">
							<?php if (get_comment_time('U', true) > time() - 3600*24*2) {
								echo ' vor '.BGTime::nice(get_comment_time('U', true), null, 1);
							} else {
								comment_time('d.m.Y');
							} ?>
					</span>
				</a>
			</div>
			<blockquote><p><?php
					// shorten long comment texts
					$text = $comment->comment_content;
					$maxLength = 55;
					if (mb_strlen($text, 'UTF-8') > $maxLength) {
						$text = mb_substr($text, 0, $maxLength-2).'…';
					}
					echo $text;
			?></p></blockquote> zu <a href="<?php the_permalink(); ?>" title="<?= the_title(); ?>"><?php
				$articleTitle = get_the_title();
				$maxLength = 25;
				if (mb_strlen($articleTitle, 'UTF-8') > $maxLength) {
					$articleTitle = mb_substr($articleTitle, 0, $maxLength - 2).'…';
				}
				echo $articleTitle;
			?></a>
		</li>
	<?php } ?>
	</ul>
</li>
<?php } ?>