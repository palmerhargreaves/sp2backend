<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 05.03.2017
 * Time: 15:40
 */

namespace common\utils;

use common\models\calendar\Calendar;
use common\models\Settings;
use Yii;

/**
 * Utils to work with dates
 * Class D
 * @package common\utils
 */
class D {
    static public $genetiveRusMonths = array(
        'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа',
        'сентября', 'октября', 'ноября', 'декабря'
    );

    static function getMonthName($month_index)
    {
        $months = array("Янв.", "Фев.", "Мар.", "Апр.", "Май", "Июнь", "Июль", "Авг.", "Сен.", "Окт.", "Ноя.", "Дек.");

        return $months[$month_index - 1];
    }

    private static $_calendar_dates = array();

    public static function toUnix($date)
    {
        return is_numeric($date) ? intval($date) : strtotime($date);
    }

    public static function getCurrentDate() {
        return date('Y-m-d H:i:s');
    }

    public static function getQuarter($date)
    {
        if (is_numeric($date))
            $month = date('n', $date);
        else
            $month = date('n', self::toUnix($date));

        return floor(($month - 1) / 3) + 1;
    }

    public static function getYear($date)
    {
        return date('Y', self::toUnix($date));
    }

    public static function makeShortDate($year, $month, $day = null) {
        return is_null($day)
            ? sprintf('%s-%s', $year, $month >= 10 ? $month : '0'.intval($month))
            : sprintf('%s-%s-%s', $year, intval($month) >= 10 ? $month : '0'.intval($month), intval($day) >= 10 ? $day : '0'.intval($day));
    }

    public static function getMonthLabel($month) {
        foreach(self::$genetiveRusMonths as $key => $item) {
            if ($key == $month - 1) {
                return self::$genetiveRusMonths[$key];
            }
        }

        return '';
    }

    /**
     * Проверка на рабочий день
     * @param $date
     * @return bool
     */
    public static function workDay($date) {
        $date = date("d-m-Y H:i:s", self::toUnix($date));

        $d = getdate(strtotime($date));
        if ($d['wday'] == 0 || $d['wday'] == 6) { return false; }

        return true;
    }

    /**
     * @param $time
     * @return string нормальный вывод
     */
    public static function toElapsedString($time) {
        $difference = time() - (is_numeric($time) ? $time : strtotime($time));

        if ($difference < 1)
            return 'только что';

        $a = array( 365 * 24 * 60 * 60  =>  'год',
            30 * 24 * 60 * 60   =>  'месяц',
            24 * 60 * 60        =>  'день',
            60 * 60             =>  'час',
            60                  =>  'минуту',
            1                   =>  'секунду'
        );

        $aPlural = array(   'год'       => 'года',
            'месяц' 	=> 'месяца',
            'день'   	=> 'дня',
            'час'   	=> 'часа',
            'минуту' 	=> 'минуты',
            'секунду'	=> 'секунд'
        );

        $aAverage = array(  'год'   	=> 'лет',
            'месяц' 	=> 'месяцев',
            'день'   	=> 'дней',
            'час'   	=> 'часов',
            'минуту' 	=> 'минут',
            'секунду'	=> 'секунд'
        );

        foreach ($a as $secs => $str) {
            $d = $difference / $secs;
            if ($d >= 1) {
                $r = round($d);
                if ($r > 1 && $r < 5) {
                    $out = $aPlural[$str];
                } else if ($r == 1) {
                    $out = $str;
                } else {
                    $out = $aAverage[$str];
                }
                return $r . ' ' . $out . ' назад';
            }
        }
    }

