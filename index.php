<?php

/**
 * Plugin Name: Webo Social Audit
 * Description: Kết nối Facebook để audit page, profile người dùng, .
 * Version: 1.2
 * Author: Tran Thanh Tung
 */

if (!defined('ABSPATH')) exit;

// Cần require autoload.php của composer ở môi trường trực tiếp của wordpress
require_once __DIR__ . '/vendor/autoload.php';

// Nhúng route/action/hook wordpress vào
\Public\Plugin::make()->init();

// Test chức năng Model Page, PageInsight, PagePost, User vào PageAction, UserAction tại autoLoginAction()