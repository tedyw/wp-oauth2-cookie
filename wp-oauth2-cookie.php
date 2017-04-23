<?php
/**
 * Plugin Name: WP OAuth2 Cookie
 * Description: OAuth2 access token is retrieved and stored as a cookie when logging in. Stays active until logging out.
 * Version: 0.1
 * Author: Tedy Warsitha
 * Author URI: http://github.com/tedyw/
 * License: GPL2+
 **/

defined('ABSPATH') or die();

class WP_OAuth2_Cookie
{

    function __construct()
    {
        $this->init();
    }

    private function init()
    {
        require_once(dirname(__FILE__) . '/admin.php');
    }

}

function wp_oauth2_cookie_login_redirect( $redirect_to, $request, $user ) {
    $url = site_url() . '/index.php?wp-oauth2-cookie=auth';
    return $url;
}

function wp_oauth2_cookie_remove_logout() {
    $client_name = sanitize_title(get_option('wp_oauth2_cookie_client_name').'-access-token');
    setcookie($client_name, "expired", time() - 3600, '/', '.' . get_option('wp_oauth2_cookie_domain'), 0, 0);
    wp_logout();
}

function wp_oauth2_cookie_query_vars()
{
    global $wp;
    $wp->add_query_var('wp-oauth2-cookie');
}

function wp_oauth2_cookie_template_include($template){
    global $wp_query;
    if ($wp_query->get( 'wp-oauth2-cookie')) {
        switch ($wp_query->get( 'wp-oauth2-cookie')) {
            case 'auth':
                load_template(dirname(__FILE__) . '/auth.php');
                break;
        }
    }
    return $template;
}

/**
 * This plugin requires oauth2 server to be installed.
 */
function wp_oauth2_cookie_load()
{
    if (!class_exists('WO_Server')) return;

        add_action ('login_form_logout' , 'wp_oauth2_cookie_remove_logout');
        add_action('init', 'wp_oauth2_cookie_query_vars');
        add_filter('template_include', 'wp_oauth2_cookie_template_include');
        add_filter('login_redirect', 'wp_oauth2_cookie_login_redirect', 10, 3);

        new WP_OAuth2_Cookie();
}

add_action('plugins_loaded', 'wp_oauth2_cookie_load');