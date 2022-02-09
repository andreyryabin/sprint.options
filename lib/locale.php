<?php

namespace Sprint\Options;

class Locale
{
    public static function isWin1251(): bool
    {
        return !(defined('BX_UTF') && BX_UTF === true);
    }

    public static function convertToWin1251IfNeed(string $msg): string
    {
        if ($msg && self::isWin1251() && self::detectUtf8($msg)) {
            $msg = (string)iconv('utf-8', 'windows-1251//IGNORE', $msg);
        }
        return $msg;
    }

    public static function convertToUtf8IfNeed(string $msg): string
    {
        if ($msg && self::isWin1251() && !self::detectUtf8($msg)) {
            $msg = (string)iconv('windows-1251', 'utf-8//IGNORE', $msg);
        }
        return $msg;
    }

    protected static function detectUtf8(string $msg): bool
    {
        return (md5($msg) == md5(iconv('utf-8', 'utf-8', $msg)));
    }

    public static function loadLocale(array $loc)
    {
        global $MESS;
        foreach ($loc as $key => $msg) {
            $MESS[$key] = self::convertToWin1251IfNeed($msg);
        }
    }
}
