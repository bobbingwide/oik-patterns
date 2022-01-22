<?php

/**
 * Class OIK_Patterns_From_Htm
 * @copyright (C) Copyright Bobbing Wide 2021
 * @package oik-patterns
 */


class OIK_Patterns_From_Htm {

	private $pattern_name = null; // theme/filename
	private $pattern_properties = [];
	private $filename = null ; // eg 404-template.htm
	private $themes = null;
	private $theme = 'thisis';
	private $files = [];

	function __construct() {


	}

	/**
	 * Hardcoded at present.
	 */
	function list_themes() {
		$this->themes = ['thisis', 'fizzie', 'twentytwentytwo'];

	}

	function register_patterns() {
		$this->list_themes();
		foreach ( $this->themes as $this->theme ) {
			$this->register_block_pattern_category();
			$this->list_files();
			foreach ( $this->files as $file ) {
				$this->file = $file;
				$this->filename = basename( $file );
				$this->register_pattern();
			}
		}

	}

	function list_files() {
		$theme_dir_root = dirname( get_stylesheet_directory() );
		$mask = $theme_dir_root . '/' . $this->theme .'/patterns/*.html';
		$this->files = glob( $mask );
		bw_trace2( $this->files, $mask );
	}

	/**
	 * Pattern properties consists of
	 *
	 * field | value
	 * ----- | -----
	 * title | The title of the pattern
	 * content | block HTML markup for the pattern
	 * description  optional |
	 * categories optional |
	 * keywords optional |
	 * viewportWidth optional |
	 */
	function load_pattern() {
		$this->pattern_name =  $this->theme . '/' . $this->filename;
		$this->pattern_properties = [];
		$this->pattern_properties['title'] = $this->filename;
		$content = file_get_contents( $this->file );
		if ( $content === false ) {
			gob();
		}
		bw_trace2( $content, $this->file  );
		$this->pattern_properties['content'] = $content;
		$this->pattern_properties['categories'] = [ $this->theme ];
	}

	function register_block_pattern_category() {
		$category_name = $this->theme;
		$category_properties = [ 'label' => $category_name ];
		register_block_pattern_category( $category_name, $category_properties );
	}

	function register_pattern() {
		$this->load_pattern();
		register_block_pattern( $this->pattern_name, $this->pattern_properties );
	}

	function get_file_list($dir, $mask) {
		$files = glob($dir .'/' . $mask);
		return $files;
	}

	function get_subdir_file_list( $theme_dir, $mask ) {
		$files2 = [];
		$subdirs = glob( $theme_dir . '/*',  GLOB_ONLYDIR  );
		foreach ( $subdirs as $subdir ) {
			$files = $this->get_file_list( $subdir, $mask );
			$files2 = array_merge( $files2, $files );
		}
		return $files2;
	}

}