<?php

namespace model;

class Order
{
    public function setOrder($email, $name, $employeeId, $schedule_date, $date_init, $date_finish): int
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'orders';

        $wpdb->insert(
            $table_name,
            array(
                'email' => $email,
                'name' => $name,
                'employeeId' => $employeeId,
                'schedule_date' => $schedule_date,
                'date_init' => $date_init,
                'date_finish' => $date_finish,
                'created_at' => current_time('mysql')
            )
        );

        return $wpdb->insert_id;
    }
}