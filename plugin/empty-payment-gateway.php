<?php
/**
 * Plugin Name: Empty Payment Gateway
 * Description: A test WooCommerce payment gateway that does nothing.
 * Version: 1.0
 * Author: Your Name
 */

defined('ABSPATH') || exit;

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}

// Include the gateway class file
function empty_payment_gateway_init() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-empty-payment-gateway.php';
}
add_action('plugins_loaded', 'empty_payment_gateway_init', 11);

// Register the payment gateway with WooCommerce
function add_empty_payment_gateway($gateways) {
    $gateways[] = 'Empty_Payment_Gateway';
    return $gateways;
}
add_filter('woocommerce_payment_gateways', 'add_empty_payment_gateway');
