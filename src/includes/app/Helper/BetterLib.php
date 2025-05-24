<?php

namespace Helper;

class BetterLib
{
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
