<?php

namespace Public;

use Service\BaseService;
use Controller\UserController;
use Controller\PageController;

class Shortcode
{
    use BaseService;
    public function init()
    {
        add_shortcode(
            SHORTCODE_OAUTH_USER,
            [UserController::make(), 'user_login']
        );
        add_shortcode(
            SHORTCODE_OAUTH_USER_LOGOUT,
            [UserController::make(), 'user_logout']
        );
        add_shortcode(
            SHORTCODE_OAUTH_PAGE,
            [PageController::make(), 'page_login']
        );
        add_shortcode(
            SHORTCODE_OAUTH_PAGE_LOGOUT,
            [PageController::make(), 'page_logout']
        );
    }
}
