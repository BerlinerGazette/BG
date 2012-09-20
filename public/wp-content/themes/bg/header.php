<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<!--[if IE 8]><meta http-equiv="X-UA-Compatible" content="IE=7" /><![endif]-->
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="google-site-verification" content="TZp-wkEIHksgKrXE8i_AobZ0TGz_2yx1ViOOzOOE7TQ" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"">
	<meta http-equiv="imagetoolbar" content="no" />
	<base href="<?php bloginfo('url'); ?>" />
	<title><?php if (is_single() || is_page() || is_archive()) { wp_title('',true); } else { bloginfo('name'); echo(' &#8212; '); bloginfo('description'); } ?></title>
	<?php wp_head(); ?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed Kommentare" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style.css?r=<?= filemtime(__DIR__.'/style.css'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/print.css?r=<?= filemtime(__DIR__.'/print.css'); ?>" type="text/css" media="print" />
	<link rel="shortcut icon" type="image/ico" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />
	<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_directory'); ?>/favicon-iphone.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/favicon-iphone.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/favicon-iphone4.png">
	<?php 

	if (is_category()) {
		$category = get_category($cat);
		$pageNames[] = $category->name;
	} elseif (is_search()) {
		$pageNames[] = 'Suche';
	} elseif (is_author()) {
		$pageNames[] = $author->first_name.'_'.$author->last_name;
	} elseif (is_single()) {
		$category = get_the_category();
		$cat = $category[0]->cat_ID;
		$pageNames[] = $category[0]->name;
	}
	
	if (!empty($pageNames) && ($page = get_page_by_title($pageNames[0].'_Sidebar', false))) {
		$backgroundImage = get_post_meta($page->ID, 'backgroundImage');
		$backgroundImage = $backgroundImage[0];
	}
	if (!empty($pageNames) && ($page = get_page_by_title($pageNames[0].'_Center', false))) {
		$backgroundImage = get_post_meta($page->ID, 'backgroundImage');
		$backgroundImage = $backgroundImage[0];
	}

	if ($cat == BGProjectConfig::$l311['category_id']) {
		$backgroundImage = 'http://berlinergazette.de/wp-content/uploads/Berliner_Gazette_Netzwerk_20101.jpg';
	}
	// Digital Backyards Background in Forum
	if (function_exists('is_bbpress') && is_bbpress()) {
		$backgroundImage = 'http://berlinergazette.de/wp-content/uploads/Digital-Backy0ards-BG-Logo-041.jpg';
	}
	if (!empty($backgroundImage)) {
		?>
		<style>
		body {
			background-image: url(<?php echo $backgroundImage; ?>);
			background-repeat: repeat;
		}
		</style>
		<?php
	}
	?>
	<script src="http://connect.facebook.net/de_DE/all.js#xfbml=1"></script>
</head>
<body <?php body_class(WPLANG); ?>>
	<div id="app">
		<div id="header">
			<h1>
				<a href="<?php bloginfo('url'); ?>" title="zur Startseite" rel="index">
					<?php
					$logoFilename = 'berliner_gazette_logo_de.gif';
					if (
						(function_exists('is_bbpress') && is_bbpress()) ||
						(isset($cat) && $cat == BGProjectConfig::$digitalBackyards['category_id'])
						) {
						$logoFilename = 'berliner_gazette_logo_en.gif';
					}
					?>
					<img src="<?php bloginfo('template_directory'); ?>/images/<?= $logoFilename; ?>" alt="" />
				</a>
			</h1>
			<div id="topbar">
				<?php
				if (function_exists('is_bbpress') && is_bbpress()) {
					echo 'Welcome to the collaborative platform of “Digital Backyards”';
				} else {
					require_once TEMPLATEPATH.'/elements/blogpost-ticker.php';
				}
				require_once TEMPLATEPATH.'/elements/searchform.php';
				?>
			</div>
		</div>
		<div id="body">
