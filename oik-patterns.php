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
 * 
 */
function oik_patterns_loaded() {
	add_action( 'oik_loaded', 'oik_patterns_init', 20 );
	add_action( 'plugins_loaded', 'oik_patterns_plugins_loaded');

    //add_action( 'after_setup_theme', 'oik_patterns_after_setup_theme');


}

/**
 * Registers .html patterns on behalf of lazy themes.
 *
 * We're dependent upon oik.
 */
function oik_patterns_init() {
	//oik_require( 'patterns/index.php', 'oik-patterns');
	//oik_blocks_lazy_register_block_patterns();
	oik_require( 'libs/class-oik-patterns-from-htm.php', 'oik-patterns');
	$oik_patterns = new OIK_Patterns_From_Htm();
	$oik_patterns->register_patterns();
}

function oik_patterns_plugins_loaded() {
   // echo "oik patterns plugins_loaded";
   bw_backtrace();
	if ( oik_patterns_validate_theme() ) {

		//add_action( 'setup_theme', 'oik_patterns_setup_theme');
		//add_filter( 'template', 'oik_patterns_template');
		//add_filter( 'stylesheet', 'oik_patterns_stylesheet');
		//add_action( 'init', 'oik_patterns_maybe_cache_patterns', 9999 );

	}


}



function oik_patterns_cache_patterns( $theme ) {
    oik_require( 'libs/class-oik-patterns-export.php', 'oik-patterns');
    $oik_patterns_export = new OIK_patterns_export( $theme );
    $oik_patterns_export->cache_theme_patterns();

}

/**
 * Implements `template` filter.
 *
 * We can filter the value of the template to change the template to the one were interested in.
 *
 * So how do we decide what to do?
 * Can we determine it from the URL?
 * https://s.b/oikcom/oik-themes/thisis-experimental-full-site-editing-theme/?oik-tab=patterns
 *
 * Well, we can't directly determine the theme name from this URL
 * so perhaps we need another query parameter.  eg preview_theme=thisis
 *
 * @TODO Cater for child themes. eg  Geologist which is a child theme of Blockbase
 * It may be necessary to set a preview_template query parameter as well.
 *
 * @param $template
 * @return mixed
 */
function oik_patterns_template( $template ) {
    $preview_theme = bw_array_get( $_REQUEST, 'preview_theme', null );

    bw_trace2( $preview_theme, "preview_theme" );
    //echo "$template $preview_theme ";
    if ( $preview_theme ) {
        $template = $preview_theme;
    }
    return $template;
}

/**
 * Implements `stylesheet` filter.
 *
 * @param $stylesheet
 * @return mixed
 */
function oik_patterns_stylesheet( $stylesheet ) {
    $preview_theme = bw_array_get( $_REQUEST, 'preview_theme', null );

    bw_trace2( $preview_theme, "preview_theme" );
    //echo "$stylesheet $preview_theme ";
    if ( $preview_theme ) {
        $stylesheet = $preview_theme;
    }
    return $stylesheet;
}

/**
 * Cache patterns any time a theme is being previewed.
 *
 * @TODO Implement logic to reduce the number of times this is done.
 *
 * @param $args
 */

function oik_patterns_maybe_cache_patterns( $args ) {
    $preview_theme = bw_array_get( $_REQUEST, 'preview_theme', null );
    if ( $preview_theme) {
        oik_patterns_cache_patterns( $preview_theme );
    }

}

function oik_patterns_validate_theme() {
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