<?php
/**
 * Plugin Name: Auto-Clear Cart After Inactivity for WooCommerce
 * Description: Enhance your WooCommerce store by automatically clearing the cart after a period of user inactivity. Includes customizable admin settings for better user experience.
 * Version: 1.0.0
 * Author:           Web Bird
 * Author URI:       https://webbird.co.uk
 * Developer:        Arafat Rahman
 * Copyright:        © 2020-2025 Artios Media (email : hi@webbird.co.uk)
 * License:          GNU General Public License v3.0
 * License URI:      http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:      wp-acc
 * Domain Path:      /languages
 * Tested up to:     6.7.2
 * PHP tested up to: 8.3.19
 */

if (!defined('ABSPATH')) exit;

define('ACC_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include settings page
require_once ACC_PLUGIN_PATH . 'includes/acc-settings-page.php';

class WP_ACC {

    public function __construct() {
        add_action('init', [$this, 'check_cart_activity']);
        add_action('template_redirect', [$this, 'update_last_activity']);
    }

    public function update_last_activity() {
        if (is_user_logged_in() || WC()->session) {
            WC()->session->set('last_activity_time', time());
        }
    }

    public function check_cart_activity() {
        if (!WC()->session) return;

        // Check if the user is on the cart page
        if (!is_cart()) return;

        $enabled = get_option('acc_enable_cart_clear', 'yes');
        if ($enabled !== 'yes') return;

        $limit_minutes = intval(get_option('acc_timeout_minutes', 60));
        $limit_seconds = $limit_minutes * 60;

        $last_activity = WC()->session->get('last_activity_time');

        if ($last_activity && (time() - $last_activity) > $limit_seconds) {
            WC()->cart->empty_cart();
            WC()->session->set('last_activity_time', time());
            wc_add_notice(__('Your cart was cleared due to inactivity.', 'auto-clear-cart'), 'notice');
        }
    }
}

new WP_ACC();
