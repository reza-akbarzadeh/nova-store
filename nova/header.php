<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <script>
            try {
                document.documentElement.dataset.theme = localStorage.getItem('nova-theme') === 'dark' ? 'dark' : 'light';
            } catch (error) {
                document.documentElement.dataset.theme = 'light';
            }
        </script>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>><?php wp_body_open(); ?>
        <a class="skip-link screen-reader-text" href="#main">رفتن به محتوای اصلی</a>
        <div class="campaign-bar">
            <strong>نوا پلاس</strong>
            <span>تا ۵۰٪ تخفیف برای خریدهای امروز</span>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>?orderby=popularity">مشاهده پیشنهادها ←</a>
        </div>
        <header class="nova-header">
            <div class="nova-header__main nova-shell">
                <a class="nova-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <span>نــوا</span>
                    <small>فروشگاه اینترنتی</small>
                </a>
                <div class="nova-search-wrap">
                    <form class="nova-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <span>⌕</span>
                        <input id="novaSearch" type="search" name="s" placeholder="جستجو در نوا" autocomplete="off">
                        <input type="hidden" name="post_type" value="product">
                    </form>
                    <div class="nova-search-results" id="novaSearchResults" hidden>
                    </div>
                </div>
                <div class="nova-header__actions">
                    <button class="nova-theme-toggle" id="novaThemeToggle" type="button" role="switch" aria-checked="false" aria-label="فعال کردن حالت تیره" title="تغییر حالت روشن و تیره">
                        <span class="nova-theme-toggle__sun" aria-hidden="true">☀</span>
                        <span class="nova-theme-toggle__moon" aria-hidden="true">☾</span>
                        <span class="nova-theme-toggle__thumb" aria-hidden="true">
                        </span>
                    </button>
                    <a class="nova-login" href="<?php echo esc_url( nova_customer_account_url() ); ?>">👤 <?php echo is_user_logged_in() ? 'حساب من' : 'ورود | ثبت‌نام'; ?></a>
                    <a class="nova-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">🛒<span class="nova-cart-count"><?php echo esc_html( nova_cart_count() ); ?></span>
                    </a>
                    <button class="nova-menu-toggle" id="novaMenuToggle" type="button" aria-label="باز کردن منوی صفحه‌ها" aria-controls="novaMobileMenu" aria-expanded="false" aria-haspopup="dialog">
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
            </div>
            <div class="nova-header__nav nova-shell">
                <button class="nova-categories-toggle" id="novaCategoryToggle">☰ دسته‌بندی کالاها</button>
                <a class="nova-home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php if ( is_front_page() ) {
    echo ' aria-current="page"';
}
?>>خانه</a><?php if ( has_nav_menu( 'primary' ) ) {
    wp_nav_menu( array( 'theme_location'=>'primary', 'container'=>'nav', 'container_class'=>'nova-nav', 'fallback_cb'=>false ) );
}
else {
    ?><nav class="nova-nav">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">فروشگاه</a>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>?orderby=popularity">پرفروش‌ترین‌ها</a>
                    <a href="<?php echo esc_url( nova_magazine_url() ); ?>">مجله نوا</a>
                </nav><?php
}
?><span class="nova-location">⌖ ارسال به سراسر ایران</span>
            </div>
            <div class="nova-mega-menu" id="novaMegaMenu">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>?product_cat=دیجیتال">⌚ کالای دیجیتال</a>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>?product_cat=خانه">☕ خانه و آشپزخانه</a>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>?product_cat=سبک-زندگی">◇ سبک زندگی</a>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">✦ همه محصولات</a>
            </div>
        </header><?php get_template_part( 'template-parts/mobile-menu' ); ?><main id="main" class="site-main">
