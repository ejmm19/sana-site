<?php

namespace model;

use Exception;

require_once "EmailSender.php";
require_once "Employees.php";
require_once "Config.php";
require_once plugin_dir_path(__DIR__).'helper/Time.php';

class Order
{
    /**
     * @return \wpdb
     */
    private function getWpdb(): \wpdb
    {
        global $wpdb;
        return $wpdb;
    }

    /**
     * @param array $dataOrder
     * @param false $sendEmail
     * @return int
     * @throws Exception
     */
    public function setOrder(array $dataOrder, bool $sendEmail = false): int
    {
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . 'orders';

        $wpdb->insert(
            $table_name,
            array(
                'name' => $dataOrder['customerName'],
                'email' => $dataOrder['customerEmail'],
                'phone' => $dataOrder['customerPhone'],
                'employeeId' => $dataOrder['employeeId'],
                'schedule_date' => $dataOrder['schedule_date'],
                'date_init' => $dataOrder['date_init'],
                'date_finish' => $dataOrder['date_finish'],
                'created_at' => current_time('mysql')
            )
        );
        if ($sendEmail) {
            $sendEmail = new EmailSender();
            $employees = new Employees();
            $timeHelper = new \helper\Time();
            $config = new Config();
            $agencyConfigs = $config->getConfig(['agencyEmails', 'whatsAppNumber'], true);
            $employeeName = wp_trim_words($employees->getEmployee($dataOrder['employeeId'])->post_title, 2, '');
            $customerName = wp_trim_words($dataOrder['customerName'], 1, '');
            $initTime = $timeHelper->formatTimeAmPm($dataOrder['date_init']);
            $endTime = $timeHelper->formatTimeAmPm($dataOrder['date_finish']);

            $bodyAgency = $this->setEmailVars(
                file_get_contents(plugin_dir_path(__DIR__).'templates/email/new_order_agency.html'),
                $dataOrder['customerName'],
                $dataOrder['customerPhone'],
                $employees->getEmployee($dataOrder['employeeId'])->post_title,
                $dataOrder['schedule_date'],
                $initTime,
                $endTime,
                $agencyConfigs['whatsAppNumber']
            );

            $toAgency = $agencyConfigs['agencyEmails'];
            $subjectAgency = 'Nuevo Agendamiento';
            $sendEmail->send($toAgency, $subjectAgency, $bodyAgency);

            $bodyCustomer = $this->setEmailVars(
                file_get_contents(plugin_dir_path(__DIR__).'templates/email/new_order.html'),
                $customerName,
                $dataOrder['customerPhone'],
                $employeeName,
                $dataOrder['schedule_date'],
                $initTime,
                $endTime,
                $agencyConfigs['whatsAppNumber']
            );

            $to = $dataOrder['customerEmail'];
            $subject = 'Agendamiento de sesión en sanamente';
            $sendEmail->send($to, $subject, $bodyCustomer);

        }
        return $wpdb->insert_id;
    }

    /**
     * @param $data
     * @return bool
     */
    public function approveOrder($data): bool
    {
        $orderId = $data['id'];
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . 'orders';
        $data = ['status' => 'approved'];
        $where = ['id' => $orderId, 'status' => 'pending'];
        $updated = $wpdb->update($table_name, $data, $where, ['%s'], ['%d', '%s']);

        return $updated !== false;
    }
    /**
     * @param $data
     * @return bool
     */
    public function deleteOrder($data): bool
    {
        $orderId = $data['id'];
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . 'orders';
        $deleted = $wpdb->delete($table_name, ['id' => $orderId], ['%d']);

        return $deleted !== false;
    }

    /**
     * @param $employeeId
     * @return array|object|null
     */
    public function getOrdersByEmployeeId($employeeId): array|object|null
    {
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . 'orders';
        $today = current_time('Y-m-d');
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT `schedule_date`, `date_init`, `date_finish` FROM $table_name WHERE employeeId = %d AND schedule_date > %s",
                $employeeId, $today
            ),
            ARRAY_A
        );
    }

    protected function setEmailVars(string $contents, $customerName, $customerPhone, $employeeName, $scheduleDate, $initTime, $endTime, $agencyPhone): string
    {
        $body = str_replace("[name]", $customerName, $contents);
        $body = str_replace("[phone]", $customerPhone, $body);
        $body = str_replace("[orientador_name]", $employeeName, $body);
        $body = str_replace("[sanamente_phone]", $agencyPhone, $body);
        $body = str_replace("[fecha]", $scheduleDate, $body);
        return str_replace("[hora]", "de {$initTime} a {$endTime}", $body);
    }

    /**
     * @param string $direction
     * @return array|object|null
     */
    public function getOrders(string $direction = 'ASC'): array|object|null
    {
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . 'orders';
        $today = current_time('Y-m-d');

        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $query = "SELECT * FROM $table_name WHERE schedule_date > %s ORDER BY created_at $direction";

        return $wpdb->get_results($wpdb->prepare($query, $today), ARRAY_A);
    }

}