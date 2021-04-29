<?php

/*
Plugin Name: oik-patterns
Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-patterns
Description: Patterns for the Gutenberg block editor
Version: 0.0.0
Author: bobbingwide
Author URI: https://bobbingwide.com/about-bobbing-wde
Text Domain: oik-patterns
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2020-2021 Bobbing Wide (email : herb@bobbingwide.com )

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
 * We're dependent upon oik
 */
function oik_patterns_loaded() {
	add_action( 'oik_loaded', 'oik_patterns_init', 20 );
}

function oik_patterns_init() {
	//oik_require( 'patterns/index.php', 'oik-patterns');
	//oik_blocks_lazy_register_block_patterns();

	oik_require( 'libs/class-oik-patterns-from-htm.php', 'oik-patterns');
	$oik_patterns = new OIK_Patterns_From_Htm();
	$oik_patterns->register_patterns();

}

oik_patterns_loaded();