    static function getElapsedTime($st)
    {
        $secs = $st;
        $mins = floor($st / 60);
        $hours = floor($mins / 60);
        $days = floor($hours / 24);
        $week = floor($days / 7);
        $month = floor($week / 4);

        if ($month > 0) {
            $week_elapsed = floor(($st - ($month * 4 * 7 * 24 * 60 * 60)) / (7 * 24 * 60 * 60));
            $days_elapsed = floor(($st - ($week * 7 * 24 * 60 * 60)) / (24 * 60 * 60));
            $hours_elapsed = floor(($st - ($days * 24 * 60 * 60)) / (60 * 60));
            $mins_elapsed = floor(($st - ($hours * 60 * 60)) / 60);
            $secs_elapsed = floor($st - $mins * 60);
        }

        if ($week > 0) {
            $days_elapsed = floor(($st - ($week * 7 * 24 * 60 * 60)) / (24 * 60 * 60));
            $hours_elapsed = floor(($st - ($days * 24 * 60 * 60)) / (60 * 60));
            $mins_elapsed = floor(($st - ($hours * 60 * 60)) / 60);
            $secs_elapsed = floor($st - $mins * 60);
            return "$week нед. $days_elapsed дн. $hours_elapsed час. $mins_elapsed мин. $secs_elapsed сек.";
        }

        if ($days > 0) {
            $hours_elapsed = floor(($st - ($days * 24 * 60 * 60)) / (60 * 60));
            $mins_elapsed = floor(($st - ($hours * 60 * 60)) / 60);
            $secs_elapsed = floor($st - $mins * 60);
            return "$days дн. $hours_elapsed час. $mins_elapsed мин. $secs_elapsed сек.";
        }

        if ($hours > 0) {
            $mins_elapsed = floor(($st - ($hours * 60 * 60)) / 60);
            $secs_elapsed = floor($st - $mins * 60);
            return "$hours час. $mins_elapsed мин. $secs_elapsed сек.";
        }

        if ($mins > 0) {
            $secs_elapsed = floor($st - $mins * 60);
            return "$mins мин. $secs_elapsed сек.";
        }

        if ($secs > 0) {
            return "$secs сек.";
        }

        return "0 сек.";
    }

    /**
     * Add minutes to time
     * @param $time
     * @param $minutes
     * @return false|int
     */
    public static function addMinutes($time, $minutes) {
        return strtotime('+'.$minutes.' minutes', !is_numeric($time) ? strtotime($time) : $time);
    }

    /**
     * Add minutes to time
     * @param $time
     * @param $seconds
     * @return false|int
     */
    public static function addSeconds($time, $seconds) {
        return strtotime('+'.$seconds.' seconds', !is_numeric($time) ? strtotime($time) : $time);
    }

    private static function loadCalendarDates() {
        self::$_calendar_dates = Calendar::find()->select(['start_date', 'end_date'])->orderBy(['id' => SORT_ASC])->all();
    }

    /**
     * @param $date
     * @param bool $only_check_in_calendar
     * @return int
     */
    static function checkDateInCalendar($date, $only_check_in_calendar = false)
    {
        if (empty(self::$_calendar_dates)) {
            self::loadCalendarDates();
        }

        $days = 0;
        $check_date = date('Y-m-d', D::toUnix($date));

        $item = null;
        foreach (self::$_calendar_dates as $calendar_date) {
            if (strtotime($check_date) >= strtotime($calendar_date->start_date) && strtotime($check_date) <= strtotime($calendar_date->end_date)) {
                $item = $calendar_date;
                break;
            }
        }

        //Делаем проверку на дату в календаре
        if ($only_check_in_calendar && !is_null($item)) {
            return true;
        }

        if (!is_null($item) && isset($item->end_date)) {
            $endDate = strtotime($item->end_date);

            $days = 1;
            $i = 1;
            while (1) {
                $calc_date = strtotime(date("Y-m-d", strtotime('+' . $i . ' days', D::toUnix($check_date))));
                if ($calc_date <= $endDate) {
                    $days++;
                    $i++;
                } else {
                    break;
                }
            }
        }

        return $days;
    }

    static function getNewDate($date, $plusDays = 1, $sign = '+', $only_days = false, $format = 'd-m-Y H:i:s', $start_from_day_idx = 1)
    {
        $total_days = 0;
        for ($i = $start_from_day_idx; $i <= $plusDays; $i++) {
            $tempDate = date($format, strtotime($sign . $i . ' days', D::toUnix($date)));

            $d = getdate(strtotime($tempDate));
            $dPlus = self::checkDateInCalendar($tempDate);
            if ($dPlus == 0) {
                if ($d['wday'] == 0 || $d['wday'] == 6) {
                    $dPlus++;
                }
            } else if ($dPlus > 1) {
                $i += $dPlus;
            }

            $plusDays += $dPlus;
            $total_days += $dPlus;
        }

        if ($only_days) {
            return $total_days;
        }

        return date($format, strtotime($sign . $plusDays . ' days', D::toUnix($date)));
    }
}
