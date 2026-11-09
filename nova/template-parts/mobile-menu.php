<?php if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$menu_links = array(
array( 'خانه', home_url( '/' ) ),
array( 'فروشگاه', wc_get_page_permalink( 'shop' ) ),
array( 'پرفروش‌ترین‌ها', add_query_arg( 'orderby', 'popularity', wc_get_page_permalink( 'shop' ) ) ),
array( 'مجله نوا', nova_magazine_url() ),
array( is_user_logged_in() ? 'حساب من' : 'ورود و ثبت‌نام', nova_customer_account_url() ),
array( 'سبد خرید', wc_get_cart_url() ),
array( 'تسویه حساب', wc_get_checkout_url() ),
);
$excluded = array_filter( array( get_option( 'page_on_front' ), get_option( 'page_for_posts' ), get_option( 'nova_magazine_page_id' ), wc_get_page_id( 'shop' ), wc_get_page_id( 'myaccount' ), wc_get_page_id( 'cart' ), wc_get_page_id( 'checkout' ) ), function( $id ) {
    return (int) $id > 0;
}
);
$other_pages = wp_list_pages( array( 'title_li' => '', 'echo' => false, 'exclude' => implode( ',', $excluded ), 'post_status' => 'publish' ) );
$categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0 ) );
?>
<dialog id="novaMobileMenu" class="nova-mobile-menu" aria-labelledby="nova-mobile-menu-title">
    <div class="nova-mobile-menu__panel">
        <div class="nova-mobile-menu__head">
            <div>
                <strong id="nova-mobile-menu-title">نــوا</strong>
                <small>هر آنچه نیاز داری، همین‌جاست.</small>
            </div>
            <button type="button" class="nova-mobile-menu__close" aria-label="بستن منو">
                <span class="nova-menu-icon" aria-hidden="true">
                    <span>
                    </span>
                    <span>
                    </span>
                    <span>
                    </span>
                </span>
            </button>
        </div>
        <nav class="nova-mobile-menu__links" aria-label="صفحه‌های سایت">
            <?php foreach ( $menu_links as $link ) : ?><a href="<?php echo esc_url( $link[1] ); ?>"><?php echo esc_html( $link[0] ); ?><span aria-hidden="true">←</span>
            </a><?php endforeach; ?>
        </nav>
        <?php if ( ! is_wp_error( $categories ) && $categories ) : ?><details class="nova-mobile-menu__categories">
            <summary>دسته‌بندی کالاها</summary>
            <ul><?php foreach ( $categories as $category ) : $url = get_term_link( $category );
if ( is_wp_error( $url ) ) {
    continue;
}
?><li>
                    <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $category->name ); ?></a>
                </li><?php endforeach; ?></ul>
        </details><?php endif; ?>
        <?php if ( $other_pages ) : ?><div class="nova-mobile-menu__pages">
            <h2>صفحه‌های بیشتر</h2>
            <ul><?php echo wp_kses_post( $other_pages ); ?></ul>
        </div><?php endif; ?>
        <?php if ( has_nav_menu( 'primary' ) ) : ?><div class="nova-mobile-menu__pages">
            <h2>لینک‌های بیشتر</h2><?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => false ) ); ?></div><?php endif; ?>
        <p class="nova-mobile-menu__note">انتخاب بهتر، خرید دل‌نشین‌تر.</p>
    </div>
</dialog>
