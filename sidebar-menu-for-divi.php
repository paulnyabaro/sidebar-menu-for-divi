<?php
/*
Plugin Name: Sidebar Menu for Divi
Plugin URI: https://github.com/paulnyabaro/sidebar-menu-for-divi
Description: Adds a toggleable sidebar menu to all pages on Divi websites.
Version: 1.0.2
Author: Paul Nyabaro
Author URI: https://www.paulnyabaro.com/
*/

// Enqueue CSS and JavaScript
function sidebar_menu_for_divi_enqueue_scripts() {
    wp_enqueue_style('nbhwc-sidebar-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('nbhwc-sidebar-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'sidebar_menu_for_divi_enqueue_scripts');

// Register settings for admin menu selection
function sidebar_menu_for_divi_settings() {
    register_setting('sidebar_menu_for_divi_settings', 'nbhwc_selected_menu');
}
add_action('admin_init', 'sidebar_menu_for_divi_settings');

// Add plugin menu to admin dashboard
function sidebar_menu_for_divi_admin_menu() {
    add_menu_page(
        'NBHWC Sidebar Menu Settings',
        'Sidebar Menu Settings',
        'manage_options',
        'nbhwc-sidebar-menu-settings',
        'sidebar_menu_for_divi_settings_page'
    );
}
add_action('admin_menu', 'sidebar_menu_for_divi_admin_menu');

// Render settings page
function sidebar_menu_for_divi_settings_page() {
    ?>
    <div class="wrap">
        <h1>NBHWC Sidebar Menu Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sidebar_menu_for_divi_settings');
            do_settings_sections('sidebar_menu_for_divi_settings');
            $selected_menu = get_option('nbhwc_selected_menu');
            $menus = get_terms('nav_menu');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Select Menu to Display</th>
                    <td>
                        <select name="nbhwc_selected_menu">
                            <?php foreach ($menus as $menu) : ?>
                                <option value="<?php echo esc_attr($menu->name); ?>" <?php selected($selected_menu, $menu->name); ?>>
                                    <?php echo esc_html($menu->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Shortcode for Toggle Button
function sidebar_menu_for_divi_shortcode() {
    ob_start();
    ?>
    <button id="nbhwc-sidebar-toggle">
        <span class="nbh-m-bar"></span>
        <span class="nbh-m-bar"></span>
        <span class="nbh-m-bar"></span>
    </button>
    <div id="nbhwc-sidebar" class="nbhwc-sidebar">
        <button id="nbhwc-sidebar-close">&times;</button>
        <?php
        $selected_menu = get_option('nbhwc_selected_menu');
        if ($selected_menu) {
            wp_nav_menu(array('menu' => $selected_menu));
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sidebar_menu_for_divi', 'sidebar_menu_for_divi_shortcode');
?>
