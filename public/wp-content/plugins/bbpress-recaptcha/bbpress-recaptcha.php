<?php

/*
Plugin Name: bbPress reCaptcha
Plugin URI: http://pippinsplugins.com/bbpress-recaptcha
Description: Adds reCaptcha to the bbPress 2.0 topic reply form
Version: 1.1
Author: Pippin Williamson
Contributors: mordauk
Author URI: http://pippinsplugins.com
*/

if( !function_exists('_recaptcha_qsencode') ) {

	require_once( dirname( __FILE__ ) . '/recaptchalib.php');

}

// Load the text domain
function bbpc_textdomain() {

	// Set filter for plugin's languages directory
	$bbpc_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$bbpc_lang_dir = apply_filters( 'bbp_recaptcha_languages_directory', $bbpc_lang_dir );


	// Traditional WordPress plugin locale filter
	$locale        = apply_filters( 'plugin_locale',  get_locale(), 'bbpress-recaptcha' );
	$mofile        = sprintf( '%1$s-%2$s.mo', 'bbpress-recaptcha', $locale );

	// Setup paths to current locale file
	$mofile_local  = $bbpc_lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/bbpress-recaptcha/' . $mofile;

	if ( file_exists( $mofile_global ) ) {
		// Look in global /wp-content/languages/bbpress-recaptcha folder
		load_textdomain( 'bbpress-recaptcha', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) {
		// Look in local /wp-content/plugins/bbpress-recaptcha/languages/ folder
		load_textdomain( 'bbpress-recaptcha', $mofile_local );
	} else {
		// Load the default language files
		load_plugin_textdomain( 'bbpress-recaptcha', false, $bbpc_lang_dir );
	}

}
add_action( 'init', 'bbpc_textdomain' );

// adds the reCaptcha to the reply to topic form
function bbpc_display_reply_recaptcha() {

	$bbpc_options = get_option('bbpc_settings');
	if( ! current_user_can('manage_options') ) {
		if( is_user_logged_in() && ! isset( $bbpc_options['show_to_logged_in'] ) )
			return;

		$publickey = trim( $bbpc_options['public_key'] );
		echo recaptcha_get_html( $publickey );
	}
}
add_action('bbp_theme_before_reply_form_submit_wrapper', 'bbpc_display_reply_recaptcha');

// adds the reCaptcha to the new topic creation form
function bbpc_display_topic_recaptcha() {

	$bbpc_options = get_option('bbpc_settings');
	if( ! current_user_can('manage_options') ) {
		if( is_user_logged_in() && !isset( $bbpc_options['show_to_logged_in'] ) )
			return;

		$publickey = trim( $bbpc_options['public_key'] ); // you got this from the signup page
		echo recaptcha_get_html($publickey);
	}
}
add_action('bbp_theme_before_topic_form_submit_wrapper', 'bbpc_display_topic_recaptcha');

function bbpc_validate_reply_recaptcha( $reply_id ) {
	if( ! current_user_can('manage_options') ) {

		$bbpc_options = get_option('bbpc_settings');

		if( is_user_logged_in() && !isset( $bbpc_options['show_to_logged_in'] ) )
			return;

		$privatekey = trim( $bbpc_options['private_key'] );
		$resp = recaptcha_check_answer(
			$privatekey,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]
		);

		if ( ! $resp->is_valid ) {
			bbp_add_error( 'bbp_reply_duplicate', __( '<strong>ERROR</strong>: The words you entered were incorrect', 'bbpress-recaptcha' ) );
		}
	}
}
add_action('bbp_new_reply_pre_extras', 'bbpc_validate_reply_recaptcha');

