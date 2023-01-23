<?php

namespace Etn\Core\Event;

use Etn\Traits\Singleton;

defined( 'ABSPATH' ) || exit;

class Helper{

	use Singleton;

	/**
	* Return currency symbol
	*/
	public function get_currency() {
		$symbol = '';
		if( class_exists('Wpeventin_Pro') && class_exists('\Etn_Pro\Core\Modules\Sells_Engine\Sells_Engine') ) {
			$sells_engine = \Etn_Pro\Core\Modules\Sells_Engine\Sells_Engine::instance()->check_sells_engine();
			if ( 'woocommerce' == $sells_engine ) {
				$symbol = get_woocommerce_currency_symbol();
			}
			else if ( 'stripe' == $sells_engine ) {
				$get_data = \Etn_Pro\Utils\Helper::retrieve_country_currency();
				$symbol   = !empty( $get_data ) ? $get_data['currency'] : '';
			}
		}else{
			$symbol = get_woocommerce_currency_symbol();
		}

		return $symbol;
	}

	/**
	* Add recurring tag
	*/
	public function recurring_tag( $data ){
		if (( is_array($data) && count($data) > 0 )) {
			foreach($data as $index => $post){
				$post_id =	$post->ID;
				$is_recurring_parent = \Etn\Utils\Helper::get_child_events( $post_id );
				if( $is_recurring_parent ){
					$post->etn_recurring = true;
				}
			}
		}
		return $data;
	}

	public function get_event_location( $event_id ){
		$location = '';
		$location_type    = get_post_meta( $event_id, 'etn_event_location_type', true );
        if ( $location_type == 'existing_location' ) {
            $location     = get_post_meta($event_id, 'etn_event_location', true);
        } else {
            $location_arr = maybe_unserialize( get_post_meta($event_id, 'etn_event_location_list', true) );

            if ( !empty( $location_arr ) && is_array( $location_arr ) ) {
                $location_names = [];

                foreach( $location_arr as $index => $location_slug ) {
                    $location_details = get_term_by( 'slug',  $location_slug, 'etn_location' );
                    $location_names[] = $location_details->name;
                }

                $location = join( ', ', $location_names );
            }
        }

        return $location;
	}

	/**
	 * Get Attendee for a event
	 */
	public function attendee_by_events( $query ) {
		if ( ( is_admin() 
			&& ( isset( $_GET['post_type'] ) && $_GET['post_type'] == "etn-attendee") )
			&& ( !empty( $_GET['event_id'] ) )
		 	&& $query->is_search ) {
				$meta_query = [
					'relation' => 'AND',
					[
						'key'     => 'etn_event_id',
						'value'   => $_GET['event_id'],
						'compare' => '=',
					],
				];
				$query->set( 'meta_query', $meta_query );	 
		}

		return $query;
	}
	
	/**
	 *  Global Date format 
	 */
	public function etn_date_format() {
		$settings                = \Etn\Utils\Helper::get_settings();
		$date_options            = \Etn\Utils\Helper::get_date_formats();
		$etn_date_format = !empty( $settings["date_format"] ) ? $date_options[$settings["date_format"]] : get_option( "date_format" );
	
		return $etn_date_format;
	}

	/**
	 *  Get Tickets by Events
	 */
	public function ticket_by_events($event_id=null) {
		$ticket_variations = array();
		$get_tickets = array('tickets' => array(),'ticket_price' => array());
		if ( !is_null($event_id)) {
			$ticket_variations = get_post_meta( $event_id , 'etn_ticket_variations', true );
		}

		if ( !empty($ticket_variations) ) {
			foreach ( $ticket_variations as $key => $value ) {
				$get_tickets['tickets'][$value['etn_ticket_name']] = $value['etn_ticket_name'];
				$get_tickets['ticket_price'][$key] = $value['etn_ticket_price'];
			}
		}

		return $get_tickets;
	}

}


