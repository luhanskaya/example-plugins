<?php

/**
 * The template for displaying all page-past-events.
 *
 * This is the template that displays all page-past-events by default.
 * Please note that this is the WordPress construct of page-past-events
 * and that other 'page-past-events' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your page-past-events you can use:
 * /mytheme/templates/page-past-events.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-past-events.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * Template Name: Past Events
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 *
 */

$context = Timber::context();
$testimonials = new Timber\PostQuery([
    array(
        'post_type' => 'mv-testimonials',
        'post_per_page' => 99999,
        'post_status' => 'publish'
    )
]);

$context['post'] = $testimonials;

$context['url_meta'] = esc_attr(get_post_meta(get_the_ID(), 'mv_testimonials_user_url', true));
$context['occupation_meta'] = esc_html__(get_post_meta(get_the_ID(), 'mv_testimonials_occupation', true));
$context['company_meta'] = esc_html__(get_post_meta(get_the_ID(), 'mv_testimonials_company', true));

Timber::render('archive-mv-testimonials.twig', $context);
