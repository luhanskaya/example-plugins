<?php
/**
 * Plugin Name: Debug Bar Actions and Filters Addon
 * Plugin URI: https://wordpress.org/plugins/debug-bar-actions-and-filters-addon/
 * Description: This plugin add two more tabs in the Debug Bar to display hooks(Actions and Filters) attached to the current request. Actions tab displays the actions hooked to current request. Filters tab displays the filter tags along with the functions attached to it with priority.
 * Version: 1.5.5
 * Author: Subharanjan
 * Author Email: subharanjanmantri@gmail.com
 * Author URI: http://subharanjan.com/
 * Depends: Debug Bar
 * Text Domain: debug-bar-actions-and-filters-addon
 * Domain Path: /languages
 * License: GPLv2
 *
 * @author  subharanjan
 * @package debug-bar-actions-and-filters-addon
 * @version 1.5.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show admin notice & de-activate itself if debug-bar plugin not active.
 */
if ( ! function_exists( 'debug_bar_action_and_filters_addon_has_parent_plugin' ) ) {
	function debug_bar_action_and_filters_addon_has_parent_plugin() {
		$file = plugin_basename( __FILE__ );

		if ( is_admin() && ( ! class_exists( 'Debug_Bar' ) && current_user_can( 'activate_plugins' ) ) && is_plugin_active( $file ) ) {
			add_action( 'admin_notices', 'rdi_randompostwidget_in_it');

			function rdi_randompostwidget_in_it() {
			echo '<div class="error"><p>\' . \'Activation failed: <strong>Debug Bar</strong> must be activated to use the <strong>Debug Bar Actions and Filters Addon</strong>. %sVisit your plugins page to install and activate.\', \'debug-bar-actions-and-filters-addon\', \'<a href="\' . admin_url( \'plugins.php#debug-bar\' ) . \'">\' ) . \'</a></p></div>\';';
			}

			deactivate_plugins( $file, false, is_network_admin() );

			// Add to recently active plugins list.
			if ( ! is_network_admin() ) {
				update_option( 'recently_activated', array( $file => time() ) + (array) get_option( 'recently_activated' ) );
			} else {
				update_site_option( 'recently_activated', array( $file => time() ) + (array) get_site_option( 'recently_activated' ) );
			}

			// Prevent trying again on page reload.
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}
}

add_action( 'admin_init', 'debug_bar_action_and_filters_addon_has_parent_plugin' );

/**
 * Function to hook with debug_bar_panels filter.
 *
 * @param array $panels list of all the panels in debug bar.
 *
 * @return array $panels modified panels list
 */
if ( ! function_exists( 'debug_bar_action_and_filters_addon_panel' ) ) {
	function debug_bar_action_and_filters_addon_panel( $panels ) {
		load_plugin_textdomain( 'debug-bar-actions-and-filters-addon' );

		require_once( plugin_dir_path( __FILE__ ) . 'class-debug-bar-action-and-filters-addon.php' );

		$panels[] = new Debug_Bar_Actions_Addon_Panel( __( 'Action Hooks', 'debug-bar-actions-and-filters-addon' ), 'debug_bar_action_and_filters_addon_display_actions' );
		$panels[] = new Debug_Bar_Filters_Addon_Panel( __( 'Filter Hooks', 'debug-bar-actions-and-filters-addon' ), 'debug_bar_action_and_filters_addon_display_filters' );

		return $panels;
	}
}
add_filter( 'debug_bar_panels', 'debug_bar_action_and_filters_addon_panel' );


/**
 * Function to display the Actions attached to current request.
 *
 * @return string $output display output for the actions panel
 */
function debug_bar_action_and_filters_addon_display_actions() {
	global $wp_actions;

	$output = '
	<div class="hooks_listing_container">
		<h2><span>' . esc_html__( 'Total Actions in this page load:', 'debug-bar-actions-and-filters-addon' ) . '</span>' . count( $wp_actions ) . '</h2>
		<h3>' . esc_html__( 'List of Action Hooks', 'debug-bar-actions-and-filters-addon' ) . '</h3>
		<ol>';

	foreach ( $wp_actions as $action_key => $action_val ) {
		$output .= '
			<li>' . $action_key . '</li>';
	}

	$output .= "
		</ol>
	</div>\n";

	return $output;
}

/**
 * Function to to check for closures
 *
 * @param   mixed $arg function name
 *
 * @return  boolean $closurecheck return whether or not a closure
 */
function dbafa_is_closure( $arg ) {
	if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
		return false;
	}

	include_once( plugin_dir_path( __FILE__ ) . 'php5.3-closure-test.php' );

	return debug_bar_action_and_filters_addon_is_closure( $arg );
}

/**
 * Function to display the Filters applied to current request.
 *
 * @return string $output display output for the filters panel
 */
