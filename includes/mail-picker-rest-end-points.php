<?php
if (!defined('ABSPATH'))
	exit();



class MailPickerRestEndPoints
{
	function __construct()
	{
		add_action('rest_api_init', array($this, 'register_routes'));
		add_action('init', array($this, 'mail_track_open'));
		add_action('init', array($this, 'link_click_track'));
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
			'/confirm_subscribe',
			array(
				'methods' => 'POST',
				'callback' => array($this, 'confirm_subscribe'),
				'permission_callback' => '__return_true',
			)
		);
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

		if (isset($_REQUEST['mail_picker_action']) && trim($_REQUEST['mail_picker_action']) == 'link_click') {

			$campaign_id = isset($_REQUEST['campaign_id']) ? sanitize_text_field($_REQUEST['campaign_id']) : '';
			$id = isset($_REQUEST['id']) ? sanitize_text_field($_REQUEST['id']) : '';
			$redirect = isset($_REQUEST['redirect']) ? sanitize_text_field($_REQUEST['redirect']) : '';


			mail_picker_update_post_meta($id, 'link_click_' . $campaign_id, $id);

			$total_link_click	= get_post_meta($campaign_id, 'total_link_click', true);
			update_post_meta($campaign_id, 'total_link_click', (int)$total_link_click + 1);

			wp_safe_redirect($redirect);
			exit;
		}
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
			$id = isset($parts[4]) ?  sanitize_text_field($parts[4]) : '';

			$id = str_replace('.png', '', $id);


