<?php
/**
 * Plugin Name: Makase Quote Widget
 * Plugin URI: https://makase.com
 * Description: Adds Makase quote request widget to your site
 * Version: 1.0.0
 * Author: Makase
 *
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 **/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu
add_action('admin_menu', 'makase_add_admin_menu');
function makase_add_admin_menu() {
    add_options_page(
        'Makase Settings',
        'Makase Quote',
        'manage_options',
        'makase-settings',
        'makase_settings_page'
    );
}

// Settings page content
function makase_settings_page() {
    ?>
    <div class="wrap">
        <h1>Makase Quote Widget Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('makase_settings');
            do_settings_sections('makase_settings');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">MAKASE ID</th>
                    <td>
                        <input type="text" name="makase_api_id" value="<?php echo esc_attr(get_option('makase_api_id')); ?>" class="regular-text" />
                        <p class="description">Found in the widget tab on the Vendor Dashboard</p>

                    </td>
                </tr>
                <tr>
                    <th scope="row">Enable Floating Widget</th>
                    <td>
                        <input type="checkbox" name="makase_enable_popup" value="1" <?php checked(get_option('makase_enable_popup', true)); ?> />
                        <p class="description">Show floating widget on all pages</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Vendor Dashboard</th>
                    <td>
                        <a href="http://pro.makase.com/widget?vendor_id=<?php echo esc_attr(get_option('makase_api_id')); ?>" target="_blank" class="button button-secondary">Makase Vendor Dashboard</a>
                    </td>
                    </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'makase_settings_init');
function makase_settings_init() {
    register_setting('makase_settings', 'makase_api_id');
    register_setting('makase_settings', 'makase_enable_popup');
}

// Add the script to frontend
add_action('wp_footer', 'makase_add_script');
function makase_add_script() {
    $api_id = get_option('makase_api_id');
    $enable_popup = get_option('makase_enable_popup', true);
    
    // Only add script if API ID is set AND popup is enabled
    if (!empty($api_id) && $enable_popup) {
        ?>
        <script src="https://api.makase.com/widget/phone-form?floating=true&no_track=true&id=<?php echo esc_attr($api_id); ?>" data-rocket-defer="" defer=""></script>
        <?php
    }
}

// Shortcode support for inline placement
add_shortcode('makase_quote', 'makase_shortcode_function');
function makase_shortcode_function() {
    $api_id = get_option('makase_api_id');
    if (!empty($api_id)) {
        // Create the container div and load the script
        $output = '<div class="makase-form" style="width: 350px; max-width: none; "></div>';
        $output .= '<script src="https://api.makase.com/widget/phone-form?no_track=true&id=' . esc_attr($api_id) . '" defer></script>';
        return $output;
    }
    return '';
}