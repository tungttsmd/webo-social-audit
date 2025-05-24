<?php

namespace Service;

use GuzzleHttp\Client as GuzzleClient;

class FacebookPageService
{
    use BaseService;
    private $client;

    public function __construct(bool $sslVerify = false)
    {
        $this->client = new GuzzleClient(['verify' => $sslVerify]);
    }

    public function getPages(string $accessToken): ?array
    {
        $response = $this->client->request('GET', 'https://graph.facebook.com/v18.0/me/accounts', [
            'query' => ['access_token' => $accessToken],
        ]);
        return json_decode($response->getBody(), true);
    }

    public function postToPage(string $pageId, string $pageAccessToken, string $message): bool
    {
        $response = $this->client->request('POST', "https://graph.facebook.com/v18.0/{$pageId}/feed", [
            'form_params' => [
                'message' => $message,
                'access_token' => $pageAccessToken,
            ],
        ]);

        $result = json_decode($response->getBody(), true);
        return isset($result['id']);
    }
}
