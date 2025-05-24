<?php

namespace Model;

use League\OAuth2\Client\Provider\Facebook;
use GuzzleHttp\Client as GuzzleClient;
use Service\BaseService;

class FacebookOAuthService
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

    public function getAuthorizationUrl(): string
    {
        $authUrl = $this->provider->getAuthorizationUrl(['scope' => $this->scopes]);
        $_SESSION['oauth2state'] = $this->provider->getState();
        return $authUrl;
    }

    public function validateState(string $state): bool
    {
        if (empty($_SESSION['oauth2state']) || $state !== $_SESSION['oauth2state']) {
            unset($_SESSION['oauth2state']);
            return false;
        }
        return true;
    }

    public function getAccessToken(string $code)
    {
        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', ['code' => $code]);
            return [
                'status' => true,
                'token' => $accessToken->getToken(),
                'msg' => 'Lấy token thành công'
            ];
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            return [
                'status' => false,
                'token' => null,
                'msg' => 'Lỗi khi lấy dữ liệu: ' . esc_html($e->getMessage())
            ];
        }
    }
}
