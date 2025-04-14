<?php
if (!defined('ABSPATH')) exit;

class ACC_Settings_Page {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_menu() {
        add_submenu_page(
            'woocommerce',
            'Auto-Clear Cart',
            'Auto-Clear Cart',
            'manage_options',
            'acc-settings',
            [$this, 'settings_page_html']
        );
    }

    public function register_settings() {
        register_setting('acc_settings_group', 'acc_timeout_minutes');
        register_setting('acc_settings_group', 'acc_enable_cart_clear');
    }

    public function settings_page_html() {
        ?>
        <div class="wrap">
            <h1>Auto-Clear Cart Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('acc_settings_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Enable Auto-Clear</th>
                        <td>
                            <select name="acc_enable_cart_clear">
                                <option value="yes" <?php selected(get_option('acc_enable_cart_clear'), 'yes'); ?>>Yes</option>
                                <option value="no" <?php selected(get_option('acc_enable_cart_clear'), 'no'); ?>>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Timeout Duration (in minutes)</th>
                        <td>
                            <input type="number" name="acc_timeout_minutes" value="<?php echo esc_attr(get_option('acc_timeout_minutes', 60)); ?>" min="1" />
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

new ACC_Settings_Page();
