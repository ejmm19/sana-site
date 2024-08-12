<?php

namespace model;

class Order
{
    public function setOrder(array $dataOrder): int
    {

        global $wpdb;
        $table_name = $wpdb->prefix . 'orders';

        $wpdb->insert(
            $table_name,
            array(
                'name' => $dataOrder['customerName'],
                'email' => $dataOrder['customerEmail'],
                // 'phone' => $dataOrder['customerPhone'],
                'employeeId' => $dataOrder['employeeId'],
                'schedule_date' => $dataOrder['schedule_date'],
                'date_init' => $dataOrder['date_init'],
                'date_finish' => $dataOrder['date_finish'],
                'created_at' => current_time('mysql')
            )
        );

        return $wpdb->insert_id;
    }

    public function getOrdersByEmployeeId($employeeId) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'orders';
        $today = current_time('Y-m-d');
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT `schedule_date`, `date_init`, `date_finish` FROM $table_name WHERE employeeId = %d AND schedule_date > %s",
                $employeeId, $today
            ),
            ARRAY_A // Devuelve los resultados como un array asociativo
        );

        return $results;
    }


}