<?php
if (!defined('ABSPATH'))
	exit();



class MailPickerRestEndPoints
{
	function __construct()
	{
		add_action('rest_api_init', array($this, 'register_routes'));
		add_action('init', array($this, 'mail_track_open'));
	}





	public function register_routes()
	{

		register_rest_route(
			'mail-picker/v2',
			'/check_subscriber',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'check_subscriber'),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mail-picker/v2',
			'/add_subscriber',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'add_subscriber'),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mail-picker/v2',
			'/unsubscribe',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'unsubscribe'),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mail-picker/v2',
			'/remove_subscriber',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'remove_subscriber'),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mail-picker/v2',
			'/mail_track_open',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'mail_track_open'),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mail-picker/v2',
			'/link_click_track',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'link_click_track'),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mail-picker/v2',
			'/confirm_subscribe',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'confirm_subscribe'),
				'permission_callback' => '__return_true',
			)
		);
















		register_rest_route(
			'mail-picker/v2',
			'/update_options',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'update_options'),
				'permission_callback' => function () {
					return current_user_can('manage_options');
				},
			)
		);

		register_rest_route(
			'mail-picker/v2',
			'/get_options',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'get_options'),
				'permission_callback' => function () {
					return current_user_can('manage_options');
				},
			)
		);
	}



	/**
	 * Return update_options
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function update_options($request)
	{
		$response = [];


		$name = isset($request['name']) ? sanitize_text_field($request['name']) : '';
		$value = isset($request['value']) ? post_grid_recursive_sanitize_arr($request['value']) : '';


		$status = update_option($name, $value);

		$response['status'] = $status;

		die(wp_json_encode($response));
	}


	/**
	 * Return get_options
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function get_options($request)
	{
		$response = [];


		$option = isset($request['option']) ? $request['option'] : '';

		//error_log($option);
		$response = get_option($option);

		die(wp_json_encode($response));
	}




	/**
	 * Return confirm_subscribe
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function confirm_subscribe($request)
	{
		$response = [];


		$subscriber_form_id = isset($request['subscriber_form_id']) ? sanitize_text_field($request['subscriber_form_id']) : '';
		$subscriber_id = isset($request['subscriber_id']) ? sanitize_text_field($request['subscriber_id']) : '';
		$redirect = isset($request['redirect']) ? sanitize_text_field($request['redirect']) : '';

		$subscriber_status_after_confirm	= get_post_meta($subscriber_form_id, 'subscriber_status_after_confirm', true);
		$send_welcome_mail	= get_post_meta($subscriber_form_id, 'send_welcome_mail', true);
		$welcome_mail_template	= get_post_meta($subscriber_form_id, 'welcome_mail_template', true);


		update_post_meta($subscriber_id, 'subscriber_status', $subscriber_status_after_confirm);
		$gmt_offset = get_option('gmt_offset');

		$current_datetime = date('Y-m-d H:i:s', strtotime('+' . $gmt_offset . ' hour'));

		update_post_meta($subscriber_id, 'last_active', $current_datetime);


		mail_picker_update_post_meta($subscriber_id, 'confirm_subscribe_' . $subscriber_form_id, $subscriber_id);
		update_post_meta($subscriber_id, 'is_confirm', 'yes');

		$total_confirm	= get_post_meta($subscriber_form_id, 'total_confirm', true);
		update_post_meta($subscriber_form_id, 'total_confirm', (int)$total_confirm + 1);


		if ($welcome_mail_template == 'yes') {



			$class_mail_picker_emails = new class_mail_picker_emails();


			$mail_subject 	= get_post_meta($subscriber_form_id, 'confirmation_mail_subject', true);
			$from_email 	= get_post_meta($subscriber_form_id, 'confirmation_mail_from_email', true);
			$from_name 	= get_post_meta($subscriber_form_id, 'confirmation_mail_from_name', true);
			$reply_to_email 	= get_post_meta($subscriber_form_id, 'confirmation_mail_reply_to_email', true);
			$reply_to_name 	= get_post_meta($subscriber_form_id, 'confirmation_mail_reply_to_name', true);



			$admin_email = get_option('admin_email');
			$site_name = get_bloginfo('name');
			$site_description = get_bloginfo('description');
			$site_url = get_bloginfo('url');
			$site_logo_url = get_bloginfo('url');

			$subscriber_email	= get_post_meta($subscriber_id, 'subscriber_email', true);
			$subscriber_phone 	= get_post_meta($subscriber_id, 'subscriber_phone', true);
			$subscriber_country_code 	= get_post_meta($subscriber_id, 'subscriber_country_code', true);
			$subscriber_country = '';
			$first_name 	= get_post_meta($subscriber_id, 'first_name', true);
			$last_name 	= get_post_meta($subscriber_id, 'last_name', true);
			$subscriber_name = $first_name . ' ' . $last_name;
			$subscriber_avatar = get_avatar($subscriber_email, '50');
			$subscriber_rating 	= get_post_meta($subscriber_id, 'subscriber_rating', true);
			$subscriber_status 	= get_post_meta($subscriber_id, 'subscriber_status', true);

			$mail_template_data = get_post($welcome_mail_template);

			$mail_template_content	= $mail_template_data->post_content;

			$mail_template_content = do_shortcode($mail_template_content);
			$mail_template_content = wpautop($mail_template_content);


			$vars = array(
				'{site_name}' => $site_name,
				'{site_description}' => $site_description,
				'{site_url}' => $site_url,
				'{site_logo_url}' => $site_logo_url,

				'{subscriber_email}' => $subscriber_email,
				'{first_name}' => $first_name,
				'{last_name}' => $last_name,
				'{subscriber_name}' => $subscriber_name,
				'{subscriber_phone}' => $subscriber_phone,
				'{subscriber_country}' => $subscriber_country,
				'{subscriber_avatar}' => $subscriber_avatar,
				'{subscriber_rating}' => $subscriber_rating,
				'{subscriber_status}' => $subscriber_status,
			);

			$vars_args = array();
			$vars_args['subscriber_id'] = $subscriber_id;
			$vars_args['form_id'] = $subscriber_form_id;

			$vars = apply_filters('mail_picker_welcome_mail_vars', $vars, $vars_args);


			$email_data['mail_to'] =  $subscriber_email;
			$email_data['mail_bcc'] =  $reply_to_email;
			$email_data['mail_from'] = $from_email;
			$email_data['mail_from_name'] = $from_name;
			$email_data['reply_to'] = $reply_to_email;
			$email_data['reply_to_name'] = $reply_to_name;

			$email_data['mail_subject'] = strtr($mail_subject, $vars);
			$email_data['mail_body'] = strtr($mail_template_content, $vars);
			$email_data['attachments'] = array();


			$status = $class_mail_picker_emails->send_email($email_data);
		}








		wp_safe_redirect($redirect);
		exit;
	}













	/**
	 * Return link_click_track
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function link_click_track($request)
	{
		$response = [];

		$campaign_id = isset($request['campaign_id']) ? sanitize_text_field($request['campaign_id']) : '';
		$subscriber_id = isset($request['subscriber_id']) ? sanitize_text_field($request['subscriber_id']) : '';
		$redirect = isset($request['redirect']) ? sanitize_text_field($request['redirect']) : '';


		mail_picker_update_post_meta($subscriber_id, 'link_click_' . $campaign_id, $subscriber_id);

		$total_link_click	= get_post_meta($campaign_id, 'total_link_click', true);
		update_post_meta($campaign_id, 'total_link_click', (int)$total_link_click + 1);

		wp_safe_redirect($redirect);
		exit;


		die(wp_json_encode($response));
	}














	/**
	 * Return mail_track_open
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function mail_track_open($request)
	{

		if (preg_match('/mail-track-open/', $_SERVER['REQUEST_URI'])) {

			$parts = isset($_SERVER['REQUEST_URI']) ? basename(esc_url_raw($_SERVER['REQUEST_URI'])) : '';


			$parts = explode('-', $parts);

			$campaign_id = isset($parts[3]) ? sanitize_text_field($parts[3]) : '';
			$subscriber_id = isset($parts[4]) ?  sanitize_text_field($parts[4]) : '';

			$subscriber_id = str_replace('.png', '', $subscriber_id);


			if (!empty($campaign_id) && !empty($subscriber_id)) {

				mail_picker_update_post_meta($subscriber_id, 'mail_open_' . $campaign_id, $subscriber_id);

				$total_mail_open	= get_post_meta($campaign_id, 'total_mail_open', true);
				update_post_meta($campaign_id, 'total_mail_open', (int)$total_mail_open + 1);
			}

			die();
		}
	}



















	/**
	 * Return remove_subscriber
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function remove_subscriber($request)
	{
		$response = [];


		$subscriber_email = isset($request['email']) ? sanitize_email($request['email']) : '';
		$first_name = isset($request['first_name']) ? sanitize_text_field($request['first_name']) : '';
		$last_name = isset($request['last_name']) ? sanitize_text_field($request['last_name']) : '';


		$meta_query[] = array(
			'key' => 'subscriber_email',
			'value' => $subscriber_email,
			'compare' => '=',
		);

		$wp_query = new WP_Query(
			array(
				'post_type' => 'subscriber',
				'post_status' => 'publish',
				'orderby' => 'date',
				'meta_query' => $meta_query,
				'order' => 'DESC',
				'posts_per_page' => -1,
			)
		);

		if ($wp_query->have_posts()) :

			$response['subscriber_found'] = 'yes';

			while ($wp_query->have_posts()) : $wp_query->the_post();

				$subscriber_id = get_the_ID();
				$subscriber_email = get_post_meta($subscriber_id, 'subscriber_email', true);

				$response['subscriber_id'] = $subscriber_id;

				if (wp_delete_post($subscriber_id, false)) {
					$response['is_removed'] = true;
				} else {
					$response['is_removed'] = false;
				}


			endwhile;

			wp_reset_query();
		else :


			$response['subscriber_found'] = 'no';


		endif;



		die(wp_json_encode($response));
	}



























	/**
	 * Return unsubscribe
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function unsubscribe($request)
	{
		$response = [];



		$subscriber_email = isset($request['email']) ? sanitize_email($request['email']) : '';
		$first_name = isset($request['first_name']) ? sanitize_text_field($request['first_name']) : '';
		$last_name = isset($request['last_name']) ? sanitize_text_field($request['last_name']) : '';


		$meta_query[] = array(
			'key' => 'subscriber_email',
			'value' => $subscriber_email,
			'compare' => '=',
		);

		$wp_query = new WP_Query(
			array(
				'post_type' => 'subscriber',
				'post_status' => 'publish',
				'orderby' => 'date',
				'meta_query' => $meta_query,
				'order' => 'DESC',
				'posts_per_page' => -1,
			)
		);

		if ($wp_query->have_posts()) :

			$response['subscriber_found'] = 'yes';

			while ($wp_query->have_posts()) : $wp_query->the_post();

				$subscriber_id = get_the_ID();
				$subscriber_email = get_post_meta($subscriber_id, 'subscriber_email', true);


				$response['subscriber_email'] = $subscriber_email;
				$response['subscriber_id'] = $subscriber_id;

				update_post_meta($subscriber_id, 'subscriber_status', 'canceled');

				$response['subscriber_status'] = 'canceled';
				$response['message'] = 'Subscriber status canceled';



			endwhile;

			wp_reset_query();
		else :


			$response['subscriber_found'] = 'no';
			$response['message'] = 'Not found';


		endif;


		die(wp_json_encode($response));
	}


























	/**
	 * Return add_subscriber
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function add_subscriber($request)
	{
		$response = [];



		$formFieldData = isset($request['formFieldData']) ? mail_picker_recursive_sanitize_arr($request['formFieldData']) : array();
		$formFieldData =  unserialize(base64_decode($formFieldData));


		$subscriber_email = isset($formFieldData['subscriber_email']) ? sanitize_email($formFieldData['subscriber_email']) : '';
		$first_name = isset($formFieldData['first_name']) ? sanitize_text_field($formFieldData['first_name']) : '';
		$last_name = isset($formFieldData['last_name']) ? sanitize_text_field($formFieldData['last_name']) : '';
		$subscriber_status = isset($formFieldData['subscriber_status']) ? sanitize_text_field($formFieldData['subscriber_status']) : 'pending';

		$subscriber_list = isset($request['subscriber_list']) ? mail_picker_recursive_sanitize_arr($request['subscriber_list']) : array();



		$meta_query[] = array(
			'key' => 'subscriber_email',
			'value' => $subscriber_email,
			'compare' => '=',
		);

		$wp_query = new WP_Query(
			array(
				'post_type' => 'subscriber',
				'post_status' => 'publish',
				'orderby' => 'date',
				'meta_query' => $meta_query,
				'order' => 'DESC',
				'posts_per_page' => -1,
			)
		);

		if ($wp_query->have_posts()) :

			$response['subscriber_found'] = 'yes';

			while ($wp_query->have_posts()) : $wp_query->the_post();

				$subscriber_id = get_the_ID();
				$subscriber_email = get_post_meta($subscriber_id, 'subscriber_email', true);


				$response['subscriber_email'] = $subscriber_email;
				$response['subscriber_id'] = $subscriber_id;


			endwhile;


			$response['message'] = __('Subscriber already exist.', 'mail-picker');
			$response['status'] = 'exist';

			wp_reset_query();
		else :

			$args['formFieldData'] = $formFieldData;

			$args['subscriber_email'] = $subscriber_email;
			$args['first_name'] = $first_name;
			$args['last_name'] = $last_name;
			$args['subscriber_status'] = $subscriber_status;
			$args['subscriber_list'] = $subscriber_list;



			$response = $this->create_subscriber($args);;


		endif;






		echo json_encode($response);

		die(wp_json_encode($response));
	}























	/**
	 * Return check_subscriber
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $post_data Post data.
	 */
	public function check_subscriber($request)
	{
		$response = [];

		$subscriber_email = isset($request['email']) ? sanitize_email($request['email']) : '';
		$first_name = isset($request['first_name']) ? sanitize_text_field($request['first_name']) : '';
		$last_name = isset($request['last_name']) ? sanitize_text_field($request['last_name']) : '';


		$meta_query[] = array(
			'key' => 'subscriber_email',
			'value' => $subscriber_email,
			'compare' => '=',
		);

		$wp_query = new WP_Query(
			array(
				'post_type' => 'subscriber',
				'post_status' => 'publish',
				'orderby' => 'date',
				'meta_query' => $meta_query,
				'order' => 'DESC',
				'posts_per_page' => -1,
			)
		);

		if ($wp_query->have_posts()) :

			$response['subscriber_found'] = 'yes';

			while ($wp_query->have_posts()) : $wp_query->the_post();

				$subscriber_id = get_the_ID();
				$subscriber_email = get_post_meta($subscriber_id, 'subscriber_email', true);


				$response['subscriber_email'] = $subscriber_email;
				$response['subscriber_id'] = $subscriber_id;


			endwhile;

			wp_reset_query();
		else :

			$args['subscriber_email'] = $subscriber_email;
			$args['first_name'] = $first_name;
			$args['last_name'] = $last_name;
			$args['subscriber_status'] = 'pending';


			//$create_response = $this->create_subscriber($args);

			$response['subscriber_found'] = 'no';


		endif;



		die(wp_json_encode($response));
	}
















	public function create_subscriber($args)
	{

		$response = array();

		$formFieldData = isset($args['formFieldData']) ? ($args['formFieldData']) : array();

		$subscriber_email = isset($args['subscriber_email']) ? sanitize_email($args['subscriber_email']) : '';
		$subscriber_status = isset($args['subscriber_status']) ? sanitize_text_field($args['subscriber_status']) : '';
		$subscriber_list = isset($args['subscriber_list']) ? mail_picker_recursive_sanitize_arr($args['subscriber_list']) : array();



		if (!empty($subscriber_email)) :

			$post_data = array(
				'post_author' => 1,
				'post_status' => 'publish',
				'post_type' => 'subscriber',
			);

			$post_id = wp_insert_post($post_data);


			$post_data = array(
				'ID'           => $post_id,
				'post_title'   => '#' . $post_id,
				// 'post_content' => 'This is the updated content.',
			);

			// Update the post into the database
			wp_update_post($post_data);


			foreach ($formFieldData as $formFieldIndex => $formFieldValue) {

				update_post_meta($post_id, $formFieldIndex, $formFieldValue);
			}

			update_post_meta($post_id, 'subscriber_status', $subscriber_status);


			wp_set_post_terms($post_id, $subscriber_list, 'subscriber_list');


			if (!empty($meta_data))
				foreach ($meta_data as $meta_key => $meta_value) {
					update_post_meta($post_id, $meta_key, $meta_value);
				}

			$response['message'] = __('Subscriber created.', 'mail-picker');
			$response['status'] = 'success';
			$response['subscriber_id'] = $post_id;

			do_action('mail_picker_subscriber_created', $post_id);


		else :
			$response['message'] = __('Subscriber create failed.', 'mail-picker');
			$response['status'] = 'fail';

			do_action('mail_picker_subscriber_create_failed', $response);


		endif;


		return $response;
	}
}

$BlockPostGrid = new MailPickerRestEndPoints();
