<?php
add_filter( 'timber/twig', 'add_to_twig' );

/**
 * My custom Twig functionality.
 *
 * @param \Twig\Environment $twig
 *
 * @return \Twig\Environment
 */
function add_to_twig( $twig ) {

	//Helpers
	$twig->addFunction( new Timber\Twig_Function( 'pvd', 'pvd' ) );
	$twig->addFunction( new Timber\Twig_Function( 'get_image', 'get_image' ) );
	$twig->addFunction( new Timber\Twig_Function( 'inline_svg', 'inline_svg' ) );
	$twig->addFunction( new Timber\Twig_Function( 'get_clean_phone', 'get_clean_phone' ) );
	$twig->addFunction( new Timber\Twig_Function( 'regex_text_to_span', 'regex_text_to_span' ) );

	return $twig;
}
