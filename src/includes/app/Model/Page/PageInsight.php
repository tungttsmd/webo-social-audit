<?php

namespace Model\Page;

use Model\Page\Page;

class PageInsight extends Page
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getInsights($ID, $accessToken, array $metrics = [], $period = 'day', $since = null, $until = null)
    {
        // === Lưu ý sử dụng getInsights ===
        // Nếu ID là của page: trả về metrics của page.
        // Nếu ID là post thường: trả về metrics của post.
        // Nếu ID là post chứa video: trả về metrics chuyên biệt video.
        // Nếu ID sai hoặc metric không phù hợp với ID: Facebook trả về lỗi như "Unsupported get request".

        // Tạo Query
        $query = [
            'access_token' => $accessToken,
            'period' => $period         //day, week, days_28 các option
        ];

        // Kiểm tra tham số vào hàm (thao tác phụ)
        if (!empty($metrics)) {
            $query['metric'] = implode(',', $metrics);
        }
        if ($since) {
            $query['since'] = $since;   //timestamp hoặc định dạng Y-m-d
        }
        if ($until) {
            $query['until'] = $until;   //timestamp hoặc định dạng Y-m-d
        }

        // Thực hiện Guzzlehttp Client gọi API 
        $response = $this->client->get("https://graph.facebook.com/v19.0/{$ID}/insights", [
            'query' => $query
        ]);

        // Trả dữ liệu mảng
        return json_decode($response->getBody(), true);
    }

    // Danh sách các metric khả dụng
    public function metricTutorial()
    {
        // === Facebook Page Insights Metrics (Graph API v19.0) ===

        // --- Tham số API ---
        // period: Các khoảng thời gian bạn muốn lấy dữ liệu, ví dụ:
        //   - day: dữ liệu theo ngày
        //   - week: dữ liệu theo tuần
        //   - days_28: dữ liệu 28 ngày gần nhất
        //   - lifetime: dữ liệu tổng cộng từ lúc tạo page/post
        // since và until: Bạn có thể lấy dữ liệu trong khoảng thời gian cụ thể
        //   - truyền timestamp hoặc định dạng Y-m-d

        // Bộ Metric đã xác mình hoạt động

        // Tổng toàn bộ post reaction trên trang
        $pageReactionTotal = [
            'page_actions_post_reactions_like_total',
            'page_actions_post_reactions_love_total',
            'page_actions_post_reactions_wow_total',
            'page_actions_post_reactions_haha_total',
            'page_actions_post_reactions_sorry_total',
            'page_actions_post_reactions_anger_total',
            'page_actions_post_reactions_total'
        ];

        // Tổng reaction của post cụ thể
        $postReactionTotal = [
            'post_reactions_like_total',
            'post_reactions_love_total',
            'post_reactions_wow_total',
            'post_reactions_haha_total',
            'post_reactions_sorry_total',
            'post_reactions_anger_total',
            'post_reactions_by_type_total'
        ];

        // Nhân khẩu học của người followers
        $pageFanInsight = [
            'page_fans',
            'page_fan_adds',
            'page_fan_adds_unique',
            'page_fan_removes',
            'page_fan_removes_unique',
            'page_fans_country',
            'page_fans_city',
            'page_fans_locale'
        ];

        // 1 lần bài viết xuất hiện trước người dùng = 1 impression 
        $postImpression =  [
            'post_impressions',
            'post_impressions_unique',
            'post_impressions_paid',
            'post_impressions_paid_unique',
            'post_impressions_fan',
            'post_impressions_fan_unique',
            'post_impressions_organic',
            'post_impressions_organic_unique',
            'post_impressions_viral',
            'post_impressions_viral_unique',
            'post_impressions_nonviral',
            'post_impressions_nonviral_unique',
        ];

        // 1 tương tác với bài viết = 1 engagement
        $postEngagement = [
            'post_clicks',
            'post_clicks_by_type',
        ];

        // Sự xuất hiện của các bài post của page
        $pagePostImpression = [
            'page_posts_impressions',                     // Tổng số lần bài đăng của Trang vào màn hình người dùng
            'page_posts_impressions_unique',              // Số người duy nhất thấy bài đăng
            'page_posts_impressions_paid',                // Lượt hiển thị trả phí (ads)
            'page_posts_impressions_paid_unique',         // Người duy nhất thấy bài đăng qua quảng cáo
            'page_posts_impressions_organic_unique',      // Người duy nhất thấy bài đăng qua phân phối tự nhiên
            'page_posts_served_impressions_organic_unique', // Số người được phục vụ bài viết (dù có hiển thị hay không)
            'page_posts_impressions_viral',               // Lượt hiển thị có kèm thông tin xã hội (bạn bè tương tác)
            'page_posts_impressions_viral_unique',        // Người duy nhất thấy bài đăng có kèm thông tin xã hội
            'page_posts_impressions_nonviral',            // Lượt hiển thị không kèm thông tin xã hội
            'page_posts_impressions_nonviral_unique',     // Người duy nhất thấy bài viết không kèm thông tin xã hội
        ];

        // Sự xuất hiện của page
        $pageImpression = [
            'page_impressions',
            'page_impressions_unique',
            'page_impressions_paid',
            'page_impressions_paid_unique',
            'page_impressions_viral',
            'page_impressions_viral_unique',
            'page_impressions_nonviral',
            'page_impressions_nonviral_unique',
        ];

        // Lượt follow, tương tác
        $pageInteraction = [
            'page_post_engagements',
            'page_fan_adds_by_paid_non_paid_unique',
            'page_lifetime_engaged_followers_unique',
            'page_daily_follows',
            'page_daily_follows_unique',
            'page_daily_unfollows_unique',
            'page_follows',
        ];

        // 1 click vào CTA hoặc nút action hoặc nút thông tin liên hệ = 1 action
        $pageCtaClick = [
            'page_total_actions',
        ];

        // đo lường video
        $pageVideoMetrics = [
            'page_video_views',
            'page_video_views_by_uploaded_hosted',
            'page_video_views_paid',
            'page_video_views_organic',
            'page_video_views_by_paid_non_paid',
            'page_video_views_autoplayed',
            'page_video_views_click_to_play',
            'page_video_views_unique',
            'page_video_repeat_views',
            'page_video_complete_views_30s',
            'page_video_complete_views_30s_paid',
            'page_video_complete_views_30s_organic',
            'page_video_complete_views_30s_autoplayed',
            'page_video_complete_views_30s_click_to_play',
            'page_video_complete_views_30s_unique',
            'page_video_complete_views_30s_repeat_views',
            'post_video_complete_views_30s_autoplayed',
            'post_video_complete_views_30s_clicked_to_play',
            'post_video_complete_views_30s_organic',
            'post_video_complete_views_30s_paid',
            'post_video_complete_views_30s_unique',
            'page_video_view_time',
        ];

        // Tổng lượt đã xem trang bởi người đăng nhập và đăng xuất
        $pageViewTotal = [
            'page_views_total'
        ];

        // Đo lường thông số post có video
        $postVideoMetrics = [
            'post_video_avg_time_watched',
            'post_video_complete_views_organic',
            'post_video_complete_views_organic_unique',
            'post_video_complete_views_paid',
            'post_video_complete_views_paid_unique',
            'post_video_retention_graph',
            'post_video_retention_graph_clicked_to_play',
            'post_video_retention_graph_autoplayed',
            'post_video_views_organic',
            'post_video_views_organic_unique',
            'post_video_views_paid',
            'post_video_views_paid_unique',
            'post_video_length',
            'post_video_views',
            'post_video_views_unique',
            'post_video_views_autoplayed',
            'post_video_views_clicked_to_play',
            'post_video_views_15s',
            'post_video_views_60s_excludes_shorter',
            'post_video_views_sound_on',
            'post_video_view_time',
            'post_video_view_time_organic',
            'post_video_view_time_by_age_bucket_and_gender',
            'post_video_view_time_by_region_id',
            'post_video_views_by_distribution_type',
            'post_video_view_time_by_distribution_type',
            'post_video_view_time_by_country_id',
            'post_video_views_live',
            'post_video_social_actions_count_unique',
        ];

        // Số lượng người like, share, comment... (theo facebook gọi là tạo story)
        $postActivities = [
            'post_activity_by_action_type',
            'post_activity_by_action_type_unique',
        ];
    }
}
