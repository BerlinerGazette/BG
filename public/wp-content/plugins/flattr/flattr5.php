<?php

if (session_id() == '') { session_start(); }

class Flattr
{
	const VERSION = '0.9.25.4';
	const WP_MIN_VER = '3.0';
	const API_SCRIPT  = 'api.flattr.com/js/0.6/load.js?mode=auto';

	/** @var array */
	protected static $categories = array('text', 'images', 'audio', 'video', 'software', 'rest');
	/** @var array */
	protected static $languages;
	/** @var Flattr */
	protected static $instance;

	/** @var Flattr_Settings */
	protected $settings;

	/** @var String */
	protected $basePath;

	public function __construct()
	{	
		if (is_admin())
		{
			if (!$this->compatibilityCheck())
			{
				return;
			}
			
			$this->init();
		} else {

                    if (( get_option('flattr_aut_page', 'off') == 'on' || get_option('flattr_aut', 'off') == 'on' ) && !in_array( 'live-blogging/live-blogging.php' , get_option('active_plugins') ))
                    {
                        if (get_option('flattr_handles_exerpt')==1) {
                            remove_filter('get_the_excerpt', 'wp_trim_excerpt');
                            add_filter('get_the_excerpt', array($this, 'filterGetExcerpt'), 1);
                        }
                        if ( get_option('flattr_override_sharethis', 'false') == 'true' ) {
                                add_action('plugins_loaded', array($this, 'overrideShareThis'));
                        }
                        add_filter('the_content', array($this, 'injectIntoTheContent'), 32767);
                    }
                }

		wp_enqueue_script('flattrscript', ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://' ) . self::API_SCRIPT, array(), '0.6', true);
	}

	function overrideShareThis() {
		if ( remove_filter('the_content', 'st_add_widget') || remove_filter('the_excerpt', 'st_add_widget') ) {
			add_filter('flattr_button', array($this, 'overrideShareThisFilter'));
		}
	}

	protected function addAdminNoticeMessage($msg)
	{
		if (!isset($this->adminNoticeMessages))
		{
			$this->adminNoticeMessages = array();
			add_action( 'admin_notices', array(&$this, 'adminNotice') );
		}
		
		$this->adminNoticeMessages[] = $msg;
	}
	
	public function adminNotice()
	{
		echo '<div id="message" class="error">';
		
		foreach($this->adminNoticeMessages as $msg)
		{
			echo "<p>{$msg}</p>";
		}
		
		echo '</div>';
	}

	protected function compatibilityCheck()
	{
		global $wp_version;
		
		if (version_compare($wp_version, self::WP_MIN_VER, '<'))
		{
			$this->addAdminNoticeMessage('<strong>Warning:</strong> The Flattr plugin requires WordPress '. self::WP_MIN_VER .' or later. You are currently using '. $wp_version);
			return false;
		}
		
		return true;
	}

	public function getBasePath()
	{
		if (!isset($this->basePath))
		{
			$this->basePath = WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/';
		}
		
		return $this->basePath;
	}

	public function getButton($skipOptionCheck = false)
	{
		global $post;

		if ( ! $skipOptionCheck && ( ($post->post_type == 'page' && get_option('flattr_aut_page', 'off') != 'on') || ($post->post_type != 'page' && get_option('flattr_aut', 'off') != 'on') || is_feed() ) )
		{
			return '';
		}

		if (get_post_meta($post->ID, '_flattr_btn_disabled', true))
		{
			return '';
		}

		if (get_option('user_based_flattr_buttons_since_time')< strtotime(get_the_time("c",$post)))
                    $flattr_uid = (get_option('user_based_flattr_buttons')&& get_user_meta(get_the_author_meta('ID'), "user_flattr_uid", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_uid", true): get_option('flattr_uid');
                else
                    $flattr_uid = get_option('flattr_uid');
                if (!$flattr_uid) {
			return '';
		}

		$selectedLanguage = get_post_meta($post->ID, '_flattr_post_language', true);
		if (empty($selectedLanguage))
		{
			$selectedLanguage = (get_option('user_based_flattr_buttons')&& get_user_meta(get_the_author_meta('ID'), "user_flattr_lng", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_lng", true): get_option('flattr_lng');
		}

		$selectedCategory = get_post_meta($post->ID, '_flattr_post_category', true);
		if (empty($selectedCategory))
		{
			$selectedCategory = (get_option('user_based_flattr_buttons')&& get_user_meta(get_the_author_meta('ID'), "user_flattr_cat", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_cat", true): get_option('flattr_cat');
		}

		$hidden = get_post_meta($post->ID, '_flattr_post_hidden', true);
		if ($hidden == '')
		{
			$hidden = get_option('flattr_hide', false);
		}

		$buttonData = array(

			'user_id'	=> $flattr_uid,
			'url'		=> get_permalink(),
			'compact'	=> ( get_option('flattr_compact', false) ? true : false ),
			'hide'		=> $hidden,
			'language'	=> $selectedLanguage,
			'category'	=> $selectedCategory,
			'title'		=> strip_tags(get_the_title()),
			'body'		=> strip_tags(preg_replace('/\<br\s*\/?\>/i', "\n", $this->getExcerpt())),
			'tag'		=> strip_tags(get_the_tag_list('', ',', ''))

		);

		if (isset($buttonData['user_id'], $buttonData['url'], $buttonData['language'], $buttonData['category']))
		{
                        $retval;
			switch (get_option(flattr_button_style)) {
                            case "text":
                                $retval = '<a href="'. static_flattr_url($post).'" title="Flattr" target="_blank">Flattr this!</a>';
                                break;
                            case "image":
                                $retval = '<a href="'. static_flattr_url($post).'" title="Flattr" target="_blank"><img src="'. FLATTRSS_PLUGIN_PATH .'/img/flattr-badge-large.png" alt="flattr this!"/></a>';
                                break;
                            default:
                                $retval = $this->getButtonCode($buttonData);;
                        }
                        return $retval;
		}
	}

	protected function getButtonCode($params)
	{
		$rev = sprintf('flattr;uid:%s;language:%s;category:%s;',
			$params['user_id'],
			$params['language'],
			$params['category']
		);

		if (!empty($params['tag']))
		{
			$rev .= 'tags:'. addslashes($params['tag']) .';';
		}

		if ($params['hide'])
		{
			$rev .= 'hidden:1;';
		}

		if ($params['compact'])
		{
			$rev .= 'button:compact;';
		}

		if (empty($params['body']) && !in_array($params['category'], array('images', 'video', 'audio')))
		{
			$params['body'] = get_bloginfo('description');

			if (empty($params['body']) || strlen($params['body']) < 5)
			{
				$params['body'] = $params['title'];
			}
		}

		return sprintf('<a class="FlattrButton" style="display:none;" href="%s" title="%s" rev="%s">%s</a>',
			$params['url'],
			addslashes($params['title']),
			$rev,
			addslashes($params['body'])
		);
	}

	public static function getCategories()
	{
		return self::$categories;
	}

	public static function filterGetExcerpt($content)
	{
            $excerpt_length = apply_filters('excerpt_length', 55);
            $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');

            return self::getExcerpt($excerpt_length, $excerpt_more);
	}

	public static function getExcerpt($excerpt_max_length = 55, $excerpt_more = ' [...]')
	{
		global $post;
		
		$excerpt = $post->post_excerpt;
		if (! $excerpt)
		{
			$excerpt = $post->post_content;
	    }

		$excerpt = strip_shortcodes($excerpt);
		$excerpt = strip_tags($excerpt);
		$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
		
		// Hacks for various plugins
		$excerpt = preg_replace('/httpvh:\/\/[^ ]+/', '', $excerpt); // hack for smartyoutube plugin
		$excerpt = preg_replace('%httpv%', 'http', $excerpt); // hack for youtube lyte plugin

            $excerpt = explode(' ', $excerpt, $excerpt_max_length);
              if ( count($excerpt) >= $excerpt_max_length) {
                array_pop($excerpt);
                $excerpt = implode(" ",$excerpt).' ...';
              } else {
                $excerpt = implode(" ",$excerpt);
              }
              $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);

	    // Try to shorten without breaking words
	    if ( strlen($excerpt) > 1024 )
	    {
			$pos = strpos($excerpt, ' ', 1024);
			if ($pos !== false)
			{
				$excerpt = substr($excerpt, 0, $pos);
			}
		}

		// If excerpt still too long
		if (strlen($excerpt) > 1024)
		{
			$excerpt = substr($excerpt, 0, 1024);
		}

		return $excerpt;
	}

	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public static function getLanguages()
	{
		if (!isset(self::$languages))
		{
			include(Flattr::getInstance()->getBasePath() . 'languages.php');
			self::$languages = $languages;
		}
		
		return self::$languages;
	}
	
	protected function init()
	{
		if (!$this->settings)
		{
			require_once($this->getBasePath() . 'settings.php');
			$this->settings = new Flattr_Settings();
		}

		if (!$this->postMetaHandler)
		{
			require_once($this->getBasePath() . 'postmeta.php');
			$this->postMetaHandler = new Flattr_PostMeta();
		}
	}

	public function setExcerpt($content)
	{
		global $post;
		return $post->post_content;
	}
	
	public function overrideShareThisFilter($button) {
		$sharethis_buttons = '';
		if ( (is_page() && get_option('st_add_to_page') != 'no') || (!is_page() && get_option('st_add_to_content') != 'no') ) {
			if (!is_feed() && function_exists('st_makeEntries')) {
				$sharethis_buttons = st_makeEntries();
			}
		}
		return $sharethis_buttons . ' <style>.wp-flattr-button iframe{vertical-align:text-bottom}</style>' . $button;
	}

	public function injectIntoTheContent($content)
	{
            global $post;

            if (in_array(get_post_type(), get_option('flattr_post_types'))) {
		$button = $this->getButton();

		$button = '<p class="wp-flattr-button">' . apply_filters('flattr_button', $button) . '</p>';

		if ( get_option('flattr_top', false) ) {
			$result = $button . $content;
		}
		else {
			$result = $content . $button;
		}
		if ( ! post_password_required($post->ID) )
		{
			return $result;
		}
		
            }
            return $content;
	}	
}

Flattr::getInstance();

/**
 * returns the Flattr button
 * Use this from your template
 */
function get_the_flattr_permalink()
{

    return Flattr::getInstance()->getButton(true);
}

/**
 * prints the Flattr button
 * Use this from your template
 */
function the_flattr_permalink()
{
	echo(get_the_flattr_permalink());
}

if (file_exists(WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/flattrwidget.php')) {
    include WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/flattrwidget.php';
}

add_action('admin_init', 'tabber_stylesheet');

/*
 * Enqueue style-file, if it exists.
 */

function tabber_stylesheet() {
    $myStyleUrl = WP_PLUGIN_URL . '/flattr/tabber.css';
    $myStyleFile = WP_PLUGIN_DIR . '/flattr/tabber.css';
    if ( file_exists($myStyleFile) ) {
        wp_register_style('myStyleSheets', $myStyleUrl);
        wp_enqueue_style( 'myStyleSheets');
    }
}

    if(!defined('FLATTRSS_PLUGIN_PATH')) { define(FLATTRSS_PLUGIN_PATH, get_bloginfo('wpurl') . '/wp-content/plugins/flattr'); }
    add_option('flattrss_api_key', "");
    add_option('flattrss_autodonate', false);
    add_option('flattrss_api_secret', "");
    add_option('flattrss_api_oauth_token',"");
    add_option('flattrss_api_oauth_token_secret',"");
    add_option('flattrss_custom_image_url', FLATTRSS_PLUGIN_PATH .'/img/flattr-badge-large.png');
    add_option('flattrss_clicktrack_since_date', date("r"));
    add_option('flattrss_clickthrough_n', 0);
    add_option('flattrss_clicktrack_enabled', true);
    add_option('flattrss_error_reporting', true);
    add_option('flattrss_autosubmit', true);
    add_option('flattrss_button_enabled', true);
    add_option('flattr_post_types', array('post','page'));
    add_option('flattr_handles_exerpt', true);
    add_option('flattr_button_style','js');

function static_flattr_url($post) {
    $id = $post->ID;
    $md5 = md5($post->post_title);

    return (get_bloginfo('wpurl') .'/?flattrss_redirect&amp;id='.$id.'&amp;md5='.$md5);
}

function flattr_post2rss($content) {
    global $post;

    $flattr = "";

    if (get_post_meta($post->ID, '_flattr_btn_disabled', false)) {
        
        $flattr_post_types = get_option('flattr_post_types');

        if (is_feed() && in_array(get_post_type(), $flattr_post_types)) {
            $flattr.= ' <p><a href="'. static_flattr_url($post).'" title="Flattr" target="_blank"><img src="'. FLATTRSS_PLUGIN_PATH .'/img/flattr-badge-large.png" alt="flattr this!"/></a></p>';
        }
        
    }
    return ($content.$flattr);
}

if(function_exists('curl_init') && get_option('flattrss_button_enabled')) {
    add_filter('the_content_feed', 'flattr_post2rss',999999);
}

$call_n = 0; # Do not delete! It will break autosubmit.
function new_flattrss_autosubmit_action () {

    global $call_n;

    $call_n += 1;

    $post = $_POST;

    if (($post['post_status'] == "publish") && (get_post_meta($post['ID'], "flattrss_autosubmited", true)=="") && ($call_n == 2) && (get_the_time('U') <= time())) {

        $e = error_reporting();
        error_reporting(E_ERROR);

        $url = get_permalink($post['ID']);
        $tagsA = get_the_tags($post['ID']);
        $tags = "";

        if (!empty($tagsA)) {
            foreach ($tagsA as $tag) {
                if (strlen($tags)!=0){
                    $tags .=",";
                }
                $tags .= $tag->name;
            }
        }

        if (trim($tags) == "") {
            $tags = "blog";
        }

        $category = "text";
        if (get_option('flattr_cat')!= "") {
            $category = get_option('flattr_cat');
        }

        $language = "en_EN";
        if (get_option('flattr_lng')!="") {
            $language = get_option('flattr_lng');
        }

        if (!function_exists('getExcerpt')) {
            function getExcerpt($post, $excerpt_max_length = 1024) {

                $excerpt = $post['post_excerpt'];
                if (trim($excerpt) == "") {
                        $excerpt = $post['post_content'];
                }

                $excerpt = strip_shortcodes($excerpt);
                $excerpt = strip_tags($excerpt);
                $excerpt = str_replace(']]>', ']]&gt;', $excerpt);

                // Hacks for various plugins
                $excerpt = preg_replace('/httpvh:\/\/[^ ]+/', '', $excerpt); // hack for smartyoutube plugin
                $excerpt = preg_replace('%httpv%', 'http', $excerpt); // hack for youtube lyte plugin

                // Try to shorten without breaking words
                if ( strlen($excerpt) > $excerpt_max_length ) {
                    $pos = strpos($excerpt, ' ', $excerpt_max_length);
                    if ($pos !== false) {
                            $excerpt = substr($excerpt, 0, $pos);
                    }
                }

                // If excerpt still too long
                if (strlen($excerpt) > $excerpt_max_length) {
                    $excerpt = substr($excerpt, 0, $excerpt_max_length);
                }

                return $excerpt;
            }
        }

        $content = preg_replace(array('/\<br\s*\/?\>/i',"/\n/","/\r/", "/ +/"), " ", getExcerpt($post));
        $content = strip_tags($content);

        if (strlen(trim($content)) == 0) {
            $content = "(no content provided...)";
        }

        $title = strip_tags($post['post_title']);
        $title = str_replace(array("\"","\'"), "", $title);

        $api_key = get_option('flattrss_api_key');
        $api_secret = get_option('flattrss_api_secret');

        $oauth_token = get_option('flattrss_api_oauth_token');
        $oauth_token_secret = get_option('flattrss_api_oauth_token_secret');

        if (get_option('user_based_flattr_buttons_since_time')< strtotime(get_the_time("c",$post))) {
            $user_id = get_current_user_id();
            $oauth_token = (get_user_meta( $user_id, "user_flattrss_api_oauth_token",true)!="")?get_user_meta( $user_id, "user_flattrss_api_oauth_token",true):get_option('flattrss_api_oauth_token');
            $oauth_token_secret = (get_user_meta( $user_id, "user_flattrss_api_oauth_token_secret",true))?get_user_meta( $user_id, "user_flattrss_api_oauth_token_secret",true):get_option('flattrss_api_oauth_token_secret');
        }

        if (!class_exists('Flattr_Rest')) {
            include 'oAuth/flattr_rest.php';
        }
        $flattr_user = new Flattr_Rest($api_key, $api_secret, $oauth_token, $oauth_token_secret);

        if ($flattr_user->error()) {
            return;
        }

        if(!function_exists("encode")) {
            function encode($string) {
                if (function_exists("mb_detect_encoding")) {
                    $string = (mb_detect_encoding($string, "UTF-8") == "UTF-8" )? $string : utf8_encode($string);
                } else {
                    $string = utf8_encode($string);
                }
                return $string;
            }
        }

        $server = $_SERVER["SERVER_NAME"];
        $server = preg_split("/:/", $server);
        $server = $server[0];

        $hidden = (get_option('flattr_hide', true) || get_post_meta($post->ID, '_flattr_post_hidden', true) ||$server == "localhost")? true:false;
        
        $flattr_user->submitThing($url, encode($title), $category, encode($content), $tags, $language, $hidden);

        if ($flattr_user->http_code == 200)
                add_post_meta($post['ID'], "flattrss_autosubmited", "true");

        error_reporting($e);
    }
}


if (get_option('flattrss_autosubmit') && function_exists('curl_init')) {
    add_action('save_post','new_flattrss_autosubmit_action',9999);
}

add_action('init', 'new_flattrss_redirect');
add_action('init', 'new_flattrss_callback');

function new_flattrss_redirect() {
    include_once 'redirect.php';
}

function new_flattrss_callback() {
    include_once 'callback.php';
}

if(is_admin()) {
    $admin_notice = "";

    $oauth_token = get_option('flattrss_api_oauth_token');
    $oauth_token_secret = get_option('flattrss_api_oauth_token_secret');

    $active_plugins = get_option('active_plugins');
    if ( in_array( 'live-blogging/live-blogging.php' , $active_plugins ) && ( get_option('flattr_aut_page', 'off') == 'on' || get_option('flattr_aut', 'off') == 'on' ) ) {
        $admin_notice .= 'echo \'<div id="message" class="updated"><p><strong>Warning:</strong> There is an <a href="http://wordpress.org/support/topic/plugin-live-blogging-how-to-avoid-the_content-of-live_blog_entries" target="_blank">incompatibility</a> with [Liveblog] plugin and automatic Flattr button injection! Automatic injection is disabled as long as [Liveblog] plugin is enabled. You need to use the manual method to add Flattr buttons to your posts.</p></div>\';';
    }

    if (defined('LIBXML_VERSION')) {
        define('LIBXML_TARGET',20616);
        if (version_compare(LIBXML_VERSION, LIBXML_TARGET, '<')) {
            $admin_notice .= 'echo \'<div id="message" class="updated"><p><strong>Warning:</strong> There might be an <a href="http://forum.flattr.net/showthread.php?tid=681" target="_blank">incompatibility</a> with your web server running libxml '.LIBXML_VERSION.'. Flattr Plugin requieres at least '.LIBXML_TARGET.'. You can help improve the Flattr experience for everybody, <a href="mailto:flattr@allesblog.de?subject='.rawurlencode("My webserver is running LIBXML Version ".LIBXML_VERSION).'">please contact me</a> :). See Feedback-tab for details.</p></div>\';';
        }
    } else {
        $admin_notice .= 'echo \'<div id="message" class="error"><p><strong>Error:</strong> Your PHP installation must support <strong>libxml</strong> for Flattr plugin to work!</p></div>\';';
    }

    if (in_array( 'flattrss/flattrss.php' , $active_plugins)) {
        $admin_notice .= 'echo \'<div id="message" class="error"><p><strong>Error:</strong> It is mandatory for <strong>FlattRSS</strong> plugin to be at least deactivated. Functionality and Settings are merged into the Flattr plugin.</p></div>\';';
    }

    if (in_array( 'flattrwidget/flattrwidget.php' , $active_plugins)) {
        $admin_notice .= 'echo \'<div id="message" class="error"><p><strong>Error:</strong> It is mandatory for <strong>Flattr Widget</strong> plugin to be at least deactivated. Functionality and Settings are merged into the Flattr plugin.</p></div>\';';
    }
    
    if ($admin_notice != "") {
        add_action( 'admin_notices',
            create_function('', $admin_notice)
        );
    }

}

if (!empty($_POST) && $_POST['fsendmail']=="on") {

    if ($_POST['fphpinfo']) {
    ob_start();
    phpinfo();
    $mailtext = ob_get_clean();

    }

    $mailtext = $_POST['ftext'] ."\n<br/><br/>".$mailtext;

    $header  = "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html; charset=iso-8859-1\r\n";

    $name = ($_POST['fname'] != "")? $_POST['fname'] : "unknown";
    $from = ($_POST['femail'] != "")? $_POST['femail'] : "support@allesblog.de";
    $header .= "From: $name <$from>\r\n";
    $header .= "X-Mailer: PHP ". phpversion();

    $fmail = mail( 'flattr@allesblog.de',
          "Wordpress Flattr Plugin Support Request",
          $mailtext,
          $header);

    $admin_notice = "";
    if ($fmail) {
        $admin_notice = 'echo \'<div id="message" class="updated"><p>Mail send successfully!</p></div>\';';
    } else {
        $admin_notice = 'echo \'<div id="message" class="error"><p>There was an error sending the email.</p></div>\';';
    }

    add_action( 'admin_notices',
        create_function('', $admin_notice)
    );
}

if (is_admin() && (ini_get('allow_url_fopen') || function_exists('curl_init')))
    add_action('in_plugin_update_message-flattr/flattr.php', 'flattr_in_plugin_update_message');

function flattr_in_plugin_update_message() {

    $url = 'http://plugins.trac.wordpress.org/browser/flattr/trunk/readme.txt?format=txt';
    $data = "";
    
    if ( ini_get('allow_url_fopen') )
        $data = file_get_contents($url);
    else
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $data = curl_exec($ch);
            curl_close($ch);
        }


    if ($data) {
        $matches = null;
        $regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote(Flattr::VERSION) . '\s*=|$)~Uis';

        if (preg_match($regexp, $data, $matches)) {
            $changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));

            echo '</div><div class="update-message" style="font-weight: normal;"><strong>What\'s new:</strong>';
            $ul = false;
            $version = 99;

            foreach ($changelog as $index => $line) {
                if (version_compare($version, Flattr::VERSION,">"))
                if (preg_match('~^\s*\*\s*~', $line)) {
                    if (!$ul) {
                        echo '<ul style="list-style: disc; margin-left: 20px;">';
                        $ul = true;
                    }
                    $line = preg_replace('~^\s*\*\s*~', '', htmlspecialchars($line));
                    echo '<li style="width: 50%; margin: 0;">' . $line . '</li>';
                } else {
                    if ($ul) {
                        echo '</ul>';
                        $ul = false;
                    }

                    $version = trim($line, " =");
                    echo '<p style="margin: 5px 0;">' . htmlspecialchars($line) . '</p>';
                }
            }

            if ($ul) {
                echo '</ul><div style="clear: left;"></div>';
            }

            echo '</div>';
        }
    }
}
