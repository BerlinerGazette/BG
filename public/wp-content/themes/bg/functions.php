<?php

/**
 * Return the first argument that is not empty
 * @param mixed
 * @return mixed
 */
function coalesce()
{
	foreach(func_get_args() as $arg) {
		if (!empty($arg)) return $arg;
	}
	return false;
}

function truncate($string, $length, $end = '…')
{
	if (strlen($string) < $length) return $string;
	return substr($string, 0, $length).$end;
}

class BGTime
{
	/**
	 * Return a nicely formatted string for a time difference or time in 
	 * past or future
	 * @param integer $timestamp
	 * @param integer $timestamp2
	 * @param integer $precision
	 * @return string
	 */
	public static function nice($timestamp, $timestamp2 = null, $precision = 2) {
		// parameter sanitize
		if ($timestamp2 == null) {
			$timestamp2 = time();
		}
		if ($precision <= 0) return null;
		$precision = coalesce(abs((int) $precision), 1);
		// translation matrix
		$delta = (int) $timestamp - (int) $timestamp2;
		$intervals = array(
			12 * 30 * 24 * 60 * 60	=> array('Jahr', 'Jahre'),
			30 * 24 * 60 * 60       => array('Monat', 'Monaten'),
			24 * 60 * 60            => array('Tag', 'Tagen'),
			60 * 60                 => array('Stunde', 'Stunden'),
			60                      => array('Minute', 'Minuten'),
			1                       => array('Sekunde', 'Sekunden'),
			0						=> array('jetzt')
		);
		foreach($intervals as $seconds => $arr) {
			if ($seconds == 0) {
				return $arr[0];
			}
			if (abs($i = $delta / $seconds) >= 1) {
				if ($precision > 1 && func_num_args() >= 2) {
					$j = floor(abs($i));
				} else {
					$j = round(abs($i));
				}		
				$add = self::nice($timestamp + $j * $seconds * ($delta < 0 ? 1 : -1), $timestamp2, $precision - 1, true);
				return $j.' '.$arr[$j > 1].(!empty($add) && $add !== $intervals[0][0] ? ', '.$add : '');
			}
		}
		return $intervals[0][0];
	}

	public static function niceShort($timestamp, $timestamp2 = null) {
		return self::nice($timestamp, $timestamp2, 1);
	}
}


 /**
 * stuff below this comment is from the theme template, not sure what is in use
 * and what not - ephigenia
 */

class BGProjectConfig {
	public static $dossiers = array(
		'category_id' => 40,
	);
	public static $learningFromFukushima = array(
		'tag_id' => 714,
	);
	public static $liquidwriting = array(
		'category_id' => 123,
	);
	public static $lebenskuenstler = array(
		'category_id' => 338,
		'tags' => array(
			"srh-hochschule-berlin",
			"osz-handel-i-berlin",
			"roentgenschule-berlin",
		),
	);
	public static $l311 = array(
		'category_id' => 714,
	);
	public static $bqv = array(
		'category_id' => 1112,
	);
	public static $digitalBackyards = array(
		'category_id' => 1247,
	);
}

/* blast you red baron! */
require_once (ABSPATH . WPINC . '/class-snoopy.php');

