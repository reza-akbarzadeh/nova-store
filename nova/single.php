<?php get_header(); ?>
<div class="nova-shell nova-magazine">
    <?php while ( have_posts() ) : the_post(); ?>
    <nav class="nova-article-breadcrumb" aria-label="مسیر مقاله">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a>
        <span>/</span>
        <a href="<?php echo esc_url( nova_magazine_url() ); ?>">مجله نوا</a>
        <span>/</span>
        <span><?php the_title(); ?></span>
    </nav>
    <article <?php post_class( 'nova-article' ); ?>>
        <header class="nova-article-header">
            <div class="nova-magazine-filters"><?php the_category( ' ' ); ?></div>
            <h1><?php the_title(); ?></h1>
            <div class="nova-story-meta">
                <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                <span><?php echo esc_html( number_format_i18n( nova_reading_minutes() ) ); ?> دقیقه مطالعه</span>
            </div>
        </header>
        <?php if ( has_post_thumbnail() ) : ?><div class="nova-article-cover"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?>
        <div class="nova-article-content"><?php the_content(); wp_link_pages(); ?></div>
        <?php if ( has_tag() ) : ?><footer class="nova-article-tags"><?php the_tags( 'موضوعات: ', '، ' ); ?></footer><?php endif; ?>
    </article>
    <?php $related = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ), 'category__in' => wp_get_post_categories( get_the_ID() ), 'ignore_sticky_posts' => true ) );
if ( $related->have_posts() ) : ?>
    <section class="nova-related-stories">
        <div class="nova-section-head">
            <h2>بیشتر بخوانید</h2>
            <a href="<?php echo esc_url( nova_magazine_url() ); ?>">همهٔ مقاله‌ها ←</a>
        </div>
        <div class="nova-magazine-grid"><?php while ( $related->have_posts() ) : $related->the_post(); get_template_part( 'template-parts/magazine-card' ); endwhile; ?></div>
    </section><?php endif; wp_reset_postdata(); ?>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>
