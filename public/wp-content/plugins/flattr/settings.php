<?php

class Flattr_Settings
{
    public function __construct()
    {
        add_action('admin_init',    array( $this, 'register_settings') );
        add_action('admin_menu',    array( $this, 'init_ui') );
    }

    public function init_ui()
    {
        $menutitle = __('Flattr', 'flattr');

        $cap = get_option('user_based_flattr_buttons')? "edit_posts":"manage_options";

        add_menu_page('Flattr',  $menutitle, $cap, 'flattr/settings.php', '', get_bloginfo('wpurl') . '/wp-content/plugins/flattr'.'/img/flattr-icon_new.png');
        add_submenu_page( 'flattr/settings.php', __('Flattr'), __('Flattr'), $cap, 'flattr/settings.php', array($this, 'render'));
        }

    public function register_settings()
    {
        register_setting('flattr-settings-group', 'flattr_uid',         array($this, 'sanitize_userid'));
        register_setting('flattr-settings-group', 'flattr_aut',         array($this, 'sanitize_auto'));
        register_setting('flattr-settings-group', 'flattr_aut_page',    array($this, 'sanitize_auto_page'));
        register_setting('flattr-settings-group', 'flattr_cat',         array($this, 'sanitize_category'));
        register_setting('flattr-settings-group', 'flattr_lng',         array($this, 'sanitize_language'));
        register_setting('flattr-settings-group', 'flattr_compact',     array($this, 'sanitize_checkbox'));
        register_setting('flattr-settings-group', 'flattr_hide',        array($this, 'sanitize_checkbox'));
        register_setting('flattr-settings-group', 'flattr_top',         array($this, 'sanitize_checkbox'));
        register_setting('flattr-settings-group', 'flattr_override_sharethis', array($this, 'sanitize_checkbox'));
        register_setting('flattr-settings-group', 'flattrss_api_key');
        register_setting('flattr-settings-group', 'flattrss_api_secret');
        register_setting('flattr-settings-group', 'flattrss_autodonate');
        register_setting('flattr-settings-group', 'flattrss_clicktrack_enabled');
        register_setting('flattr-settings-group', 'flattrss_error_reporting');
        register_setting('flattr-settings-group', 'flattrss_custom_image_url');
        register_setting('flattr-settings-group', 'flattrss_autosubmit');
        register_setting('flattr-settings-group', 'flattr_post_types');

        register_setting('flattr-settings-group', 'flattrss_button_enabled');
        register_setting('flattr-settings-group', 'flattr_handles_exerpt');
        register_setting('flattr-settings-group', 'flattr_button_style');

        register_setting('flattr-settings-group', 'flattr_warn_ignore_version');

        register_setting('flattr-settings-group', 'user_based_flattr_buttons');

        if (isset($_POST['user_flattr_uid']) && isset($_POST['user_flattr_cat']) && isset ($_POST['user_flattr_lng'])) {
            require_once( ABSPATH . WPINC . '/registration.php');
            $user_id = get_current_user_id( );

            update_user_meta( $user_id, "user_flattr_uid", $_POST['user_flattr_uid'] );
            update_user_meta( $user_id, "user_flattr_cat", $_POST['user_flattr_cat'] );
            update_user_meta( $user_id, "user_flattr_lng", $_POST['user_flattr_lng'] );
        }

        if(get_option('user_based_flattr_buttons')) {
            add_option('user_based_flattr_buttons_since_time', time());
        }
    }

    public function render()
    {
        if (current_user_can("activate_plugins")) {
            include('settings-template.php');
        } elseif (current_user_can("edit_posts") && get_option('user_based_flattr_buttons')) {
            include('user-settings-template.php');
       }
    }

    public function sanitize_category($category)
    {
        return $category;
    }

    public function sanitize_language($language)
    {
        return $language;
    }

    public function sanitize_checkbox($input)
    {
        return ($input == 'true' ? 'true' : '');
    }

    public function sanitize_auto($input)
    {
        return ($input == 'on' ? 'on' : '');
    }

    public function sanitize_auto_page($input)
    {
        return ($input == 'on' ? 'on' : '');
    }

    public function sanitize_userid($userId)
    {
        if (preg_match('/[^A-Za-z0-9-_.]/', $userId)) {
            $userId = false;
        }
        else if (is_numeric($userId)) {
            $userId = intval($userId);
        }
        return $userId;
    }
}
