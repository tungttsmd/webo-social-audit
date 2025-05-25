<?php

namespace Model\User;

use GuzzleHttp\Client as GuzzleClient;
use Service\BaseService;

class User
{
    use BaseService;

    private $client;
    private $scopes;
    public function __construct()
    {
        $this->scopes = implode(',', [
            'id',                   // Yêu cầu GrapQL trả id fb user
            'name',                 // Yêu cầu GrapQL trả Họ tên
            'email',                // Yêu cầu GrapQL trả email
            'gender',               // Yêu cầu GrapQL trả giới tính
            'birthday',             // Yêu cầu GrapQL trả ngày tháng năm sinh
            'location',             // Yêu cầu GrapQL trả thông tin cư trú
            'hometown',             // Yêu cầu GrapQL trả thông tin quê quán
            'link',                 // Yêu cầu GrapQL trả link user profile
            'age_range',            // Yêu cầu GrapQL trả data tuổi
            'friends',              // Yêu cầu GrapQL trả data friends
            'picture.type(large)',  // Yêu cầu GrapQL trả src avatar
        ]);
        $this->client = new GuzzleClient(['verify' => false]);
    }
    public function getUserProfile($token)
    {
        try {
            $query = $this->client->get('https://graph.facebook.com/me', [
                'query' => [
                    'fields' =>  $this->scopes,
                    'access_token' => $token
                ]
            ]);

            $response = json_decode($query->getBody()->getContents(), true);

            return [
                'Facebook ID: ' => $response['id'] ?? "Không tìm thấy",
                'Họ tên: ' => $response['name'] ?? "Không tìm thấy",
                'Email: ' => $response['email'] ?? "Không tìm thấy",
                'Giới tính: ' => $response['gender'] ?? "Không tìm thấy",
                'Ngày sinh: ' => $response['birthday'] ?? "Không tìm thấy",
                'Nơi ở: ' => $response['location']['name'] ?? "Không tìm thấy",
                'Quê quán: ' => $response['hometown']['name'] ?? "Không tìm thấy",
                'Link trang cá nhân: ' => $response['link'] ?? "Không tìm thấy",
                'Khoảng tuổi (trên)' => $response['age_range']['min'] ?? "Không tìm thấy",
                'Tổng số bạn bè' => $response['friends']['summary']['total_count'] ?? "Không tìm thấy",
                'Ảnh đại diện' => '<img src="' . $response['picture']['data']['url'] . '"/>' ?? "Không tìm thấy",
                'Mã Token' => $token,
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            return ['error' => 'Lỗi Facebook API: ' . $responseBody];
        }
    }
    public function fetchId($userToken)
    {
        $query = $this->client->get('https://graph.facebook.com/me', [
            'query' => [
                'fields' =>  $this->scopes,
                'access_token' => $userToken
            ]
        ]);
        return json_decode($query->getBody()->getContents(), true);
    }
    public function getPageManageList($userToken)
    {
        $response = $this->client->get("https://graph.facebook.com/v19.0/me/accounts", [
            'query' => [
                'access_token' => $userToken
            ]
        ]);
        return json_decode($response->getBody(), true);
    }
}
