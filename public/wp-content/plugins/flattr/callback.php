<?php

if ( isset ($_REQUEST['oauth_token']) && isset ($_REQUEST['oauth_verifier']) && isset($_REQUEST['page']) && ($_REQUEST['page']=="flattr/settings.php")) {

    if (session_id() == '') { session_start(); }

    include_once "oAuth/flattr_rest.php";

    $api_key = get_option('flattrss_api_key');
    $api_secret = get_option('flattrss_api_secret');

    $flattr = new Flattr_Rest($api_key, $api_secret, $_SESSION['flattrss_current_token']['oauth_token'], $_SESSION['flattrss_current_token']['oauth_token_secret']);

    $access_token = $flattr->getAccessToken($_REQUEST['oauth_verifier']);

    if ($flattr->http_code == 200) {

        add_option('flattrss_api_oauth_token', $access_token['oauth_token']);
        update_option('flattrss_api_oauth_token', $access_token['oauth_token']);

        add_option('flattrss_api_oauth_token_secret', $access_token['oauth_token_secret']);
        update_option('flattrss_api_oauth_token_secret', $access_token['oauth_token_secret']);

        require_once( ABSPATH . WPINC . '/registration.php');
        $user_id = get_current_user_id( );

        update_user_meta( $user_id, "user_flattrss_api_oauth_token", $access_token['oauth_token'] );
        update_user_meta( $user_id, "user_flattrss_api_oauth_token_secret", $access_token['oauth_token_secret'] );

    } else {
        wp_die("<h1>Callback Error.</h1><p>Please clear browser cach and cookies, then try again. Sorry for the inconvenience.</p><p align='right'>Michael Henke</p>");
    }

    header("Status: 307");
    header("Location: ". get_bloginfo('wpurl') .'/wp-admin/admin.php?page=flattr/settings.php');

    exit(307);
 }