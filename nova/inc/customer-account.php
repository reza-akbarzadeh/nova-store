<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Allow customer testing on loopback only; preserve production visibility settings.
add_filter( 'woocommerce_coming_soon_exclude', function( $exclude ) {
    $host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
    return in_array( $host, array( 'localhost', '127.0.0.1', '::1', '[::1]' ), true ) ? true : $exclude;
}
);
function nova_customer_account_url() {
    return is_user_logged_in() ? wc_get_account_endpoint_url( 'edit-account' ) : wc_get_page_permalink( 'myaccount' );
}
add_action( 'woocommerce_edit_account_form', function() {
    $phone = get_user_meta( get_current_user_id(), 'billing_phone', true );
    woocommerce_form_field( 'nova_customer_phone', array(
    'type' => 'tel', 'label' => 'شماره تماس', 'class' => array( 'form-row-wide' ),
    'autocomplete' => 'tel', 'custom_attributes' => array( 'dir' => 'ltr' ),
    ), $phone );
    echo '<p class="nova-account-help">آدرس تحویل سفارش را می‌توانید از بخش <a href="' . esc_url( wc_get_account_endpoint_url( 'edit-address' ) ) . '">آدرس‌ها</a> ثبت یا ویرایش کنید.</p>';
}
);
add_action( 'woocommerce_save_account_details_errors', function( $errors ) {
    if ( isset( $_POST['nova_customer_phone'] ) ) {
        $phone = sanitize_text_field( wp_unslash( $_POST['nova_customer_phone'] ) );
        if ( '' !== $phone && ! WC_Validation::is_phone( $phone ) ) {
            $errors->add( 'nova_invalid_phone', 'لطفاً شماره تماس معتبر وارد کنید.' );
        }
    }
}
);
add_action( 'woocommerce_save_account_details', function( $user_id ) {
    if ( isset( $_POST['nova_customer_phone'] ) ) {
        update_user_meta( $user_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['nova_customer_phone'] ) ) );
    }
}
);
// Repair the customer account page once and enable customer registration.
function nova_setup_customer_account() {
    if ( ! class_exists( 'WooCommerce' ) || '1' === get_option( 'nova_customer_account_setup' ) ) {
        return;
    }
    $account = get_post( (int) get_option( 'woocommerce_myaccount_page_id' ) );
    if ( ! $account || 'page' !== $account->post_type || 'publish' !== $account->post_status ) {
        $account = get_page_by_path( 'my-account' );
        if ( ! $account || 'publish' !== $account->post_status ) {
            $page_id = wp_insert_post( array(
            'post_type' => 'page', 'post_status' => 'publish',
            'post_title' => 'حساب کاربری', 'post_name' => 'my-account',
            'post_content' => '[woocommerce_my_account]',
            ), true );
            if ( is_wp_error( $page_id ) || ! $page_id ) {
                return;
            }
            $account = get_post( $page_id );
        }
        update_option( 'woocommerce_myaccount_page_id', $account->ID );
    }
    if ( ! has_shortcode( $account->post_content, 'woocommerce_my_account' ) ) {
        $updated = wp_update_post( array( 'ID' => $account->ID, 'post_content' => $account->post_content . "\n[woocommerce_my_account]" ), true );
        if ( is_wp_error( $updated ) ) {
            return;
        }
    }
    update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
    update_option( 'woocommerce_registration_generate_password', 'no' );
    update_option( 'nova_customer_account_setup', '1' );
}
add_action( 'init', 'nova_setup_customer_account', 30 );
