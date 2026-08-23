<?php
/**
 * Reading Progress Bar — Feature Module
 *
 * Responsibilities:
 *   1. Inject the progress bar <div> into the page via the `wp_body_open` hook.
 *   2. Enqueue the CSS and JS assets, but ONLY on single blog post pages.
 *
 * No HTML is printed inline in PHP — the element is added via the hook so it
 * remains compatible with themes that call wp_body_open() (Blocksy does this).
 *
 * @package RajContentToolkit
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ---------------------------------------------------------------------------
// 1. Output the progress bar HTML element
// ---------------------------------------------------------------------------

/**
 * Outputs the reading progress bar <div> immediately after <body> opens.
 *
 * Hooked to `wp_body_open` (WordPress 5.2+). Blocksy and most modern themes
 * call wp_body_open() inside their <body> tag, so the bar is the very first
 * element rendered — exactly where a top-fixed bar should live.
 *
 * @return void
 */
function rct_reading_progress_bar_html() {

	// Only render on single blog posts, not pages, archives, or the homepage.
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	// The aria-* attributes make the bar accessible to screen readers.
	echo '<div
		id="rct-reading-progress-bar"
		role="progressbar"
		aria-label="' . esc_attr__( 'Reading progress', 'raj-content-toolkit' ) . '"
		aria-valuemin="0"
		aria-valuemax="100"
		aria-valuenow="0"
	></div>' . "\n";
}
add_action( 'wp_body_open', 'rct_reading_progress_bar_html' );

// ---------------------------------------------------------------------------
// 2. Enqueue CSS and JS assets
// ---------------------------------------------------------------------------

/**
 * Registers and enqueues the reading progress bar stylesheet and script.
 *
 * Assets are only loaded on single blog posts to keep front-end weight
 * minimal everywhere else.
 *
 * @return void
 */
function rct_reading_progress_enqueue_assets() {

	// Guard: skip enqueueing on every page type except single posts.
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	// CSS — loaded in <head> via wp_enqueue_style.
	wp_enqueue_style(
		'rct-reading-progress',                            // Unique handle.
		RCT_PLUGIN_URL . 'assets/css/reading-progress.css', // File URL.
		array(),                                           // No stylesheet dependencies.
		RCT_VERSION                                        // Version for cache busting.
	);

	// JavaScript — loaded just before </body> (in_footer = true).
	// No jQuery dependency; the script is vanilla ES6.
	wp_enqueue_script(
		'rct-reading-progress',                           // Unique handle.
		RCT_PLUGIN_URL . 'assets/js/reading-progress.js', // File URL.
		array(),                                          // No script dependencies.
		RCT_VERSION,                                      // Version for cache busting.
		true                                              // Load in footer.
	);
}
add_action( 'wp_enqueue_scripts', 'rct_reading_progress_enqueue_assets' );
