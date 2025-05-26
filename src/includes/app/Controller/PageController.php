<?php

namespace Controller;

use Action\PageAction;
use Model\Auth\OAuthPage;
use Model\Page\Page;
use Model\Page\PageInsight;
use Model\Page\PageSetting;
use Service\BaseService;

class PageController
{
    use BaseService;
    private $oAuthPage;

    public function __construct()
    {
        $this->oAuthPage = OAuthPage::make();
        $this->sessionStart();
    }
    public function page_login()
    {
        if (!empty($_SESSION['fb_access_token'])) {
            return PageAction::make()->autoLoginAction($_SESSION['fb_access_token']);
        } elseif (isset($_GET['webo_oauth'], $_GET['code']) && $this->oAuthPage->csrfState($_GET['state'] ?? '')) {
            return PageAction::make()->firstAuthLoginAction($_GET['code']);
        }
        $msg = PageAction::make()->notLoginYetAction();
        return PageAction::make()->loginButtonDraw($msg ?? '');
    }
    public function page_logout()
    {
        return PageAction::make()->logoutAction();
    }
    public function pageViewInsight()
    {
        if (!empty($_SESSION['fb_access_token'])) {
            $pageData = Page::make()->getPageId($_SESSION['fb_access_token'], 0);
            $response = PageInsight::make()->getInsight($pageData->page_id, $pageData->page_access_token, [
                'page_actions_post_reactions_like_total',
            ]);

            ob_start();
            var_dump($response['data'][0]);
            return ob_get_clean();
        };
        $msg = PageAction::make()->notLoginYetAction();
        return PageAction::make()->loginButtonDraw($msg ?? '');
    }

}
