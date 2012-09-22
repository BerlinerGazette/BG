		</div>
	</div>
	<div id="footer">
		<?php require dirname(__FILE__).'/elements/footer-navigation.php'; ?>
		
		CC <?= date('Y'); ?> <a href="<?php bloginfo('url') ?>" rel="index" title="Zur Startseite"><?= mb_strtoupper(get_bloginfo('name')); ?></a> - <?= mb_strtoupper(get_bloginfo('description')); ?>
		&mdash;
			<a href="<?php bloginfo('url'); ?>/impressum/" title="Impressum">Impressum</a> |
			<a href="<?php bloginfo('rss2_url'); ?>" title="<?= bloginfo('name'); ?> RSS-Feed abonnieren" rel="alternate"><?= __('RSS-Feed'); ?></a> | 
			<a href="#app"><?= __('Nach Oben'); ?></a><br />
			powered by <a href="http://www.wordpress.org/" rel="external" title="Wordpress Homepage">Wordpress</a>,
			Theme: Berliner Gazette,
			Hosting: <a href="http://df.eu/kwk/277171/" rel="external">Domainfactory</a>,
			Programmierung &amp; Gestaltung: <a href="http://www.foobugs.com" title="foobugs - Oelke &amp; Eichner GbR - Professionelle Web-Anwendungsentwicklung aus Berlin">foobugs Oelke &amp; Eichner GbR</a>, 
		<?php wp_footer(); ?>
		<?php if (get_current_user() == 'ephigenia') {
			printf('<br />'.__('%d queries. %s seconds.', 'kubrick'), get_num_queries(), timer_stop(0, 3));
		} ?>
	</div>
	<?php if (1 == 1 || getenv('APPLICATION_ENV') == 'development') { ?>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/source/vendor/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/source/twitter-wall.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/source/app.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/source/ticker.js"></script>
	<?php } else { ?>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/min/app.js?r=3"></script>
	<?php } ?>
</body>
</html>
