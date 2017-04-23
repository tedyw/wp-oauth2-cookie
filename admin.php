<?php

defined('ABSPATH') or die();

class WP_OAuth2_Cookie_Admin
{
    function __construct()
    {
        $this->init();
    }

    private function init()
    {
        add_action('admin_menu', array($this, 'create_menu'));

    }

    public function create_menu()
    {
        add_menu_page('OAuth2 Cookie Settings', 'OAuth2 Cookie', 'administrator', __FILE__, array($this, 'settings_page'), '
dashicons-admin-network');
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function register_settings()
    {
        //register our settings
        register_setting('wp_oauth2_cookie-settings-group', 'wp_oauth2_cookie_client_name');
        register_setting('wp_oauth2_cookie-settings-group', 'wp_oauth2_cookie_client_id');
        register_setting('wp_oauth2_cookie-settings-group', 'wp_oauth2_cookie_client_secret');
    }

    public function settings_page()
    {
        ?>
        <div class="wrap">
            <h1>OAuth2 Cookie Settings</h1>
            <p>This plugin requests an OAuth2 access token when logging in to wordpress.<br/>
                If successful the access token is stored as a cookie with the key "[client name] + -access-token",
                which is to be used by external applications.</p>
            <p>
            <ol>
                <li>Look for credentials or create these in OAuth 2 Server -> Clients.</li>
                <li>Fill in the credentials from the chosen client that will be used for requesting access tokens.</li>
            </ol>
            <form method="post" action="options.php">
                <?php settings_fields('wp_oauth2_cookie-settings-group'); ?>
                <?php do_settings_sections('wp_oauth2_cookie-settings-group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Client name (used as cookie name)</th>
                        <td><input type="text" name="wp_oauth2_cookie_client_name"
                                   value="<?php echo esc_attr(get_option('wp_oauth2_cookie_client_name')); ?>"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Client ID</th>
                        <td><input type="text" name="wp_oauth2_cookie_client_id"
                                   value="<?php echo esc_attr(get_option('wp_oauth2_cookie_client_id')); ?>"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Client Secret</th>
                        <td><input type="text" name="wp_oauth2_cookie_client_secret"
                                   value="<?php echo esc_attr(get_option('wp_oauth2_cookie_client_secret')); ?>"/></td>
                    </tr>
                </table>

                <?php submit_button(); ?>

            </form>
        </div>
    <?php }
}

new WP_OAuth2_Cookie_Admin();





