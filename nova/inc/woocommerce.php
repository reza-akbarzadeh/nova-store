<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function nova_cart_count() {
    return function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}
function nova_cart_fragment( $fragments ) {
    ob_start();
    ?><span class="nova-cart-count"><?php echo esc_html( nova_cart_count() ); ?></span><?php
    $fragments['.nova-cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'nova_cart_fragment' );
function nova_loop_thumbnail() {
    global $product;
    if ( $product && has_post_thumbnail( $product->get_id() ) ) {
        echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ) );
    }
    else {
        echo '<span class="nova-product-symbol nova-shop-symbol">' . esc_html( nova_symbol( $product ) ) . '</span>';
    }
}
add_action( 'wp', function() {
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
    add_action( 'woocommerce_before_shop_loop_item_title', 'nova_loop_thumbnail', 10 );
}
);
function nova_symbol( $product = null ) {
    if ( ! $product ) {
        global $product;
    }
    $symbol = $product ? $product->get_meta( '_nova_symbol' ) : '';
    return $symbol ?: '📦';
}
function nova_product_card( $product ) {
    if ( ! $product || ! $product->is_visible() ) {
        return;
    }
    $discount = 0;
    if ( $product->is_on_sale() && $product->get_regular_price() ) {
        $discount = round( ( 1 - (float) $product->get_sale_price() / (float) $product->get_regular_price() ) * 100 );
    }
    ?>
<article class="nova-product-card">
    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="nova-product-card__link">
        <?php if ( has_post_thumbnail( $product->get_id() ) ) : ?>
        <?php echo $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ); ?>
        <?php else : ?><span class="nova-product-symbol"><?php echo esc_html( nova_symbol( $product ) ); ?></span><?php endif; ?>
        <h3><?php echo esc_html( $product->get_name() ); ?></h3>
        <div class="nova-product-meta">
            <span>موجود در انبار</span>
            <b>★ <?php echo esc_html( number_format_i18n( (float) $product->get_average_rating(), 1 ) ); ?></b>
        </div>
        <div class="nova-product-price"><?php if ( $discount ) : ?><span class="nova-discount-badge"><?php echo esc_html( $discount ); ?>٪</span><?php endif; ?><strong><?php echo wp_kses_post( $product->get_price_html() ); ?></strong>
        </div>
    </a>
    <?php echo wp_kses_post( apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf( '<a href="%s" data-quantity="1" class="button add_to_cart_button ajax_add_to_cart" data-product_id="%d" rel="nofollow">افزودن به سبد</a>', esc_url( $product->add_to_cart_url() ), $product->get_id() ), $product ) );
    ?>
</article>
<?php
}
add_filter( 'loop_shop_columns', function() {
    return 4;
}
);
add_filter( 'loop_shop_per_page', function() {
    return 12;
}
, 20 );
add_filter( 'woocommerce_currency_symbol', function( $symbol, $currency ) {
    return 'IRT' === $currency ? 'تومان' : $symbol;
}
, 10, 2 );
