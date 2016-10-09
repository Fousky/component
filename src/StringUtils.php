<?php

namespace Fousky\Component;

/**
 * @author Lukáš Brzák <lukas.brzak@aquadigital.cz>
 */
class StringUtils
{
    /**
     * @param int $length
     *
     * @return string
     */
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Clean beginning and ending slashes from some path
     *
     * @param $path
     *
     * @return string
     */
    public static function cleanPathSlashes($path)
    {
        return preg_replace('#^/|/$#', '', urldecode($path));
    }
}
