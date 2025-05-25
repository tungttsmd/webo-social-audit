<?php

namespace Controller;

use Action\UserAction;
use Model\Auth\OAuthUser;
use Service\BaseService;

class UserController
{
    use BaseService;

    private $oAuthUser;
    public function __construct()
    {
        $this->oAuthUser = OAuthUser::make();
        $this->sessionStart();
    }
    public function user_login()
    {
        if (!empty($_SESSION['fb_access_token'])) {
            return UserAction::make()->autoLoginAction($_SESSION['fb_access_token']);
        } elseif (isset($_GET['webo_oauth'], $_GET['code']) && $this->oAuthUser->csrfState($_GET['state'])) {
            return UserAction::make()->firstAuthLoginAction($_GET['code']);
        }
        $msg = UserAction::make()->notLoginYetAction();
        return UserAction::make()->loginButtonDraw($msg ?? '');
    }
    public function user_logout()
    {
        return UserAction::make()->logoutAction();
    }
}
