<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2022
 * @package oik-patterns
 *
 */

class OIK_patterns_import {


    private $patterns = [];
    private $patterns_json = null;
    private $theme;


    function __construct($theme = null)
    {
        $this->theme = $theme;
        if ($theme) {
            //echo "Importing cached patterns for: $theme";
        } else {
            //echo "Importing patterns for current theme";
        }
    }

    function display_cached_patterns() {
        //e( "Cached patterns");
        $this->load_patterns_json();
        if ( $this->patterns_json ) {
            // There could be some cached patterns.
            // The array may be empty though.
            e(sprintf(_n('%$1s pattern', '%1$s patterns', 'oik-themes'), count($this->patterns)));
            $this->accordion_start();
            foreach ( $this->patterns as $pattern ) {
               $this->display_pattern($pattern);
            }
            $this->accordion_end();
        }
        return bw_ret();
    }

    function display_pattern( $pattern ) {

        h3( $pattern->title );
        sdiv( 'pattern');

        $cached_pattern = $this->get_cached_file( $pattern );
        e( $cached_pattern );
        p( $pattern->name );
        if ( isset( $pattern->categories )) {
            p(implode(',', $pattern->categories));
        }
        stag( 'pre', 'pattern', null, 'style="font-size:12px;"');
        e( esc_html( $cached_pattern ));
        etag( 'pre');
        ediv();
    }

    function get_cached_file( $pattern ) {
        $file = $this->get_pattern_filename( $pattern );
        if ( file_exists( $file )) {
            $cached_pattern = file_get_contents( $file );
        } else {
            $cached_pattern = "Missing pattern. File $file doesn't exist";
        }
        return $cached_pattern;
    }

    function accordion_start() {
        oik_require("shortcodes/oik-jquery.php");
        bw_jquery_enqueue_script("jquery-ui-accordion");
        bw_jquery_enqueue_style("jquery-ui-accordion");
        $selector = $this->bw_accordion_id();
        bw_jquery("#$selector", "accordion", '{ heightStyle: "content"}');
        $class = "bw_accordion";
        sdiv( $class, $selector );
    }

    /**
     * Returns the next selector for [bw_accordion]
     *
     * $inc  | action | return
     * ----  | ------ | ------
     * true  | $accordion_id++ | next value
     * false | nop    | current value
     * null  | 0    | current value	= 0
     *
     * @param bool|null $inc - increment the id?
     * @return string - tab selector ID
     */
    function bw_accordion_id( $inc=true ) {
        static $accordion_id = 0;
        if ( $inc ) {
            $accordion_id++;
        } elseif ( null === $inc ) {
            $accordion_id = 0;
        }
        return( "bw_accordion-$accordion_id" );
    }

    function accordion_end() {
        ediv();
    }


    function format_accordion( $file, $type=null ) {
        //bw_format_accordion()
        h3( basename( $file ) );
        $contents = file_get_contents( $file );
        $contents = str_replace( '[', '&#091;', $contents);
        //bw_geshi_it()
        sdiv();
        if ( 'patterns' === $type ) {
            $this->preview_pattern($file, $contents);
        }
        stag( 'pre', 'pattern');
        e( esc_html( $contents ));
        etag( 'pre');

        ediv();


    }



    /**
     * Returns the file name for the pattern.json file for this theme.
     *
     * @return mixed
     */
    function get_patterns_json_file() {
        $pattern_json_parts = [ WP_LANG_DIR ];
        $pattern_json_parts[] = 'themes';
        $pattern_json_parts[] = $this->theme;
        $pattern_json_parts[] = get_locale();
        $pattern_json_file = implode( '/', $pattern_json_parts );
        //wp_mkdir_p( $pattern_json_file );
        $pattern_json_file .= '/patterns.json';
        return $pattern_json_file;
    }

    function load_patterns_json() {
        $patterns_json_file = $this->get_patterns_json_file();
        //e( $patterns_json_file );
        if ( file_exists( $patterns_json_file )) {
            $this->patterns_json = file_get_contents( $patterns_json_file );
            $patterns = json_decode( $this->patterns_json);
            $this->patterns = $patterns;
        }
    }

    /**
     * Determines the pattern filename.
     *
     * Takes into account the categories.
     *
     * @param $pattern
     */
    function get_pattern_filename( $pattern ) {
        $pattern_filename_parts = [ WP_LANG_DIR ];
        $pattern_filename_parts[] = 'themes';
        $pattern_filename_parts[] = $this->theme;
        $pattern_filename_parts[] = get_locale();

        if ( isset( $pattern->categories)) {
            foreach ($pattern->categories as $category) {
                $pattern_filename_parts[] = $category;
            }
        }

        $pattern_name_parts = explode( '/', $pattern->name);
        $pattern_file_name = array_pop( $pattern_name_parts ); // Discard the filename
        array_shift( $pattern_name_parts );  // Discard the theme name
        foreach ( $pattern_name_parts as $subdir ) {
            $pattern_filename_parts[] = $subdir;
        }
        $pattern_folder = implode( '/', $pattern_filename_parts );
        //echo "Pattern filename: " . $pattern_filename;
        //wp_mkdir_p( $pattern_folder );
        $pattern_filename = $pattern_folder . '/' . $pattern_file_name . '.html';
        //echo "Pattern file name: " . $pattern_filename;
        return $pattern_filename;

    }

    function count_patterns() {
    	$this->load_patterns_json();
    	if ( $this->patterns_json  ) {
		    return count( $this->patterns );
	    }
    	$link = '<a href="?preview_theme=' . $this->theme . '">?</a>';
    	return $link;
    }

}