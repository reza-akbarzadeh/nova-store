<?php get_header(); ?><div class="nova-shell nova-content">
    <h1><?php single_post_title(); ?></h1><?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?><article <?php post_class(); ?>>
        <h2>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2><?php the_excerpt(); ?></article><?php endwhile; the_posts_pagination(); else : ?><p>محتوایی پیدا نشد.</p><?php endif; ?></div><?php get_footer(); ?>
