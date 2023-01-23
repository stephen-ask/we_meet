<?php

namespace Etn\Core\Zoom_Meeting;

defined( 'ABSPATH' ) || exit;

/**
 * Zoom module helper function
 */
class Helper {

	/**
	 * Send Email With Zoom Details
	 *
	 * @param [type] $order_id
	 * @param [type] $order
	 *
	 * @since 2.4.1
	 *
	 * @return void
	 */
	public static function send_email_with_zoom_meeting_details( $order_id, $order, $report_event_id = null, $gateway = 'woocommerce' ) {

		$zoom_event_count  = 0;
		$mail_body_content = '';

		if ( $gateway == 'woocommerce' ) {
				foreach ( $order->get_items() as $item_id => $item ) {
						// Get the product name
						$product_name = $item->get_name();
						$event_id     = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";

						if ( !empty( $event_id ) ) {
								$product_post = get_post( $event_id );
						} else {
								$product_post = get_page_by_title( $product_name, OBJECT, 'etn' );
						}

						if (  ( empty( $report_event_id ) && !empty( $product_post ) ) || ( !empty( $product_post ) && !empty( $event_id ) && ( $report_event_id == $product_post->ID ) ) ) {
								//this is an Eventin event
								$event_id      = $product_post->ID;
						}
				}  
		} else {
				$event_id = $report_event_id;
		}
		

		$is_zoom_event = self::check_if_zoom_event( $event_id );

		if ( $is_zoom_event ) {
				$zoom_event_count++; //zoom event found in this order
				$mail_body_content .= self::zoom_mail_body_content($event_id, $mail_body_content);
		}

		// send email with zoom event details
		if ( $zoom_event_count > 0 ) {
				self::zoom_email_event_details($order_id, $mail_body_content);
		}

		return;
	}

	/**
	 * prepare zoom email body
	 *
	 * @param [int] $event_id
	 * @param [string] $mail_body_content
	 *
	 * @return string
	 */
	public static function zoom_mail_body_content( $event_id = null, $mail_body_content = '') {

			$event_name        = get_the_title( $event_id );
			$event_link        = ( \Etn\Utils\Helper::is_recurrence( $event_id ) ) ? get_the_permalink( wp_get_post_parent_id( $event_id ) ) : get_the_permalink( $event_id );
			$zoom_meeting_id   = get_post_meta( $event_id, 'etn_zoom_id', true );
			$zoom_meeting_url  = get_post_meta( $zoom_meeting_id, 'zoom_join_url', true );
			$meeting_password  = get_post_meta( $zoom_meeting_id, 'zoom_password', true );
			$date_options      = \Etn\Utils\Helper::get_date_formats();
			$event_options     = get_option( "etn_event_options" );
			$etn_start_date    = strtotime( get_post_meta( $event_id, 'etn_start_date', true ) );
			$etn_start_time    = strtotime( get_post_meta( $event_id, 'etn_start_time', true ) );
			$etn_end_date      = strtotime( get_post_meta( $event_id, 'etn_end_date', true ) );
			$etn_end_time      = strtotime( get_post_meta( $event_id, 'etn_end_time', true ) );
			$event_time_format = empty( $event_options["time_format"] ) ? '12' : $event_options["time_format"];
			$event_start_date  = ( isset( $event_options["date_format"] ) && $event_options["date_format"] !== '' ) ? date_i18n( $date_options[$event_options["date_format"]], $etn_start_date ) : date_i18n( get_option( 'date_format' ), $etn_start_date );
			$event_start_time  = ( $event_time_format == "24" || $event_time_format == "" ) ? date_i18n( 'H:i', $etn_start_time ) : date_i18n( get_option( 'time_format' ), $etn_start_time );
			$event_end_time    = ( $event_time_format == "24" || $event_time_format == "" ) ? date_i18n( 'H:i', $etn_end_time ) : date_i18n( get_option( 'time_format' ), $etn_end_time );
			$event_end_date    = '';

			if ( $etn_end_date ) {
					$event_end_date = isset( $event_options["date_format"] ) && ( "" != $event_options["date_format"] ) ? date_i18n( $date_options[$event_options["date_format"]], $etn_end_date ) : date_i18n( get_option( 'date_format' ), $etn_end_date );
			}

			ob_start();
			?>
			<div class="etn-invoice-zoom-event">
					<span class="etn-invoice-zoom-event-title">
							<?php echo esc_html( $event_name ) . esc_html__( " zoom meeting details : ", "eventin" ); ?>
					</span>
					<div class="etn-invoice-zoom-event-details">
							<?php
							if ( !empty( \Etn\Utils\Helper::get_option( 'invoice_include_event_details' ) ) ) {
							?>
									<div class="etn-invoice-email-event-meta">
											<div>
													<?php echo esc_html__( 'Event Page: ', 'eventin' ); ?>
													<a href="<?php echo esc_url( $event_link ); ?>"><?php echo esc_html__( 'Click here. ', 'eventin' ); ?></a>
											</div>
											<div><?php echo esc_html__( 'Start: ', 'eventin' ) . $event_start_date . " " . $event_start_time; ?></div>
											<div><?php echo esc_html__( 'End: ', 'eventin' ) . $event_end_date . " " . $event_end_time; ?></div>
									</div>
							<?php
							}
							?>

							<div class="etn-zoom-meeting-url">
									<span><?php echo esc_html__( 'Meeting URL: ', 'eventin' ); ?></span>
									<a target="_blank" href="<?php echo esc_url( $zoom_meeting_url ); ?>">
											<?php echo esc_html__( 'Click to join Zoom meeting', 'eventin' ); ?>
									</a>
							</div>
							<?php

							if ( !empty( $meeting_password ) ) {
									?>
									<div class="etn-zoom-meeting-password">
											<span>
													<?php echo esc_html__( 'Meeting Password: ', 'eventin' ) . $meeting_password; ?>
											</span>
									</div>
									<?php
							}
							?>
					</div>
			</div>

			<?php
			$zoom_details = ob_get_clean();
			$mail_body_content .= $zoom_details;
			
			return $mail_body_content;
	}

