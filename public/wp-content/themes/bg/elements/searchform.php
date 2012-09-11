<form method="get" id="searchForm" action="<?php bloginfo('home'); ?>/">
	<fieldset>
		<?php if (function_exists('is_bbpress') && is_bbpress()) { ?>
		<input type="hidden" name="post_type[]" value="topic" />
		<input type="hidden" name="post_type[]" value="reply" />
		<?php } ?>
		<input type="text" class="search_input input" value="Suchbegriff eingeben" name="s" size="21" />
		<input type="submit" class="search_submit submit" id="searchsubmit" value="Finden" />
	</fieldset>
</form>
