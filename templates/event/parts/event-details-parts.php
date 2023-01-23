<?php
namespace Etn\Templates\Event\Parts;
use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Event details class.
 *
 * @since 3.3.9
 */
class EventDetailsParts {

	/**
	 * Process event single tag lists
	 *
	 * @param [type] $event_id
	 *
	 * @return void
	 */
	public static function event_single_tag_list( $single_event_id ) {
		?>
		<div class="etn-event-tag-list">
			<?php
			global $post;
			$etn_terms = wp_get_post_terms($single_event_id, 'etn_tags');
			if ($etn_terms) {
					?>
					<h4 class="etn-tags-title">
							<?php 
							$tag_title = apply_filters( 'etn_event_tag_list_title', esc_html__('Tags', "eventin") ); 
							echo esc_html( $tag_title );
							?>
					</h4>
					<?php
					$output = array();

					if( is_array( $etn_terms ) ){
							foreach ($etn_terms as $term) {
									$term_link =  get_term_link($term->slug, 'etn_tags');
									$output[] = '<a href="' . $term_link . '">' . $term->name . '</a>';
							}
					}
					echo Helper::kses( join(' ', $output) );
			}
			?>
	</div>
		<?php
	}

	/**
	 * Process event single tag lists
	 *
	 * @param [type] $event_id
	 *
	 * @return void
	 */
	public static function event_single_organizers( $etn_organizer_events ) {
		?>
		<div class="etn-widget etn-event-organizers">
			<h4 class="etn-widget-title etn-title">
					<?php 
					$event_organizers_title = apply_filters( 'etn_event_organizers_title', esc_html__("Organizers", "eventin") );
					echo esc_html( $event_organizers_title );
					?> 
			</h4>
			<?php
			$term_details = get_term_by('slug',  $etn_organizer_events, 'etn_speaker_category');
			
			if( !empty($term_details) ){
					$organizer_term_id = $term_details->term_id;

					$data = Helper::post_data_query('etn-speaker', -1 , 'DESC' , [$organizer_term_id] , 'etn_speaker_category' );

					if (isset( $data ) && !empty( $data )) {
							foreach ( $data as $value ) { 
									$social = get_post_meta( $value->ID , 'etn_speaker_socials', true);
									$email = get_post_meta( $value->ID , 'etn_speaker_website_email', true);
									$etn_speaker_company_logo = get_post_meta( $value->ID , 'etn_speaker_company_logo', true);
									$logo = wp_get_attachment_image_src($etn_speaker_company_logo, 'full');
									?>
									<div class="etn-organaizer-item">
											<?php if (isset($logo[0])) { ?>
													<div class="etn-organizer-logo">
															<?php echo wp_get_attachment_image($etn_speaker_company_logo, 'full'); ?>
													</div>
											<?php } ?>
											<h4 class="etn-organizer-name">
													<?php echo esc_html( get_the_title( $value->ID ) ); ?>
											</h4>

											<?php if ($email) { ?>
													<div class="etn-organizer-email">
															<span class="etn-label-name"><?php echo esc_html__('Email :', "eventin"); ?></span>
															<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
													</div>
											<?php } ?>
											<?php if (is_array( $social ) && !empty( $social )) { ?>
													<div class="etn-social">
															<span class="etn-label-name"><?php echo esc_html__('Social :', "eventin"); ?></span>
																	<?php foreach ($social as $social_value) {  ?>
																			<?php $etn_social_class = 'etn-' . str_replace('fab fa-', '', $social_value['icon']); ?>

																			<a href="<?php echo esc_url($social_value["etn_social_url"]); ?>" target="_blank" class="<?php echo esc_attr($etn_social_class); ?>" title="<?php echo esc_attr($social_value["etn_social_title"]); ?>"><i class="etn-icon <?php echo esc_attr($social_value["icon"]); ?>" rel="noopener"></i></a>
																	<?php  } ?>
													</div>
											<?php } ?>
									</div>
							<?php  }
					}
			}
			?>
	</div>
		<?php
	}

