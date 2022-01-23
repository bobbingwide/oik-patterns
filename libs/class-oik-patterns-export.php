<?php

class OIK_patterns_export {

    private $patterns;
    private $patterns_json;
    private $theme;
    private $template;


    function __construct( $theme=null ) {
        $this->theme = $theme;
        $this->template = $theme;
        if ( $theme ) {
            //echo "Exporting patterns for: $theme";
        } else {
            //echo "Exporting patterns for current theme";
        }


    }

    function cache_theme_patterns() {
    	$this->patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
	    bw_backtrace();
        //echo "export_patterns";
        bw_trace2( $this->patterns, "patterns", false );
        $this->build_patterns_json();
        $this->export_patterns_json();

        foreach ( $this->patterns as $pattern ) {
            if ( $this->is_for_theme( $pattern )) {
                //echo "Processing " . $pattern['name'];

                $pattern_filename = $this->get_pattern_filename($pattern);
                $pattern_content = $this->get_pattern_content($pattern);
                $this->write_file( $pattern_filename, $pattern_content );
            }
        }

    }

    /**
     * Builds a JSON file containing the patterns for the theme.
     *
     * This assumes that the theme's patterns are prefixed by the theme name.
     *
     */
    function build_patterns_json() {
        $this->patterns_json = [];
        foreach ( $this->patterns as $pattern ) {
            if ( $this->is_for_theme( $pattern )) {
                $pattern_json = $pattern;
                unset($pattern_json['content']);
                $this->patterns_json[] = $pattern_json;
            }
        }
    }

    /**
     * Checks if the pattern is for the theme.
     *
     * Each pattern name is expected to be prefixed by the theme name
     * or 'core' for WordPress.
     *
     * What about plugins?
     *
     * @param $pattern
     * @returns bool - true if the pattern is for the theme
     */
    function is_for_theme( $pattern ) {
        $is_for_theme = false !== strpos( $pattern['name'], $this->theme .'/' );
        return $is_for_theme;
    }

    /**
     * Exports the patterns.json file for the theme into the themes's patterns cache.
     *
     * wp-content/languages/themes/theme/locale/pattern.json
     *
     * Notes:
     * - In a multi site environment these files will be shared.
     * - Logic should also support plugins that offer patterns.
     * - This includes WordPress itself, with patterns prefixed 'core'.
     *
     */
    function export_patterns_json() {
        $patterns_json_file = $this->get_patterns_json_file();
        $this->patterns_string = json_encode( $this->patterns_json );
        $this->write_file( $patterns_json_file, $this->patterns_string );
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
        wp_mkdir_p( $pattern_json_file );
        $pattern_json_file .= '/patterns.json';
        return $pattern_json_file;
    }

    function write_file( $file, $contents ) {
        file_put_contents( $file, $contents );
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

        if ( isset( $pattern['categories'])) {
            foreach ($pattern['categories'] as $category) {
                $pattern_filename_parts[] = $category;
            }
        }

        $pattern_name_parts = explode( '/', $pattern['name']);
        $pattern_file_name = array_pop( $pattern_name_parts ); // Discard the filename
        array_shift( $pattern_name_parts );  // Discard the theme name
        foreach ( $pattern_name_parts as $subdir ) {
            $pattern_filename_parts[] = $subdir;
        }
        $pattern_folder = implode( '/', $pattern_filename_parts );
        //echo "Pattern filename: " . $pattern_filename;
        wp_mkdir_p( $pattern_folder );
        $pattern_filename = $pattern_folder . '/' . $pattern_file_name . '.html';
        //echo "Pattern file name: " . $pattern_filename;
        return $pattern_filename;

    }

    function get_pattern_content( $pattern) {
        return $pattern['content'];
    }

	/**
	 * Validates the theme to be installed.
	 *
	 * If valid then it sets up the other filters to support previewing.
	 * @return bool
	 */
    function validate_theme() {
    	$is_valid = $this->check_theme_and_template();
    	if ( $is_valid ) {
		    add_action( 'setup_theme', [$this,'oik_patterns_setup_theme']);
		    add_filter( 'template', [$this,'oik_patterns_template']);
		    add_filter( 'stylesheet', [$this, 'oik_patterns_stylesheet']);
		    add_action( 'init', [$this,'oik_patterns_maybe_cache_patterns'], 9999 );
	    }
    	return $is_valid;
	}

	/**
	 * Checks for the existence of the theme and template.
	 *
	 * Do we need to validate the theme name; can we trust WordPress core functions?
	 */
	function check_theme_and_template() {
		$is_valid = false;
		$theme = wp_get_theme( $this->theme );
	//print_r( $theme );
		if ( $theme->exists() ) {
			$this->template = $theme->get_template();
			if ( $theme->get_stylesheet() === $this->template  ) {
				$is_valid =true;
			} else {
				$template=wp_get_theme( $this->template );
				if (  $template->exists() ) {
					$is_valid =true;
				}
			}
		}
		//print_r( $theme );
		//$this->template = $this->check_theme();

		return $is_valid;

	}


	function oik_patterns_setup_theme( $theme ) {
		//echo "oik patterns setup_theme";
		bw_trace2();
		bw_backtrace();
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
		$template = $this->template;
		return $template;
	}

	/**
	 * Implements `stylesheet` filter.
	 *
	 * @param $stylesheet
	 * @return mixed
	 */
	function oik_patterns_stylesheet( $stylesheet ) {
		$stylesheet = $this->theme;
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

		$this->oik_patterns_cache_patterns();


	}

	function oik_patterns_cache_patterns() {
		oik_require( 'libs/class-oik-patterns-export.php', 'oik-patterns');
		$oik_patterns_export = new OIK_patterns_export( $this->theme );
		$oik_patterns_export->cache_theme_patterns();

	}






}