<?php

namespace App\Services;

class NetworkService
{
    /**
     * Pshkniny away aya internet haya yan na
     * * @return bool
     */
    public static function isOnline(): bool
    {
        // پشکنینی هێڵ لە ڕێگەی پۆرتی 53 (DNS) ی گوگلەوە بە تایم ئاوتی 1 چرکە
        $connected = @fsockopen("8.8.8.8", 53, $errno, $errstr, 1.0);
        
        if ($connected) {
            fclose($connected);
            return true; // Online
        }

        return false; // Offline
    }
}