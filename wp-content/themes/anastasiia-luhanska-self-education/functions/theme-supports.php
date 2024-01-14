<?php

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support( 'title-tag');
add_theme_support( 'mv-testimonials');

function alphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Home right sidebar',
		'id'            => 'home_right_1',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );


	register_sidebar( array(
		'name'          => 'Blog Bottom Widget',
		'id'            => 'blog_bottom_widget',
		'before_widget' => '<div class="widget_mv-testimonials">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="mb-5">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'alphabet_widgets_init' );
