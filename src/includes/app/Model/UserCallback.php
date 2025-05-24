<?php

namespace Model;

use League\OAuth2\Client\Provider\Facebook;
use Service\BaseService;
use GuzzleHttp\Client as GuzzleClient;

class UserCallback
{
    use BaseService;

    public function callbackAfterLogin($token)
    {
        return $this->userProfileTable($token);
    }
    public function userProfileTable($token)
    {
        ob_start();
        $data = User::make()->readProfile($token);
        $this->render('userProfileTable', ['data' => $data]);
        return ob_get_clean();
    }
}
