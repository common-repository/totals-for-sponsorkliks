<?php

defined( 'ABSPATH' ) or die( "No editing allowed here, sorry!" );

/*
Plugin Name: Totals for SponsorKliks
Plugin URI:  https://pboekelaar.wordpress.org/plugins/totals-for-sponsorkliks/
Description: This plugin shows you the total commission, earned using SponsorKliks
Version: 0.1.5
Author: Peter Boekelaar
Author URI:  https://pboekelaar.wordpress.org/
Text Domain: totals-for-sponsorkliks
Domain Path: /languages
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Totals for SponsorKliks is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Totals for SponsorKliks is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Totals for SponsorKliks. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/
// Creating the widget
class totals_for_sponsorkliks_widget extends WP_Widget {

    function __construct() {
        parent::__construct(

        // Base ID of your widget
            'totals_for_sponsorkliks_widget',

            // Widget name will appear in UI
            __('Totals for SponsorKliks', 'totals-for-sponsorkliks'),

            // Widget description
            array( 'description' => __( 'This plugin shows you the total commission, earned using SponsorKliks', 'totals-for-sponsorkliks' ), )
        );
    }

    // Creating widget front-end

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $club_id = $instance['club_id'];
        $show_logo = $instance['show_logo'];
        $total_prefix = $instance['total_prefix'];
        $commission_type = $instance['commission_type'];
        $result = get_commissions_total($club_id, $commission_type);
        $fmt = numfmt_create( get_locale(), NumberFormatter::CURRENCY );
        $total = numfmt_format_currency($fmt, $result, "EUR");

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

        if ( $show_logo ) {
            ?><p>
            <img src="<?php echo plugin_dir_url(__FILE__).'sponsorkliks-logo.png';?>"/>
            </p>
            <?php
        }
        // This is where you run the code and display the output
        echo $total_prefix." ".$total;
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form( $instance ) {
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'New title', 'totals-for-sponsorkliks' );
        $show_logo = isset( $instance[ 'show_logo' ] ) ? $instance[ 'show_logo' ] : false;
        $club_id = isset( $instance[ 'club_id' ] ) ? $instance[ 'club_id' ] : '';
        $total_prefix =    isset( $instance[ 'total_prefix'    ] ) ? $instance[ 'total_prefix' ] : __( 'Total commission:', 'totals-for-sponsorkliks' );
        $commission_type =    isset( $instance[ 'commission_type' ] ) ? $instance[ 'commission_type' ] : 'total';
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'club_id' ); ?>"><?php _e( 'Club ID:', 'totals-for-sponsorkliks' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'club_id' ); ?>" name="<?php echo $this->get_field_name( 'club_id' ); ?>" type="text" value="<?php echo esc_attr( $club_id ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'totals-for-sponsorkliks' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_logo' ); ?>"><?php _e( 'Show SponsorKliks logo:', 'totals-for-sponsorkliks' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'show_logo' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_logo' ); ?>" name="<?php echo $this->get_field_name( 'show_logo' ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'commission_type' ); ?>"><?php _e( 'Commission type:', 'totals-for-sponsorkliks' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'commission_type' ); ?>" name="<?php echo $this->get_field_name( 'commission_type' ); ?>" size="1">
				<option value="total" <?php if($commission_type=='total') echo 'selected'; ?>><?php _e( 'Total', 'totals-for-sponsorkliks' ); ?></option>
				<option value="transferred" <?php if($commission_type=='transferred') echo 'selected'; ?>><?php _e( 'Transferred', 'totals-for-sponsorkliks' ); ?></option>
				<option value="qualified" <?php if($commission_type=='qualified') echo 'selected'; ?>><?php _e( 'Qualified', 'totals-for-sponsorkliks' ); ?></option>
				<option value="pending" <?php if($commission_type=='pending') echo 'selected'; ?>><?php _e( 'Pending', 'totals-for-sponsorkliks' ); ?></option>
			</select>
		</p>
        <p>
            <label for="<?php echo $this->get_field_id( 'total_prefix' ); ?>"><?php _e( 'Prefix title amount:', 'totals-for-sponsorkliks' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'total_prefix' ); ?>" name="<?php echo $this->get_field_name( 'total_prefix' ); ?>" type="text" value="<?php echo esc_attr( $total_prefix ); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['show_logo'] = ( ! empty( $new_instance['show_logo'] ) ) ? strip_tags( $new_instance['show_logo'] ) : false;
        $instance['club_id'] = ( ! empty( $new_instance['club_id'] ) ) ? strip_tags( $new_instance['club_id'] ) : '';
        $instance['total_prefix'] = ( ! empty( $new_instance['total_prefix'] ) ) ? strip_tags( $new_instance['total_prefix'] ) : '';
        $instance['commission_type'] = ( ! empty( $new_instance['commission_type'] ) ) ? strip_tags( $new_instance['commission_type'] ) : 'total';
        return $instance;
    }

// Class wpb_widget ends here
}

function nl_money_str_to_float($value) {
    return floatval(str_replace(',','',$value));
}

function get_commissions_total($club=0, $commission_type="total" ) {

    // Standard URL for Sponsorkliks API
    $url = "https://www.sponsorkliks.com/api/?club=$club&call=commissions_total";

    // Check for valid commission type, if not provided, default to 'transferred'
    if (!in_array($commission_type, Array("transferred","qualified","pending","total")))
        $commission_type = "total";

    // Let's do the call
    $response = wp_remote_get($url);
    $responseBody = wp_remote_retrieve_body( $response );

    if ( is_wp_error( $response ) ) {
        return number_format("0", 2);
    } else {
        // Format json to object and return commission type or 0,00
        $commission_total = json_decode($responseBody, true);
        // Return the right amount

        switch ($commission_type) {
            case "qualified":
                $the_money = nl_money_str_to_float($commission_total["commissions_total"]["accepted"]) +
                    nl_money_str_to_float($commission_total["commissions_total"]["qualified"]) +
                    nl_money_str_to_float($commission_total["commissions_total"]["sponsorkliks"]);
                break;
            default:
                $the_money = nl_money_str_to_float($commission_total["commissions_total"][$commission_type]);
                break;
        }
        return strval($the_money);
    }
}

function totals_for_sponsorkliks_shortcode($atts) {

    $atts = shortcode_atts( array(
        "club" => 0,
        "commission" => "total"
    ), $atts );

    // Get the commission for club x with commission type y
    $result = get_commissions_total($atts["club"], $atts["commission"]);
    $fmt = numfmt_create( get_locale(), NumberFormatter::CURRENCY );

    // Return amount in Euro's in local format
    return numfmt_format_currency($fmt, $result, "EUR");
}

function totals_for_sponsorkliks_load_plugin_textdomain() {
    load_plugin_textdomain( 'totals_for_sponsorkliks', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

// Register and load the widget
function totals_for_sponsorkliks_load_widget() {
    register_widget( 'totals_for_sponsorkliks_widget' );
}

// Load the actions and shortcode
add_shortcode("totals-for-sponsorkliks", "totals_for_sponsorkliks_shortcode");
add_action( 'plugins_loaded', 'totals_for_sponsorkliks_load_plugin_textdomain' );
add_action( 'widgets_init', 'totals_for_sponsorkliks_load_widget' );

?>