			if (!empty($campaign_id) && !empty($id)) {

				mail_picker_update_post_meta($id, 'mail_open_' . $campaign_id, $id);

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


		$email = isset($request['email']) ? sanitize_email($request['email']) : '';



		$meta_query[] = array(
			'key' => 'email',
			'value' => $email,
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

			$response['found'] = 'yes';

			while ($wp_query->have_posts()) : $wp_query->the_post();

				$id = get_the_ID();
				$email = get_post_meta($id, 'email', true);

				$response['id'] = $id;

				if (wp_delete_post($id, false)) {
					$response['is_removed'] = true;
				} else {
					$response['is_removed'] = false;
				}


			endwhile;

			wp_reset_query();
		else :


			$response['found'] = 'no';


		endif;



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


		$email = isset($request['email']) ? sanitize_email($request['email']) : '';



		$meta_query[] = array(
			'key' => 'email',
			'value' => $email,
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

			$response['found'] = 'yes';

			while ($wp_query->have_posts()) : $wp_query->the_post();

				$id = get_the_ID();
				$email = get_post_meta($id, 'email', true);

				$response['status'] = 'active';

				update_post_meta($id, 'status', 'active');



			endwhile;

			wp_reset_query();
		else :


			$response['found'] = 'no';


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



		$email = isset($request['email']) ? sanitize_email($request['email']) : '';



		$meta_query[] = array(
			'key' => 'email',
			'value' => $email,
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

			$response['found'] = 'yes';

			while ($wp_query->have_posts()) : $wp_query->the_post();

				$id = get_the_ID();
				$email = get_post_meta($id, 'email', true);


				$response['email'] = $email;
				$response['id'] = $id;

				update_post_meta($id, 'status', 'unsubscribed');

				$response['status'] = 'unsubscribed';
				$response['message'] = 'Subscriber status unsubscribed';



			endwhile;

			wp_reset_query();
		else :


			$response['found'] = 'no';
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




		$email = isset($request['email']) ? sanitize_email($request['email']) : '';
		$first_name = isset($request['first_name']) ? sanitize_text_field($request['first_name']) : '';
		$last_name = isset($request['last_name']) ? sanitize_text_field($request['last_name']) : '';
		$status = isset($request['status']) ? sanitize_text_field($request['status']) : 'pending';

		$subscriber_list = isset($request['subscriber_list']) ? mail_picker_recursive_sanitize_arr($request['subscriber_list']) : array();


		$meta_query[] = array(
			'key' => 'email',
			'value' => $email,
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

			$response['found'] = 'yes';

			while ($wp_query->have_posts()) : $wp_query->the_post();

				$id = get_the_ID();
				$email = get_post_meta($id, 'email', true);


				$response['email'] = $email;
				$response['id'] = $id;


			endwhile;


			$response['message'] = __('Subscriber already exist.', 'mail-picker');
			$response['status'] = 'exist';

			wp_reset_query();
		else :


			$args['email'] = $email;
			$args['first_name'] = $first_name;
			$args['last_name'] = $last_name;
			$args['status'] = $status;
			$args['subscriber_list'] = $subscriber_list;



			$response = $this->create_subscriber($args);;


		endif;






		//echo json_encode($response);

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

		$email = isset($request['email']) ? sanitize_email($request['email']) : '';
		$id = isset($request['id']) ? sanitize_email($request['id']) : '';
		$first_name = isset($request['first_name']) ? sanitize_text_field($request['first_name']) : '';
		$last_name = isset($request['last_name']) ? sanitize_text_field($request['last_name']) : '';

		if (!empty($email)) {
			$meta_query[] = array(
				'key' => 'email',
				'value' => $email,
				'compare' => '=',
			);
		}


		if (!empty($first_name)) {
			$meta_query[] = array(
				'key' => 'first_name',
				'value' => $first_name,
				'compare' => 'LIKE',
			);
		}
		if (!empty($last_name)) {
			$meta_query[] = array(
				'key' => 'last_name',
				'value' => $last_name,
				'compare' => 'LIKE',
			);
		}




		$wp_query_args = [];
		$wp_query_args["post_type"] = 'subscriber';
		$wp_query_args["post_status"] = 'publish';
		$wp_query_args["orderby"] = 'date';
		$wp_query_args["order"] = 'DESC';
		$wp_query_args["posts_per_page"] = -1;

		if (!empty($meta_query)) {
			$wp_query_args["meta_query"] = $meta_query;
		}

		if (!empty($id)) {
			$wp_query_args["post__in"] = [$id];
		}






		$wp_query = new WP_Query(
			$wp_query_args
		);

		if ($wp_query->have_posts()) :

			$response['found'] = 'yes';
			$posts = [];
			$i = 0;
			while ($wp_query->have_posts()) : $wp_query->the_post();

				$id = get_the_ID();
				$email = get_post_meta($id, 'email', true);
				$first_name = get_post_meta($id, 'first_name', true);
				$last_name = get_post_meta($id, 'last_name', true);
				$status = get_post_meta($id, 'status', true);


				$posts[$i]['email'] = $email;
				$posts[$i]['id'] = $id;
				$posts[$i]['first_name'] = $first_name;
				$posts[$i]['last_name'] = $last_name;
				$posts[$i]['status'] = $status;

				$i++;
			endwhile;
			$response['posts'] = $posts;

			wp_reset_query();
		else :

			$args['email'] = $email;
			$args['first_name'] = $first_name;
			$args['last_name'] = $last_name;
			//$args['status'] = 'pending';


			//$create_response = $this->create_subscriber($args);

			$response['found'] = 'no';


		endif;



		die(wp_json_encode($response));
	}
















	public function create_subscriber($args)
	{

		$response = array();

		error_log(serialize($args));

		$email = isset($args['email']) ? sanitize_email($args['email']) : '';
		$first_name = isset($args['first_name']) ? sanitize_text_field($args['first_name']) : '';
		$last_name = isset($args['last_name']) ? sanitize_text_field($args['last_name']) : '';
		$status = isset($args['status']) ? sanitize_text_field($args['status']) : '';
		$subscriber_list = isset($args['subscriber_list']) ? sanitize_text_field($args['subscriber_list']) : array();



		if (!empty($email)) :

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

			wp_update_post($post_data);



			update_post_meta($post_id, 'email', $email);
			update_post_meta($post_id, 'status', $status);
			update_post_meta($post_id, 'first_name', $first_name);
			update_post_meta($post_id, 'last_name', $last_name);

			error_log($subscriber_list);

			wp_set_post_terms($post_id, $subscriber_list, 'subscriber_list');


			// if (!empty($meta_data))
			// 	foreach ($meta_data as $meta_key => $meta_value) {
			// 		update_post_meta($post_id, $meta_key, $meta_value);
			// 	}

			$response['message'] = __('Subscriber created.', 'mail-picker');
			$response['status'] = 'success';
			$response['id'] = $post_id;

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
