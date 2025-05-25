<?php

namespace Model\Auth;

use League\OAuth2\Client\Provider\Facebook;
use GuzzleHttp\Client as GuzzleClient;
use Service\BaseService;

class OAuth
{
    use BaseService;
    private $provider;
    private $scopes;

    public function __construct(string $redirectUri, array $scopes, bool $sslVerify = false)
    {
        $providerOptions = [
            'clientId'          => get_option('fb_app_id'),
            'clientSecret'      => get_option('fb_app_secret'),
            'redirectUri'       => $redirectUri,
            'graphApiVersion'   => 'v18.0',
        ];

        $this->provider = new Facebook($providerOptions);
        $this->provider->setHttpClient(new GuzzleClient(['verify' => $sslVerify]));
        $this->scopes = $scopes;
    }

    public function getAuthUrl(): string
    {
        /* getAuthorizationUrl có gửi state (cách thức chống csrf cho facebook authorization, fb cũng trả lại state bạn cung cấp) */
        $authUrl = $this->provider->getAuthorizationUrl(['scope' => $this->scopes]);
        $_SESSION['oauth2state'] = $this->provider->getState(); // Lấy state (chống csrf mà getAuthorizationUrl tự tạo)
        return $authUrl;
    }
    public function csrfState(string $state): bool
    {
        if (empty($_SESSION['oauth2state']) || $state !== $_SESSION['oauth2state']) {
            unset($_SESSION['oauth2state']);
            return false;
        }
        return true;
    }
    public function getAuthToken(string $code)
    {
        return $this->provider->getAccessToken('authorization_code', ['code' => $code]);
    }
}
