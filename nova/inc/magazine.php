<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function nova_magazine_url() {
    $page_id = (int) get_option( 'nova_magazine_page_id' );
    return $page_id && 'publish' === get_post_status( $page_id ) ? get_permalink( $page_id ) : home_url( '/blog/' );
}
function nova_setup_magazine() {
    if ( '1' === get_option( 'nova_magazine_setup' ) ) {
        return;
    }
    $page = get_page_by_path( 'blog' );
    if ( ! $page ) {
        $id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'مجله نوا', 'post_name' => 'blog' ), true );
        if ( is_wp_error( $id ) || ! $id ) {
            return;
        }
    }
    else {
        $id = $page->ID;
    }
    update_post_meta( $id, '_wp_page_template', 'page-magazine.php' );
    update_option( 'nova_magazine_page_id', $id );
    update_option( 'nova_magazine_setup', '1' );
}
add_action( 'init', 'nova_setup_magazine', 30 );
add_action( 'wp_dashboard_setup', function() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        return;
    }
    wp_add_dashboard_widget( 'nova_magazine_guide', 'مجله نوا — انتشار مقاله', function() {
        echo '<p>از بخش <strong>نوشته‌ها ← افزودن نوشته</strong> مقاله بسازید. عنوان، متن، تصویر شاخص، دسته‌بندی و در صورت تمایل چکیده را وارد کنید.</p>';
        echo '<p>با زدن «انتشار»، مقاله در مجله و تازه‌ترین مطالب صفحهٔ اصلی نمایش داده می‌شود. پیش‌نویس‌ها برای مشتری‌ها نمایش داده نمی‌شوند.</p>';
        echo '<p><a class="button button-primary" href="' . esc_url( admin_url( 'post-new.php' ) ) . '">افزودن مقاله</a> <a class="button" href="' . esc_url( nova_magazine_url() ) . '">مشاهده مجله</a></p>';
    }
    );
}
);
function nova_reading_minutes() {
    $text = wp_strip_all_tags( strip_shortcodes( get_the_content() ) );
    $count = preg_match_all( '/\S+/u', $text );
    return max( 1, (int) ceil( $count / 200 ) );
}
