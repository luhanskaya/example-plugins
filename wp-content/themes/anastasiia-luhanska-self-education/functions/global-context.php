<?php

if (class_exists('Timber')) {
	$timber = new Timber\Timber();
	$timber::$dirname = array('templates', 'blocks', 'layouts');

	add_filter('timber/context', 'add_to_context');
}

function add_to_context($context)
{
	$context['homelink'] = get_home_url();
	$context['menu']  = new Timber\Menu();

	$context['object'] = get_queried_object();
	return $context;
}
