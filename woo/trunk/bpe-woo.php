<?php
/**
 * Plugin Name: Billing Process Engineering for Woo
 * Description: A plugin to manage WooCommerce checkout fields with additional customization and validation.
 * Version: 2.0
 * Author: SAM Web Design Agency
 * Author URI: https://samwda.ir
 * Text Domain: bpe-woo
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly
add_action( 'init', 'wpdocs_load_textdomain' );

function wpdocs_load_textdomain() {
	load_plugin_textdomain( 'wpdocs_textdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
// Register custom settings page in WooCommerce
function bpefw_register_settings_page() {
    add_menu_page(
        __('BPE Woo Settings', 'bpe-woo'),
        __('BPE Woo Settings', 'bpe-woo'),
        'manage_options',
        'bpe-woo',
        'bpefw_render_settings_page',
        plugin_dir_url(__FILE__) . 'assets/logo.png',
        56
    );
}
add_action('admin_menu', 'bpefw_register_settings_page');

// Render settings page content
function bpefw_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('BPE Woo Settings', 'bpe-woo'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('bpefw_settings_group');
            do_settings_sections('bpe-woo-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register plugin settings
function bpefw_register_settings() {
    $settings = [
        'bpefw_remove_company' => __('Remove Company Field', 'bpe-woo'),
        'bpefw_remove_additional_info' => __('Remove Additional Information Section', 'bpe-woo'),
        'bpefw_remove_address_for_digital' => __('Remove Address Fields for Digital Products', 'bpe-woo'),
        'bpefw_require_mobile' => __('Require Mobile Number for Checkout', 'bpe-woo'),
        'bpefw_optional_email' => __('Make Email Optional', 'bpe-woo'),
        'bpefw_remove_state_for_digital' => __('Remove State/County for Digital Products', 'bpe-woo'),
        'bpefw_use_shortcode_checkout' => __('Use Shortcode for Checkout Page(Required)', 'bpe-woo')
    ];

    foreach ($settings as $option => $label) {
        register_setting('bpefw_settings_group', $option);

        add_settings_section(
            'bpefw_settings_section',
            __('Checkout Field Customizations', 'bpe-woo'),
            null,
            'bpe-woo-settings'
        );

        add_settings_field(
            $option,
            $label,
            'bpefw_checkbox_callback',
            'bpe-woo-settings',
            'bpefw_settings_section',
            ['label_for' => $option]
        );
    }
}
add_action('admin_init', 'bpefw_register_settings');

function bpefw_checkbox_callback($args) {
    $option = get_option($args['label_for'], 'no');
    ?>
    <input type="checkbox" id="<?php echo esc_attr($args['label_for']); ?>" name="<?php echo esc_attr($args['label_for']); ?>" value="yes" <?php checked('yes', $option); ?>>
    <?php
}

// Customize checkout fields based on settings
function bpefw_customize_checkout_fields($fields) {
    if (get_option('bpefw_remove_company', 'no') === 'yes') {
        unset($fields['billing']['billing_company']);
    }

    if (get_option('bpefw_remove_additional_info', 'yes') === 'yes') {
        unset($fields['order']['order_comments']);
    }

    if (get_option('bpefw_remove_address_for_digital', 'no') === 'yes') {
        $has_digital = false;
        foreach (WC()->cart->get_cart_contents() as $item) {
            $product = wc_get_product($item['product_id']);
            if ($product->is_virtual()) {
                $has_digital = true;
                break;
            }
        }
        if ($has_digital) {
            unset($fields['billing']['billing_address_1']);
            unset($fields['billing']['billing_address_2']);
            unset($fields['billing']['billing_city']);
            unset($fields['billing']['billing_postcode']);
            unset($fields['billing']['billing_country']);
        }
    }

    if (get_option('bpefw_remove_state_for_digital', 'yes') === 'yes') {
        $has_digital = false;
        foreach (WC()->cart->get_cart_contents() as $item) {
            $product = wc_get_product($item['product_id']);
            if ($product->is_virtual()) {
                $has_digital = true;
                break;
            }
        }
        if ($has_digital) {
            unset($fields['billing']['billing_state']);
        }
    }

    if (get_option('bpefw_require_mobile', 'yes') === 'yes') {
        $fields['billing']['billing_phone']['required'] = true;
    }

    if (get_option('bpefw_optional_email', 'no') === 'yes') {
        $fields['billing']['billing_email']['required'] = false;
    }

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'bpefw_customize_checkout_fields');

// Convert checkout page to shortcode
function bpefw_convert_checkout_to_shortcode($old_value, $new_value) {
    if (get_option('bpefw_use_shortcode_checkout', 'no') === 'yes') {
        $checkout_page_id = wc_get_page_id('checkout');

        if ($checkout_page_id && get_post_type($checkout_page_id) === 'page') {
            wp_update_post([
                'ID'           => $checkout_page_id,
                'post_content' => '[woocommerce_checkout]',
            ]);
        }
    }
}
add_action('update_option_bpefw_use_shortcode_checkout', 'bpefw_convert_checkout_to_shortcode', 10, 2);
