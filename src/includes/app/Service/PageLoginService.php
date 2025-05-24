<?php

namespace Service;

use GuzzleHttp\Client as GuzzleClient;

class PageLoginService
{
    private GuzzleClient $client;

    public function __construct()
    {
        $this->client = new GuzzleClient(['verify' => false]);
    }

    public function fetchPages(string $userAccessToken): array
    {
        $response = $this->client->request('GET', 'https://graph.facebook.com/v18.0/me/accounts', [
            'query' => [
                'access_token' => $userAccessToken,
            ],
        ]);
        return json_decode($response->getBody(), true);
    }

    public function postToPage(string $pageId, string $pageAccessToken, string $message): array
    {
        $response = $this->client->request('POST', "https://graph.facebook.com/v18.0/{$pageId}/feed", [
            'form_params' => [
                'message' => $message,
                'access_token' => $pageAccessToken,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
