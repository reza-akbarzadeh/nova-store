<?php
get_header();
$shop_url = wc_get_page_permalink( 'shop' );
$sale_products = class_exists( 'WooCommerce' ) ? wc_get_products( array( 'status'=>'publish', 'limit'=>8, 'orderby'=>'date', 'order'=>'DESC' ) ) : array();
$popular_products = class_exists( 'WooCommerce' ) ? wc_get_products( array( 'status'=>'publish', 'limit'=>8, 'orderby'=>'popularity', 'order'=>'DESC' ) ) : array();
?>
<section class="nova-stories nova-shell" aria-label="پیشنهادهای روز">
    <?php foreach ( array( array('٪','تخفیف امروز'),array('⌚','دیجیتال تازه'),array('☕','خانه زیبا'),array('♧','انتخاب سبز'),array('✦','پرفروش‌ها'),array('🎁','هدیه نوا'),array('⚡','ارسال فوری'),array('✓','اصل و معتبر') ) as $story ) : ?>
    <a href="<?php echo esc_url( $shop_url ); ?>">
        <span><?php echo esc_html( $story[0] ); ?></span>
        <b><?php echo esc_html( $story[1] ); ?></b>
    </a>
    <?php endforeach; ?>
</section>

<section class="nova-hero nova-shell">
    <div class="nova-hero__copy">
        <span>جشنواره آخر هفته</span>
        <p>فرصت کوتاه، انتخاب‌های بزرگ</p>
        <h1>از نو<br>
            <em>زندگی کن!</em>
        </h1>
        <a href="<?php echo esc_url( $shop_url ); ?>?orderby=popularity">خرید با تخفیف ←</a>
    </div>
    <div class="nova-hero__art" aria-hidden="true">
        <i class="nova-phone">
        </i>
        <i class="nova-headphone">
        </i>
        <i class="nova-box">
        </i>
        <strong>تا<br>۵۰٪<br>
            <small>تخفیف</small>
        </strong>
    </div>
</section>

<section class="nova-services nova-shell">
    <a href="<?php echo esc_url( $shop_url ); ?>">
        <span>⚡</span>
        <b>ارسال فوری</b>
        <small>تحویل امروز</small>
    </a>
    <a href="<?php echo esc_url( $shop_url ); ?>">
        <span>🛍</span>
        <b>سوپرمارکت</b>
        <small>تازه و سریع</small>
    </a>
    <a href="<?php echo esc_url( $shop_url ); ?>">
        <span>٪</span>
        <b>حراج استایل</b>
        <small>تا ۷۰٪ تخفیف</small>
    </a>
    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
        <span>✦</span>
        <b>نوا پلاس</b>
        <small>خدمات ویژه</small>
    </a>
    <a href="<?php echo esc_url( $shop_url ); ?>">
        <span>🎁</span>
        <b>کارت هدیه</b>
        <small>برای عزیزان</small>
    </a>
    <a href="#footer">
        <span>♻</span>
        <b>نوا سرویس</b>
        <small>خدمات پس از فروش</small>
    </a>
</section>

<section class="nova-amazing nova-shell">
    <div class="nova-amazing__title">
        <p>پیشنهاد</p>
        <h2>شگفت‌انگیز</h2>
        <span>🎁</span>
        <a href="<?php echo esc_url( $shop_url ); ?>">مشاهده همه ←</a>
    </div>
    <div class="nova-products-track"><?php foreach ( $sale_products as $product ) {
    nova_product_card( $product );
}
?></div>
</section>

<section class="nova-fresh nova-shell">
    <div>
        <span>🛒</span>
        <strong>نوا فِرِش</strong>
        <b>تا ۴۰٪ تخفیف</b>
    </div>
    <p>🍎　🥛　🍪　☕　🥜　🧃</p>
    <a href="<?php echo esc_url( $shop_url ); ?>">بیش از ۱۰۰ کالا ←</a>
</section>