if ( function_exists('register_sidebars') ) {

	// left sidebar
	register_sidebar(array(
		'name' => 'Sidebar left top (global)',
		'id' => 'main_sidebar_left_top',
	));
	
	// right sidebar
	register_sidebar(array(
		'name' => 'Sidebar right (global)',
		'id' => 'main_sidebar_right',
	));
	register_sidebar(array(
		'name' => 'Sidebar right (detailpage)',
		'id' => 'main_sidebar_right_detailpage',
	));
	
	// liquidwriting
	register_sidebar(array(
		'name' => 'liquidwriting',
		'id' => 'sidebar-liquidwriting',
	));

	// lebenskünstler project
	register_sidebar(array(
		'name' => 'lebenskuenstler-left',
		'id' => 'sidebar-lebenskuenstler-left'
	));
	register_sidebar(array(
		'name' => 'lebenskuenstler-right',
		'id' => 'sidebar-lebenskuenstler-right'
	));
	
	// l311 / learning from fukushima symposium
	register_sidebar(array(
		'name' => 'l311-left',
		'id' => 'sidebar-l311-left'
	));
	register_sidebar(array(
		'name' => 'l311-right',
		'id' => 'sidebar-l311-right'
	));
	
	// bqv (2012)
	register_sidebar(array(
		'name' => 'bqv-left',
		'id' => 'sidebar-bqv-left',
	));
	register_sidebar(array(
		'name' => 'bqv-right',
		'id' => 'sidebar-bqv-right',
	));

	// bbpress (forum) sidebar
	register_sidebar(array(
		'name' => 'bbpress-left',
		'id' => 'sidebar-bbpress-left',
	));
	
	// Digital Backyards (2012)
	register_sidebar(array(
		'name' => 'digital-backyards-left',
		'id' => 'sidebar-digital-backyards-left',
	));
	register_sidebar(array(
		'name' => 'digital-backyards-right',
		'id' => 'sidebar-digital-backyards-right',
	));
}

$current = 'r167';
function k2info($show='') {
global $current;
	switch($show) {
	case 'version' :
    	$info = 'Beta Two '. $current;
    	break;
    case 'scheme' :
    	$info = bloginfo('template_url') . '/styles/' . get_option('k2scheme');
    	break;
    }
    echo $info;
}

function k2update() {
	if ( !empty($_POST) ) {
		if ( isset($_POST['k2scheme_file']) ) {
			$k2scheme_file = $_POST['k2scheme_file'];
			update_option('k2scheme', $k2scheme_file, '','');
		}
		if ( isset($_POST['livesearch']) ) {
			$search = $_POST['livesearch'];
			update_option('k2livesearch', $search, '','');
		}
		if ( isset($_POST['livecommenting']) ) {
			$commenting = $_POST['livecommenting'];
			update_option('k2livecommenting', $commenting, '','');
		}
		if ( isset($_POST['widthtype']) ) {
			$widthtype = $_POST['widthtype'];
			update_option('k2widthtype', $widthtype, '','');
		}
		if ( isset($_POST['asides_text']) ) {
			$asides_text = $_POST['asides_text'];
			update_option('k2asidescategory', $asides_text, '','');
		}
		if ( isset($_POST['asidesposition']) ) {
			$asidesposition = $_POST['asidesposition'];
			update_option('k2asidesposition', $asidesposition, '','');
		}
		if ( isset($_POST['asidesnumber']) ) {
			$asidesnumber = $_POST['asidesnumber'];
			update_option('k2asidesnumber', $asidesnumber, '','');
		}
		if ( isset($_POST['about_text']) ) {
			$about = $_POST['about_text'];
			update_option('k2aboutblurp', $about, '','');
		}
		if ( isset($_POST['deliciousname']) ) {
			$name = $_POST['deliciousname'];
			update_option('k2deliciousname', $name, '','');
		}
		if ( isset($_POST['archives']) ) {
			$add = $_POST['archives'];
			update_option('k2archives', $add, '','');
			create_archive();
		} else {
		// thanks to Michael Hampton, http://www.ioerror.us/ for the assist
			$remove = '';
			update_option('k2archives', $remove, '','');
			delete_archive();
		}

		if ( isset($_POST['configela']) ) {
			if (!setup_archive()) unset($_POST['configela']);
		}
	}
}

