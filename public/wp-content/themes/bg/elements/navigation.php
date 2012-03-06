<?php
// single blog post view
if (is_single()) : ?>
	<div class="pagination">
		<span class="previous"><?php previous_post_link('&larr; %link') ?></span>
		<span class="next"><?php next_post_link('%link &rarr;') ?></span>
	</div>
<?php else : ?>
	<div class="pagination">
		<?php if(function_exists('wp_page_numbers')) { wp_page_numbers(); } ?>
	</div>
<?php endif; ?>