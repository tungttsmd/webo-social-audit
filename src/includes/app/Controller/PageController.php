<?php
namespace Controller;

use Model\PageLogin;
use Service\BaseService;
use Service\PageLoginService;

class PageController
{
    use BaseService;
    private PageLogin $model;
    private PageLoginService $service;

    public function __construct()
    {
        $this->model = new PageLogin();
        $this->service = new PageLoginService();

        if (!session_id()) {
            session_start();
        }
    }

    public function page_login()
    {
        if (!empty($_SESSION['fb_access_token'])) {
            return $this->page_login_callback_with_token($_SESSION['fb_access_token']);
        }

        if (isset($_GET['webo_oauth']) && isset($_GET['code'])) {
            return $this->page_login_callback();
        }

        $authUrl = $this->model->getAuthorizationUrl();

        ob_start();
        // giả sử bạn có render method hoặc template ở đây
        echo '<a href="' . htmlspecialchars($authUrl) . '">Login with Facebook Page</a>';
        return ob_get_clean();
    }

    public function page_login_callback()
    {
        if (!$this->model->validateState($_GET['state'] ?? '')) {
            return 'Trạng thái xác thực không hợp lệ.';
        }

        try {
            $accessTokenObj = $this->model->getAccessToken($_GET['code']);
            $_SESSION['fb_access_token'] = $accessTokenObj->getToken();
            return $this->page_login_callback_with_token($accessTokenObj->getToken());
        } catch (\Exception $e) {
            return 'Lỗi khi lấy token: ' . $e->getMessage();
        }
    }

    public function page_login_callback_with_token($accessToken)
    {
        $pagesData = $this->service->fetchPages($accessToken);

        if (empty($pagesData['data'])) {
            return 'Không có trang nào hoặc token không hợp lệ.';
        }

        foreach ($pagesData['data'] as $page) {
            echo 'Page name: ' . htmlspecialchars($page['name']) . '<br>';
            echo 'Page ID: ' . htmlspecialchars($page['id']) . '<br>';
            echo 'Page Access Token: ' . htmlspecialchars($page['access_token']) . '<br><br>';

            // Ví dụ đăng bài
            $result = $this->service->postToPage($page['id'], $page['access_token'], 'Nội dung bài đăng trên Page');
            if (isset($result['id'])) {
                echo "Đăng bài thành công, ID bài: " . htmlspecialchars($result['id']) . "<br>";
            } else {
                echo "Đăng bài thất bại<br>";
            }
        }
    }
}
