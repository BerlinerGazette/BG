<?php

    define(FLATTRSS_PLUGIN_PATH, get_bloginfo('wpurl') . '/wp-content/plugins/flattr');

    include_once 'oAuth/flattr_rest.php';
    include_once 'oAuth/oauth.php';

    $server = $_SERVER["SERVER_NAME"];
    $server = preg_split("/:/", $server);
    $server = $server[0];

    $server2 = substr(home_url('','http'),7);
    $server2 = preg_split("/\//", $server2);
    $server2 = $server2[0];

    ?>
<div class="wrap flattr-wrap" style="width:90%">
            <div>
            <!-- <h2><?php _e('Flattr Settings'); ?> <img id="loaderanim" onload="javascript:{document.getElementById('loaderanim').style.display='none'};" src="<?php echo get_bloginfo('wpurl') . '/wp-content/plugins/flattr'.'/img/loader.gif' ?>"/></h2> -->
<div class="tabber">
    <div style="float:right; margin-top: -31px;margin-left: 10px;"><img src="../wp-content/plugins/flattr/img/flattr-logo-beta-small.png" alt="Flattr Beta Logo"/><br />
        <ul style="margin-top: 10px;">
            <li style="display: inline;">
                <script type="text/javascript">
                    var flattr_uid = "der_michael";
                    var flattr_btn = "compact";
                    var flattr_tle = "Wordpress Flattr plugin";
                    var flattr_dsc = "Give your readers the opportunity to Flattr your effort. See http://wordpress.org/extend/plugins/flattr/ for details.";
                    var flattr_cat = "software";
                    var flattr_tag = "wordpress,plugin,flattr,rss";
                    var flattr_url = "http://wordpress.org/extend/plugins/flattr/";
                </script><script src="<?php echo (isset($_SERVER['HTTPS'])) ? 'https' : 'http'; ?>://api.flattr.com/button/load.js" type="text/javascript"></script>
            </li>
            <li style="display: inline-block;position:relative; top: -6px;"><a href="https://flattr.com/donation/give/to/der_michael" style="color:#ffffff;text-decoration:none;background-image: url('<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/flattr/img/bg-boxlinks-green.png');border-radius:3px;text-shadow:#666666 0 1px 1px;width:53px;padding:1px;padding-top: 2px;padding-bottom: 2px;display:block;text-align:center;font-weight: bold;" target="_blank">Donate</a></li>
        </ul>
    </div>
    <div class="tabbertab" title="Flattr Account" style="border-left:0;">
        <form method="post" action="options.php">
        <?php settings_fields( 'flattr-settings-group' ); ?>
        <?php if (current_user_can( "activate_plugins" )): ;?>
        <p><input type="checkbox" name="user_based_flattr_buttons"<?php echo get_option('user_based_flattr_buttons')?" checked":"";?> />&nbsp;If you tick this box, every user of the blog will have the chance to register it's own Flattr buttons. Buttons will then be linked to post authors and only display if the user completed plugin setup.</p>
        <?php endif; ?>
		<h2><?php _e('Basic Setup'); ?></h2>
		<p>
                    The basic account setup enables this plugin to work.
                </p>
                <table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('The blogs/your Flattr account'); ?></th>
				<td>
					<input name="flattr_uid" type="text" value="<?php echo(get_option('flattr_uid')); ?>" />
				</td>
			</tr>
		</table>
