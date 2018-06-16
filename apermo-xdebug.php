<?php
/**
 * Apermo Xdebug
 *
 * @author      Christoph Daum
 * @copyright   2018 Christoph Daum
 * @license     GPL-2.0+
 * @package     apermo-xdebug
 *
 * @wordpress-plugin
 * Plugin Name: Apermo Xdebug
 * Plugin URI:  https://wordpress.org/plugins/apermo-xdebug/
 * Version:     1.0.1
 * Description: Indents xDebug messages inside the backend, so that these are no longer partly hidden underneath the admin menu. And it will also give you links to directly search for the error message on Google or Stackoverflow.
 * Author:      Christoph Daum
 * Author URI:  https://christoph-daum.de
 * Text Domain: apermo-xdebug
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Apermo Xdebug
 * Copyright (C) 2018, Christoph Daum - c.daum@apermo.de
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'You shall not pass' );
}

/**
 * Class ApermoXdebug
 *
 * Formats Xdebug output inside the WordPress Backend to not interfeer with the Menu.
 */
class ApermoXdebug {
	/**
	 * ApermoXdebug constructor.
	 */
	public function __construct() {
		add_action( 'admin_head', array( $this, 'print_css' ) );
		add_action( 'admin_head', array( $this, 'print_javascript' ) );
	}

	/**
	 * Outputs <style> for the WordPress Backend.
	 * Called by hook: admin_head
	 */
	public function print_css() {
		?>
		<style>
			.xdebug-error {
				float: right;
				width: calc( 100vw - 200px );
				margin-right: 20px;
				margin-bottom: 20px;
				position: relative;
				z-index: 9991;
			}
			/* Had to move this a bit, otherwise links wouldn't be clickable in the Xdebug notice */
			#adminmenuwrap {
				z-index: 9992;
			}

			.sticky-menu.auto-fold .xdebug-error, .folded .xdebug-error {
				width: calc( 100vw - 80px );

			}
			@media screen and (max-width: 782px) {
				.auto-fold .xdebug-error {
					margin-right: 10px;
					width: calc( 100vw - 20px );
				}
			}
			@media screen and (max-width: 600px) {
				.xdebug-error {
					float: none;
					margin-left: 10px;
				}
			}
		</style>
		<?php
	}

	/**
	 * Outputs <javascript> for the WordPress Backend.
	 * Called by hook: admin_head
	 */
	public function print_javascript() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				const wp_path = '<?php echo esc_url( get_home_path() ); ?>';
				console.log( wp_path );
				$('.xdebug-error').each(function () {
					var search_term = encodeURI (
						$(this).find('tr:first-of-type th').html()
							.replace( wp_path, '/' )
							.replace(/<\/?[^>]+(>|$)/g, '')
							.replace( '( ! )', '' )
							.trim()
					);
					$(this).find('tr:first-of-type th')
						.append('<br><a href="https://www.google.de/search?q='+search_term+'" target="_blank">Google for the Error</a>')
						.append(' <a href="https://stackoverflow.com/search?q='+search_term+'" target="_blank">Search Stackoverflow for the Error</a>');
				});
			});
		</script>
		<?php
	}
}

// Run boy, run!
add_action( 'plugins_loaded', function () {
	new ApermoXdebug();
} );
