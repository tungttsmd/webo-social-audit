<?php

namespace Model\Page;

use Model\Page\Page;

class PageSetting extends Page
{
    public function __construct()
    {
        parent::__construct();
    }
    public function viewSetting($page_id, $accessToken)
    {
        $response = $this->client->get("https://graph.facebook.com/v19.0/{$page_id}", [
            'query' => [
                'access_token' => $accessToken,
                'fields' => implode(', ', $this->settingFields())
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    public function updateSetting($page_id, $accessToken)
    {
        $response = $this->client->post("https://graph.facebook.com/v19.0/{$page_id}", [
            'form_params' => [
                'access_token' => $accessToken,
            ]
        ]);
        return json_decode($response->getBody(), true); // Trả success => true
    }

    // Danh sách các setting fields khả dụng
    public function settingFields()
    {
        /* Những trường cho phép sửa đổi */
        $settingUpdateField = [
            'about',                    // Thay đổi mục Giới thiệu
            'description',              // Thay đổi mục Chi tiết page
            'website',                  // Thay đổi mục website của page (nếu chưa có thì thêm vào)
            'phone',                    // Thay đổi mục số điện thoại của page (định dạng có mã vùng +84....)
            'emails',                   // Thay đổi mục email (lưu ý, phải gửi dưới dạng mảng  'emails' => ['tungttcute@gmail.com'])
            'location',                 // Thay đổi mục Địa chỉ có định dạng json 'location' => {"street":"123 ABC Street","city":"Hanoi","country":"Vietnam","zip":"100000"}
            'hours',                    // Thay đổi mục giờ đóng/mở cửa định dạng json 'hours' => {"mon_1_open":"09:00","mon_1_close":"17:00"}
            'is_permanently_closed',    // Thay đổi trạng thái đóng vĩnh viễn true/false
            'temporary_status',         // Thay đổi trạng thái hoạt động tạm thời 'tempory_status' => OPERATING_AS_USUAL/TEMPORARY_CLOSED/DIFFERENTLY_OPEN
            'general_info',             // Thay đổi Thông tin tổng quan (Chỉ hiển thị khi là Thông tin doanh nghiệp)
            'company_overview',         // Thay đổi Tổng quan công ty (Chỉ hiển thị khi là Thông tin doanh nghiệp)
            'bio',                      // Thay đổi mục tiểu sử (Chỉ hiển thị khi là Thông tin doanh nghiệp)
            'mission',                  // Thay đổi mục Sứ mệnh (Chỉ hiển thị khi là Thông tin doanh nghiệp)
            'cover', // Sử dụng phối hợp offset_x, offset_y, focus_x, focus_y, no_feed_story, no_notification để thay đổi ảnh
        ];
        return $settingUpdateField;
    }
}
