<?php
/**
 * Plugin Name: Church Pack Pro—Upcoming Events
 * Plugin URI: http://code.andrewrminion.com/
 * Description: A mini WordPress plugin based on Church Pack Pro that adds support for a year’s worth of events rather than just the current month.
 * Version: 1.0
 * Author: Andrew Minion
 * Author URI: http://andrewrminion.com
 * Copyright 2014  AndrewRMinion Design  (email : andrew@andrewrminion.com)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License, version 2, as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// security check
defined( 'ABSPATH' ) or die();

// add shortcode to embed upcoming events calendar
add_shortcode('upcoming_events', 'wpfc_display_upcoming_events_shortcode');
function wpfc_display_upcoming_events_shortcode($atts) {
	ob_start(); ?>
<div id="church-pack" class="calselect">
<?php
    for ($i = 0; $i <= 5; $i++) {
        $month = date( 'n' ) + $i;
        if ($month <= 12) {
            $year = date( 'Y' );
        } else {
            $month = $month - 12;
            $year = date ( 'Y' ) + 1;
        }
    ?>
        <div class="calHead">
            <h2 class="event-month"><?php if ( function_exists( wpfc_monthname ) ) { echo wpfc_monthname( $month , $year ); } ?></h2>
        </div>
        <div class="calentries"><?php if ( function_exists( wpfc_get_the_calendar() ) ) { echo wpfc_get_the_calendar( $month , $year ); } ?></div>
    <?php }
?>
</div>
	<?php
	$buffer = ob_get_clean();
	return $buffer;
}
// end shortcode

// add CSS styles
function add_upcoming_styles() {
    $custom_css = "
    #church-pack .calentries {
        margin-bottom: 60px;
    }
    ";
    wp_add_inline_style( 'church-pack', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'add_upcoming_styles' );
// end add CSS styles
?>
