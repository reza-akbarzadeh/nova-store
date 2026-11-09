<?php if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<article <?php post_class( 'nova-story-card' ); ?>>
    <a class="nova-story-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
        <?php if ( has_post_thumbnail() ) {
    the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'alt' => '' ) );
}
else {
    ?><span class="nova-story-placeholder">نــوا<span>بخوان، انتخاب کن، بهتر زندگی کن</span>
        </span><?php
}
?>
    </a>
    <div class="nova-story-card__body">
        <?php $categories = get_the_category(); if ( $categories ) : ?><a class="nova-story-category" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a><?php endif; ?>
        <h3>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="nova-story-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 23 ) ); ?></p>
        <div class="nova-story-meta">
            <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
            <span><?php echo esc_html( number_format_i18n( nova_reading_minutes() ) ); ?> دقیقه مطالعه</span>
        </div>
        <a class="nova-story-more" href="<?php the_permalink(); ?>">خواندن مقاله <span aria-hidden="true">←</span>
        </a>
    </div>
</article>
