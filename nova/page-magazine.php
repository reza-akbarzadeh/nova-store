<?php
/* Template Name: مجله نوا */
get_header();
$search = isset( $_GET['magazine_search'] ) && is_string( $_GET['magazine_search'] ) ? sanitize_text_field( wp_unslash( $_GET['magazine_search'] ) ) : '';
$category = isset( $_GET['magazine_category'] ) && is_scalar( $_GET['magazine_category'] ) ? absint( $_GET['magazine_category'] ) : 0;
$current = isset( $_GET['magazine_page'] ) && is_scalar( $_GET['magazine_page'] ) ? max( 1, absint( $_GET['magazine_page'] ) ) : 1;
$articles = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 9, 'paged' => $current, 's' => $search, 'cat' => $category, 'ignore_sticky_posts' => true ) );
?>
<div class="nova-shell nova-magazine">
    <section class="nova-magazine-hero">
        <div>
            <span class="nova-magazine-kicker">مجلهٔ سبک زندگی و راهنمای خرید</span>
            <h1>انتخاب بهتر،<br>
                <em>زندگی دل‌نشین‌تر.</em>
            </h1>
            <p>ایده‌های تازه، راهنمای خرید و نکته‌هایی برای استفادهٔ بهتر از چیزهایی که دوستشان داریم.</p>
        </div>
        <div class="nova-magazine-art" aria-hidden="true">
            <span>نــوا</span>
            <small>کمی بیشتر بدانیم.</small>
        </div>
    </section>
    <?php if ( is_page() ) : while ( have_posts() ) : the_post(); if ( trim( get_the_content() ) ) : ?><div class="nova-magazine-intro"><?php the_content(); ?></div><?php endif; endwhile; endif; ?>
    <section aria-labelledby="nova-articles-heading">
        <div class="nova-magazine-toolbar">
            <div>
                <span class="nova-magazine-kicker">برای خواندن وقت بگذار</span>
                <h2 id="nova-articles-heading">تازه‌های مجله</h2>
            </div>
            <form class="nova-magazine-search" method="get" action="<?php echo esc_url( nova_magazine_url() ); ?>"><?php if ( ! get_option( 'permalink_structure' ) ) : ?><input type="hidden" name="page_id" value="<?php echo esc_attr( get_option( 'nova_magazine_page_id' ) ); ?>"><?php endif; ?><label class="screen-reader-text" for="magazine-search">جستجو در مقاله‌ها</label>
                <input id="magazine-search" type="search" name="magazine_search" value="<?php echo esc_attr( $search ); ?>" placeholder="دنبال چه موضوعی می‌گردی؟"><?php if ( $category ) : ?><input type="hidden" name="magazine_category" value="<?php echo esc_attr( $category ); ?>"><?php endif; ?><button type="submit">جستجو</button>
            </form>
        </div>
        <nav class="nova-magazine-filters" aria-label="موضوع مقاله‌ها">
            <a href="<?php echo esc_url( nova_magazine_url() ); ?>"<?php if ( ! $category && ! $search ) {
    echo ' aria-current="page"';
}
?>>همهٔ مطالب</a><?php foreach ( get_categories( array( 'hide_empty' => true ) ) as $topic ) : ?><a href="<?php echo esc_url( add_query_arg( 'magazine_category', $topic->term_id, nova_magazine_url() ) ); ?>"<?php if ( $category === $topic->term_id ) {
    echo ' aria-current="page"';
}
?>><?php echo esc_html( $topic->name ); ?></a><?php endforeach; ?></nav>
        <?php if ( $articles->have_posts() ) : ?><div class="nova-magazine-grid"><?php while ( $articles->have_posts() ) : $articles->the_post(); get_template_part( 'template-parts/magazine-card' ); endwhile; ?></div>
        <nav class="nova-magazine-pagination" aria-label="صفحه‌های مقاله‌ها"><?php echo wp_kses_post( paginate_links( array( 'base' => str_replace( '999999999', '%#%', add_query_arg( 'magazine_page', 999999999 ) ), 'format' => '', 'current' => $current, 'total' => $articles->max_num_pages, 'prev_text' => '→ قبلی', 'next_text' => 'بعدی ←' ) ) );
?></nav>
        <?php else : ?><div class="nova-magazine-empty">
            <h3><?php echo $search || $category ? 'مقاله‌ای با این مشخصات پیدا نشد.' : 'به‌زودی با خواندنی‌های تازه برمی‌گردیم.'; ?></h3>
            <p><?php echo $search || $category ? 'موضوع یا عبارت دیگری را امتحان کنید.' : 'راهنماها و ایده‌های تازهٔ نوا در این بخش منتشر می‌شوند.'; ?></p><?php if ( current_user_can( 'edit_posts' ) ) : ?><a class="nova-story-more" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>">نوشتن اولین مقاله ←</a><?php endif; ?></div><?php endif; wp_reset_postdata(); ?>
    </section>
</div>
<?php get_footer(); ?>
