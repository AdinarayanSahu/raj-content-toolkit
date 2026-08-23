<?php
/**
 * Plugin Name:       Raj Content Toolkit
 * Description:       A private, modular content toolkit for WordPress. Version 1.0.0 includes a lightweight Reading Progress Bar for blog posts.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Adinarayan Sahu
 * License:           Private
 * Text Domain:       raj-content-toolkit
 *
 * @package RajContentToolkit
 */

// Prevent direct file access.
if (!defined('ABSPATH')) {
	exit;
}

// ---------------------------------------------------------------------------
// Plugin Constants
// ---------------------------------------------------------------------------

/**
 * Absolute filesystem path to the plugin root directory (with trailing slash).
 * Use this for require_once and file_exists checks.
 */
define('RCT_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Public URL to the plugin root directory (with trailing slash).
 * Use this for enqueuing assets.
 */
define('RCT_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Plugin version string.
 * Passed as the $ver argument to wp_enqueue_* to bust browser caches
 * automatically whenever you bump this number.
 */
define('RCT_VERSION', '1.0.0');

// ---------------------------------------------------------------------------
// Feature Modules
// ---------------------------------------------------------------------------
// Each feature lives in its own file inside /includes/.
// To add a new feature in a future version, simply add a require_once here.

require_once RCT_PLUGIN_DIR . 'includes/reading-progress.php';
