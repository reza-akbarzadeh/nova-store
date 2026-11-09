<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Rename only the original untranslated default, preserving custom categories.
add_action( 'init', function() {
    $id = (int) get_option( 'default_category' );
    $term = get_term( $id, 'category' );
    if ( $term && ! is_wp_error( $term ) && 'uncategorized' === strtolower( trim( $term->name ) ) ) {
        wp_update_term( $id, 'category', array( 'name' => 'عمومی' ) );
    }
}
, 35 );
add_action( 'add_meta_boxes_post', function() {
    add_meta_box( 'nova-magazine-settings', 'مجله نوا — عکس و دستهٔ مقاله', 'nova_magazine_settings_box', 'post', 'normal', 'high' );
}
);
function nova_magazine_settings_box( $post ) {
    wp_nonce_field( 'nova_magazine_settings', 'nova_magazine_nonce' );
    $image_id = get_post_thumbnail_id( $post );
    $categories = wp_get_post_categories( $post->ID );
    $selected = $categories ? reset( $categories ) : get_option( 'default_category' );
    ?>
<p>عکس این بخش روی کارت مجله، صفحهٔ اصلی و بالای مقاله نمایش داده می‌شود.</p>
<div id="nova-article-image-preview"><?php if ( $image_id ) {
        echo wp_get_attachment_image( $image_id, 'medium', false, array( 'style' => 'max-width:100%;height:auto;border-radius:10px' ) );
    }
    ?></div>
<input type="hidden" id="nova-article-image" name="nova_article_image" value="<?php echo esc_attr( $image_id ); ?>">
<input type="hidden" id="nova-article-image-changed" name="nova_article_image_changed" value="0">
<p>
    <button type="button" class="button button-primary" id="nova-article-image-select">انتخاب یا بارگذاری عکس مقاله</button> <button type="button" class="button" id="nova-article-image-remove">حذف عکس</button>
</p>
<p>
    <label for="nova-article-category">
        <strong>دستهٔ مقاله</strong>
    </label>
</p>
<?php wp_dropdown_categories( array( 'taxonomy' => 'category', 'hide_empty' => false, 'name' => 'nova_article_category', 'id' => 'nova-article-category', 'selected' => $selected, 'hierarchical' => true ) ); ?>
<input type="hidden" name="nova_article_original_category" value="<?php echo esc_attr( $selected ); ?>">
<?php if ( current_user_can( 'manage_categories' ) ) : ?><p>
    <label for="nova-new-category">یا دستهٔ جدید بسازید</label>
    <br>
    <input id="nova-new-category" name="nova_new_category" type="text" class="widefat" placeholder="مثلاً راهنمای خرید، تکنولوژی، سبک زندگی">
</p><?php endif; ?>
<p class="description">برای ذخیرهٔ عکس و دسته، دکمهٔ «انتشار» یا «به‌روزرسانی» مقاله را بزنید. انتخاب چند دسته هم از بخش «دسته‌ها» در تنظیمات نوشته امکان‌پذیر است.</p>
<?php
}
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        return;
    }
    $screen = get_current_screen();
    if ( ! $screen || 'post' !== $screen->post_type ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script( 'nova-magazine-admin', get_template_directory_uri() . '/assets/js/magazine-admin.js', array( 'media-editor' ), NOVA_VERSION, true );
}
);
add_action( 'save_post_post', function( $post_id ) {
    if ( ! isset( $_POST['nova_magazine_nonce'] ) || ! is_string( $_POST['nova_magazine_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nova_magazine_nonce'] ) ), 'nova_magazine_settings' ) ) {
        return;
    }
    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['nova_article_image_changed'] ) && '1' === $_POST['nova_article_image_changed'] && current_user_can( 'upload_files' ) ) {
        $image_id = isset( $_POST['nova_article_image'] ) && is_scalar( $_POST['nova_article_image'] ) ? absint( $_POST['nova_article_image'] ) : 0;
        if ( ! $image_id ) {
            delete_post_thumbnail( $post_id );
        }
        elseif ( wp_attachment_is_image( $image_id ) && current_user_can( 'edit_post', $image_id ) ) {
            set_post_thumbnail( $post_id, $image_id );
        }
    }
    $taxonomy = get_taxonomy( 'category' );
    if ( ! current_user_can( $taxonomy->cap->assign_terms ) ) {
        return;
    }
    $selected = isset( $_POST['nova_article_category'] ) && is_scalar( $_POST['nova_article_category'] ) ? absint( $_POST['nova_article_category'] ) : 0;
    $original = isset( $_POST['nova_article_original_category'] ) && is_scalar( $_POST['nova_article_original_category'] ) ? absint( $_POST['nova_article_original_category'] ) : 0;
    $new_name = isset( $_POST['nova_new_category'] ) && is_string( $_POST['nova_new_category'] ) ? sanitize_text_field( wp_unslash( $_POST['nova_new_category'] ) ) : '';
    if ( $new_name && current_user_can( 'manage_categories' ) ) {
        $term = term_exists( $new_name, 'category' );
        if ( ! $term ) {
            $term = wp_insert_term( $new_name, 'category' );
        }
        if ( ! is_wp_error( $term ) && $term ) {
            $selected = (int) ( is_array( $term ) ? $term['term_id'] : $term );
        }
    }
    if ( $selected && $selected !== $original && term_exists( $selected, 'category' ) ) {
        wp_set_post_categories( $post_id, array( $selected ) );
    }
}
);
