<?php

/**
 * Single User Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">

	<?php bbp_get_template_part( 'user', 'details' ); ?>

	<?php bbp_get_template_part( 'user', 'subscriptions' ); ?>

	<?php bbp_get_template_part( 'user', 'favorites' ); ?>

	<?php bbp_get_template_part( 'user', 'topics-created' ); ?>
</div>
