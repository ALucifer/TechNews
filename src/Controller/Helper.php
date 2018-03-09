<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 02/03/2018
 * Time: 14:35
 */

namespace App\Controller;


trait Helper
{

    /**
     * @param $text
     * @return null|string|string[]
     */
    public function slugify ($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}