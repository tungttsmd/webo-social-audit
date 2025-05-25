<?php

namespace Model\Admin;

use Service\BaseService;

class AdminSetting
{
    use BaseService;
    private $permissionRequest;

    public function __construct()
    {
       $this::sessionStart();
    }
    public function webo_social_audit_settings_page()
    {
        $this->render('settingPage', []);
    }
}
