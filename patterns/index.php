<?php



function oik_blocks_lazy_register_block_patterns() {
	//oik_require( 'patterns/index.php', 'oik-blocks' );
	//oik_blocks_lazy_register_block_patterns();

	if ( class_exists( 'WP_Block_Patterns_Registry' ) ) {
		register_block_pattern( 'text-two-columns', array( 'title'     =>__( 'Two columns dummy', 'oik-blocks' ),
		                                                   'categories'=>array( 'hero' ),
		                                                   'content'   =>'Fern'
		) );
		register_block_pattern_category( 'oik', [ 'label' => _x( 'oik', 'Block pattern category', 'oik-blocks' ) ] );
	}
	oik_blocks_maybe_register_block_pattern( 'oik/person-pattern', 'oik-person' );
	oik_blocks_maybe_register_block_pattern( 'oik/fern-pattern', 'oik-fern' );

	// Attempted duplicate pattern
	oik_blocks_maybe_register_block_pattern( 'oik/fern-pattern', 'oik-fern' );

}

function oik_blocks_maybe_register_block_pattern( $pattern_name, $pattern ) {
	if ( class_exists( 'WP_Block_Patterns_Registry' ) && ! WP_Block_Patterns_Registry::get_instance()->is_registered( $pattern_name ) ) {
		register_block_pattern( $pattern_name, oik_blocks_return_pattern( $pattern ) );
	} else {
		// Maybe it's already registered
	}
}

function oik_blocks_return_pattern( $pattern ) {
	$path = oik_path( "patterns/$pattern.php", 'oik-patterns' );
	return require( $path );

}
