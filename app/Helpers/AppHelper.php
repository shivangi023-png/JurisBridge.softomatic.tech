<?php

namespace App\Helpers;

class AppHelper
{

    public static function  moneyFormatIndia($num)
    {
        $pre = NULL;
        $sep = array();
        $app = '00';
        $s = substr($num, 0, 1);
        if ($s == '-') {
            $pre = '-';
            $num = substr($num, 1);
        }
        $num = explode('.', $num);
        if (count($num) > 1) $app = $num[1];
        if (strlen($num[0]) < 4) return $pre . $num[0] . '.' . $app;
        $th = substr($num[0], -3);
        $hu = substr($num[0], 0, -3);
        while (strlen($hu) > 0) {
            $sep[] = substr($hu, -2);
            $hu = substr($hu, 0, -2);
        }
        return $pre . implode(',', array_reverse($sep)) . ',' . $th . '.' . $app;
    }

    public static function  moneyFormatWithoutZeroIndia($num)
    {
        $pre = NULL;
        $sep = array();
        $s = substr($num, 0, 1);
        if ($s == '-') {
            $pre = '-';
            $num = substr($num, 1);
        }
        $num = explode('.', $num);
        if (count($num) > 1);
        if (strlen($num[0]) < 4) return $pre . $num[0];
        $th = substr($num[0], -3);
        $hu = substr($num[0], 0, -3);
        while (strlen($hu) > 0) {
            $sep[] = substr($hu, -2);
            $hu = substr($hu, 0, -2);
        }
        return $pre . implode(',', array_reverse($sep)) . ',' . $th;
    }
}