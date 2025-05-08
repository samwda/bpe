<?php
/**
 * Plugin Name: Billing Process Engineering for Woo
 * Description: A plugin to manage WooCommerce checkout fields with additional customization and validation.
 * Version: 1.0
 * Author: Seyyed Ahmadreza Mahjoob
 * Text Domain: bpe-woo
 * Author URI: https://samwda.ir
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins: woocommerce
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Add settings to the WooCommerce admin panel.
function bpefw_add_settings($settings) {
    $settings[] = array(
        'title' => __('Billing Process Settings', 'bpe-woo'),
        'type'  => 'title',
        'id'    => 'bpefw_options',
    );

    $settings[] = array(
        'title'    => __('Enable Validation', 'bpe-woo'),
        'desc'     => __('Enable email or phone validation.', 'bpe-woo'),
        'id'       => 'bpefw_enable_validation',
        'default'  => 'yes',
        'type'     => 'checkbox',
    );

    $settings[] = array(
        'title'    => __('Remove Company Field', 'bpe-woo'),
        'desc'     => __('Remove the company name field from checkout.', 'bpe-woo'),
        'id'       => 'bpefw_remove_company',
        'default'  => 'no',
        'type'     => 'checkbox',
    );

    $settings[] = array(
        'title'    => __('Remove Order Notes', 'bpe-woo'),
        'desc'     => __('Remove the order notes field from checkout.', 'bpe-woo'),
        'id'       => 'bpefw_remove_order_notes',
        'default'  => 'no',
        'type'     => 'checkbox',
    );

    $settings[] = array(
        'type' => 'sectionend',
        'id'   => 'bpefw_options',
    );

    return $settings;
}
add_filter('woocommerce_get_settings_checkout', 'bpefw_add_settings');

// Validate email or phone based on settings.
function bpefw_validate_email_or_phone($posted) {
    $enabled = get_option('bpefw_enable_validation', 'yes');
    if ($enabled === 'yes') {
        $email = isset($_POST['billing_email']) ? sanitize_email($_POST['billing_email']) : '';
        $phone = isset($_POST['billing_phone']) ? sanitize_text_field($_POST['billing_phone']) : '';
        if (empty($email) && empty($phone)) {
            wc_add_notice(
                __('Please provide either an email address or phone number.', 'bpe-woo'),
                'error'
            );
        }
    }
}
add_action('woocommerce_after_checkout_validation', 'bpefw_validate_email_or_phone');

// Customize checkout fields based on settings.
function bpefw_customize_checkout_fields($fields) {
    if (get_option('bpefw_remove_company', 'no') === 'yes') {
        unset($fields['billing']['billing_company']);
    }
    if (get_option('bpefw_remove_order_notes', 'no') === 'yes') {
        unset($fields['order']['order_comments']);
    }
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'bpefw_customize_checkout_fields');

// Conditional logic for checkout fields.
function bpefw_adjust_fields_based_on_cart($fields) {
    $cart_contents = WC()->cart->get_cart_contents();
    $has_digital = false;
    $has_physical = false;

    foreach ($cart_contents as $item) {
        $product = wc_get_product($item['product_id']);
        if ($product->is_virtual()) {
            $has_digital = true;
        } else {
            $has_physical = true;
        }
    }

    // Example: Hide shipping fields if all products are digital.
    if ($has_digital && !$has_physical) {
        unset($fields['shipping']);
    }

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'bpefw_adjust_fields_based_on_cart');
