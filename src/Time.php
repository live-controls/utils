<?php

namespace LiveControls\Utils;

use Exception;
use Illuminate\Support\Carbon;

class Time
{

    /**
     * Returns an array of dates based on the startDate and the payment periods.
     * Example: 30/60/90 would return a Carbon for 30, 60 and 90 days later.
     * 
     * @param Carbon $startDate
     * @param string $periodString
     * @return array
     */
    public static function periodToDates(Carbon $startDate, string $periodString): array
    {
        $periods = explode('/', $periodString);
        $lastPeriod = 0;
        $dates = [];
        foreach($periods as $period){
            if(filter_var($period, FILTER_VALIDATE_INT) === false){
                throw new Exception("Invalid value for period: ".$period);
            }
            
            $period = (int)$period; //Convert string to integer

            if($period < $lastPeriod){
                throw new Exception("{$period} needs to be bigger than {$lastPeriod}");
            }
            $lastPeriod = $period;
            $dates[] = $startDate->copy()->addDays($period);
        }
        return $dates;
    }

    /**
     * Will normalize the following values: string, int, float, Carbon to a normal carbon value
     *
     * @param string|int|float|Carbon $value
     * @return Carbon
     */
    public static function normalizeToCarbon(string|int|float|Carbon $value): Carbon
    {
        if($value instanceof Carbon){
            return $value;
        }

        if(is_numeric($value)){
            return Carbon::createFromTimestamp($value);
        }

        return Carbon::parse($value);
    }

    /**
     * Converts seconds into a readable string. Uses the format from $formatString.
     *
     * @param int $seconds
     * @param string $formatString
     * @return string
     */
    public static function secondsToTime(int $seconds, string $formatString = '%a days, %h hours, %i minutes and %s seconds'): string
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format($formatString);
    }

    /**
     * Returns the previous timespan as an array 'previousFrom', 'previousTo'. The new values are already in Carbon. If $simple is set to true, the plain difference in days will be returned, so a full month won't return the full month before but 31 days before!
     *
     * @param Carbon $from
     * @param Carbon $to
     * @param boolean $simple
     * @return array
     */
    public static function previousTimespan(Carbon $from, Carbon $to, bool $simple = false): array
    {
        if(!$simple && $from->day == 1 && $to->day == $to->endOfMonth()->day && $from->month == $to->month && $from->year == $to->year){
            //Should return the previous month
            return [
                'previousFrom' => $from->copy()->subMonth(),
                'previousTo' => $to->copy()->subMonth()
            ];
        }elseif(!$simple && $from->day == 1 && $from->month == 1 && $to->day == 31 && $to->month == 12 && $from->year == $to->year){
            //Should return the previous year
            return [
                'previousFrom' => $from->copy()->subYear(),
                'previousTo' => $to->copy()->subYear(),
            ];
        }
        else
        {
            //If simple is set to true, we ignore anything like full month or full year and just return the plain days
            $difference = $from->diffInDays($to);
            if($difference < 1){
                //If they are on the same day we can subtract only a single day
                return [
                    'previousFrom' => $from->copy()->subDay(),
                    'previousTo' => $from->copy()->subDay(),
                ];
            }
            $difference = round($difference); //Will round the difference to evade problems with 29.999999999 values which should be 30
            return [
                'previousFrom' => $from->copy()->subDays($difference),
                'previousTo' => $to->copy()->subDays($difference),
            ];
        }
    }

    /**
     * Checks if the date string is a valid date
     *
     * @param string $dateString Needs to be in format DD/MM
     * @return boolean
     */
    public static function isValidDate(string $dateString): bool
    {
        [$day, $month] = explode('/',$dateString);
        return checkdate((int)$month, (int)$day, 2000);
    }

    /**
     * Returns true if the startTime is after the endTime
     *
     * @param string $startTime Needs to be in HH:MM format
     * @param string $endTime Needs to be in HH:MM format
     * @return boolean
     */
    public static function isStartAfterEndTime(string $startTime, string $endTime): bool
    {
        [$startHours, $startMinutes] = explode(':', $startTime);
        [$endHours, $endMinutes] = explode(':', $endTime);
        $startTime = Carbon::createFromTime($startHours, $startMinutes);
        $endTime = Carbon::createFromTime($endHours, $endMinutes);
        return $startTime > $endTime;
    }

    /**
     * Calculates the days between $fromDay and $toDay over a specific month
     *
     * @param integer $fromDay The weekday the calculation should start (0 is sunday)
     * @param integer $toDay The weekday the calculation should end (0 is sunday)
     * @param integer $month The month of the year the calculation should take place in
     * @param integer $year The year the calculation should take place in
     * @return int The amount of days used with this setting
     */
    public static function calculateDaysInMonth(int $fromDay, int $toDay, int $month, int $year): int
    {
        $days = 0;

        $lastday = date("t", mktime(0,0,0,$month,1,$year));
        for($i = 1; $i <= $lastday; $i++)
        {
            if($lastday >= $i)
            {
                $weekday = date("w", mktime(0,0,0,$month, $i, $year));
                if($weekday <= $toDay && $weekday >= $fromDay){
                    $days++;
                }
            }
        }
        return $days;
    }

}