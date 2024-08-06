<?php

require_once plugin_dir_path(__DIR__).'model/Schedule.php';

function schedule($attr): string
{
    $schedule = new \Model\Schedule();
    return $schedule->getScheduleView($attr);
}

add_shortcode('customer_schedule', 'schedule');