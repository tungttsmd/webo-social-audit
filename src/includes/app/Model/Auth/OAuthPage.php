<?php

namespace Model\Auth;

class OAuthPage extends OAuth
{
    public function __construct()
    {
        $redirectUrl = site_url('/' . OAUTH_PAGE . '?webo_oauth=1');
        $scopes = [
            'public_profile',              // Thông tin user quản lý Page
            'email',                       // Thông tin email quản lý page
            'pages_show_list',             // Danh sách page user quản lý (không cần thiết)
            'pages_manage_metadata',       // U Cập nhật cài đặt của page
            'pages_manage_posts',          // C, U, D các bài viết của Page
            'pages_manage_engagement',     // C, U, D Quản lý comment điều khiển được cách Page phản hồi lại cái người ta viết
            'pages_read_engagement',       // R Đọc các bài viết của Page
            'pages_read_user_content',     // R, D  Đọc/xoá được cái người ta viết (comment, review, vistor post)
            'read_insights',               // R (thống kê)
        ];
        parent::__construct($redirectUrl, $scopes, false);
    }
}
