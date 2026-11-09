<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function nova_setup() {
    load_theme_textdomain( 'nova', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'woocommerce', array( 'thumbnail_image_width' => 420, 'single_image_width' => 720, 'product_grid' => array( 'default_rows' => 3, 'min_rows' => 1, 'default_columns' => 4, 'min_columns' => 2, 'max_columns' => 5 ) ) );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    register_nav_menus( array( 'primary' => 'منوی اصلی', 'footer' => 'منوی فوتر' ) );
}
add_action( 'after_setup_theme', 'nova_setup' );
function nova_assets() {
    wp_enqueue_style( 'nova-font', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800;900&display=swap', array(), null );
    wp_enqueue_style( 'nova-core', get_template_directory_uri() . '/assets/css/core.css', array(), NOVA_VERSION );
    wp_enqueue_style( 'nova-woo', get_template_directory_uri() . '/assets/css/woocommerce.css', array( 'nova-core' ), NOVA_VERSION );
    wp_enqueue_style( 'nova-magazine', get_template_directory_uri() . '/assets/css/magazine.css', array( 'nova-core' ), NOVA_VERSION );
    wp_enqueue_style( 'nova-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array( 'nova-core', 'nova-woo', 'nova-magazine' ), NOVA_VERSION );
    wp_enqueue_style( 'nova-dark', get_template_directory_uri() . '/assets/css/dark.css', array( 'nova-responsive' ), NOVA_VERSION );
    wp_enqueue_script( 'nova-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), NOVA_VERSION, true );
    wp_localize_script( 'nova-theme', 'novaData', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'nova_search' ), 'cartUrl' => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' ) ) );
}
add_action( 'wp_enqueue_scripts', 'nova_assets' );
function nova_widgets() {
    register_sidebar( array( 'name' => 'فیلتر فروشگاه', 'id' => 'shop-sidebar', 'before_widget' => '<section class="nova-widget">', 'after_widget' => '</section>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );
}
add_action( 'widgets_init', 'nova_widgets' );