function bbpc_validate_topic_recaptcha( $reply_id ) {
	if( ! current_user_can('manage_options') ) {

		$bbpc_options = get_option('bbpc_settings');

		if( is_user_logged_in() && !isset( $bbpc_options['show_to_logged_in'] ) )
			return;

		$privatekey = trim( $bbpc_options['private_key'] );
		$resp = recaptcha_check_answer(
			$privatekey,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]
		);

		if ( ! $resp->is_valid ) {
			bbp_add_error( 'bbp_reply_duplicate', __( '<strong>ERROR</strong>: The words you entered were incorrect', 'bbpress-recaptcha' ) );
		}
	}
}
add_action('bbp_new_topic_pre_extras', 'bbpc_validate_topic_recaptcha');


/*******************************************
* ettings Page
*******************************************/

function bbpc_settings_page() {
	$bbpc_options = get_option('bbpc_settings');

	?>
	<div class="wrap">
		<h2><?php _e( 'bbPress reCaptcha Settings', 'bbpress-recaptcha' ); ?></h2>
		<?php
		if ( ! isset( $_REQUEST['updated'] ) )
			$_REQUEST['updated'] = false;
		?>
		<?php if ( false !== $_REQUEST['updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'bbpress-recaptcha' ); ?></strong></p></div>
		<?php endif; ?>
		<form method="post" action="options.php">

			<?php settings_fields( 'bbpc_settings_group' ); ?>

			<h4><?php _e( 'reCaptcha Keys', 'bbpress-recaptcha' ); ?></h4>
			<p>
				<label for="bbpc_settings[public_key]"><?php _e( 'reCaptcha Public Key', 'bbpress-recaptcha' ); ?></label><br/>
				<input id="bbpc_settings[public_key]" style="width: 300px;" name="bbpc_settings[public_key]" type="text" value="<?php echo esc_attr( $bbpc_options['public_key'] ); ?>" />
				<p class="description"><?php _e( 'This your own personal reCaptcha Public key. Go to <a href="https://www.google.com/recaptcha/admin/list">your account</a>, then click on your domain (or add a new one) to find your public key.', 'bbpress-recaptcha' ); ?></p>
			</p>
			<p>
				<label for="bbpc_settings[private_key]"><?php _e( 'reCaptcha Private Key', 'bbpress-recaptcha' ); ?></label><br/>
				<input id="bbpc_settings[private_key]" style="width: 300px;" name="bbpc_settings[private_key]" type="text" value="<?php echo esc_attr( $bbpc_options['private_key'] ); ?>" />
				<p class="description"><?php _e( 'This your own personal reCaptcha Private key. Go to <a href="https://www.google.com/recaptcha/admin/list">your account</a>, then click on your domain (or add a new one) to find your private key.', 'bbpress-recaptcha' ); ?></p>
			</p>
			<p>
				<input id="bbpc_settings[show_to_logged_in]" name="bbpc_settings[show_to_logged_in]" type="checkbox" value="1" <?php checked( true, isset( $bbpc_options['show_to_logged_in'] ) ); ?>/>
				<label for="bbpc_settings[show_to_logged_in]"><?php _e( 'Show to logged-in users?' ); ?></label><br/>
				<p class="description"><?php _e( 'Require the logged in users fill out the reCaptcha form? Note, admins are always excluded.', 'bbpress-recaptcha' ); ?></p>
			</p>

			<!-- save the options -->
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'bbpress-recaptcha' ); ?>" />
			</p>

		</form>
	</div><!--end wrap-->

	<?php
}

// register the plugin settings
function bbpc_register_settings() {

	// create whitelist of options
	register_setting( 'bbpc_settings_group', 'bbpc_settings' );
}
add_action( 'admin_init', 'bbpc_register_settings' );


function bbpc_settings_menu() {

	// add settings page
	add_submenu_page('options-general.php', __( 'bbPress reCaptcha Settings', 'bbpress-recaptcha' ), __( 'bbPress reCaptcha', 'bbpress-recaptcha' ), 'manage_options', 'bbpress-recaptcha-settings', 'bbpc_settings_page');
}
add_action('admin_menu', 'bbpc_settings_menu');