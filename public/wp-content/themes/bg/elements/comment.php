<li class="comment <?= $comment->comment_type ?>" id="comment-<?php comment_ID() ?>">
	<div class="meta">
		<cite class="author"><?php comment_author_link() ?></cite>
		am <a class="time" href="<?php the_permalink(); ?>#comment-<?php comment_ID() ?>" title="Permalink zu diesem Kommentar" rel="bookmark"><?php comment_date('d.m.Y H:i') ?></a>
	</div>
	<?php
		$text = get_comment_text();
		$text = preg_replace('@\r\n|\n\r|\n@', '<br />', $text);
		$text = preg_replace('@(?<!href="|">|src=")((?:http|https|ftp|nntp)://[^ <\n]+)@i', '<a href="\1" rel="external">\1</a>', $text);
		echo $text;
	?> 
	<?php if ($comment->comment_approved == '0') : ?>
		<p class="hint"><?= __('Dein Kommentar wird von einem Moderator überprüft.'); ?></p>
	<?php endif; ?>
</li>