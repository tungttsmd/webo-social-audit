<?php

namespace Model;

use League\OAuth2\Client\Provider\Facebook;
use GuzzleHttp\Client as GuzzleClient;

class PageLogin
{
    private Facebook $provider;
    private string $redirectUri;

    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        $this->redirectUri = site_url('/oauth-page?webo_oauth=1');
        $this->provider = new Facebook([
            'clientId'          => get_option('fb_app_id'),
            'clientSecret'      => get_option('fb_app_secret'),
            'redirectUri'       => $this->redirectUri,
            'graphApiVersion'   => 'v18.0',
        ]);
        $this->provider->setHttpClient(new GuzzleClient(['verify' => false]));
    }

    public function getAuthorizationUrl(): string
    {
        $permissions = [
            'email',
            'public_profile',
            'pages_show_list',
            'pages_read_engagement',
            'pages_manage_posts',
        ];

        $authUrl = $this->provider->getAuthorizationUrl(['scope' => $permissions]);
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
        return $this->provider->getAccessToken('authorization_code', ['code' => $code]);
    }
}
