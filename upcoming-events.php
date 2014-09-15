<?php
/**
 * Plugin Name: Church Pack Pro—Upcoming Events
 * Plugin URI: http://code.andrewrminion.com/
 * Description: A mini WordPress plugin based on Church Pack Pro that adds support for a year’s worth of events rather than just the current month.
 * Version: 1.1
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
            <h2 class="event-month"><?php echo wpfc_ue_monthname( $month , $year ); ?></h2>
        </div>
        <div class="calentries"><?php echo wpfc_ue_get_the_calendar( $month , $year ); ?></div>
    <?php }
?>
</div>
	<?php
	$buffer = ob_get_clean();
	return $buffer;
}
// end shortcode

// copy necessary functions
function wpfc_ue_monthname($month,$year) {
	global $post, $wp_locale;
	if ($month) {
		$output = date_i18n( 'F Y' , mktime(0, 0, 0, $month, 1, $year), false );
	} else {
		$output = date_i18n( 'F Y' , time(), false );
	}
	return $output;
}

function wpfc_ue_get_the_calendar($cmonth,$cyear) {
	global $calentries;
	$calentries = array();
	$output = '';
	wpfc_get_the_events($cmonth,$cyear);

	if($calentries) {
		$calentries = wpfc_subval_sort($calentries,'strdate');
		foreach ($calentries as $cal_the_entry) {

			$tmonth = date_i18n( 'D' , $cal_the_entry['strdate'] , false );
			$caltime = get_post_meta($cal_the_entry['cids'], '_wpfc_timestartentry', true);
			$output .= '<div class="calsingleentry"><div class="daydisplay"><span>' . $tmonth .   '</span><h1>'. date('d', $cal_the_entry['strdate']) . ' </h1></div>';
			$output .= '<div class="shortcalentry"><a href="' . $cal_the_entry['clink'] . '">' . $cal_the_entry['ctitle'] .  '</a>';
			$output .= '<span class="intdesc">';
			if ($caltime){
				$output .=  __('Time: ', 'church-pack') . '' . $caltime . '&nbsp;';
			}
			if ($caltime && $cal_the_entry['clocation']) {
				$output .= '|&nbsp;';
			}
			if ($cal_the_entry['clocation']){
				$output .= __('Location: ', 'church-pack') . '' . $cal_the_entry['clocation']  . ' ';
			}
			$output .= '</span></div></div>';
		}
		return $output;
	} else {
		return '<p>' . __('No events currently scheduled', 'church-pack') . '</p>';
	}
}
// end necessary functions

// add CSS styles
function add_upcoming_styles() {
    wp_enqueue_style( 'church-pack-upcoming-evetns', plugins_url( 'upcoming-events.css', __FILE__ ), 'church-pack' );
}
add_action( 'wp_enqueue_scripts', 'add_upcoming_styles' );
// end add CSS styles
?>
