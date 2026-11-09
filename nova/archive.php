<?php get_header(); ?>
<div class="nova-shell nova-magazine">
    <header class="nova-magazine-toolbar">
        <div>
            <a class="nova-story-more" href="<?php echo esc_url( nova_magazine_url() ); ?>">مجله نوا ←</a>
            <h1><?php the_archive_title(); ?></h1><?php the_archive_description(); ?></div>
    </header>
    <?php if ( have_posts() ) : ?><div class="nova-magazine-grid"><?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/magazine-card' ); endwhile; ?></div>
    <div class="nova-magazine-pagination"><?php the_posts_pagination( array( 'prev_text' => '→ قبلی', 'next_text' => 'بعدی ←' ) ); ?></div><?php else : ?><p>هنوز مطلبی در این بخش منتشر نشده است.</p><?php endif; ?></div>
<?php get_footer(); ?>
