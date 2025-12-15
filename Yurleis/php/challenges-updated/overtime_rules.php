<?php
/**
 * rules:
 *      Monday–Friday: overtime 18:00–06:00
 *      SATURDAY:  Normal: 06:00–13:00 Overtime: 13:00–23:59
 *      SUNDAY → everything overtime
 * 
 *      Night window: 18:00–06:00
 *      Sunday premium (+50%)
 * 
 */
function get_overtime_hours_by_rules(string $date, string $startTime, string $endTime): array
{
    $start = new DateTime("$date $startTime");
    $end   = new DateTime("$date $endTime");

    if ($end <= $start) {
        $end->modify('+1 day');
    }

    $totalHours = ($end->getTimestamp() - $start->getTimestamp()) / 3600.0;

    $overtimeHours = 0.0;
    $nightOvertime = 0.0;
    $dayOvertime   = 0.0;
    $sundayHours   = 0.0;

    $cursor = clone $start;

    while ($cursor < $end) {

        $dow  = (int)$cursor->format('N');   // 1=Mon .. 7=Sun
        $time = $cursor->format('H:i');
        $minute = 1/60;

        $isOvertime = false;

        if ($dow >= 1 && $dow <= 5) {

            if ($time >= '18:00' || $time < '06:00') {
                $isOvertime = true;
            }

        } elseif ($dow === 6) {

            if ($time < '06:00') {
                $isOvertime = true;

            } elseif ($time >= '13:00') {
                $isOvertime = true;
            }

        } else {
            $isOvertime = true;
        }

        if ($isOvertime) {

            $overtimeHours += $minute;
            $isNight = ($time >= '18:00' || $time < '06:00');

            if ($isNight) {
                $nightOvertime += $minute;
            } else {
                $dayOvertime += $minute;
            }

            if ($dow === 7) {
                $sundayHours += $minute;
            }
        }

        $cursor->modify('+1 minute');
    }

    return [
        'total_hours'    => $totalHours,
        'overtime_hours' => $overtimeHours,
        'night_hours'    => $nightOvertime,
        'day_hours'      => $dayOvertime,
        'sunday_hours'   => $sundayHours,
        'dow'            => (int)$start->format('N'),
    ];
}