<section class="nova-ads nova-shell">
    <a href="<?php echo esc_url( $shop_url ); ?>?product_cat=خانه" class="coral">
        <small>خانه نو</small>
        <strong>رنگ تازه<br>برای خانه</strong>
        <span>تا ۴۰٪ تخفیف ←</span>
        <i>🏺</i>
    </a>
    <a href="<?php echo esc_url( $shop_url ); ?>?product_cat=دیجیتال" class="mint">
        <small>تکنولوژی روز</small>
        <strong>دنیایت را<br>هوشمند کن</strong>
        <span>خرید دیجیتال ←</span>
        <i>📱</i>
    </a>
    <a href="<?php echo esc_url( $shop_url ); ?>?product_cat=سبک-زندگی" class="purple">
        <small>استایل تو</small>
        <strong>ساده ولی<br>متفاوت</strong>
        <span>مشاهده کالکشن ←</span>
        <i>🎒</i>
    </a>
    <a href="<?php echo esc_url( $shop_url ); ?>" class="amber">
        <small>حال خوب</small>
        <strong>برای لحظه‌های<br>آرام</strong>
        <span>انتخاب کن ←</span>
        <i>☕</i>
    </a>
</section>

<section class="nova-section nova-shell">
    <h2>خرید بر اساس دسته‌بندی</h2>
    <div class="nova-category-grid"><?php foreach ( array( array('⌚','کالای دیجیتال','دیجیتال'),array('☕','خانه و آشپزخانه','خانه'),array('◇','مد و سبک زندگی','سبک-زندگی'),array('✦','زیبایی و سلامت',''),array('◉','ورزش و سفر',''),array('♧','بومی و محلی',''),array('☆','کودک و نوزاد',''),array('▤','کتاب و هنر','') ) as $cat ) : ?><a href="<?php echo esc_url( add_query_arg( 'product_cat', $cat[2], $shop_url ) ); ?>">
            <span><?php echo esc_html( $cat[0] ); ?></span>
            <b><?php echo esc_html( $cat[1] ); ?></b>
        </a><?php endforeach; ?></div>
</section>

<section class="nova-brands nova-shell">
    <h2>★ محبوب‌ترین برندها</h2>
    <div>
        <span>سامسونگ</span>
        <span>Apple</span>
        <span>SONY</span>
        <span>شیائومی</span>
        <span>adidas</span>
        <span>Philips</span>
        <span>Nestlé</span>
    </div>
</section>

<section class="nova-popular nova-shell">
    <div class="nova-section-head">
        <h2>🔥 پرفروش‌ترین کالاها</h2>
        <a href="<?php echo esc_url( $shop_url ); ?>?orderby=popularity">مشاهده همه</a>
    </div>
    <div class="nova-products-grid"><?php foreach ( $popular_products as $product ) {
    nova_product_card( $product );
}
?></div>
</section>

<section class="nova-home-journal nova-shell" aria-labelledby="nova-home-journal-title">
    <div class="nova-section-head">
        <div>
            <span class="nova-magazine-kicker">ایده‌هایی برای انتخاب بهتر</span>
            <h2 id="nova-home-journal-title">خواندنی‌های مجله نوا</h2>
        </div>
        <a href="<?php echo esc_url( nova_magazine_url() ); ?>">همهٔ مقاله‌ها ←</a>
    </div>
    <?php $journal = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 4, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) ); ?>
    <?php if ( $journal->have_posts() ) : ?><div class="nova-magazine-grid nova-magazine-grid--home"><?php while ( $journal->have_posts() ) : $journal->the_post(); get_template_part( 'template-parts/magazine-card' ); endwhile; ?></div>
    <?php else : ?><div class="nova-magazine-empty">
        <h3>خواندنی‌های تازه در راه‌اند.</h3>
        <p>راهنمای خرید و ایده‌های زندگی بهتر را در مجله نوا بخوانید.</p>
        <a class="nova-story-more" href="<?php echo esc_url( nova_magazine_url() ); ?>">رفتن به مجله ←</a><?php if ( current_user_can( 'edit_posts' ) ) : ?><p>
            <a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>">افزودن اولین مقاله</a>
        </p><?php endif; ?></div><?php endif; wp_reset_postdata(); ?>
</section>
<?php get_footer(); ?>