	/**
	 * prepare zoom email details
	 *
	 * @param [int] $order_id
	 * @param [string] $mail_body_content
	 *
	 * @return void
	 */
	public static function zoom_email_event_details( $order_id = null, $mail_body_content = '') {
			ob_start();
			?>
			<div>
					<?php echo esc_html__( "Your order no: {$order_id} includes Event(s) which will be hosted on Zoom. Zoom meeting details are as follows. ", 'eventin' ); ?>
			</div>
			<br><br>
			<?php
			$mail_body_header = ob_get_clean();
			$mail_body        = $mail_body_header . $mail_body_content;
			$subject          = esc_html__( 'Event zoom meeting details', "eventin" );
			$from             = \Etn\Utils\Helper::get_settings()['admin_mail_address'];
			$from_name        = \Etn\Utils\Helper::retrieve_mail_from_name();

			$to = !empty( get_post_meta( $order_id, '_billing_email', true ) ) ? get_post_meta( $order_id, '_billing_email', true ) : "";

			\Etn\Utils\Helper::send_email( $to, $subject, $mail_body, $from, $from_name );
			update_post_meta( $order_id, 'etn_zoom_email_sent', 'yes' );
	}
	/**
	 * zoom types; meeting, webinar
	 */
	public static function zoom_types() {
		$zoom_types = [ 
				'2' => esc_html__( 'Meeting', 'eventin' ), 
				'5' => esc_html__( 'Webinar', 'eventin' ) 
		];

		return $zoom_types;
	}

	/**
	 * extra zoom settings/options
	 */
	public static function get_zoom_meta_settings( $type = 2, $settings_data = null, $meta_array = [] ) {

			if ( !empty( $settings_data ) ) {
					$meta_array += [
							'zoom_meeting_authentication'   => $settings_data->meeting_authentication,
							'zoom_host_video'               => $settings_data->host_video,
							'zoom_auto_recording'           => $settings_data->auto_recording,
					];

					if ( $type == '2' ) {
							$meta_array             += [
									'zoom_participant_panelists_video'    => $settings_data->participant_video,
									'zoom_waiting_room'         => $settings_data->waiting_room,
									'zoom_join_before_host'     => $settings_data->join_before_host,
									'zoom_mute_upon_entry'      => $settings_data->mute_upon_entry,
							];
					} elseif( $type == '5' ) {
							$meta_array             += [
									'zoom_participant_panelists_video'          => $settings_data->panelists_video,
									'zoom_question_and_answer'      => $settings_data->question_and_answer->enable,
									'zoom_practice_session'         => $settings_data->practice_session,
									'zoom_hd_video'                 => $settings_data->hd_video,
									'zoom_hd_video_for_attendees'   => $settings_data->hd_video_for_attendees,
							];
					}
			}

			return $meta_array;
	}

	/**
	 * sync time delete zoom posts
	 */
	public static function delete_zoom_posts( $post_ids = [] ) {
			if ( !empty( $post_ids ) ) {
					foreach ( $post_ids as $post_id => $zoom_id ) {
							wp_delete_post( $post_id );
					}
			}
	}

	/**
	 * Get zoom meeting data by meeting id
	 *
	 * @param [type] $meeting_id
	 * @return void
	 */
	public static function get_zoom_meetings( $meeting_id = null, $return_additional_data = false ) {
		$return_zoom_meetings = [];

		try {
				if ( empty( $meeting_id ) ) {
						$meetings = get_posts( [
								'post_type'      => 'etn-zoom-meeting',
								'posts_per_page' => -1,
								'post_status'    => 'publish',
						] );

						if ( $return_additional_data ) {
								foreach ( $meetings as $meeting ) {
										$post_id = $meeting->ID;
										$return_zoom_meetings[ $post_id ] = [
												'zoom_id'   => get_post_meta( $post_id, 'zoom_meeting_id', true ),
												'zoom_type' => get_post_meta( $post_id, 'zoom_meeting_type', true ),
										];
								}
						} else {
								foreach ( $meetings as $meeting ) {
										$return_zoom_meetings[ $meeting->ID ] = $meeting->post_title;
								}
						}

						return $return_zoom_meetings;
				} else {
						// return single meeting
				}
		} catch ( \Exception $es ) {
				return [];
		}
	}

	/**
	 * Check If Zoom Event
	 *
	 * @since 2.4.1
	 *
	 * @return bool
	 *
	 * check if a provided event id is zoom event
	 */
	public static function check_if_zoom_event( $event_id ) {
		$is_zoom_event   = get_post_meta( $event_id, 'etn_zoom_event', true );
		$zoom_meeting_id = get_post_meta( $event_id, 'etn_zoom_id', true );

		if ( isset( $is_zoom_event ) && "on" == $is_zoom_event && !empty( $zoom_meeting_id ) ) {
				return true;
		}

		return false;
	}

	/**
	 * Check If Zoom Details Email Sent Already
	 *
	 * @param [type] $order_id
	 *
	 * @since 2.4.1
	 *
	 * @return bool
	 */
	public static function check_if_zoom_email_sent_for_order( $order_id ) {

			$is_email_sent = ( !empty( get_post_meta( $order_id, 'etn_zoom_email_sent', true ) ) && 'yes' === get_post_meta( $order_id, 'etn_zoom_email_sent', true ) ) ? true : false;
			return $is_email_sent;
	}

}
