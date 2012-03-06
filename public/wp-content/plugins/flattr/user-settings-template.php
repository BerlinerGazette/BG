<?php

    define(FLATTRSS_PLUGIN_PATH, get_bloginfo('wpurl') . '/wp-content/plugins/flattr');
    include_once 'oAuth/flattr_rest.php';
    include_once 'oAuth/oauth.php';
    ?>
<div class="wrap flattr-wrap" style="width:90%">
            <div>
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
        <form method="post" action="admin.php?page=flattr/settings.php">
        	<h2><?php _e('User Setup'); ?></h2>
		<p>
                    Set up your own Flattr user for all your posts.
                </p>
                <table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Your Flattr account name'); ?></th>
				<td>
					<input name="user_flattr_uid" type="text" value="<?php echo(get_user_meta(get_current_user_id( ), "user_flattr_uid", true)); ?>" />
				</td>
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

    $url = $flattr->getAuthorizeUrl($token, 'read,readextended,click,publish');

        ?><a name="Authorize"><div id="icon-options-general" class="icon32"><br /></div><h2>Authorize App</h2></a>
        <p>In order to automatically generate the correct "<em>Things</em>" link for your blog post from the feed, you need to authorize you Flattr app with your Flattr account.</p>
          <p><a href="<?php echo $url;?>">(re-)Authorize with Flattr</a>.
<?php

    $oauth_token = get_user_meta(get_current_user_id( ), "user_flattrss_api_oauth_token", true);
    $oauth_token_secret = get_user_meta(get_current_user_id( ), "user_flattrss_api_oauth_token_secret", true);

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
?>

    </div>
    <div class="tabbertab" title="Post/Page Buttons">
		<h2>Post/Page Buttons</h2>
                <p>These options are for the Flattr buttons automatically generated for posts and pages.</p>

			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php _e('Default category for your posts'); ?></th>
					<td>
						<select name="user_flattr_cat">
							<?php
								foreach (Flattr::getCategories() as $category)
								{
									printf('<option value="%1$s" %2$s>%1$s</option>',
										$category,
										($category == get_user_meta(get_current_user_id( ), "user_flattr_cat", true) ? 'selected' : '')
									);
								}
							?>
						</select>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Default language for your posts'); ?></th>
					<td>
						<select name="user_flattr_lng">
							<?php
								foreach (Flattr::getLanguages() as $languageCode => $language)
								{
									printf('<option value="%s" %s>%s</option>',
										$languageCode,
										($languageCode == get_user_meta(get_current_user_id( ), "user_flattr_lng", true) ? 'selected' : ''),
										$language
									);
								}
							?>
						</select>
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