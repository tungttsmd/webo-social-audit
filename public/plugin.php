<?php

namespace Public;

use Model\AdminSetting;
use Service\BaseService;
use Model\Login as LoginAuth;
use Controller\UserController;
use Controller\PageController;

class Plugin
{
    use BaseService;
    public function init()
    {
        // ====== 1. MENU SETTING ======
        add_action('admin_menu', function () {
            add_menu_page(
                'Webo Social Audit',
                'Webo Social Audit',
                'manage_options',
                'webo_social_audit',
                [AdminSetting::make(), 'webo_social_audit_settings_page']
            );
        });

        add_action('admin_init', function () {
            register_setting('webo_social_audit_settings', 'fb_app_id');
            register_setting('webo_social_audit_settings', 'fb_app_secret');
        });

        // ====== 2. SHORTCODE LOGIN FB ======
        add_shortcode(
            'webo_oauth_user_login',
            [UserController::make(), 'user_login']
        );
        add_shortcode(
            'webo_oauth_page_login',
            [PageController::make(), 'page_login']
        );

        // Hook khai báo assets
        add_action('wp_enqueue_scripts', [$this, 'styleEnqueue']);
        add_action('wp_enqueue_scripts', [$this, 'scriptEnqueue']);
        add_action('admin_enqueue_scripts', [$this, 'adminStyleEnqueue']);
    }

    public function styleEnqueue()
    {
        $url = plugin_dir_url(__DIR__) . "src/assets/css/";
        // Enqueue CSS
        wp_enqueue_style(
            'profileTable', // Handle
            $url . "profileTable.css", // URL file CSS
            array(), // Dependencies (nếu có)
            '1.0.0', // Version
            'all' // Media
        );
        wp_enqueue_style(
            'fbLoginButton', // Handle
            $url . "fbLoginButton.css", // URL file CSS
            array(), // Dependencies (nếu có)
            '1.0.0', // Version
            'all' // Media
        );
    }
    public function adminStyleEnqueue()
    {
        $url = plugin_dir_url(__DIR__) . "src/assets/css/";
        // Enqueue admin CSS
        wp_enqueue_style(
            'oauthSetting', // Handle
            $url . "oauthSetting.css", // URL file CSS
            array(), // Dependencies (nếu có)
            '1.0.0', // Version
            'all' // Media
        );
    }
    public function scriptEnqueue()
    {
        // $path = plugin_dir_url(__DIR__) . "src/assets/js/";
        // // Enqueue JS
        // wp_enqueue_script(
        //     'myScript', // Handle
        //     $path. "myScript.js", // URL file JS
        //     array('jquery'), // Dependencies, ví dụ jquery
        //     '1.0.0', // Version
        //     true // Load ở footer nếu true, header nếu false
        // );
    }
}
