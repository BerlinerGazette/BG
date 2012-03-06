<?php
/**
 * @package Flattr
 * @author Michael Henke
 * @version 0.9.25.4.1
Plugin Name: Flattr
Plugin URI: http://wordpress.org/extend/plugins/flattr/
Description: Give your readers the opportunity to Flattr your effort
Version: 0.9.25.4.1
Author: Michael Henke
Author URI: http://allesblog.de/
License: This code is (un)licensed under the kopimi (copyme) non-license; http://www.kopimi.com. In other words you are free to copy it, taunt it, share it, fork it or whatever. :)
Comment: The author of this plugin is not affiliated with the flattr company in whatever meaning.
 */

if (version_compare(PHP_VERSION, '5.0.0', '<'))
{
	require_once( WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/flattr4.php');
}
else
{
	require_once( WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/flattr5.php');

        $flattr_check = array();

        if (isset ($_POST['flattr_warn_ignore'])) {
            update_option('flattr_warn_ignore_version', Flattr::VERSION);
        }
        
        if (version_compare(get_option('flattr_warn_ignore_version'), Flattr::VERSION, '!=')) {
            $flattr_check['DOMDocument'] = class_exists('DOMDocument');
            $flattr_check['cURL'] = function_exists('curl_init');
            $flattr_check['libxml'] = defined('LIBXML_VERSION');

            if (in_array(FALSE, $flattr_check)) {
                add_action( 'admin_notices','flattrCheckAdminNotice' );
            }
        }

        function flattrCheckAdminNotice() {

                global $flattr_check;
                echo '<div id="message" class="error">';
                echo '<div style="float:right"><form method="post">'.
                     '<input type="submit" class="button" name="flattr_warn_ignore" value="Ignore"/>'.
                     '</form></div>';
                if (!$flattr_check['DOMDocument']) {
                    echo '<p><strong>Warning:</strong> You need <a href="http://php.net/manual/en/dom.installation.php" target="_blank">DOM support</a> enabled for Flattr Plugin to work properly.</p>';
                }
                if (!$flattr_check['cURL']) {
                    echo '<p><strong>Warning:</strong> You need <a href="http://php.net/manual/en/curl.installation.php" target="_blank">cURL support</a> enabled for Flattr Plugin to work properly.</p>';
                }
                if (!$flattr_check['libxml']) {
                    echo '<p><strong>Warning:</strong> You need <a href="http://de.php.net/manual/en/libxml.installation.php" target="_blank">libXML support</a> enabled for Flattr Plugin to work properly.</p>';
                }

                echo '</div>';
        }
}