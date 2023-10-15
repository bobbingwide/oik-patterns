<?php

/**
 * Class OIK_Patterns_From_Htm
 * @copyright (C) Copyright Bobbing Wide 2021,2022, 2023
 * @package oik-patterns
 */

class OIK_Patterns_From_Htm {

	private $pattern_name = null; // theme/filename
	private $pattern_properties = [];
	private $filename = null ; // eg 404-template.htm
	private $themes = null;
	private $theme = 'thisis';
	private $theme_name = 'ThisIs...'; // Theme name
	private $files = [];
	private $file;
	private $categories;

	function __construct() {
	}

	/**
	 * Builds an array of block themes.
	 *
	 * Originally hard coded to return: thisis, fizzie, wizzie, sb and written
	 * this now returns an array of block themes.
	 *
	 * Any one of these themes may contain a `patterns` folder which contains `.html` patterns.
	 * Or it may contain patterns which are registered from `.php` files.
	 *
	 * @returns array Associative array of slug to name
	 */
	function list_themes() {
		$themes = wp_get_themes();
		$this->themes = [];
		foreach ( $themes as $key => $theme  ) {
			if ( $theme->is_block_theme() ) {
			    $name = $theme->display('Name');
			    $template = $theme->get_template();
			    if ( $template !== $key ) {
			        $template_theme = bw_array_get( $themes, $template, null );
			        if ( null !== $template_theme ) {
                        $name .= ' child of ';
                        $name .= $template_theme->display('Name');
                    }
                }
				$this->themes[ $key ]=$name;
			}
		}
		asort( $this->themes, SORT_FLAG_CASE | SORT_NATURAL | SORT_STRING );
	}

	/**
	 * Returns the list of themes.
	 *
	 * Assumes list_themes() has been called.
	 *
	 * @return array
	 */
	function get_themes() {
		return $this->themes;
	}

	function register_patterns() {
		$this->list_themes();
		foreach ( $this->themes as $this->theme => $this->theme_name ) {
			$this->register_block_pattern_category();
			//$this->list_files();
			$this->files = $this->get_all_patterns( $this->theme );
			foreach ( $this->files as $file ) {
				$this->file = $file;
				$this->filename = basename( $file );
				$this->register_categories();
				$this->register_pattern();
			}
		}
	}

	function list_files() {
		$theme_dir_root = dirname( get_stylesheet_directory() );
		$mask = $theme_dir_root . '/' . $this->theme .'/patterns/*.html';
		$this->files = glob( $mask );
		//bw_trace2( $this->files, $mask );
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
	function load_pattern()
    {
        $this->pattern_name = $this->theme . '/' . $this->filename;
        $this->pattern_properties = [];
        $this->pattern_properties['title'] = $this->get_title();
        $content = file_get_contents($this->file);
        if ($content === false) {
            gob();
        }
        //bw_trace2( $content, $this->file  );
        $this->pattern_properties['content'] = $content;

        // Set categories based on sub-folders and add the theme for good measure.
        $this->pattern_properties['categories'] = $this->categories;
        $this->pattern_properties['categories'][] = $this->theme;
    }

    /**
     * Extends the pattern from a matching .json file
     *
     * Enables patterns to be displayed as the pattern to choose for a new page or other CPT
     */
    function extend_pattern() {
	    $pattern_json_file = str_replace( '.html', '.json', $this->file );
	    if ( file_exists( $pattern_json_file )) {
	        $pattern_json = file_get_contents( $pattern_json_file );
	        //echo $pattern_json;

	        $pattern_properties = json_decode( $pattern_json, true );
	        //print_r( $pattern_properties );
	        if ( isset( $pattern_properties['blockTypes'])) {
                $this->pattern_properties['blockTypes'] = $pattern_properties['blockTypes'];
            }
            if ( isset( $pattern_properties['postTypes'])) {
                $this->pattern_properties['postTypes'] = $pattern_properties['postTypes'];
            }


        }


	}

	function get_title() {
	    $title = $this->filename;
	    $title = str_replace( '-', ' ', $title );
	    $title = str_replace( '.html', '', $title );
	    $title = ucfirst( $title );
	    return $title;
    }

    function get_label( $part ) {
		$title = $part;
	    $title = str_replace( '-', ' ', $title );
	    $title = str_replace( '.html', '', $title );
	    $title = ucfirst( $title );
	    return $title;
    }

	/**
	 * Registers the pattern category for the theme.
	 *
	 */
	function register_block_pattern_category() {
		$category_name = $this->theme;
		$category_properties = [ 'label' => $this->theme_name ];
		register_block_pattern_category( $category_name, $category_properties );
	}

	/**
	 * Registers categories for each subfolder under patterns.
	 *
	 */
	function register_categories() {
		$this->categories = [];
		$parts = explode( '/', $this->file );
		array_pop( $parts );
		$patterns_found = false;
		foreach ( $parts as $part ) {
			if ( $part === 'patterns') {
				$patterns_found = true;
				continue;
			}
			if ( $patterns_found ) {
				$this->categories[] =$part;
				$category_properties=[ 'label'=> $this->get_label( $part ) ];
				register_block_pattern_category( $part, $category_properties );
			}
		}
	}

	function register_pattern() {
		$this->load_pattern();
		$this->extend_pattern();

		register_block_pattern( $this->pattern_name, $this->pattern_properties );
	}

	function get_all_patterns( $slug ) {
		$theme_dir = get_theme_root();
		$theme_dir .= '/';
		$theme_dir .= $slug;
		$dirs = [ 'patterns' ];
		$masks = [ '*.html'];
		$files = [];
		foreach ( $dirs as $dir ) {
			$files1 = $this->get_subdir_file_list( $theme_dir . '/' . $dir, $masks );
			$files = array_merge( $files, $files1 );
		}
		return $files;
	}

	function get_file_list($dir, $mask) {
		$files = glob($dir .'/' . $mask);
		return $files;
	}

	function get_subdir_file_list( $dir, $masks ) {
		//echo "gsfl:" . $dir .PHP_EOL;
		$files = [];
		foreach ( $masks as $mask ) {
			//$theme_dir .= $this->get_template_part_dir( $slug );
			$files1=$this->get_file_list( $dir, $mask );
			$files = array_merge( $files, $files1 );
		}
		$subdirs = glob( $dir . '/*',  GLOB_ONLYDIR  );
		foreach ( $subdirs as $subdir ) {
			$files1=$this->get_subdir_file_list( $subdir, $masks );
			$files = array_merge( $files, $files1 );
		}
		//print_r( $files );
		return $files;
	}
}