	/**
	 * Process event single category list
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	public static function event_single_category( $single_event_id ) {
		$data                   = Helper::single_template_options( $single_event_id );
		$etn_event_socials      = isset( $data['etn_event_socials']) ? $data['etn_event_socials'] : [];
		?>
		<div class="etn-event-meta">
				<div class="etn-event-category">
						<?php
						global $post;
						$etn_cat_terms = wp_get_post_terms( $single_event_id, 'etn_category');
						if ($etn_cat_terms) {
								$output = array();
								if( is_array( $etn_cat_terms ) ){
										foreach ($etn_cat_terms as $term) {
												$term_link =  get_term_link($term->slug, 'etn_category');
												$output[] = '<a  href="' . $term_link . '">' . $term->name . '</a>';
										}
								}
								echo "<span>" . Helper::kses(join(' ', $output)) . "</span>";
						}
						?>
				</div>
				<div class="etn-event-social-wrap">
					<i class="etn-icon etn-share"></i>
					<div class="etn-social xx">
							<?php if (is_array($etn_event_socials)) : ?>
									<?php foreach ($etn_event_socials as $social) : ?>
											<?php $etn_social_class = 'etn-' . str_replace('etn-icon fa-', '', $social['icon']); ?>
											<a href="<?php echo esc_url($social['etn_social_url']); ?>" target="_blank" rel="noopener" class=""> <i class="etn-icon <?php echo esc_attr($social['icon']); ?>"></i> </a>
									<?php endforeach; ?>
							<?php endif; ?>
					</div>
				</div>
		</div>
		<?php
	}

	/**
	 * Process event single category list
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	public static function event_single_sidebar_meta( $single_event_id ) {
		$event_options  = get_option("etn_event_options");
		$data           = Helper::single_template_options( $single_event_id );

		?>
		<div class="etn-event-meta-info etn-widget">
			<ul>
					<?php
					// event date
					if(!isset($event_options["etn_hide_date_from_details"])){
							$separate = !empty($data['event_end_date']) ? ' - ' : '';
							?>
							<li>
									<span> <?php echo esc_html__('Date : ', "eventin"); ?></span>
									<?php echo esc_html( $data['event_start_date']  . $separate . $data['event_end_date'] ) ; ?>
							</li>
							<?php
					}
					?>
					<?php
					// event time
					if ( !isset($event_options["etn_hide_time_from_details"]) && ( !empty( $data['event_start_time'] ) || !empty( $data['event_end_time'] ) )) {
							$separate = !empty($data['event_end_time']) ? ' - ' : '';
							?>
							<li>
									<span><?php echo esc_html__('Time : ', "eventin"); ?></span>
									<?php echo esc_html($data['event_start_time'] . $separate . $data['event_end_time']); ?>
									<span class="etn-event-timezone">
											<?php
											if ( !empty( $data['event_timezone'] ) && !isset($event_options["etn_hide_timezone_from_details"]) ) {
													?>
													(<?php echo esc_html( $data['event_timezone'] ); ?>)
													<?php
											}
											?>
									</span>
							</li>
							<?php
					}
					?>
					<?php 
					if(!empty($data['etn_deadline_value'])){ 
							?>
							<li>
									<span><?php echo esc_html__('Reg. Deadline : ', "eventin"); ?></span>
									<?php echo esc_html($data['etn_deadline_value']); ?>
							</li>
							<?php 
					} 
					?>
					
					<?php
					if( !class_exists('Wpeventin_Pro') || get_post_meta($single_event_id, 'etn_event_location_type', true) != 'new_location' ) {
							if ( !isset($event_options["etn_hide_location_from_details"]) && !empty($data['etn_event_location'])) {
									?>
									<li>
											<span><?php echo esc_html__('Venue : ', "eventin") ?></span>
											<?php echo esc_html($data['etn_event_location']);  ?>
									</li>
									<?php 
							}
					}
					?>
			</ul>
			<?php
			?>
		</div> 
		<?php
	}

}