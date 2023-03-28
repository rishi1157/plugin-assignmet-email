<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rishabh.com
 * @since      1.0.0
 *
 * @package    Email_Post
 * @subpackage Email_Post/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Email_Post
 * @subpackage Email_Post/admin
 * @author     Rishabh  <rishabh.pandey@wisdmlabs.com>
 */
class Email_Post_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Post_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Post_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/email-post-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Post_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Post_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/email-post-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function get_post_content() {
		$args = array(
			'date_query' => array(
				array( 'after' => '24 hours ago', ),
			),
		);
		$query = new WP_Query($args);
		$posts = $query->posts();
		$content = array();

		foreach ( $posts as $post ) {
			$post_data = array(
				'title' => $post->post_title,
				'url' => get_permalink( $post->ID ),
				'meta_title' => get_post_meta($post->ID),
				'meta_description' => get_post_meta($post->ID),
				'meta_keywords' => get_post_meta($post->ID),
				'page_speed' => $this->get_page_speed( get_permalink( $post->ID ) ),
			);
			array_push($content, $post_data);
		}
		return $content;
	}

	public function get_page_speed($url) {
		$api_key = '416ca0ef-63e4-4caa-a047-ead672ecc874';
		$new_url = 'http://www.webpagetest.org/runtest.php?url=' . $url . '&runs=1&f=xml&k=' . $api_key;
		$result = simplexml_load_file($new_url);
		$status = $result -> statusCode;
		if ($status == 400) {
			return "Limit Exceeded!!";
		}
		else {
			$test_id = $result->data->testId;
			$status_code = 100;
			while( $status_code != 200) {
				sleep(50);
				$xml_result = simplexml_load_file( 'http://www.webpagetest.org/xmlResult/' . $test_id . '/' );
				$status_code = $xml_result->statusCode;
				$time = (float)($xml_result->data->median->firstView->loadTime) / 100;
			};
			return $time;
		}
	}

	public function send_daily_mail() {
		$content = $this->get_post_content();
		$message = '';
		foreach ($content as $data) {
			$message .= 'Title: ' . $data['title'] . '\n';
			$message .= 'URL: ' . $data['url'] . '\n';
			$message .= 'Meta Title: ' . $data['meta_title'] . '\n';
			$message .= 'Meta Description: ' . $data['meta_description'] . '\n';
			$message .= 'Meta Keywords: ' . $data['meta_keywords'] . '\n';
			$message .= 'Page Speed Score: ' . $data['page_speed'] . '\n';
			$message .= '\n';
		}
		$headers = array(
			'From: rishabh.pandey@wisdmlabs.com',
			'Content-Type: text/html; charset-UTF-8'
		);

		wp_mail(get_option('admin_email'), 'Summary of daily posts', $message, $headers);
	}

}
