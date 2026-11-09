<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function nova_ajax_search() {
    check_ajax_referer( 'nova_search', 'nonce' );
    $query = sanitize_text_field( wp_unslash( $_GET['q'] ?? '' ) );
    if ( mb_strlen( $query ) < 2 ) {
        wp_send_json_success( array() );
    }
    $products = wc_get_products( array( 'status' => 'publish', 'limit' => 6, 's' => $query ) );
    $result = array_map( function( $product ) {
        return array( 'name' => $product->get_name(), 'url' => $product->get_permalink(), 'price' => wp_strip_all_tags( $product->get_price_html() ), 'symbol' => nova_symbol( $product ) );
    }
    , $products );
    wp_send_json_success( $result );
}
add_action( 'wp_ajax_nova_search', 'nova_ajax_search' );
add_action( 'wp_ajax_nopriv_nova_search', 'nova_ajax_search' );