function debug_bar_action_and_filters_addon_display_filters() {
	$wp_filter = $GLOBALS['wp_filter'];
	ksort( $wp_filter );

	/* Create header row. */
	$header_row = '
			<tr>
				<th>' . esc_html__( 'Hook', 'debug-bar-actions-and-filters-addon' ) . '</th>
				<th>' . esc_html__( 'Priority', 'debug-bar-actions-and-filters-addon' ) . '</th>
				<th>' . esc_html__( 'Registered callbacks', 'debug-bar-actions-and-filters-addon' ) . '</th>
			</tr>';

	$table = '<table class="debug-bar-table debug-bar-actions-filters">
		<thead>' . $header_row . '
		</thead>
		<tfoot>' . $header_row . '
		</tfoot>
		<tbody>';

	$hook_in_count        = 0;
	$callbacks_registered = array();
	foreach ( $wp_filter as $filter_key => $filter_val ) {
		if ( $filter_val instanceof WP_Hook ) {
			$filter_val = $filter_val->callbacks;
		}

		$filter_count = count( $filter_val );

		$rowspan = '';
		if ( $filter_count > 1 ) {
			$rowspan = ' rowspan="' . $filter_count . '"';
		}

		$table .= '
			<tr>
				<th' . $rowspan . '>' . esc_html( $filter_key ) . '</th>';

		if ( $filter_count > 0 ) {
			ksort( $filter_val );
			$first = true;
			foreach ( $filter_val as $priority => $functions ) {
				if ( $first !== true ) {
					$table .= '
			<tr>';
				} else {
					$first = false;
				}

				$table .= '
				<td class="prio">' . intval( $priority ) . '</td>
				<td><ul>';

				foreach ( $functions as $single_function ) {
					$signature = $single_function['function'];
					if ( ( ! is_string( $single_function['function'] ) && ! is_object( $single_function['function'] ) ) && ( ! is_array( $single_function['function'] ) || ( is_array( $single_function['function'] ) && ( ! is_string( $single_function['function'][0] ) && ! is_object( $single_function['function'][0] ) ) ) ) ) {
						// Type 1 - not a callback
						continue;
					} elseif ( dbafa_is_closure( $single_function['function'] ) ) {
						// Type 2 - closure
						$table .= '<li>[<em>' . esc_html__( 'closure', 'debug-bar-actions-and-filters-addon' ) . '</em>]</li>';
						$signature = get_class( $single_function['function'] ) . $hook_in_count;
					} elseif ( is_object( $single_function['function'] ) && is_callable( $single_function['function'] ) ) {
						// Type 3 - invokable class/ object of a class that implements the `__invoke()`/ a function object or functor.
						$signature = esc_html( get_class( $single_function['function'] ) ) . ' -> __invoke';
						$table .= '<li>[<em>' . esc_html__( 'object', 'debug-bar-actions-and-filters-addon' ) . '</em>] ' . $signature . '</li>';
					} elseif ( ( is_array( $single_function['function'] ) || is_object( $single_function['function'] ) ) && dbafa_is_closure( $single_function['function'][0] ) ) {
						// Type 4 - closure within an array
						$table .= '<li>[<em>' . esc_html__( 'closure', 'debug-bar-actions-and-filters-addon' ) . '</em>]</li>';
						$signature = get_class( $single_function['function'] ) . $hook_in_count;
					} elseif ( is_string( $single_function['function'] ) && strpos( $single_function['function'], '::' ) === false ) {
						// Type 5 - simple string function (includes lambda's)
						$signature = sanitize_text_field( $single_function['function'] );
						$table .= '<li>' . $signature . '</li>';
					} elseif ( is_string( $single_function['function'] ) && strpos( $single_function['function'], '::' ) !== false ) {
						// Type 6 - static class method calls - string
						$signature = str_replace( '::', ' :: ', sanitize_text_field( $single_function['function'] ) );
						$table .= '<li>[<em>' . esc_html__( 'class', 'debug-bar-actions-and-filters-addon' ) . '</em>] ' . $signature . '</li>';
					} elseif ( is_array( $single_function['function'] ) && ( is_string( $single_function['function'][0] ) && is_string( $single_function['function'][1] ) ) ) {
						// Type 7 - static class method calls - array
						$signature = sanitize_text_field( $single_function['function'][0] ) . ' :: ' . sanitize_text_field( $single_function['function'][1] );
						$table .= '<li>[<em>' . esc_html__( 'class', 'debug-bar-actions-and-filters-addon' ) . '</em>] ' . $signature . '</li>';
					} elseif ( is_array( $single_function['function'] ) && ( is_object( $single_function['function'][0] ) && is_string( $single_function['function'][1] ) ) ) {
						// Type 8 - object method calls
						$signature = esc_html( get_class( $single_function['function'][0] ) ) . ' -> ' . sanitize_text_field( $single_function['function'][1] );
						$table .= '<li>[<em>' . esc_html__( 'object', 'debug-bar-actions-and-filters-addon' ) . '</em>] ' . $signature . '</li>';
					} else {
						// Type 9 - undetermined
						$table .= '<li><pre>' . var_export( $single_function, true ) . '</pre></li>';
					}

					$hook_in_count ++;
					$callbacks_registered[] = $signature;
				}
				$table .= '</ul></td>
			</tr>';
			}
		} else {
			$table .= '<td>&nbsp;</td><td>&nbsp;</td>
			</tr>';
		}
	}
	$table .= '
		</tbody>
	</table>';

	$unique_callbacks = count( array_unique( $callbacks_registered ) );

	$output = '
	<div class="hooks_listing_container">
		<h2><span>' . esc_html__( 'Total hooks with registered actions/filters:', 'debug-bar-actions-and-filters-addon' ) . '</span>' . count( $wp_filter ) . '</h2>
		<h2><span>' . esc_html__( 'Total registered callbacks:', 'debug-bar-actions-and-filters-addon' ) . '</span>' . $hook_in_count . '</h2>
		<h2><span>' . esc_html__( 'Unique registered callbacks:', 'debug-bar-actions-and-filters-addon' ) . '</span>' . $unique_callbacks . '</h2>
		' . $table . '
	</div>';

	return $output;
}
