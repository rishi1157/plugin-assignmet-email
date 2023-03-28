<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://rishabh.com
 * @since      1.0.0
 *
 * @package    Email_Post
 * @subpackage Email_Post/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Email_Post
 * @subpackage Email_Post/includes
 * @author     Rishabh  <rishabh.pandey@wisdmlabs.com>
 */
class Email_Post_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'schedule_mail' );
	}

}
