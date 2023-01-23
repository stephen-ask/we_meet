<?php 
$addons_options =  get_option( 'etn_addons_options' );
if(class_exists('Wpeventin_Pro')){
	$buddyboss  = !empty( $addons_options['buddyboss'] ) &&  $addons_options['buddyboss'] == "on" ? "checked" : "";
	$dokan      = (\Etn\Core\addons\Helper::instance()->check_active_module('dokan') ) == true ? "checked" : "";
	$disable_switch = ""; 
} else {
	$buddyboss = "";
	$dokan = "";
	$disable_switch = "disabled"; 
}
 

?>
<div class="etn-admin-sec">
	<div class="etn-row">
		<div class="etn-col-md-5">
			<div class="etn-addons-content">
				<h1 class="etn-main-title">
					<?php echo esc_html__('Extensions to Power Up Your Plugins','eventin'); ?>
				</h1>
				<p class="etn-desc">
					<?php echo esc_html__('Extensions are quick solutions our team came up with to solve specific issues you may need. (Note - extensions are not covered by our support team.)','eventin'); ?>
				</p>
			</div>
		</div>
	</div>
	<form action=""  method="post" id="etn_addons_form">
		<div class="etn-row">
			<div class="etn-col-md-6 etn-col-lg-4">
				<div class="etn-label-item etn-addons-item">
					<div class="etn-label">
						<div class="etn-label-icon">
							<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M63.9993 32C63.9993 49.6735 49.6728 64 31.9993 64C14.3258 64 0 49.6735 0 32C0 14.3265 14.3258 0 31.9993 0C49.6728 0 63.9993 14.3272 63.9993 32Z" fill="#F1634C"/>
								<path d="M21.2461 15.7875C21.2461 15.7875 39.2682 14.4601 39.2682 30.0165C39.2682 45.5729 34.5048 48.665 30.3316 49.6802C30.3316 49.6802 47.6995 53.8619 47.6995 31.9837C47.6995 10.1055 25.5463 13.2588 21.2461 15.7875Z" fill="white"/>
								<path d="M35.9027 42.2427C35.9027 42.2427 34.1618 48.2829 28.9028 49.0468C23.6438 49.8108 22.664 46.9603 19.0084 47.1551C19.0084 47.1551 18.7095 44.3198 21.669 43.9196C24.6284 43.5195 30.1767 44.3016 33.8639 41.9677C33.8639 41.9677 36.046 40.8217 36.4719 40.2698L35.9027 42.2427Z" fill="white"/>
								<path d="M20.9727 16.8743V24.3641V43.0002C21.1568 42.9542 21.3433 42.9181 21.5313 42.8923C22.4213 42.7719 23.5071 42.7519 24.6569 42.7299C25.7417 42.7099 26.9297 42.687 28.1177 42.5771V33.7857C28.1177 30.8655 27.9324 27.8793 28.1177 24.9638C28.2266 23.2554 29.5501 21.6682 31.1306 21.0484C32.5182 20.507 33.9172 20.7075 35.1596 21.313C29.2102 16.9096 22.534 16.8275 20.9727 16.8743Z" fill="white"/>
							</svg>
						</div>
						<div class="etn-label-content">
							<label for="dokan_mod">
								<?php if(!class_exists('Wpeventin_Pro')){ ?>
									<a target="_blank" title="<?php echo esc_attr('Go Pro', 'eventin'); ?>" class="etn-pro-deactive" href="<?php echo esc_url(\Etn\Bootstrap::get_pro_link()) ?>">
										<?php esc_html_e('Dokan', 'eventin'); ?>
									</a> 
								<?php }else{ ?>
									<?php esc_html_e('Dokan', 'eventin'); ?>
								<?php } ?>
							</label>
							<div class="etn-desc">
								<?php esc_html_e('Connect Eventin with Dokan to create and manage a multivendor event marketplace.', 'eventin'); ?>
							</div>
							<span class="etn-badge"><?php echo esc_html__('Pro','eventin'); ?></span>
						</div>
					</div>
					<div class="etn-meta">
						<input type="checkbox" name="dokan" class="etn-admin-control-input" value="off" <?php echo esc_attr( $dokan ) ?>  />
						<input type="checkbox" name="dokan" id='dokan_mod' class="etn-admin-control-input" <?php echo esc_attr( $dokan ) ." ". esc_attr( $disable_switch ); ?>  />
						<label for="dokan_mod" class="etn_switch_button_label"></label>
					</div>
				</div>
			</div>
			<div class="etn-col-md-6 etn-col-lg-4">
				<div class="etn-label-item etn-addons-item">
					<div class="etn-label">
						<div class="etn-label-icon">
							<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M63.9993 32C63.9993 49.6735 49.6728 64 31.9993 64C14.3258 64 0 49.6735 0 32C0 14.3265 14.3258 0 31.9993 0C49.6728 0 63.9993 14.3272 63.9993 32Z" fill="#E0623D"/>
								<path d="M39.0831 20.4392C37.2604 20.44 35.455 20.7919 33.7655 21.4757L34.0056 20.1762C34.0056 20.1762 35.6214 13.1936 28.9974 12.7724C28.4691 12.7751 28.3045 13.0471 28.2352 13.4136L26.9385 20.4465C25.1805 20.4683 23.4419 20.8166 21.8111 21.4737L22.0505 20.1741C22.0505 20.1741 23.6663 13.1936 17.043 12.7724C16.5171 12.7744 16.3501 13.044 16.2829 13.4071L12.7374 32.6347C12.5718 33.5057 12.4823 34.3895 12.47 35.276C12.3678 43.4566 18.7752 50.1121 26.7563 50.1121C34.7373 50.1121 41.3125 43.4566 41.4154 35.276C41.4427 33.0691 40.9064 31.5784 39.9551 30.7085C38.6771 29.541 36.4356 29.7731 35.3581 31.216C34.3618 32.5495 34.5131 34.5907 34.5041 35.276C34.4826 36.9888 33.9061 38.6484 32.861 40.0056C31.847 38.6394 31.3113 36.9772 31.3366 35.276C31.39 31.0012 34.826 27.524 38.9948 27.524C43.1635 27.524 46.5126 31.0012 46.4606 35.276C46.4457 36.5934 46.102 37.8863 45.4607 39.0372C44.8194 40.188 43.9008 41.1606 42.7883 41.8664C42.6386 41.9606 42.2503 42.1931 41.9444 42.5274C41.6821 42.8146 41.494 43.2037 41.4109 43.3589C40.1373 45.708 38.3363 47.7298 36.1494 49.2654C36.0257 49.3517 35.8057 49.519 35.8747 49.6894C35.9384 49.8464 36.2925 49.9036 36.4474 49.9285C37.1959 50.052 37.9534 50.114 38.7121 50.1138C46.6917 50.1138 53.2679 43.4583 53.3698 35.2777C53.4716 27.0971 47.0641 20.4392 39.0831 20.4392ZM19.3794 35.276C19.4262 31.5025 22.1108 28.3547 25.6071 27.6674L24.6922 32.6347C24.527 33.5058 24.4376 34.3895 24.425 35.276C24.3904 38.1075 25.136 40.7543 26.4562 43.0069C22.4704 42.7969 19.3285 39.4139 19.3794 35.276Z" fill="white"/>
							</svg>
						</div>
						<div class="etn-label-content">
							<label for="buddyboss_mod"> 
								<?php if(!class_exists('Wpeventin_Pro')){ ?>
									<a target="_blank" title="<?php echo esc_attr('Go Pro', 'eventin'); ?>" class="etn-pro-deactive" href="<?php echo esc_url(\Etn\Bootstrap::get_pro_link()) ?>">
									<?php esc_html_e('BuddyBoss', 'eventin'); ?>
									</a> 
								<?php }else{ ?>
									<?php esc_html_e('BuddyBoss', 'eventin'); ?>
								<?php } ?> 
								</label>
							<div class="etn-desc">
								<?php esc_html_e('Connect Eventin with BuddyBoss and manage events in the border social community.', 'eventin'); ?>
							</div>
							<span class="etn-badge"><?php echo esc_html__('Pro','eventin'); ?></span>
						</div>
					</div>
					<div class="etn-meta">
						<input type="checkbox" name="buddyboss" class="etn-admin-control-input" value="off" <?php echo esc_attr( $buddyboss ) ?>  />
						<input type="checkbox" name="buddyboss" id='buddyboss_mod' class="etn-admin-control-input" <?php echo esc_attr( $buddyboss ) ." ". esc_attr( $disable_switch );?>  />
						<label for="buddyboss_mod" class="etn_switch_button_label"></label>
					</div>
				</div>
			</div>				
		</div>
		<div class="mt-4 etn_submit_wrap">
			<input type="hidden" name="etn_addons_action" value="addons_save">
			<input type="submit" name="submit" id="eventin_addons_submit" class="etn-btn etn-btn-primary etn_save_settings" value="<?php esc_attr_e('Save Change', 'eventin'); ?>">
		</div>
		<?php wp_nonce_field('eventin-addons-page', 'eventin-addons-page'); ?>
	</form>
</div>