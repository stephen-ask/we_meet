<?php

namespace Etn\Core\Addons;

defined( 'ABSPATH' ) || exit;

class Helper {

	use \Etn\Traits\Singleton;
	/**
	* Check active module
	*/
	public function check_active_module( $modules_name = '' ){
		$addons_options =  get_option( 'etn_addons_options' );
		$enable_module = false;
		switch ($modules_name) {
			case 'buddy_boss':
				$enable_module = !empty( $addons_options['buddyboss'] ) &&  $addons_options['buddyboss'] == "on" ? true : false;
				break;
			case 'dokan':
				if ( class_exists( 'WeDevs_Dokan' )  ) {
					$enable_module = ( empty( $addons_options['dokan'] ) ||  $addons_options['dokan'] == "on" ) ? true : false;
				} else {
					$enable_module = ( !empty( $addons_options['dokan'] ) &&  $addons_options['dokan'] == "on" ) ? true : false;
				}
				break;
			default:
				break;
		}
		return $enable_module;
	}
}