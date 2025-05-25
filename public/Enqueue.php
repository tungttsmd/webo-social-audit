<?php

namespace Public;

use Service\BaseService;

class Enqueue
{
    use BaseService;
    public function init()
    {
        // Hook khai báo tải assets
        add_action('wp_enqueue_scripts', [$this, 'styleEnqueue']);
        add_action('wp_enqueue_scripts', [$this, 'scriptEnqueue']);
        add_action('admin_enqueue_scripts', [$this, 'adminStyleEnqueue']);
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
