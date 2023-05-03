<?php

namespace W360\SecureData\Support;

class Binary
{

    /**
     * Safe string length
     *
     * @param string $str
     * @return int
     */
    public static function safeStrlen(
        string $str
    ): int {
        if (\function_exists('mb_strlen')) {
            return (int) \mb_strlen($str, '8bit');
        } else {
            return \strlen($str);
        }
    }

    /**
     * check is binary a string
     *
     * @param $str
     * @return bool
     */
    public static function checkIs($str) {
        if (\function_exists('mb_detect_encoding')) {
            return false === mb_detect_encoding((string)$str, null, true);
        }else{
            return false;
        }
    }

}