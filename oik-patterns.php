<?php

/*
Plugin Name: oik-patterns
Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-patterns
Description: Loads and caches patterns for the Gutenberg block editor
Version: 0.1.0
Author: bobbingwide
Author URI: https://bobbingwide.com/about-bobbing-wde
Text Domain: oik-patterns
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2020-2022 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

/**
 * Function to run when plugin loaded.
 */
function oik_patterns_loaded() {
	add_action( 'oik_loaded', 'oik_patterns_init', 20 );
	add_action( 'plugins_loaded', 'oik_patterns_plugins_loaded');
}

/**
 * Registers .html patterns on behalf of lazy themes.
 *
 * We're dependent upon oik.
 */
function oik_patterns_init() {
	oik_require( 'libs/class-oik-patterns-from-htm.php', 'oik-patterns');
	$oik_patterns = new OIK_Patterns_From_Htm();
	$oik_patterns->register_patterns();
}

/**
 * Validates the `preview_theme` query arg, if set.
 *
 * @return bool
 */
function oik_patterns_plugins_loaded() {
	$is_valid = false;
	if ( isset( $_REQUEST['preview_theme'])) {
		$theme = $_REQUEST['preview_theme'];
		oik_require( 'libs/class-oik-patterns-export.php', 'oik-patterns');
		$oik_patterns_export = new OIK_patterns_export( $theme );
		$is_valid = $oik_patterns_export->validate_theme();
	}
	return $is_valid;
}

oik_patterns_loaded();