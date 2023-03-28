<?php

/**
 * Fired during plugin activation
 *
 * @link       https://rishabh.com
 * @since      1.0.0
 *
 * @package    Email_Post
 * @subpackage Email_Post/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Email_Post
 * @subpackage Email_Post/includes
 * @author     Rishabh  <rishabh.pandey@wisdmlabs.com>
 */
class Email_Post_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! wp_next_scheduled( 'schedule_mail' ) ) {
			wp_schedule_event( strtotime( 'today 12:00am'), 'daily', 'schedule_mail' );
		}
	}
	
}
