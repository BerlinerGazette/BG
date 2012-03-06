<li class="teaser feeds">
	<h2>RSS-Feeds</h2>
	<ul class="list">
		<li><a class="icon-feed" rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>">Alle Artikel</a></li>
		<?php if (!empty($cat) && !is_front_page()) { ?>
		<li><a class="icon-feed" rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo get_category_link($cat).'feed'?>">Artikel aus dieser Kategorie</a></li>
		<?php } ?>
		<li><a class="icon-feed" rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed Kommentare" href="<?php bloginfo('comments_rss2_url'); ?>">Alle Kommentare</a></li>
	</ul>
	<h2>Soziale Netzwerke</h2>
	<ul class="list">
		<li><a class="icon-facebook" href="http://www.facebook.com/berlinergazetteredaktion" title="Mit der Berliner Gazette interagieren auf Facebook" rel="me">Facebook</a></li>
		<li><a class="icon-google-plus" href="https://plus.google.com/105282900469532082343/posts" title="Berlinergazette auf Google+" rel="me">Google+</a></li>						
		<li><a class="icon-soundcloud" href="http://soundcloud.com/berliner-gazette" title="Berlinergazette bei Soundcloud" rel="me">Soundcloud</a></li>						
		<li><a class="icon-twitter" href="http://twitter.com/berlinergazette" title="Die Berliner Gazette auf Twitter verfolgen" rel="me">Twitter</a></li>
		<li><a class="icon-vimeo" href="http://vimeo.com/berlinergazette" title="Berlinergazette auf VIMEO" rel="me">Vimeo</a></li>						
	</ul>
	<h2>Berliner Gazette Shop</h2>
	<ul class="list">
		<li><a class="icon-spreadshirt" href="http://berlinergazette.spreadshirt.de/" title="Der Berliner Gazette Spreadshirt Shop">Spreadshirt</a></li>
	</ul>
</li>