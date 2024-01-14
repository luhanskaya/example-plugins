<?php

$composer_autoload = ABSPATH . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
    require_once $composer_autoload;
}

defined('THEME_TD') ? THEME_TD : define('THEME_TD', 'custom-theme');

// Skeleton activate core
new SkeletonTheme\Core();
