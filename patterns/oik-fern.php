<?php


//$content = 'Classic plastic!';

$content = block_writer( 'core/paragraph', null, 'Person');


if ( !function_exists( 'block_writer')) {
	function block_writer( $block_type_name, $atts=null, $content=null ) {
		oik_require_lib( 'oik-blocks' );
		$attributes=\oik\oik_blocks\oik_blocks_atts_encode( $atts );
		$content   =\oik\oik_blocks\oik_blocks_generate_block( $block_type_name, $attributes, $content );

		//echo $this->content;
		return $content;
	}

}
return array(
	'title'      => __( 'Fern', 'oik-blocks' ),
	'categories' => array( 'hero', 'fern' ),
	'keywords' => array( 'fern', 'pteridological' ),
	'content'    => $content
);
