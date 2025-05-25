<?php

namespace Public;

use Model\Admin\AdminSetting;
use Service\BaseService;

class Setting
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
    }
}
