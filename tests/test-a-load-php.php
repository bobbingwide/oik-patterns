<?php

/**
 * @package oik-patterns
 * @copyright (C) Copyright Bobbing Wide 2023
 *
 * Unit tests to load all the PHP files for PHP 8.2
 */
class Tests_load_php extends BW_UnitTestCase
{

	/**
	 * set up logic
	 *
	 * - ensure any database updates are rolled back
	 * - we need oik-googlemap to load the functions we're testing
	 */
	function setUp(): void 	{
		parent::setUp();
	}

	function test_load_libs_php() {
		oik_require( 'libs/class-oik-patterns-export.php', 'oik-patterns');
		oik_require( 'libs/class-oik-patterns-from-htm.php', 'oik-patterns');
		oik_require( 'libs/class-oik-patterns-import.php', 'oik-patterns');

		$this->assertTrue( true );
	}

	function test_load_patterns_php() {
		oik_require( 'patterns/index.php', 'oik-patterns');
		oik_require( 'patterns/oik-fern.php', 'oik-patterns');
		oik_require( 'patterns/oik-person.php', 'oik-patterns');
		$this->assertTrue( true );
	}

	function test_load_plugin_php() {
		oik_require( 'oik-patterns.php', 'oik-patterns');
		$this->assertTrue( true );
	}
}



