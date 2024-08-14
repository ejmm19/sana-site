<?php

namespace model;

class Config
{

    const string TABLE_NAME = 'config_data';

    /**
     * @return \wpdb
     */
    private function getWpdb(): \wpdb
    {
        global $wpdb;
        return $wpdb;
    }

    /**
     * @param $path
     * @param $value
     * @return void
     */
    private function setConfig($path, $value): void
    {
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . self::TABLE_NAME;

        if ($this->pathExists($path)) {
            $wpdb->update(
                $table_name,
                ['value' => $value],
                ['path' => $path],
                ['%s'],
                ['%s']
            );
        } else {
            $wpdb->insert(
                $table_name,
                ['path' => $path, 'value' => $value],
                ['%s', '%s']
            );
        }

    }

    /**
     * @param $data
     * @return void
     */
    public function setMultipleConfig($data): void
    {
        if (!empty($data)) {
            foreach ($data as $path => $value) {
                $this->setConfig($path, $value);
            }
        }
    }

    public function pathExists($path): bool
    {
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE path = %s", $path);
        $count = $wpdb->get_var($query);

        return $count > 0;
    }


    public function updateMultipleConfig($data): void
    {
        if (!empty($data)) {
            foreach ($data as $path => $value) {
                $this->updateConfig($path, $value);
            }
        }
    }

    /**
     * @param array $paths
     * @return object|array|null
     */
    public function getConfig(array $paths, $onlyValues = false): object|array|null
    {
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $placeholders = implode(',', array_fill(0, count($paths), '%s'));
        $selectFields = $onlyValues ? 'path, value' : '*';
        $sql = $wpdb->prepare("SELECT $selectFields FROM $table_name WHERE path IN ($placeholders)", ...$paths);
        $rows = $wpdb->get_results($sql, ARRAY_A);

        $result = [];
        foreach ($rows as $row) {
            $row['value'] = maybe_unserialize($row['value']);
            if ($onlyValues) {
                $result[$row['path']] = $row['value'];
            } else {
                $result[$row['path']] = $row;
            }
        }

        return $result;
    }

    /**
     * @param $path
     * @param $new_value
     * @return void
     */
    private function updateConfig($path, $new_value): void
    {
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $wpdb->update(
            $table_name,
            [
                'value' => maybe_serialize($new_value) // Serializa el valor si es necesario
            ],
            ['path' => $path],
            ['%s'], // value (LONGTEXT)
            ['%s']  // path (VARCHAR)
        );

    }

    /**
     * @param $path
     * @return bool
     */
    private function deleteConfig($path): bool
    {
        $wpdb = $this->getWpdb();
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $result = $wpdb->delete($table_name, ['path' => $path], ['%s']);

        return $result !== false;
    }


}