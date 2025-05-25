<?php

namespace Public;

use Model\Admin\AdminSetting;
use Service\BaseService;
use Controller\PageController;

class Plugin
{
    use BaseService;
    public function init()
    {
        // ====== 1. KÉO ADMIN SETTING ======
        Setting::make()->init();

        // ====== 2. KÉO SHORTCODE ======
        Shortcode::make()->init();

        // ====== 3. KÉO ENQUEUE SCRIPT ======
        Enqueue::make()->init();
    }
}
