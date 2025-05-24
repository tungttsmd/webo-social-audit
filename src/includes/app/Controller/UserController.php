<?php

namespace Controller;

use Helper\BetterLib;
use Model\UserCallback;
use Service\BaseService;
use Model\FacebookOAuthService;

class UserController
{
    use BaseService;
    public function user_login()
    {
        if (!session_id()) {
            session_start();
        }

        if (!empty($_SESSION['fb_access_token'])) {
            return UserCallback::make()->callbackAfterLogin($_SESSION['fb_access_token']);
        }

        if (isset($_GET['webo_oauth']) && isset($_GET['code'])) {
            return $this->user_login_callback();
        }

        $scopes = [
            'email',
            'public_profile',
            'user_friends',
            'user_birthday',
            'user_gender',
            'user_location',
            'user_hometown',
            'user_link',
            'user_photos',
            'user_posts',
            'user_videos',
            'user_likes',
            'user_age_range',
        ];

        $oauthService = new FacebookOAuthService(site_url('/oauth-user?webo_oauth=1'), $scopes);
        $authUrl = $oauthService->getAuthorizationUrl();

        ob_start();
        $this->render('facebookLoginButton', ['authUrl' => $authUrl]);
        return ob_get_clean();
    }

    public function user_login_callback()
    {
        $oauthService = new FacebookOAuthService(site_url('/oauth-user?webo_oauth=1'), []);

        if (!isset($_GET['state']) || !$oauthService->validateState($_GET['state'])) {
            return 'Trạng thái xác thực không hợp lệ.';
        }

        if (!isset($_GET['code'])) {
            return 'Không có mã xác thực.';
        }

        $tokenResult = $oauthService->getAccessToken($_GET['code']);
        if ($tokenResult['status']) {
            $_SESSION['fb_access_token'] = $tokenResult['token'];
            return UserCallback::make()->callbackAfterLogin($tokenResult['token']);
        }
        return $tokenResult['msg'];
    }

    // Tương tự page login, gọi các service tương ứng.
   
}
