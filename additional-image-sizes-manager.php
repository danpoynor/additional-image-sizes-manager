<?php
/**
 * Additional Image Sizes
 *
 * @package           Additional Image Sizes Manager
 * @author            Dan Poynor
 * @link              https://danpoynor.com
 * @version           1.0.0
 * @copyright         2023 Dan Poynor
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Additional Image Sizes Manager
 * Description: Add additional image sizes to the WordPress Media Settings page.
 * Version: 1.0.0
 * Author: Dan Poynor
 * Author URI: https://danpoynor.com
 * Text Domain: additional-image-sizes-manager
 */

 // Prevent 'headers already sent' errors when redirecting after updates
 ob_start();

if ( ! defined( 'ABSPATH' ) ) {
    // If this file is called directly, abort
    exit;
}

if ( is_admin() ) {
    // We are in admin mode
    require_once __DIR__ . '/admin/additional-image-sizes-manager-admin.php';

    // Add settings link on plugin page
    function aism_settings_link($links, $file)
    {
        // Check if the current plugin is the one you want
        if ($file === plugin_basename(__FILE__)) {
            $settings_link = '<a href="options-general.php?page=additional_image_sizes_manager">' . __('Settings', 'additional-image-sizes-manager') . '</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }
    add_filter('plugin_action_links', 'aism_settings_link', 10, 2);
}

// Register the additional image sizes
function aism_register_sizes()
{
    // Get additional sizes
    $additional_sizes = get_option('additional_image_sizes_manager');

    // Check if the option value is valid and not empty
    if (false !== $additional_sizes && !empty($additional_sizes)) {
        $additional_sizes = unserialize($additional_sizes);

        foreach ($additional_sizes as $size) {
            // Sanitize the name and make it lowercase with underscores for spaces
            $size['name'] = sanitize_title($size['name']);
            $size['name'] = str_replace(' ', '_', strtolower($size['name']));
            add_image_size($size['name'], $size['max_width'], $size['max_height']);
        }
    }
}
add_action('after_setup_theme', 'aism_register_sizes');