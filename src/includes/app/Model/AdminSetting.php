<?php

namespace Model;

use League\OAuth2\Client\Provider\Facebook;
use GuzzleHttp\Client as GuzzleClient;
use Helper\BetterLib;
use Service\BaseService;

class AdminSetting
{
    use BaseService;


    private $permissionRequest;

    public function __construct()
    {
        // Khởi động SESSION (dùng check @csrf)
        if (!session_id()) {
            session_start();
        }
    }
    // Nhóm admin Setting
    public function webo_social_audit_settings_page()
    {
        $this->render('settingPage', []);
    }

    // // Nhóm dành cho userProfile
    // public function user_login()
    // {
    //     // Nếu đã có access token trong session -> dùng luôn, gọi callback
    //     if (!empty($_SESSION['fb_access_token'])) {
    //         return UserCallback::make()->callbackAfterLogin($_SESSION['fb_access_token']);
    //     }
    //     // Bước 1.1: Đã đăng nhập -> thực hiện callback
    //     if (isset($_GET['webo_oauth']) && isset($_GET['code'])) {
    //         return $this->user_login_callback();
    //     }
    //     // Bước 1.2: Cấu hình yêu cầu quyền người dùng
    //     $permissionRequest = [
    //         'email',                    // Email người dùng
    //         'public_profile',           // Thông tin công khai: tên, ảnh đại diện, id,...
    //         'user_friends',             // Danh sách bạn bè cũng dùng app này
    //         'user_birthday',            // Ngày sinh
    //         'user_gender',              // Giới tính
    //         'user_location',            // Vị trí hiện tại
    //         'user_hometown',            // Quê quán
    //         'user_link',                // Link trang Facebook cá nhân
    //         'user_photos',              // Ảnh của người dùng
    //         'user_posts',               // Bài viết của người dùng
    //         'user_videos',              // Video của người dùng
    //         'user_likes',               // Trang người dùng đã like
    //         'user_gender',              // Giới tính người dùng
    //         'user_age_range',           // Khoảng tuổi
    //         'user_link',                // Link profile
    //     ];

    //     // Bước 1.3: Provider cấu hình
    //     $providerOptions = [
    //         'clientId'          => get_option('fb_app_id'),
    //         'clientSecret'      => get_option('fb_app_secret'),
    //         'redirectUri'       => site_url('/oauth-user?webo_oauth=1'),
    //         'graphApiVersion'   => 'v18.0',
    //     ];

    //     // Bước 2: Chưa đăng nhập -> Bắt đầu cấu hình liên kết app FB: App ID, App secrect, Web redirect 
    //     $provider = new Facebook($providerOptions);

    //     // Bước 3: Tắt SSL của GuzzleClient (Thử nghiệm, pass lỗi)
    //     $provider->setHttpClient(new GuzzleClient([
    //         'verify' => false,
    //     ]));

    //     // Bước 4: Cấu hình: yêu cầu quyền từ người dùng (scope yêu cầu quyền)
    //     $authUrl = $provider->getAuthorizationUrl(['scope' => $permissionRequest]);

    //     // Bước 5: Lưu state vào session để sau kiểm tra (bước chống @csrf)
    //     $_SESSION['oauth2state'] = $provider->getState();

    //     // Bước 6: Đưa nút đăng  nhập facebook cho người dùng
    //     ob_start();
    //     $this->render('facebookLoginButton', ['authUrl' => $authUrl]);
    //     return ob_get_clean();
    // }
    // public function user_login_callback()
    // {
    //     $token = BetterLib::oopstd($this->user_token());
    //     if ($token->status) {
    //         // Lưu access token vào session để dùng lần sau
    //         $_SESSION['fb_access_token'] = $token->token;

    //         return UserCallback::make()->callbackAfterLogin($token->token);
    //         // Redirect về URL sạch để tránh reload bị lỗi 'code đã dùng'
    //         wp_redirect(site_url('/oauth-user'));
    //         exit;
    //     } else {
    //         return $token->msg;
    //     }
    // }
    // public function user_token()
    // {
    //     $msg = '';
    //     $flag = true;

