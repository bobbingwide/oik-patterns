<?php


//$content = 'Classic plastic!';



if ( !function_exists( 'block_writer')) {
	function block_writer( $block_type_name, $atts=null, $content=null ) {
		oik_require_lib( 'oik-blocks' );
		$attributes=\oik\oik_blocks\oik_blocks_atts_encode( $atts );
		$content   =\oik\oik_blocks\oik_blocks_generate_block( $block_type_name, $attributes, $content );

		//echo $this->content;
		return $content;
	}
}

$nested_content[] = '<div class="wp-block-group"><div class="wp-block-group__inner-container">';
$nested_content[] = block_writer( 'core/paragraph', null, 'Person');
$nested_content[] = block_writer( 'core/image', null, '<figure class="wp-block-image"><img alt=""/></figure>') ;
$nested_content[] = '</div></div>';


$content = block_writer( 'core/group', null,implode( '', $nested_content ) );

return array(
	'title'      => __( 'Person', 'oik-blocks' ),
	'categories' => array( 'oik' ),
	'keywords' => array( 'profile', 'person'),
	'content'    => $content
);
