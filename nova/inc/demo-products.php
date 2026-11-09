<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function nova_seed_products() {
    if ( ! class_exists( 'WooCommerce' ) || get_option( 'nova_seeded' ) ) {
        return;
    }
    $items = array(
    array( 'هدفون بی‌سیم آوا پرو', 'دیجیتال', 4100000, 3290000, 12, '🎧', 'حذف نویز فعال، شارژدهی ۳۲ ساعته و بلوتوث نسخه ۵.۳' ),
    array( 'ساعت هوشمند مینیمال', 'دیجیتال', 4990000, 4190000, 8, '⌚', 'نمایشگر AMOLED، پایش ضربان قلب و مقاومت در برابر آب' ),
    array( 'اسپیکر همراه موج', 'دیجیتال', 2800000, 2350000, 19, '🔊', 'توان خروجی ۳۰ وات و باتری ۱۸ ساعته' ),
    array( 'پاوربانک سریع ۲۰۰۰۰', 'دیجیتال', 2100000, 1680000, 25, '🔋', 'شارژ سریع ۲۲.۵ وات و نمایشگر شارژ' ),
    array( 'ماگ سرامیکی رُخ', 'خانه', 650000, 480000, 34, '☕', 'ساخت دست، حجم ۳۵۰ میلی‌لیتر' ),
    array( 'گلدان سفالی خاک', 'خانه', 950000, 760000, 9, '🏺', 'سفال دست‌ساز با لعاب مات' ),
    array( 'چراغ رومیزی آرامیس', 'خانه', 2400000, 1890000, 14, '💡', 'نور قابل تنظیم و بدنه فلزی' ),
    array( 'ست پذیرایی مینیمال', 'خانه', 2950000, 2150000, 6, '🍽️', '۱۲ پارچه سرامیک درجه یک' ),
    array( 'بطری دوجداره روز', 'سبک زندگی', 1150000, 890000, 28, '🧴', 'حفظ دما تا ۱۲ ساعت و بدون نشتی' ),
    array( 'کوله روزمره نوا', 'سبک زندگی', 2250000, 1750000, 17, '🎒', 'محفظه لپ‌تاپ و پارچه ضدآب' ),
    array( 'عینک آفتابی کلاسیک', 'سبک زندگی', 1450000, 980000, 21, '🕶️', 'فیلتر UV400 و فریم سبک' ),
    array( 'کفش پیاده‌روی سبک', 'سبک زندگی', 3200000, 2650000, 15, '👟', 'زیره EVA و کفی طبی' ),
    );
    foreach ( $items as $item ) {
        $term = term_exists( $item[1], 'product_cat' );
        if ( ! $term ) {
            $term = wp_insert_term( $item[1], 'product_cat' );
        }
        $product = new WC_Product_Simple();
        $product->set_name( $item[0] );
        $product->set_status( 'publish' );
        $product->set_regular_price( $item[2] );
        $product->set_sale_price( $item[3] );
        $product->set_manage_stock( true );
        $product->set_stock_quantity( $item[4] );
        $product->set_description( $item[6] );
        $product->set_short_description( $item[6] );
        if ( ! is_wp_error( $term ) ) {
            $product->set_category_ids( array( (int) ( is_array( $term ) ? $term['term_id'] : $term ) ) );
        }
        $id = $product->save();
        update_post_meta( $id, '_nova_symbol', $item[5] );
    }
    update_option( 'nova_seeded', 1 );
}
add_action( 'admin_init', 'nova_seed_products' );
