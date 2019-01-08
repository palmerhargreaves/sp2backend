<?php

namespace common\utils;

use common\models\Settings;
use PHPExcel_Worksheet_Drawing;
use Yii;

/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 08.07.2017
 * Time: 11:21
 */
class Utils
{
    static function trim_text($input, $length, $ellipses = true, $strip_html = true)
    {
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space ? $last_space : $length);

        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
    }

    public static function numberFormat($value)
    {
        return sprintf('%s %s', number_format($value, 2, '.', ' '), Settings::getValue('currency'));
    }

    public static function generateSeoURL($string, $wordLimit = 0)
    {
        $separator = '-';

        if ($wordLimit != 0) {
            $wordArr = explode(' ', $string);
            $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
        }

        $quoteSeparator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;' => '',
            '[^\w\d _-]' => '',
            '\s+' => $separator,
            '(' . $quoteSeparator . ')+' => $separator
        );

        $string = strip_tags($string);
        foreach ($trans as $key => $val) {
            $string = preg_replace('#' . $key . '#i', $val, $string);
        }

        $string = strtolower($string);

        return trim(trim($string, $separator));
    }

    public static function my_str_split($string)
    {
        $slen = strlen($string);
        for ($i = 0; $i < $slen; $i++) {
            $sArray[$i] = $string{$i};
        }
        return $sArray;
    }

    public static function noDiacritics($string)
    {
        //cyrylic transcription
        $cyrylicFrom = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
        $cyrylicTo = array('A', 'B', 'W', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Ch', 'C', 'Tch', 'Sh', 'Shtch', '', 'Y', '', 'E', 'Iu', 'Ia', 'a', 'b', 'w', 'g', 'd', 'ie', 'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ch', 'c', 'tch', 'sh', 'shtch', '', 'y', '', 'e', 'iu', 'ia');


        $from = array("Á", "À", "Â", "Ä", "A", "A", "Ã", "Å", "A", "Æ", "C", "C", "C", "C", "Ç", "D", "Ð", "Ð", "É", "È", "E", "Ê", "Ë", "E", "E", "E", "?", "G", "G", "G", "G", "á", "à", "â", "ä", "a", "a", "ã", "å", "a", "æ", "c", "c", "c", "c", "ç", "d", "d", "ð", "é", "è", "e", "ê", "ë", "e", "e", "e", "?", "g", "g", "g", "g", "H", "H", "I", "Í", "Ì", "I", "Î", "Ï", "I", "I", "?", "J", "K", "L", "L", "N", "N", "Ñ", "N", "Ó", "Ò", "Ô", "Ö", "Õ", "O", "Ø", "O", "Œ", "h", "h", "i", "í", "ì", "i", "î", "ï", "i", "i", "?", "j", "k", "l", "l", "n", "n", "ñ", "n", "ó", "ò", "ô", "ö", "õ", "o", "ø", "o", "œ", "R", "R", "S", "S", "Š", "S", "T", "T", "Þ", "Ú", "Ù", "Û", "Ü", "U", "U", "U", "U", "U", "U", "W", "Ý", "Y", "Ÿ", "Z", "Z", "Ž", "r", "r", "s", "s", "š", "s", "ß", "t", "t", "þ", "ú", "ù", "û", "ü", "u", "u", "u", "u", "u", "u", "w", "ý", "y", "ÿ", "z", "z", "ž");
        $to = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", "C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", "G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", "e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", "I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", "N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", "h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", "l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", "o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", "Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");


        $from = array_merge($from, $cyrylicFrom);
        $to = array_merge($to, $cyrylicTo);

        $newstring = str_replace($from, $to, $string);

        return $newstring;
    }

    public static function makeSlugs($string, $maxlen = 0)
    {
        $newStringTab = array();
        $string = strtolower(self::noDiacritics($string));
        if (function_exists('str_split')) {
            $stringTab = str_split($string);
        } else {
            $stringTab = self::my_str_split($string);
        }

        $numbers = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "-");
        //$numbers=array("0","1","2","3","4","5","6","7","8","9");

        foreach ($stringTab as $letter) {
            if (in_array($letter, range("a", "z")) || in_array($letter, $numbers)) {
                $newStringTab[] = $letter;
            } elseif ($letter == " ") {
                $newStringTab[] = "-";
            }
        }

        if (count($newStringTab)) {
            $newString = implode($newStringTab);
            if ($maxlen > 0) {
                $newString = substr($newString, 0, $maxlen);
            }

            $newString = self::removeDuplicates('--', '-', $newString);
        } else {
            $newString = '';
        }

        return $newString;
    }


    public static function checkSlug($sSlug)
    {
        if (preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9\-]*$/", $sSlug) == 1) {
            return true;
        }

        return false;
    }

    public static function removeDuplicates($sSearch, $sReplace, $sSubject)
    {
        $i = 0;
        do {

            $sSubject = str_replace($sSearch, $sReplace, $sSubject);
            $pos = strpos($sSubject, $sSearch);

            $i++;
            if ($i > 100) {
                die('self::removeDuplicates() loop error');
            }

        } while ($pos !== false);

        return $sSubject;
    }

    public static function statusesOnOff() {
        return [
            0 => Yii::t('app', 'Откл.'),
            1 => Yii::t('app', 'Акт.'),
        ];
    }

    static function normalize($name)
    {
        $str = '';
        $name = mb_strtolower(Utils::toUtf8($name), 'UTF-8');

        for ($n = 0, $len = mb_strlen($name, 'UTF-8'); $n < $len; $n++) {
            $new_sym = $sym = mb_substr($name, $n, 1, 'UTF-8');
            if (!Utils::isSymEnabled($sym)) {
                $new_sym = Utils::symToTranslit($sym);
                if (!$new_sym)
                    $new_sym = '_';
            }

            $str .= $new_sym;
        }

        return $str;
    }

    static function isSymEnabled($sym)
    {
        $enabled = 'abcdefghijklmnopqrstuvwxyz0123456789';
        return mb_strpos($enabled, $sym, 0, 'UTF-8') !== false;
    }

    static function symToTranslit($sym)
    {
        static $translit = array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'yo',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ы' => 'yi',
            'э' => 'ye',
            'ю' => 'yu',
            'я' => 'ya'
        );

        return isset($translit[$sym]) ? $translit[$sym] : false;
    }

    static function toUtf8($name)
    {
        return mb_convert_encoding($name, 'UTF-8', 'UTF-8,CP1251,ASCII');
    }

    static function drawExcelImage($icon, $coordinates, $pExcel, $offsetX = 3, $offsetY = 3, $label = '')
    {
        $imageModelStatus = new PHPExcel_Worksheet_Drawing();

        $imageModelStatus->setPath(Yii::$app->params['images_path'] . '/' . $icon);
        $imageModelStatus->setName($label);
        $imageModelStatus->setDescription($label);
        $imageModelStatus->setHeight(16);
        $imageModelStatus->setWidth(16);

        $imageModelStatus->setOffsetX($offsetX);
        $imageModelStatus->setOffsetY($offsetY);

        $imageModelStatus->setWorksheet($pExcel->getActiveSheet());
        $imageModelStatus->setCoordinates($coordinates);
    }

    static function getElapsedTime($st)
    {
        $mins = floor($st / 60);
        $hours = floor($mins / 60);
        $days = floor($hours / 24);

        return $days;
    }

    static function getQuartersMonths() {
        return array
        (
            1 => array(1, 2, 3),
            2 => array(4, 5, 6),
            3 => array(7, 8, 9),
            4 => array(10, 11, 12)
        );
    }

}