    //     // Bước 0: Provider cấu hình
    //     $providerOptions = [
    //         'clientId'          => get_option('fb_app_id'),
    //         'clientSecret'      => get_option('fb_app_secret'),
    //         'redirectUri'       => site_url('/oauth-user?webo_oauth=1'),
    //         'graphApiVersion'   => 'v18.0',
    //     ];

    //     // Bước 1: Cấu hình: App ID, App secrect, Web redirect 
    //     $provider = new Facebook($providerOptions);

    //     // Bước 2: Tắt SSL của GuzzleClient (Thử nghiệm, pass lỗi)
    //     $provider->setHttpClient(new GuzzleClient([
    //         'verify' => false,
    //     ]));

    //     // Bước 3: Kiểm tra trạng thái state chống CSRF
    //     if ($flag && (empty($_GET['state']) || ($_GET['state'] !== ($_SESSION['oauth2state'] ?? '')))) {
    //         unset($_SESSION['oauth2state']);
    //         $msg = 'Trạng thái xác thực không hợp lệ.';
    //         $flag = false;
    //     }

    //     // Bước 4: Kiểm tra đường link có $_GET['code'] hợp lệ hay không
    //     if ($flag && !isset($_GET['code'])) {
    //         $msg = 'Không có mã xác thực.';
    //         $flag = false;
    //     }

    //     // Bước 5: xử lí token
    //     try {
    //         if ($flag) {
    //             // Bước 5.1: Lấy access token
    //             $accessToken = $provider->getAccessToken('authorization_code', [
    //                 'code' => $_GET['code']
    //             ]);
    //             return [
    //                 'status' => true,
    //                 'token' => $accessToken->getToken(),
    //                 'msg' => 'Lấy token thành công'
    //             ];
    //         } else {
    //             return [
    //                 'status' => false,
    //                 'token' => null,
    //                 'msg' => $msg
    //             ];
    //         }
    //     } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    //         return [
    //             'status' => false,
    //             'token' => null,
    //             'msg' => 'Lỗi khi lấy dữ liệu: ' . esc_html($e->getMessage())
    //         ];
    //     }
    // }



    // // Nhóm CRUB PAGE
    // public function page_login()
    // {
    //     // Nếu đã có token lưu trong session, bỏ qua login
    //     if (!empty($_SESSION['fb_access_token'])) {
    //         return $this->page_login_callback_with_token($_SESSION['fb_access_token']);
    //     }

    //     // Nếu là callback sau khi login thành công
    //     if (isset($_GET['webo_oauth']) && isset($_GET['code'])) {
    //         return $this->page_login_callback(); // sẽ xử lý và lưu token luôn
    //     }

    //     // Cấu hình quyền
    //     $permissionRequest = [
    //         'email',
    //         'public_profile',
    //         'pages_show_list',
    //         'pages_read_engagement',
    //         'pages_manage_posts',
    //     ];

    //     // Cấu hình provider
    //     $providerOptions = [
    //         'clientId'        => get_option('fb_app_id'),
    //         'clientSecret'    => get_option('fb_app_secret'),
    //         'redirectUri'     => site_url('/oauth-page?webo_oauth=1'),
    //         'graphApiVersion' => 'v18.0',
    //     ];

    //     $provider = new Facebook($providerOptions);
    //     $provider->setHttpClient(new GuzzleClient(['verify' => false]));

    //     $authUrl = $provider->getAuthorizationUrl(['scope' => $permissionRequest]);

    //     $_SESSION['oauth2state'] = $provider->getState();

