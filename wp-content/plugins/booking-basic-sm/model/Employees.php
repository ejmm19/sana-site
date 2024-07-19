<?php
declare(strict_types=1);

namespace model;

use WP_Query;
class Employees
{

    /**
     * @param int $posts_per_page
     * @param bool $isArray
     * @return WP_Query|array
     */
    public function getEmployees(int $posts_per_page = -1, bool $isArray = false): WP_Query|array
    {
        $args = [
            'post_type' => 'orientadores',
            'posts_per_page' => $posts_per_page,
            'order' => 'ASC',
            'orderby' => 'title'
        ];
        $response = new WP_Query($args);
        $employees = [];
        while ($response->have_posts()) {
            $response->the_post();
            $employees[] = [
                'id' => get_the_ID(),
                'name' => get_the_title(),
                'image' => get_the_post_thumbnail_url(),
                'calendar' => get_the_post_thumbnail_url(),
            ];
        }
        wp_reset_postdata();

        return $isArray ? $employees : $response;
    }

    /**
     * @param $id
     * @return \WP_Post|null
     */
    public function getEmployee($id): ?\WP_Post
    {
        $specific_args = [
            'post_type' => 'orientadores',
            'p' => $id
        ];
        $specific_query = new WP_Query($specific_args);
        $employee = null;
        if ($specific_query->have_posts()) {
            $specific_query->the_post();
            $employee = $specific_query->post;
        }
        wp_reset_postdata();
        return $employee;
    }
}