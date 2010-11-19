<?php
function smarty_block_html5_article($params, $content)
{
	// Extends default params with passed ones, creating a shortcut by the way
	$p 			= array( 
		'html5' 		=> true,
		'fallbackTag' 	=> 'div',
		'class' 		=> '',
		'id' 			=> ''
	) + (array) $params;
	$tag 		= $p['html5'] ? 'article' : $p['fallbackTag'];
	$classes 	= (is_string($p['class']) ? trim($p['class']) . ' ' : '') . 'article';
	
	return '<' . $tag . ' class="' . $classes . '"' . ( !empty($p['id']) ? ' id="' . $p['id'] . '"' : '' ) . '>' . $content . '</' . $tag . '>';
} 

?>