    //     ob_start();
    //     $this->render('facebookLoginButton', ['authUrl' => $authUrl]);
    //     return ob_get_clean();
    // }
    // public function page_login_callback()
    // {
    //     $token = BetterLib::oopstd($this->page_token());
    //     if ($token->status) {
    //         $_SESSION['fb_access_token'] = $token->token; // <-- LƯU VÀO SESSION
    //         return $this->page_login_callback_with_token($token->token);
    //         // Redirect về URL sạch để tránh reload bị lỗi 'code đã dùng'
    //         wp_redirect(site_url('/oauth-page'));
    //         exit;
    //     } else {
    //         return $token->msg;
    //     }
    // }
    // public function page_token()
    // {
    //     $msg = '';
    //     $flag = true;

    //     // Bước 0: Provider cấu hình
    //     $providerOptions = [
    //         'clientId'          => get_option('fb_app_id'),
    //         'clientSecret'      => get_option('fb_app_secret'),
    //         'redirectUri'       => site_url('/oauth-page?webo_oauth=1'),
    //         'graphApiVersion'   => 'v18.0',
    //     ];

    //     // Bước 1: Cấu hình: App ID, App secrect, Web redirect 
    //     $provider = new Facebook($providerOptions);

    //     // Bước 2: Tắt SSL của GuzzleClient (Thử nghiệm, pass lỗi)
    //     $provider->setHttpClient(new GuzzleClient([
    //         'verify' => false,
    //     ]));

    //     // Bước 3: Kiểm tra trạng thái state chống CSRF
    //     if ($flag && (empty($_GET['state']) || ($_GET['state'] !== ($_SESSION['oauth2state'] ?? '')))) {
    //         unset($_SESSION['oauth2state']);
    //         $msg = 'Trạng thái xác thực không hợp lệ.';
    //         $flag = false;
    //     }

    //     // Bước 4: Kiểm tra đường link có $_GET['code'] hợp lệ hay không
    //     if ($flag && !isset($_GET['code'])) {
    //         $msg = 'Không có mã xác thực.';
    //         $flag = false;
    //     }

    //     // Bước 5: xử lí token
    //     try {
    //         if ($flag) {
    //             // Bước 5.1: Lấy access token
    //             $accessToken = $provider->getAccessToken('authorization_code', [
    //                 'code' => $_GET['code']
    //             ]);
    //             return [
    //                 'status' => true,
    //                 'token' => $accessToken->getToken(),
    //                 'msg' => 'Lấy token thành công'
    //             ];
    //         } else {
    //             return [
    //                 'status' => false,
    //                 'token' => null,
    //                 'msg' => $msg
    //             ];
    //         }
    //     } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    //         return [
    //             'status' => false,
    //             'token' => null,
    //             'msg' => 'Lỗi khi lấy dữ liệu: ' . esc_html($e->getMessage())
    //         ];
    //     }
    // }
    // public function page_login_callback_with_token($accessToken)
    // {
    //     $client = new GuzzleClient(['verify' => false]);

    //     $response = $client->request('GET', 'https://graph.facebook.com/v18.0/me/accounts', [
    //         'query' => [
    //             'access_token' => $accessToken,
    //         ],
    //     ]);

    //     $pages = json_decode($response->getBody(), true);

    //     foreach ($pages['data'] as $page) {
    //         echo 'Page name: ' . $page['name'] . PHP_EOL;
    //         echo 'Page ID: ' . $page['id'] . PHP_EOL;
    //         echo 'Page Access Token: ' . $page['access_token'] . PHP_EOL;
    //     }

    //     $pageAccessToken = $page['access_token'];
    //     $pageId = $page['id'];
    //     $response = $client->request('POST', "https://graph.facebook.com/v18.0/{$pageId}/feed", [
    //         'form_params' => [
    //             'message' => 'Nội dung bài đăng trên Page',
    //             'access_token' => $pageAccessToken,
    //         ],
    //     ]);

    //     $result = json_decode($response->getBody(), true);

    //     if (isset($result['id'])) {
    //         echo "Đăng bài thành công, ID bài: " . $result['id'];
    //     } else {
    //         echo "Đăng bài thất bại";
    //     }
    // }
}
