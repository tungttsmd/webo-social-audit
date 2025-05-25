<?php

namespace Action;

use Model\Auth\OAuthUser;
use Model\Page\Page;
use Model\User\User;
use Service\BaseService;

class UserAction
{
    use BaseService;
    private $oAuthUser;
    private $userFetch;
    public function __construct()
    {
        $this->oAuthUser = OAuthUser::make();
        $this->userFetch = User::make();
    }
    public function logoutAction()
    {
        if (isset($_SESSION['fb_access_token'])) {
            unset($_SESSION['fb_access_token']);
            return "Bạn đã đăng xuất thành công !";
        } else {
            return "Trang đăng xuất (bạn chưa đăng nhập)";
        }
    }
    public function loginButtonDraw(string $alert = '')
    {
        ob_start();
        echo $alert ? "<b>$alert</b><br>" : '';
        $this->render('facebookLoginButton', ['authUrl' => $this->oAuthUser->getAuthUrl()]);
        return ob_get_clean();
    }
    public function autoLoginAction($userToken)
    {
        $response = User::make()->fetchId($userToken);

        if (empty($response)) {
            $msg = 'Lỗi App Facebook: Không tìm thấy trang, App ID, App Secret, App Facebook sai đối tượng, sai quyền hoặc token không hợp lệ.';
            return $this->loginButtonDraw($msg);
        }
        $data = [
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
            'Mã Token' => $userToken,
        ];
        $data = User::make()->getUserProfile($userToken);

        /* Test Chức năng ở đây */
        ob_start();
        $this->render('profileTable', ['data' => $data]);
        return ob_get_clean();
    }
    public function convertUrlCodeIntoTokenAction(string $codeGetQueryString)
    {
        $getCode = $this->oAuthUser->getAuthToken($codeGetQueryString);
        $token = $getCode->getToken();
        $_SESSION['fb_access_token'] = $token;
        return $token;
    }
    public function notLoginYetAction()
    {
        if (isset($_GET['webo_oauth'], $_GET['code'])) {
            return 'Lỗi xác thực @csrf: phiên xác thực không hợp lệ!';
        } else {
            return 'Đăng nhập: thực hiện đăng nhập để lấy token';
        }
    }
    public function firstAuthLoginAction(string $codeGetQueryString)
    {
        try {
            $token = UserAction::make()->convertUrlCodeIntoTokenAction($codeGetQueryString);
            return UserAction::make()->autoLoginAction($token);
        } catch (\Exception $e) {
            return UserAction::make()->loginButtonDraw('Lỗi khi lấy token: ' . $e->getMessage());
        }
    }
}
