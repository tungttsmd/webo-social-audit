<?php

namespace Model\Page;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Service\BaseService;

class Page
{
    use BaseService;
    protected $client;

    public function __construct()
    {
        // Tắt xác minh SSL để chạy DEV
        $this->client = new GuzzleClient([
            'verify' => false, // ⚠️ Tắt xác minh SSL chỉ dùng trong môi trường DEV
        ]);
    }
    // Lấy thông tin chi tiết trang Facebook theo pageId
    // Trả về các thông tin như tên, mô tả, số lượng fan, địa chỉ email, điện thoại, website, ảnh đại diện, trạng thái xác thực...
    public function getPageProfile($pageId, $accessToken)
    {
        $response = $this->client->get("https://graph.facebook.com/v19.0/{$pageId}", [
            'query' => [
                'fields' => implode(', ', [
                    'id',
                    'name',
                    'about',
                    'fan_count',
                    'emails',
                    'location',
                    'link',
                    'phone',
                    'rating_count',
                    'overall_star_rating',
                    'website',
                    'category',
                    'cover',
                    'description',
                    'picture',
                    'verification_status',
                    'whatsapp_number'
                ]),
                'access_token' => $accessToken
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
    public function fetchId(string $userToken): array
    {
        $response = $this->client->request('GET', 'https://graph.facebook.com/v18.0/me/accounts', [
            'query' => [
                'access_token' => $userToken,
            ],
        ]);
        return json_decode($response->getBody(), true);
    }
    public function getUserRoles($pageId, $accessToken)
    {
        $response = $this->client->get("https://graph.facebook.com/v19.0/{$pageId}/roles", [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
    public function ddToken($accessToken)
    {
        return $accessToken;
    }
    public function getPageId($userToken, int $index = 0)
    {
        try {
            $response = Page::make()->fetchId($userToken);
            /* $index là số thứ tự page mà user tạo ra userToken quản lý */
            $pageAccessToken = $response['data'][$index]['access_token'];
            $pageId = $response['data'][$index]['id'];
            return $this::oopstd([
                'page_access_token' => $pageAccessToken,
                'page_id' => $pageId
            ]);
        } catch (Exception $e) {
            return "Lỗi khi lấy page_id: " . $e->getMessage();
        }
    }
}
