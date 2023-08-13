<?php

namespace App\Infrastructure\Helper;

class IpHelper
{
    public static function getUserIp()
    {
        $user_ip_address = '127.0.0.1';

        if (isset($_SERVER['HTTP_CLIENT_IP']) === true && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $user_ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) === true && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) === true && !empty($_SERVER['REMOTE_ADDR'])) {
            $user_ip_address = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['SERVER_ADDR']) === true && !empty($_SERVER['SERVER_ADDR'])) {
            $user_ip_address = $_SERVER['SERVER_ADDR'];
        }

        return $user_ip_address;
    }
}
