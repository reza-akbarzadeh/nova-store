<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
define( 'NOVA_VERSION', '1.4.1' );
foreach ( array( 'setup', 'woocommerce', 'search', 'customer-account', 'magazine', 'magazine-admin', 'demo-products' ) as $module ) {
    require_once get_template_directory() . '/inc/' . $module . '.php';
}
