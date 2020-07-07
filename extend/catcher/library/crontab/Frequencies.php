<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\library\crontab;

use think\helper\Str;

/**
 * From Laravel
 *
 * Trait ManagesFrequencies
 * @package catcher\library\crontab
 */
trait Frequencies
{

    /**
     * The Cron expression representing the event's frequency.
     *
     * @param  string  $expression
     * @return $this
     */
    public function cron($expression)
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * 每十秒
     *
     * @time 2020年07月04日
     * @return $this
     */
    public function everyTenSeconds()
    {
        $this->second = 10;

        return $this;
    }

    /**
     * 每二十秒
     *
     * @time 2020年07月04日
     * @return $this
     */
    public function everyTwentySeconds()
    {
        $this->second = 20;

        return $this;
    }

    /**
     * 每三十秒
     *
     * @time 2020年07月04日
     * @return $this
     */
    public function everyThirtySeconds()
    {
        $this->second = 30;

        return $this;
    }

    /**
     * 每分钟
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function everyMinute()
    {
        return $this->spliceIntoPosition(1, '*');
    }

    /**
     * 5 分钟
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function everyFiveMinutes()
    {
        return $this->spliceIntoPosition(1, '*/5');
    }

    /**
     * 10 分钟
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function everyTenMinutes()
    {
        return $this->spliceIntoPosition(1, '*/10');
    }

    /**
     * 15 分钟
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function everyFifteenMinutes()
    {
        return $this->spliceIntoPosition(1, '*/15');
    }

    /**
     * 三十分钟
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function everyThirtyMinutes()
    {
        return $this->spliceIntoPosition(1, '0,30');
    }

    /**
     * 每小时
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function hourly()
    {
        return $this->spliceIntoPosition(1, 0);
    }

    /**
     * 小时的时间
     *
     * @param  array|int  $offset
     * @return $this
     */
    public function hourlyAt($offset)
    {
        $offset = is_array($offset) ? implode(',', $offset) : $offset;

        return $this->spliceIntoPosition(1, $offset);
    }


    /**
     * 每天
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function daily()
    {
        return $this->spliceIntoPosition(1, 0)
            ->spliceIntoPosition(2, 0);
    }

    /**
     * 每天固定时间启动
     *
     * @param  string  $time
     * @return $this
     */
    public function at($time)
    {
        return $this->dailyAt($time);
    }

    /**
     * 每天固定时间启动 (10:00, 19:30, etc).
     *
     * @param  string  $time
     * @return $this
     */
    public function dailyAt($time)
    {
        $segments = explode(':', $time);

        return $this->spliceIntoPosition(2, (int) $segments[0])
            ->spliceIntoPosition(1, count($segments) === 2 ? (int) $segments[1] : '0');
    }

    /**
     * 每两天一次
     *
     * @param  int  $first
     * @param  int  $second
     * @return $this
     */
    public function twiceDaily($first = 1, $second = 13)
    {
        $hours = $first.','.$second;

        return $this->spliceIntoPosition(1, 0)
            ->spliceIntoPosition(2, $hours);
    }

    /**
     * 工作日跑
     *
     * @return $this
     */
    public function weekdays()
    {
        return $this->spliceIntoPosition(5, '1-5');
    }

    /**
     * 周末
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function weekends()
    {
        return $this->spliceIntoPosition(5, '0,6');
    }

    /**
     * 周一
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function mondays()
    {
        return $this->days(1);
    }

    /**
     * 周二
     *
     * @return $this
     */
    public function tuesdays()
    {
        return $this->days(2);
    }

    /**
     * 周三
     *
     * @return $this
     */
    public function wednesdays()
    {
        return $this->days(3);
    }

    /**
     * 周四
     *
     * @return $this
     */
    public function thursdays()
    {
        return $this->days(4);
    }

    /**
     * 周五
     *
     * @return $this
     */
    public function fridays()
    {
        return $this->days(5);
    }

    /**
     * 周六
     *
     * @return $this
     */
    public function saturdays()
    {
        return $this->days(6);
    }

    /**
     * 周日
     *
     * @return $this
     */
    public function sundays()
    {
        return $this->days(0);
    }

    /**
     * 每周
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function weekly()
    {
        return $this->spliceIntoPosition(1, 0)
            ->spliceIntoPosition(2, 0)
            ->spliceIntoPosition(5, 0);
    }

    /**
     * 每周的某个时间
     *
     * @param  int  $day
     * @param  string  $time
     * @return $this
     */
    public function weeklyOn($day, $time = '0:0')
    {
        $this->dailyAt($time);

        return $this->spliceIntoPosition(5, $day);
    }

    /**
     * 每月的某天某个时间
     *
     * @time 2020年07月04日
     * @param int $day
     * @param string $time
     * @return Frequencies
     */
    public function monthlyOn($day = 1, $time = '0:0')
    {
        $this->dailyAt($time);

        return $this->spliceIntoPosition(3, $day);
    }

    /**
     * 每月两次
     *
     * @time 2020年07月04日
     * @param int $first
     * @param int $second
     * @return Frequencies
     */
    public function twiceMonthly($first = 1, $second = 16)
    {
        $days = $first.','.$second;

        return $this->spliceIntoPosition(1, 0)
            ->spliceIntoPosition(2, 0)
            ->spliceIntoPosition(3, $days);
    }

    /**
     * 每月
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function monthly()
    {
        return $this->spliceIntoPosition(1, 0)
            ->spliceIntoPosition(2, 0)
            ->spliceIntoPosition(3, 1);
    }

    /**
     * 每个季度
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function quarterly()
    {
        return $this->spliceIntoPosition(1, 0)
            ->spliceIntoPosition(2, 0)
            ->spliceIntoPosition(3, 1)
            ->spliceIntoPosition(4, '1-12/3');
    }

    /**
     * 一周中的几天运行
     *
     * @time 2020年07月04日
     * @param $days
     * @return Frequencies
     */
    public function days($days)
    {
        $days = is_array($days) ? $days : func_get_args();

        return $this->spliceIntoPosition(5, implode(',', $days));
    }

    /**
     * 每年
     *
     * @time 2020年07月04日
     * @return Frequencies
     */
    public function yearly()
    {
        return $this->spliceIntoPosition(1, 0)
            ->spliceIntoPosition(2, 0)
            ->spliceIntoPosition(3, 1)
            ->spliceIntoPosition(4, 1);
    }

    /**
     * Splice the given value into the given position of the expression.
     *
     * @param  int  $position
     * @param  string  $value
     * @return $this
     */
    protected function spliceIntoPosition($position, $value)
    {
        $segments = explode(' ', $this->expression);

        $segments[$position - 1] = $value;

        return $this->cron(implode(' ', $segments));
    }

    /**
     * call
     *
     * @time 2020年07月04日
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
       if (Str::contains($name, 'every')) {
           $num = (int)Str::substr(str_replace('every', '',$name), 0, 2);
           if (Str::contains($name, 'second')) {
               return $this->spliceIntoPosition(1, $num < 60 ? $num : 1);
           }

           if (Str::contains($name, 'minute')) {
               return $this->spliceIntoPosition(2, $num < 60 ? $num : 1);
           }

           if (Str::contains($name, 'hour')) {
               return $this->spliceIntoPosition(3, $num < 24 ? $num : 1);
           }

           if (Str::contains($name, 'day')) {
               return $this->spliceIntoPosition(4, $num < 31 ? $num : 1);
           }

           if (Str::contains($name, 'month')) {
               return $this->spliceIntoPosition(5, $num < 12 ? $num : 1);
           }
        }

       // other to do

       return $this;
   }

}