<?php

$TwitterWidgetDefaultConfig = array(
	'subject' => 'Wasser',
	'search' => 'Wasser OR Water',
	'title' => 'Twittermeldungen zu',
);
$TwitterWidgetConfig = (is_array($TwitterWidgetConfig) ? $TwitterWidgetConfig : array()) + $TwitterWidgetDefaultConfig;

?>
<li class="teaser twitterWidget">
	<script src="http://widgets.twimg.com/j/2/widget.js"></script>
	<script>
	new TWTR.Widget({
	 version: 2,
	 type: 'search',
	 search: '<?= $TwitterWidgetConfig['search'] ?>',
	 interval: 6000,
	 title: '<?= $TwitterWidgetConfig['title']; ?>',
	 subject: '<?= $TwitterWidgetConfig['subject'] ?>',
	 width: 200,
	 height: 300,
	 theme: {
	   shell: {
	     background: '#ebebeb',
	     color: '#0000'
	   },
	   tweets: {
	     background: '#ffffff',
	     color: '#444444',
	     links: '#1985b5'
	   }
	 },
	 features: {
	   scrollbar: false,
	   loop: true,
	   live: true,
	   hashtags: true,
	   timestamp: true,
	   avatars: true,
	   toptweets: true,
	   behavior: 'default'
	 }
	}).render().start();
	</script>
</li>