<?php

if ( !function_exists('skeleton_theme_vars') ) {

	function skeleton_theme_vars() {
		// Throw variables from back to front end.
		$theme_vars = array(
			'home'   => get_home_url(),
			'isHome' => is_front_page(),
		);

		wp_localize_script('manifest', 'themeVars', $theme_vars);
	}

	add_action('wp_enqueue_scripts', 'skeleton_theme_vars');
}
