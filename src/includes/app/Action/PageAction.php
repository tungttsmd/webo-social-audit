<?php

namespace Action;

use Model\Auth\OAuthPage;
use Model\Page\Page;
use Service\BaseService;

class PageAction
{
    use BaseService;
    private $pageFetch;
    private $oAuthPage;
    public function __construct()
    {
        $this->pageFetch = Page::make();
        $this->oAuthPage = OAuthPage::make();
    }
    public function autoLoginAction(string $accessToken)
    {
        $response = $this->pageFetch->fetchId($accessToken);

        if (empty($response['data'])) {
            $msg = 'Lỗi App Facebook: Không tìm thấy trang, App ID, App Secret, App Facebook sai đối tượng, sai quyền hoặc token không hợp lệ.';
            return $this->loginButtonDraw($msg);
        }

        $res = $this::oopstd($response['data'][0]);

        $data = $this::oopstd([
            'page_id' => $res->id,                      // Dùng để thao tác API (page_id)
            'access_token' => $res->access_token,       // Dùng để thao tác API (access_token)
        ]);

        ob_start();
        var_dump(Page::make()->getPageProfile($data->page_id, $data->access_token));
        return ob_get_clean();
    }
    public function firstAuthLoginAction($codeGetQueryString)
    {
        try {
            $token = PageAction::make()->convertUrlCodeIntoTokenAction($codeGetQueryString);
            return PageAction::make()->autoLoginAction($token);
        } catch (\Exception $e) {
            return PageAction::make()->loginButtonDraw('Lỗi khi lấy token: ' . $e->getMessage());
        }
    }
    public function loginButtonDraw(string $alert = '')
    {
        ob_start();
        echo $alert ? "<b>$alert</b><br>" : '';
        $this->render('facebookLoginButton', ['authUrl' => $this->oAuthPage->getAuthUrl()]);
        return ob_get_clean();
    }
    public function convertUrlCodeIntoTokenAction(string $codeGetQueryString)
    {
        /* Facebook trả về Url có query string 'code' sẽ cho phép lấy lại token */
        $getCode = $this->oAuthPage->getAuthToken($codeGetQueryString);
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
    public function logoutAction()
    {
        if (isset($_SESSION['fb_access_token'])) {
            unset($_SESSION['fb_access_token']);
            return "Bạn đã đăng xuất thành công !";
        } else {
            return "Trang đăng xuất (bạn chưa đăng nhập)";
        }
    }
}
