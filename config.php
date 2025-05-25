<?php
/* Đường dẫn trang callback oauth user - Đặt cùng trang chứa shortcode [webo_oauth_user_login]*/
$config_oauth_user = 'oauth-user';

/* Đường dẫn trang callback oauth page - Đặt cùng trang chứa shortcode [webo_oauth_page_login]*/
$config_oauth_page = 'oauth-page';

/* Đường dẫn trang đăng xuất User - Đặt vào trang shortcode [webo_oauth_user_logout]*/

/* Đường dẫn trang đăng xuất Page - Đặt vào trang shortcode [webo_oauth_page_logout]*/

/* Tuỳ biến tên shortcode OAuth User Login */
$config_short_code_oauth_user = 'webo_oauth_user_login';

/* Tuỳ biến tên shortcode OAuth Page Login */
$config_short_code_oauth_page = 'webo_oauth_page_login';

/* Tuỳ biến tên shortcode OAuth User Logout*/
$config_short_code_oauth_user_logout = 'webo_oauth_user_logout';

/* Tuỳ biến tên shortcode OAuth Page Logout*/
$config_short_code_oauth_page_logout = 'webo_oauth_page_logout';

/* Định nghĩa constant */
define('OAUTH_USER', $config_oauth_user);
define('OAUTH_PAGE', $config_oauth_page);
define('SHORTCODE_OAUTH_USER', $config_short_code_oauth_user);
define('SHORTCODE_OAUTH_PAGE', $config_short_code_oauth_page);
define('SHORTCODE_OAUTH_USER_LOGOUT', $config_short_code_oauth_user_logout);
define('SHORTCODE_OAUTH_PAGE_LOGOUT', $config_short_code_oauth_page_logout);
