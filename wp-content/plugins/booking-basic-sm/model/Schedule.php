<?php

namespace model;

use helper\Time;

class Schedule
{

    /**
     * @param $attr
     * @return false|string
     */
    public function getScheduleView($attr): false|string
    {
        $employeeData = [];
        if (!empty($attr) && isset($attr['employee'])) {
            $employeesModel = new Employees();
            $order = new Order('');
            $employeeObject = $employeesModel->getEmployee($attr['employee']);
            if (!empty($employeeObject)) {
                $employeeData['id'] = $employeeObject->ID;
                $employeeData['name'] = wp_trim_words($employeeObject->post_title, '2', '');
                $employeeData['fullName'] = $employeeObject->post_title;
                $employeeData['scheduled'] = $order->getOrdersByEmployeeId($employeeObject->ID);
                $employeeData['logoPago'] = home_url().'/wp-content/plugins/booking-basic-sm/templates/frontend/assets/images/logowompi.png';
            }
        }

        $contents = file_get_contents(plugin_dir_path(__DIR__).'templates/frontend/view/customer-schedule.html');
        return str_replace("[employeeData]", json_encode($employeeData), $contents);
    }
}