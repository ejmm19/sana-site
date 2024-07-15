<?php

namespace Model;

use WP_Query;

class Product
{
    private function getProducts($posts_per_page = -1): WP_Query
    {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $posts_per_page,
            'order' => 'ASC',
            'orderby' => 'title',
        ];
        return new WP_Query($args);
    }

    public function getProductList($limit = -1): string
    {
        $products = $this->getProducts($limit);
        ob_start();
        echo '<div id="product-list" class="my-5">
                <h2 class="text-center">' . __('Products') . '</h2>
                <div class="row">';
                    if ($products->have_posts()) {
                        while ($products->have_posts()) {
                            $products->the_post(); ?>
                            <div class="col-lg-4 col-sm-6 col-12 product-item">
                                <a href="<?php the_permalink(); ?>">
                                    <figure class="px-1">
                                        <?php the_post_thumbnail("large", ['class' => 'image-hover']); ?>
                                        <div class="middle">
                                            <div class="text"><?= __('View product') ?></div>
                                        </div>
                                    </figure>
                                    <h4 class="my-3 text-center">
                                        <?php the_title(); ?>
                                    </h4>
                                </a>
                            </div>
                            <?php
                        }
                    }
        echo '</div>';
                if ($limit > -1) {
                    echo '<div class="text-center">
                            <a href="'.home_url().'/productos">'.__('View more').'</a>
                        </div>';
                }
        echo '</div>';

        wp_reset_postdata();

        return ob_get_clean();
    }
}