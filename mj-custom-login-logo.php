<?php
/**
 * Plugin Name: MJ Custom Login Logo
 * Plugin URI: https://www.linkedin.com/posts/jahanzaibmushtaq11_wordpress-plugindevelopment-webdevelopment-activity-7270791502883119105-oUOd?utm_source=share&utm_medium=member_desktop
 * Description: A plugin to change the WordPress login logo via the admin dashboard.
 * Version: 1.0
 * Author: Jahanzaib Mushtaq
 * Author URI: https://www.linkedin.com/in/jahanzaibmushtaq11/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register the admin menu
function cll_register_admin_menu() {
    add_menu_page(
        'Custom Login Logo',         // Page title
        'MJ Login Logo',       // Menu title
        'manage_options',            // Capability
        'cll-settings',              // Menu slug
        'cll_settings_page',         // Callback function
        'dashicons-admin-appearance' ,
        21
    );
}
add_action('admin_menu', 'cll_register_admin_menu');

// Render the admin settings page
function cll_settings_page() {
    ?>
    <div class="wrap">
        <h1 style="font-size: 50px;">Developed by Dev. Jahanzaib Mushtaq</h1>
        <h1>Custom Login Logo Settings</h1>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
            settings_fields('cll_settings_group');
            do_settings_sections('cll-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function cll_register_settings() {
    register_setting('cll_settings_group', 'cll_logo_url');

    add_settings_section(
        'cll_main_section',          // Section ID
        'Upload Custom Logo',        // Title
        null,                        // Callback (null for no description)
        'cll-settings'               // Page slug
    );

    add_settings_field(
        'cll_logo',                  // Field ID
        'Login Logo',                // Title
        'cll_logo_field_callback',   // Callback function
        'cll-settings',              // Page slug
        'cll_main_section'           // Section ID
    );
}
add_action('admin_init', 'cll_register_settings');

// Render the logo upload field
function cll_logo_field_callback() {
    $logo_url = get_option('cll_logo_url');
    ?>
    <input type="text" id="cll_logo_url" name="cll_logo_url" value="<?php echo esc_attr($logo_url); ?>" style="width: 70%;">
    <button type="button" class="button" id="upload_logo_button">Upload Logo</button>
    <?php if ($logo_url): ?>
        <p><img src="<?php echo esc_url($logo_url); ?>" alt="Login Logo" style="max-width: 200px;"></p>
    <?php endif; ?>
    <?php
}

// Enqueue Media Uploader
function cll_enqueue_media_uploader($hook) {
    if ($hook !== 'toplevel_page_cll-settings') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('cll-media-uploader', plugin_dir_url(__FILE__) . 'media-uploader.js', ['jquery'], null, true);
}
add_action('admin_enqueue_scripts', 'cll_enqueue_media_uploader');

// Change the login logo dynamically
function cll_custom_login_logo() {
    $logo_url = get_option('cll_logo_url');
    if ($logo_url):
        ?>
        <style type="text/css">
            #login h1 a {
                background-image: url('<?php echo esc_url($logo_url); ?>');
                background-size: contain;
                width: 100%;
                height: 100px; /* Adjust height */
            }
        </style>
        <?php
    endif;
}
add_action('login_enqueue_scripts', 'cll_custom_login_logo');