function create_archive() {
global $wpdb, $user_ID;
get_currentuserinfo();
	$check = $wpdb->query("SELECT * from $wpdb->posts WHERE post_title = 'Archives'");
		if(!$check) {
	$message = "Do not edit this page";
	$title_message = 'Archives';
	$content = apply_filters('content_save_pre', $message);
	$post_title = apply_filters('title_save_pre', $title_message);
	$now = current_time('mysql');
	$now_gmt = current_time('mysql', 1);
	$post_author = $user_ID;
	$id_result = $wpdb->get_row("SHOW TABLE STATUS LIKE '$wpdb->posts'");
	$post_ID = $id_result->Auto_increment;
	$post_name = sanitize_title($post_title, $post_ID);
	$ping_status = get_option('default_ping_status');
	$comment_status = get_option('default_comment_status');
	
	$postquery ="INSERT INTO $wpdb->posts
			(ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,  post_status, comment_status, ping_status, post_password, post_name, to_ping, post_modified, post_modified_gmt, post_parent, menu_order)
			VALUES
			('$post_ID', '$post_author', '$now', '$now_gmt', '$content', '$post_title', '', 'static', '$comment_status', '$ping_status', '', '$post_name', '', '$now', '$now_gmt', '', '')";
	$result = $wpdb->query($postquery);
	$metaquery = "INSERT INTO $wpdb->postmeta(meta_id, post_id, meta_key, meta_value) VALUES('', '$post_ID', '_wp_page_template', 'archives.php')";
	$result2 = $wpdb->query($metaquery);
	}
}

function delete_archive() {
global $wpdb;
	$check = $wpdb->query("SELECT * from $wpdb->posts WHERE post_title = 'Archives'");
		if($check) {
	$burninate = $wpdb->query("DELETE from $wpdb->posts WHERE post_title = 'Archives' and post_status = 'static'");
	$result = $wpdb->query($burninate);
	}
}

function setup_archive() {
	global $wpdb;

	if (file_exists(ABSPATH . 'wp-content/plugins/UltimateTagWarrior/ultimate-tag-warrior-core.php') && in_array('UltimateTagWarrior/ultimate-tag-warrior.php', get_option('active_plugins'))) {
		$menu_order="chrono,tags,cats";
	} else {
		$menu_order="chrono,cats";
	}

	$initSettings = array(

	// we always set the character set from the blog settings
		'newest_first' => 0,
		'num_entries' => 1,
		'num_entries_tagged' => 0,
		'num_comments' => 1,
		'fade' => 1,
		'hide_pingbacks_and_trackbacks' => 1,
		'use_default_style' => 1,
		'paged_posts' => 1,
		'selected_text' => '',
		'selected_class' => 'selected',
		'comment_text' => '<span>%</span>',
		'number_text' => '<span>%</span>',
		'number_text_tagged' => '(%)',
		'closed_comment_text' => '<span>%</span>',
		'day_format' => 'jS',
		'error_class' => 'alert',
	// allow truncating of titles
		'truncate_title_length' => 0,
		'truncate_cat_length' => 25,
		'truncate_title_text' => '&#8230;',
		'truncate_title_at_space' => 1,
		'abbreviated_month' => 1,
		'tag_soup_cut' => 0,
		'tag_soup_X' => 0,
	// paged posts related stuff
		'paged_post_num' => 15,
		'paged_post_next' => '&laquo; vorige 15 Beiträge',
		'paged_post_prev' => 'nächste 15 Beiträge &raquo;',
	// default text for the tab buttons
		'menu_order' => $menu_order,
		'menu_month' => 'Chronology',
		'menu_cat' => 'Taxonomy',
		'menu_tag' => 'Folksonomy',
		'before_child' => '&nbsp;&nbsp;&nbsp;',
		'after_child' => '',
		'loading_content' => '<img src="'.get_bloginfo('template_url').'/images/spinner.gif" class="elaload" alt="Spinner" />',
		'idle_content' => '',
		'excluded_categories' => '0');

	if (function_exists('af_ela_set_config')) {
		$ret = af_ela_set_config($initSettings);
	}

	return $ret;
}

// if we can't find k2 installed lets go ahead and install all the options that run K2.  This should run only one more time for all our existing users, then they will just be getting the upgrade function if it exists.

