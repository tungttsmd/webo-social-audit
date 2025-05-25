<?php

namespace Service;

trait BaseService
{
    public function render($nameView, $data)
    {
        // Vui lòng sử dụng mọi dữ liệu trong view theo biến $data
        extract($data);
        $path = plugin_dir_path(__DIR__) . 'View/' . $nameView . '.php';
        include $path;
    }
    public static function make(...$args): static
    {
        return new static(...$args);
    }
    public static function sessionStart()
    {
        if (!session_id()) {
            session_start();
        }
    }
    public static function oopstd($mixList)
    {
        if (is_array($mixList)) {
            $object = new \stdClass();
            foreach ($mixList as $key => $value) {
                $object->$key = self::oopstd($value);
            }
            return $object;
        } elseif (is_object($mixList)) {
            foreach ($mixList as $key => $value) {
                $mixList->$key = self::oopstd($value);
            }
            return $mixList;
        } else {
            return $mixList;
        }
    }
    public static function oopunset(object $mixList, $unsetKeys)
    {
        $keys = is_array($unsetKeys) ? $unsetKeys : [$unsetKeys];

        foreach ($keys as $key) {
            if (property_exists($mixList, $key)) {
                unset($mixList->$key);
            }
        }
        return $mixList;
    }
    # Hàm debug tốt hơn, giúp ngăn chạy lệnh kế tiếp, nhẹ nhanh hơn
    public static function betterDebug($value = 'exit only')
    {
        var_dump($value);
        exit;
    }
}
