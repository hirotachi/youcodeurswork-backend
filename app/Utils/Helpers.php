<?php

namespace App\Utils;

class Helpers
{

    /*
     * convert date to elapsed time
     */
    static public function time_elapsed_string($datetime, $full = false): string
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);

        $diff = $now->diff($ago); // return DateInterval

        $diff->w = floor($diff->d / 7); // weeks
        $diff->d -= $diff->w * 7; // days

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ); // pluralization

        foreach ($string as $k => &$v) {
            if ($diff->$k) { // if there is a difference
                $v = $diff->$k.' '.$v.($diff->$k > 1 ? 's' : ''); // add the difference to the string
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string).' ago' : 'just now';
    }
}
