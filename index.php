<?php

/**
 * Plugin Name: Webo Social Audit
 * Description: Kết nối Facebook để lấy thông tin profile người dùng.
 * Version: 1.0
 * Author: Webo.vn
 */

if (!defined('ABSPATH')) exit;

// Cần require autoload.php của composer ở môi trường trực tiếp của wordpress
require_once __DIR__ . '/vendor/autoload.php';

use \Public\Plugin;

Plugin::make()->init();