<?php if (get_option('flattr_uid') && function_exists('curl_init')) { ?>
                <h2>Advanced Setup</h2>
                <p>
                    The advanced account setup enables autosubmit feature.
                </p>
<?php
    $oauth_token = get_option('flattrss_api_oauth_token');
    $oauth_token_secret = get_option('flattrss_api_oauth_token_secret');
    $flattrss_api_key = get_option('flattrss_api_key');
    $flattrss_api_secret = get_option('flattrss_api_secret');

    if ($oauth_token == $oauth_token_secret || $flattrss_api_key == $flattrss_api_secret) {
?>
      <ol>
          <li>Login to your Flattr Account at <a href="https://flattr.com/" target="_blank">flattr.com</a></li>
          <li>To get your personal Flattr APP Key and APP Secret you need to <a href="https://flattr.com/apps/new" target="_blank">register your blog</a> as Flattr app. <small><a href="http://developers.flattr.net/doku.php/register_your_application" target="_blank">(More Info)</a></small></li>
          <li>Choose reasonable values for <em>Application name</em>, <em>Application website</em> and <em>Application description</em></li>
          <li>It is mandatory to <strong>select BROWSER application type!</strong> This plugin will currently <strong>not work if CLIENT is selected</strong>.</li>
          <li>You must use <code><?php echo ($server == $server2)? $server2 : "$server2</code> or <code>$server"; ?></code> as callback domain.</li>
          <li>Copy 'n Paste your APP Key and APP Secret in the corresponding fields below. Save Changes.</li>
          <li>As soon as you saved your APP information <a href="#Authorize">authorize</a> your Flattr account with your own application.</li>
          <li>If everything is done correctly you'll see your <a href="#UserInfo">Flattr username and info</a> on this site.</li>
      </ol>
<?php } ?>
   <table class="form-table">
            <tr valign="top">
                <th scope="row">Callback Domain</th>
                <td><input size="30" value="<?php echo $server2; ?>" readonly/><?php if ($server!=$server2) : ?>&nbsp;or
                    <br /><input size="30" value="<?php echo $server; ?>" readonly/><p>One of the above values should work. If not. Please contact me.</p>
                <?php endif; ?></td>
            </tr>
            <tr valign="top">
                <th scope="row">APP_KEY</th>
                <td><input size="70" name="flattrss_api_key" value="<?php echo get_option('flattrss_api_key') ?>"/></td>
            </tr>
            <tr valign="top">
                <th scope="row">APP_SECRET</th>
                <td><input size="70" name="flattrss_api_secret" value="<?php echo get_option('flattrss_api_secret') ?>"/></td>
            </tr>
    </table>
    <?php

    $api_key = get_option('flattrss_api_key');
    $api_secret = get_option('flattrss_api_secret');

    if ($api_key != $api_secret) {

    $flattr = new Flattr_Rest($api_key, $api_secret);

    # Do not rawurlencode!
    $callback_ = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ;

    $token = $flattr->getRequestToken( $callback_ );
    $_SESSION['flattrss_current_token'] = $token;

    if ($token != "") {

        $url = $flattr->getAuthorizeUrl($token, 'read,readextended,click,publish');

        ?><a name="Authorize"><div id="icon-options-general" class="icon32"><br /></div><h2>Authorize App</h2></a>
        <p>In order to automatically generate the correct "<em>Things</em>" link for your blog post from the feed, you need to authorize you Flattr app with your Flattr account.</p>
          <p><a href="<?php echo $url;?>">(re-)Authorize with Flattr</a>.</p>
        <?php
    } else {
        ?><a name="Authorize"><div id="icon-options-general" class="icon32"><br /></div><h2>Authorize App</h2></a>
        <p>Unable to aquire oAuth token. What now?</p>
        <ol>
            <li>Check PHP cURL support</li>
            <li>Check PHP libXML support</li>
            <li>Check PHP DOM support</li>
            <li>DoubleCheck APP_KEY & APP_SECERT</li>
            <li>Flattr Service might be down?</li>
            <li>There might be a communication/firewall issue between your webserver and flattr.com</li>
            <li>Try again later...</li>
        </ol>
        <?php
    }

                #print_r($flattr);

    $oauth_token = get_option('flattrss_api_oauth_token');
    $oauth_token_secret = get_option('flattrss_api_oauth_token_secret');

    if ($oauth_token != $oauth_token_secret) {
        $flattr_user = new Flattr_Rest($api_key, $api_secret, $oauth_token, $oauth_token_secret);
        if ( $flattr_user->error() ) {
            echo( 'Error ' . $flattr_user->error() );
        }
        $user = $flattr_user->getUserInfo();
?>
    <div style="float:right"><img src="<?php echo $user['gravatar'];?>"></div><a name="UserInfo"><h2><img src="<?php echo FLATTRSS_PLUGIN_PATH .'/img/flattr_button.png' ?>" alt="flattr"/>&nbsp;Advanced Flattr User Info</h2></a>
    <p><?php echo $user['firstname'];?>&nbsp;<?php echo $user['lastname'];?><br/>
    <?php echo $user['username'];?>(<?php echo $user['id'];?>)</p>
    <p>Flattr: <a href="https://flattr.com/profile/<?php echo $user['username'];?>" target="_blank">Profile</a>, <a href="https://flattr.com/dashboard" target="_blank">Dashboard</a>, <a href="https://flattr.com/settings" target="_blank">Settings</a></p>
        <?php
        #print_r($flattr_user);
    }
  }
}
?>
    </div>
    <div class="tabbertab" title="Post/Page Buttons">
 		<h2>Button Style</h2>
                <p>What do you want your Flattr button to look like?</p>
                <table id="option">
                <tr>
                    <td><input type="radio" name="flattr_button_style" value="js"<?=(get_option('flattr_button_style')=="js")?" checked":"";?>/></td>
                    <td><script type="text/javascript">
                            var flattr_uid = "der_michael";
                            var flattr_btn = "<?=get_option('flattr_compact')?"compact":"";?>";
                            var flattr_tle = "Wordpress Flattr plugin";
                            var flattr_dsc = "Give your readers the opportunity to Flattr your effort. See http://wordpress.org/extend/plugins/flattr/ for details.";
                            var flattr_cat = "software";
                            var flattr_tag = "wordpress,plugin,flattr,rss";
                            var flattr_url = "http://wordpress.org/extend/plugins/flattr/";
                        </script><script src="<?php echo (isset($_SERVER['HTTPS'])) ? 'https' : 'http'; ?>://api.flattr.com/button/load.js" type="text/javascript"></script></td>
                    <td>JavaScript Version</td>
                </tr><tr>
                    <td><input type="radio" name="flattr_button_style" value="image"<?=(get_option('flattr_button_style')=="image")?" checked":"";?>/></td>
                    <td>
                        <img src="<?=get_option('flattrss_custom_image_url');?>"/>
                    </td>
                    <td>static Image</td>
                </tr><tr>
                    <td><input type="radio" name="flattr_button_style" value="text"<?=(get_option('flattr_button_style')=="text")?" checked":"";?>/></td>
                    <td><a href="#">Flattr this!</a></td>
                    <td>static Text</td>
                </tr>
                </table>
		<h2>Post/Page Buttons</h2>
                <p>These options are for the Flattr buttons automatically generated for posts and pages.</p>
		
			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php _e('Default category for your posts'); ?></th>
					<td>
						<select name="flattr_cat">
							<?php
								foreach (Flattr::getCategories() as $category)
								{
									printf('<option value="%1$s" %2$s>%1$s</option>',
										$category,
										($category == get_option('flattr_cat') ? 'selected' : '')
									);
								}
							?>
						</select>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Default language for your posts'); ?></th>
					<td>
						<select name="flattr_lng">
							<?php
								foreach (Flattr::getLanguages() as $languageCode => $language)
								{
									printf('<option value="%s" %s>%s</option>',
										$languageCode,
										($languageCode == get_option('flattr_lng') ? 'selected' : ''),
										$language
									);
								}
							?>
						</select>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Insert button before the content'); ?></th>
					<td><input <?php if (get_option('flattr_top', 'false') == 'true') { echo(' checked="checked"'); } ?> type="checkbox" name="flattr_top" value="true" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Use the compact button'); ?></th>
					<td><input <?php if (get_option('flattr_compact', 'false') == 'true') { echo(' checked="checked"'); } ?> type="checkbox" name="flattr_compact" value="true" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Hide my posts from listings on flattr.com'); ?></th>
					<td><input <?php if (get_option('flattr_hide', 'false') == 'true') { echo(' checked="checked"'); } ?> type="checkbox" name="flattr_hide" value="true" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Insert Flattr button into posts automagically'); ?></th>
					<td><input <?php if (get_option('flattr_aut', 'off') == 'on') { echo(' checked="checked"'); } ?> type="checkbox" name="flattr_aut" value="on" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Insert Flattr button into pages automagically'); ?></th>
					<td><input <?php if (get_option('flattr_aut_page', 'off') == 'on') { echo(' checked="checked"'); } ?> type="checkbox" name="flattr_aut_page" value="on" /></td>
				</tr>
                                <tr valign="top">
                                    <th scope="row" colspan="2">You can use <code>&lt;?php the_flattr_permalink() ?&gt;</code> in your template/theme to insert a flattr button
                                    </th>
                                </tr>

				<?php if ( function_exists('st_add_widget') ) { ?>
					<tr valign="top">
						<th scope="row"><?php _e('Override ShareThis widget'); ?></th>
						<td><input <?php if (get_option('flattr_override_sharethis', 'false') == 'true') { echo(' checked="checked"'); } ?> type="checkbox" name="flattr_override_sharethis" value="true" /><br />(will add the Flattr button after the ShareThis buttons)</td>
					</tr>
				<?php } ?>
			</table>
    </div>
    <div class="tabbertab">
            <h2>Advanced Settings</h2>
            <?php if (!function_exists('curl_init')) { ?>
            <p id="message" class="updated" style="padding:10px;"><strong>Attention:</strong>&nbsp;Currently nothing can be autosubmitted. Enable cURL extension for your webserver to use this feature!</p>
            
            <?php }?>

            <table>
                <tr valign="top">
                    <th scope="row">Automatic Submission</th>
                    <td><p><input name="flattrss_autosubmit" type="checkbox"<?php echo get_option('flattrss_autosubmit')? " checked": ""; echo ($oauth_token != $oauth_token_secret)? "":" disabled"; ?> />&nbsp;Check this box to automatically submit your blog post when you publish. You need to complete the full advanced setup in order for autosubmission to work.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Excerpt Handling</th>
                    <td><p>Let <select name="flattr_handles_exerpt">
                                <option value="1" <?php echo (get_option('flattr_handles_exerpt')==1)? " selected": "";?>>Flattr Plugin</option>
                                <option value="0" <?php echo (get_option('flattr_handles_exerpt')==0)? " selected": "";?>>Wordpress</option>
                           </select> handle the excerpt. If you are new to the plugin select Wordpress here and see if it works out for you. If your upgrading from an earlier version this will likely default to Flattr plugin.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Suppress Warnings</th>
                    <td><p><input name="flattrss_error_reporting" type="checkbox"<?php echo get_option('flattrss_error_reporting')? " checked": "" ?>/>&nbsp;This is an advanced option for supression of error messages upon redirect from feed to thing. Use with caution, as flattr things might be submitted incomplete. Incomplete things are subject to be hidden on the flattr homepage!<br>If in doubt, leave disabled.</p>
                    </td>
                </tr>
            </table>
            <h2>Feed Settings</h2>
            <?php if (!function_exists('curl_init')) { ?>
            <p id="message" class="updated" style="padding:10px;"><strong>Attention:</strong>&nbsp;Currently no button will be inserted in your RSS feed. Enable cURL extension for your webserver to use this feature.</p>
            <?php }?>
            <table>
                <tr valign="top">
                <th scope="row">RSS/Atom Feed Button</th>
                <td><p><input name="flattrss_button_enabled" type="checkbox" <?php if(get_option('flattrss_button_enabled')) {echo "checked";}?> />&nbsp;A Flattr button will be included in the RSS/Atom Feed of your blog.</p>
                </td>
                </tr>
                <tr valign="top">
                <th scope="row">Custom Image URL</th>
                <td><p>This image is served as static image to be included in the RSS/Atom Feed of your blog.</p><input name="flattrss_custom_image_url" size="70" value="<?php echo get_option('flattrss_custom_image_url');?>"/><br/>
                    <?php if ( get_option('flattrss_custom_image_url') != FLATTRSS_PLUGIN_PATH .'/img/flattr-badge-large.png') { ?>
                    Default Value:<br>
                    <input size="70" value="<?php echo FLATTRSS_PLUGIN_PATH .'/img/flattr-badge-large.png';?>" readonly><br />
                    <?php } ?>
                    Preview:<br>
                    <img src="<?php echo get_option('flattrss_custom_image_url');?>">
                    <p></p>
                </td>
                </tr>
            </table>
    </div>
    <div class="tabbertab">
        <h2>Expert Settings</h2>
        <p><strong>WARNING:</strong> Please do not change any value unless you are exactly sure of what you are doing! Settings made on this page will likely override every other behaviour.</p>
        <table>
            <tr valign="top">
                <th scope="row">Post Type</th>
                <td><p>Append Flattr Button only to selected post types.</p><ul>
                    <?php $types = get_post_types();
                          $flattr_post_types = get_option('flattr_post_types');
                        foreach ($types as $type) {
                            $selected = (is_array($flattr_post_types) && in_array($type, $flattr_post_types))? " checked" : "";
                            echo "<li><input name=\"flattr_post_types[]\" value=\"$type\" type=\"checkbox\"$selected/>&nbsp;$type</li>";
                        }
                    ?></ul>
                </td>
            </tr>
        </table>
    </div>

    <div class="tabbertab" title="Feedback">
        <h2>Feedback</h2>
        <p>Please post feedback regarding wordpress integration on <a href="http://wordpress.org/tags/flattr?forum_id=10" target="_blank">the plugins board at wordpress.org</a>. You can use <a href="http://forum.flattr.net/" target="_blank">the official flattr board</a> for every concern regarding flattr.</p>
        <p>If you have a certain remark, request or simply something you want to let me know feel free to mail me at <a href="mailto:flattr@allesblog.de?subject=Flattr Wordpress Plugin" title="flattr@allesblog.de">flattr@allesblog.de</a>. Please note that I'm not an official part of the Flattr Dev-Team. So I can only answer questions regarding the flattr wordpress plugin alone.</p>
        <p><strong>Spread the word!</strong></p>
        <p>You can help getting Flattr out there!</p>
        <h2>Debug</h2>
        <p>
            Please provide the following information with your support request. All fields are <em>optional</em>. However, If you expect a reply, provide at least a valid eMail address.
        </p>
        <table>
            <tr><td>Your Name:</td><td><input type="text" name="fname" /></td></tr>
            <tr><td>Your eMail:</td><td><input type="text" name="femail" /></td></tr>
            <tr><td>Comment:</td><td><textarea cols="80" rows="10" name="ftext">What's your problem?</textarea></td></tr>
            <tr><td>DEBUG:</td><td><input type="checkbox" checked name="fphpinfo">&nbsp;Include extended debug information in mail. <a href="http://php.net/manual/function.phpinfo.php" target="_blank">phpinfo()</a></td></tr>
            <tr><td>Send Mail</td><td><input type="checkbox" name="fsendmail">&nbsp;&lArr;&nbsp;tick this box and click "Save Changes" to submit support request.</td></tr>
        </table>
    </div>
    <p class="submit">
        <input type="submit" class="button-primary" value="Save Changes" />
        <input type="reset" class="button" value="Reset" />
    </p>
       		</form>
</div>
</div>

        </div><script type="text/javascript" src="<?php echo FLATTRSS_PLUGIN_PATH . '/tabber.js'; ?>"></script>