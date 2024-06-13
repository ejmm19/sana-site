<?php

/** Shortcodes **/
function productsList($attr = ['limit' => -1]): string
{
    $products = new \Model\Product();
    return $products->getProductList(!empty($attr) ? $attr['limit'] : '');
}

add_shortcode('products_list', 'productsList');

function orientadorList($attr = ['limit' => -1]): string
{
    $orientadores = new \Model\Orientadores();
    return $orientadores->getOrientadorList(!empty($attr) ? $attr['limit'] : '');
}

add_shortcode('orientador_list', 'orientadorList');
/** Shortcodes **/