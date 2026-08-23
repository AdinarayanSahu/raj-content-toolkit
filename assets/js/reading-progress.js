/**
 * Reading Progress Bar — Scroll Handler
 *
 * How it works:
 *   1. On every scroll event, we schedule a rAF (requestAnimationFrame) tick.
 *   2. Inside the tick, we calculate how far through the article the user is.
 *   3. We update the bar's width and ARIA attribute to reflect the percentage.
 *
 * Performance notes:
 *   - The scroll listener is passive (does not block rendering).
 *   - rAF batches DOM writes to the browser's paint cycle.
 *   - A boolean flag (ticking) prevents multiple rAF calls per scroll burst.
 *
 * @package RajContentToolkit
 */

( function () {

	'use strict';

	// ── Element references ────────────────────────────────────────────
	/** @type {HTMLElement|null} */
	const bar = document.getElementById( 'rct-reading-progress-bar' );

	// Safety check: if the element doesn't exist for any reason, exit silently.
	if ( ! bar ) {
		return;
	}

	// ── rAF throttle flag ─────────────────────────────────────────────
	// Prevents queuing more than one animation frame per scroll burst.
	let ticking = false;

	// ── Progress calculation ──────────────────────────────────────────
	/**
	 * Calculates the current reading progress as a percentage (0–100) and
	 * updates the bar element.
	 *
	 * Logic:
	 *   scrollTop  = how far the user has scrolled from the top (px).
	 *   scrollable = total scrollable distance = document height - viewport height.
	 *   progress   = scrollTop / scrollable * 100, clamped to [0, 100].
	 *
	 * @return {void}
	 */
	function updateProgress() {

		const scrollTop    = window.scrollY || document.documentElement.scrollTop;
		const docHeight    = document.documentElement.scrollHeight;
		const winHeight    = window.innerHeight;
		const scrollable   = docHeight - winHeight;

		// Avoid division by zero on very short pages.
		const progress = scrollable > 0
			? Math.min( 100, Math.max( 0, ( scrollTop / scrollable ) * 100 ) )
			: 0;

		// Round to one decimal place to avoid micro-jitter in the width value.
		const rounded = Math.round( progress * 10 ) / 10;

		// Update the bar's visual width.
		bar.style.width = rounded + '%';

		// Keep the ARIA attribute in sync for assistive technologies.
		bar.setAttribute( 'aria-valuenow', Math.round( rounded ) );

		// Reset the throttle flag so the next scroll event can queue a new frame.
		ticking = false;
	}

	// ── Scroll event listener ─────────────────────────────────────────
	/**
	 * Scroll handler: uses rAF to throttle DOM updates.
	 *
	 * The `passive: true` option tells the browser this handler will never
	 * call event.preventDefault(), allowing it to optimise scroll performance.
	 *
	 * @return {void}
	 */
	function onScroll() {
		if ( ! ticking ) {
			window.requestAnimationFrame( updateProgress );
			ticking = true;
		}
	}

	window.addEventListener( 'scroll', onScroll, { passive: true } );

	// ── Initial render ────────────────────────────────────────────────
	// Set the bar's initial state in case the user arrives mid-page
	// (e.g., via an anchor link or browser back-navigation).
	updateProgress();

}() );
