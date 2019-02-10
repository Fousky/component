<?php declare(strict_types = 1);

namespace Fousky\Component;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class StringUtils
{
    public static function generateRandomString(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function cleanPathSlashes(string $path): string
    {
        return preg_replace('#^/|/$#', '', urldecode($path));
    }
}
