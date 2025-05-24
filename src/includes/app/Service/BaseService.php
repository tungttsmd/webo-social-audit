<?php

namespace Service;

trait BaseService
{
    public static function make(...$args): static
    {
        return new static(...$args);
    }
    public function render($nameView, $data)
    {
        // Vui lòng sử dụng mọi dữ liệu trong view theo biến $data
        extract($data);
        $path = plugin_dir_path(__DIR__) . 'View/' . $nameView . '.php';
        include $path;
    }
}
