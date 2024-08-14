<?php

namespace helper;

use Exception;

class Time
{
    /**
     * @throws Exception
     */
    public function formatTimeAmPm($dateStr): string
    {
        $date = new \DateTime($dateStr);
        return $date->format('g:i A');
    }
}