if (!get_option('k2installed')) {
add_option('k2installed', $current, 'This options simply tells me if K2 has been installed before', $autoload);
add_option('k2aboutblurp', 'This is the about text', 'Allows you to write a small blurp about you and your blog, which will be put on the frontpage', $autoload);
add_option('k2asidescategory', '0', 'A category which will be treated differently from other categories', $autoload);
add_option('k2asidesposition', '0', 'Whether to use inline or sidebar asides', $autoload);
add_option('k2livesearch', 'live', "If you don't trust JavaScript and Ajax, you can turn off LiveSearch. Otherwise I suggest you leave it on", $autoload); // (live & classic)
add_option('k2asidesnumber', '3', 'The number of Asides to show in the Sidebar. Default is 3.', $autoload);
add_option('k2widthtype', 'flexible', "Determines whether to use flexible or fixed width.", $autoload); // (flexible & fixed)
add_option('k2deliciousname', '', 'Makes use of Alexander Malovs Delicious plugin to show the delicious links on the sidebar.', $autoload);
add_option('k2archives', '', 'Set whether K2 has a Live Archive page', $autoload);
add_option('k2scheme', '', 'Choose the Scheme you want K2 to use', $autoload);
add_option('k2livecomments', '0', "If you don't trust JavaScript and Ajax, you can turn off Live Commenting. Otherwise I suggest you leave it on", $autoload);
}

// Here we handle upgrading our users with new options and such.  If k2installed is in the DB but the version they are running is lower than our current version, trigger this event.

	elseif (get_option('k2installed') < $current) {
	/* Do something! */
	//add_option('k2upgrade-test', 'das ist der Text', 'Einfach testen', $autoload);
}

// Let's add the options page.
add_action ('admin_menu', 'k2menu');

$k2loc = '../themes/' . basename(dirname($file)); 

function k2menu() {
	add_submenu_page('themes.php', 'Cutline Options', 'Cutline Options', 5, $k2loc . 'functions.php', 'menu');
}

function menu() {
	load_plugin_textdomain('k2options');
	//this begins the admin page
?>

<?php if (isset($_POST['Submit'])) : ?>
	<div class="updated">
		<p><?php _e('Cutline Options sind aktualisiert'); ?></p>
	</div>
<?php endif; ?>

<div class="wrap">

	<h2><?php _e('Cutline Options'); ?></h2>
	<form name="dofollow" action="" method="post">
	  <input type="hidden" name="action" value="<?php k2update(); ?>" />
	  <input type="hidden" name="page_options" value="'dofollow_timeout'" />
		<table width="700px" cellspacing="2" cellpadding="5" class="editform">
			<?php if (function_exists('delicious')) { ?> 
			<tr valign="top">
			<th scope="row"><?php echo __('Delicious User Name'); ?></th>
			<td>
				<label for="deliciousname"><?php echo __('Delicious User Name'); ?></label>
				<input name="deliciousname" style="width: 300px;" id="deliciousname" value="<?php echo get_option('k2deliciousname'); ?>">
				<p><small>Schreibe deinen schönen Namen hier, <a href="http://www.w-a-s-a-b-i.com/archives/2004/10/15/delisious-cached/">Alexander Malov's del.icio.us plugin</a></small></p>
			</td>
			</tr>
			<?php } ?>
			<tr valign="top">
			<th scope="row"><?php echo __('Archives Page'); ?></th>
			<td>
				<input name="archives" id="add-archive" type="checkbox" value="add_archive" <?php checked('add_archive', get_option('k2archives')); ?> />
				<label for="add-archives"><?php _e('Aktiviere die Cutline Archiv-Seite') ?></label>
				<p><small>Aktiviere diese Box, dann wird oben ein Menü gezeigt.</small></p>
			</td>
			</tr>
		</table>
	
		<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Options') ?> &raquo;" /></p>
	
	</form>
</div>

<div class="wrap">
	<p style="text-align: center;">Bekomme hilfe für Cutline mit <a href="http://cutline.tubetorial.com">der Cutline Supportseite</a>.</p>
</div>

<?php } // this ends the admin page ?>