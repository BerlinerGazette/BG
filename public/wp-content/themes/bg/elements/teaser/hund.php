<li class="teaser teaser-hund" style="font-weight:bold; font-size: 1.2em; line-height: 1.2em;">
	<img title="Spenden" src="http://berlinergazette.de/wp-content/uploads/bg_dog_185px_white_transparent.png" alt="Die Berliner Gazette durch Spenden unterstützen" />
	<?php if (!isset($cat) || $cat != BGProjectConfig::$digitalBackyards['category_id']) { ?>
	<p>
		Hallo Leser, würdest du uns dein Geld geben? Warum? Lies <a href="http://berlinergazette.de/aufruf-spenden/">hier</a>.</a><br>
		Ansonsten: Ein Klick auf den grünen Knopf unten genügt! Wie das geht, steht <a href="http://berlinergazette.de/was-ist-flattr/">hier</a>.
	</p>
	<?php } ?>
	<a class="FlattrButton" style="display:none;"
			title="<?= bloginfo('name') ?>"
			data-flattr-uid="BerlinerGazette"
			data-flattr-category="text"
			href="https://flattr.com/profile/BerlinerGazette">
	</a>
	<noscript><a href="http://flattr.com/thing/336467/BerlinerGazette-on-Flattr" target="_blank">
	<img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>
</li>