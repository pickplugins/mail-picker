<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 


class class_mail_picker_manage_subscriber{
	
	public function __construct() {

        add_action('init', array( $this, 'check_subscriber' ));
        add_action('init', array( $this, 'add_subscriber' ));
        add_action('init', array( $this, 'unsubscribe' ));
        add_action('init', array( $this, 'remove_subscriber' ));
        add_action('init', array( $this, 'mail_track_open' ));
        add_action('init', array( $this, 'link_click_track' ));

        add_action('init', array( $this, 'confirm_subscribe' ));


    }


    public function mail_track_open(){

            if (preg_match('/mail-track-open/', $_SERVER['REQUEST_URI'])) {

                $parts = isset($_SERVER['REQUEST_URI']) ? basename(esc_url_raw($_SERVER['REQUEST_URI'])) : '';


                $parts = explode('-', $parts);

                $campaign_id = isset($parts[3]) ? sanitize_text_field($parts[3]) : '';
                $subscriber_id = isset($parts[4]) ?  sanitize_text_field($parts[4]) : '';

                $subscriber_id = str_replace('.png','',$subscriber_id);


                if (!empty($campaign_id) && !empty($subscriber_id)) {

                    mail_picker_update_post_meta($subscriber_id, 'mail_open_'.$campaign_id, $subscriber_id);

                    $total_mail_open	= get_post_meta( $campaign_id, 'total_mail_open', true);
                    update_post_meta($campaign_id, 'total_mail_open', (int)$total_mail_open+1);

                }

                die();
            }

    }



    public function link_click_track(){


        if (isset($_REQUEST['mail_picker_action']) && trim($_REQUEST['mail_picker_action']) == 'link_click') {

            $campaign_id = isset($_REQUEST['campaign_id']) ? sanitize_text_field($_REQUEST['campaign_id']) : '';
            $subscriber_id = isset($_REQUEST['subscriber_id']) ? sanitize_text_field($_REQUEST['subscriber_id']) : '';
            $redirect = isset($_REQUEST['redirect']) ? sanitize_text_field($_REQUEST['redirect']) : '';


            mail_picker_update_post_meta($subscriber_id, 'link_click_'.$campaign_id, $subscriber_id);

            $total_link_click	= get_post_meta( $campaign_id, 'total_link_click', true);
            update_post_meta($campaign_id, 'total_link_click', (int)$total_link_click+1);

            wp_safe_redirect($redirect);
            exit;

        }


    }


    public function confirm_subscribe(){


        if (isset($_REQUEST['mail_picker_action']) && trim($_REQUEST['mail_picker_action']) == 'confirm_subscribe') {

            $subscriber_form_id = isset($_REQUEST['subscriber_form_id']) ? sanitize_text_field($_REQUEST['subscriber_form_id']) : '';
            $subscriber_id = isset($_REQUEST['subscriber_id']) ? sanitize_text_field($_REQUEST['subscriber_id']) : '';
            $redirect = isset($_REQUEST['redirect']) ? sanitize_text_field($_REQUEST['redirect']) : '';

            $subscriber_status_after_confirm	= get_post_meta( $subscriber_form_id, 'subscriber_status_after_confirm', true);
            $send_welcome_mail	= get_post_meta( $subscriber_form_id, 'send_welcome_mail', true);
            $welcome_mail_template	= get_post_meta( $subscriber_form_id, 'welcome_mail_template', true);


            update_post_meta($subscriber_id, 'subscriber_status', $subscriber_status_after_confirm);
            $gmt_offset = get_option('gmt_offset');

            $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));

            update_post_meta($subscriber_id, 'last_active', $current_datetime);


            mail_picker_update_post_meta($subscriber_id, 'confirm_subscribe_'.$subscriber_form_id, $subscriber_id);
            update_post_meta($subscriber_id, 'is_confirm', 'yes');

            $total_confirm	= get_post_meta( $subscriber_form_id, 'total_confirm', true);
            update_post_meta($subscriber_form_id, 'total_confirm', (int)$total_confirm+1);


            if($welcome_mail_template == 'yes'){



                $class_mail_picker_emails = new class_mail_picker_emails();


                $mail_subject 	= get_post_meta( $subscriber_form_id, 'confirmation_mail_subject', true);
                $from_email 	= get_post_meta( $subscriber_form_id, 'confirmation_mail_from_email', true);
                $from_name 	= get_post_meta( $subscriber_form_id, 'confirmation_mail_from_name', true);
                $reply_to_email 	= get_post_meta( $subscriber_form_id, 'confirmation_mail_reply_to_email', true);
                $reply_to_name 	= get_post_meta( $subscriber_form_id, 'confirmation_mail_reply_to_name', true);



                $admin_email = get_option('admin_email');
                $site_name = get_bloginfo('name');
                $site_description = get_bloginfo('description');
                $site_url = get_bloginfo('url');
                $site_logo_url = get_bloginfo('url');

                $subscriber_email	= get_post_meta( $subscriber_id, 'subscriber_email', true);
                $subscriber_phone 	= get_post_meta( $subscriber_id, 'subscriber_phone', true);
                $subscriber_country_code 	= get_post_meta( $subscriber_id, 'subscriber_country_code', true);
                $subscriber_country = '';
                $first_name 	= get_post_meta( $subscriber_id, 'first_name', true);
                $last_name 	= get_post_meta( $subscriber_id, 'last_name', true);
                $subscriber_name = $first_name.' '.$last_name;
                $subscriber_avatar = get_avatar($subscriber_email,'50');
                $subscriber_rating 	= get_post_meta( $subscriber_id, 'subscriber_rating', true);
                $subscriber_status 	= get_post_meta( $subscriber_id, 'subscriber_status', true);

                $mail_template_data = get_post( $welcome_mail_template );

                $mail_template_content	= $mail_template_data->post_content;

                $mail_template_content = do_shortcode($mail_template_content);
                $mail_template_content = wpautop($mail_template_content);


                $vars = array(
                    '{site_name}'=> $site_name,
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
                $email_data['mail_from'] = $from_email ;
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


    }






    public function check_subscriber(){

        if (isset($_REQUEST['mail_picker_action']) && trim($_REQUEST['mail_picker_action']) == 'check_subscriber') {

            $response = array();


            $subscriber_email = isset($_REQUEST['email']) ? sanitize_email($_REQUEST['email']) : '';
            $first_name = isset($_REQUEST['first_name']) ? sanitize_text_field($_REQUEST['first_name']) : '';
            $last_name = isset($_REQUEST['last_name']) ? sanitize_text_field($_REQUEST['last_name']) : '';


            $meta_query[] = array(
                'key' => 'subscriber_email',
                'value' => $subscriber_email,
                'compare' => '=',
            );

            $wp_query = new WP_Query(
                array (
                    'post_type' => 'subscriber',
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'meta_query' => $meta_query,
                    'order' => 'DESC',
                    'posts_per_page' => -1,
                )
            );

            if ($wp_query->have_posts()):

                $response['subscriber_found'] = 'yes';

                while ( $wp_query->have_posts() ) : $wp_query->the_post();

                    $subscriber_id = get_the_ID();
                    $subscriber_email = get_post_meta($subscriber_id, 'subscriber_email', true);


                    $response['subscriber_email'] = $subscriber_email;
                    $response['subscriber_id'] = $subscriber_id;


                endwhile;

                wp_reset_query();
            else:

                $args['subscriber_email'] = $subscriber_email;
                $args['first_name'] = $first_name;
                $args['last_name'] = $last_name;
                $args['subscriber_status'] = 'pending';


                //$create_response = $this->create_subscriber($args);

                $response['subscriber_found'] = 'no';


            endif;






            echo json_encode($response);
            exit(0);
        }

    }


    public function add_subscriber(){

        if (isset($_REQUEST['mail_picker_action']) && trim($_REQUEST['mail_picker_action']) == 'add_subscriber') {

            $response = array();



            $formFieldData = isset($_REQUEST['formFieldData']) ? mail_picker_recursive_sanitize_arr($_REQUEST['formFieldData']) : array();
            $formFieldData =  unserialize(base64_decode($formFieldData));


            $subscriber_email = isset($formFieldData['subscriber_email']) ? sanitize_email($formFieldData['subscriber_email']) : '';
            $first_name = isset($formFieldData['first_name']) ? sanitize_text_field($formFieldData['first_name']) : '';
            $last_name = isset($formFieldData['last_name']) ? sanitize_text_field($formFieldData['last_name']) : '';
            $subscriber_status = isset($formFieldData['subscriber_status']) ? sanitize_text_field($formFieldData['subscriber_status']) : 'pending';

            $subscriber_list = isset($_REQUEST['subscriber_list']) ? mail_picker_recursive_sanitize_arr( $_REQUEST['subscriber_list'] ) : array();



            $meta_query[] = array(
                'key' => 'subscriber_email',
                'value' => $subscriber_email,
                'compare' => '=',
            );

            $wp_query = new WP_Query(
                array (
                    'post_type' => 'subscriber',
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'meta_query' => $meta_query,
                    'order' => 'DESC',
                    'posts_per_page' => -1,
                )
            );

            if ($wp_query->have_posts()):

                $response['subscriber_found'] = 'yes';

                while ( $wp_query->have_posts() ) : $wp_query->the_post();

                    $subscriber_id = get_the_ID();
                    $subscriber_email = get_post_meta($subscriber_id, 'subscriber_email', true);


                    $response['subscriber_email'] = $subscriber_email;
                    $response['subscriber_id'] = $subscriber_id;


                endwhile;


                $response['message'] = __('Subscriber already exist.', 'mail-picker');
                $response['status'] = 'exist';

                wp_reset_query();
            else:

                $args['formFieldData'] = $formFieldData;

                $args['subscriber_email'] = $subscriber_email;
                $args['first_name'] = $first_name;
                $args['last_name'] = $last_name;
                $args['subscriber_status'] = $subscriber_status;
                $args['subscriber_list'] = $subscriber_list;



                $response = $this->create_subscriber($args);;


            endif;






            echo json_encode($response);
            exit(0);
        }

    }



    public function unsubscribe(){

        if (isset($_REQUEST['mail_picker_action']) && trim($_REQUEST['mail_picker_action']) == 'unsubscribe') {

            $response = array();


            $subscriber_email = isset($_REQUEST['email']) ? sanitize_email($_REQUEST['email']) : '';
            $first_name = isset($_REQUEST['first_name']) ? sanitize_text_field($_REQUEST['first_name']) : '';
            $last_name = isset($_REQUEST['last_name']) ? sanitize_text_field($_REQUEST['last_name']) : '';


            $meta_query[] = array(
                'key' => 'subscriber_email',
                'value' => $subscriber_email,
                'compare' => '=',
            );

            $wp_query = new WP_Query(
                array (
                    'post_type' => 'subscriber',
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'meta_query' => $meta_query,
                    'order' => 'DESC',
                    'posts_per_page' => -1,
                )
            );

            if ($wp_query->have_posts()):

                $response['subscriber_found'] = 'yes';

                while ( $wp_query->have_posts() ) : $wp_query->the_post();

                    $subscriber_id = get_the_ID();
                    $subscriber_email = get_post_meta($subscriber_id, 'subscriber_email', true);


                    $response['subscriber_email'] = $subscriber_email;
                    $response['subscriber_id'] = $subscriber_id;

                    update_post_meta( $subscriber_id, 'subscriber_status', 'canceled' );

                    $response['subscriber_status'] = 'canceled';
                    $response['message'] = 'Subscriber status canceled';



                endwhile;

                wp_reset_query();
            else:


                $response['subscriber_found'] = 'no';
                $response['message'] = 'Not found';


            endif;






            echo json_encode($response);
            exit(0);
        }

    }


    public function remove_subscriber(){

        if (isset($_REQUEST['mail_picker_action']) && trim($_REQUEST['mail_picker_action']) == 'remove_subscriber') {

            $response = array();


            $subscriber_email = isset($_REQUEST['email']) ? sanitize_email($_REQUEST['email']) : '';
            $first_name = isset($_REQUEST['first_name']) ? sanitize_text_field($_REQUEST['first_name']) : '';
            $last_name = isset($_REQUEST['last_name']) ? sanitize_text_field($_REQUEST['last_name']) : '';


            $meta_query[] = array(
                'key' => 'subscriber_email',
                'value' => $subscriber_email,
                'compare' => '=',
            );

            $wp_query = new WP_Query(
                array (
                    'post_type' => 'subscriber',
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'meta_query' => $meta_query,
                    'order' => 'DESC',
                    'posts_per_page' => -1,
                )
            );

            if ($wp_query->have_posts()):

                $response['subscriber_found'] = 'yes';

                while ( $wp_query->have_posts() ) : $wp_query->the_post();

                    $subscriber_id = get_the_ID();
                    $subscriber_email = get_post_meta($subscriber_id, 'subscriber_email', true);

                    $response['subscriber_id'] = $subscriber_id;

                    if(wp_delete_post($subscriber_id, false)){
                        $response['is_removed'] = true;

                    }else{
                        $response['is_removed'] = false;

                    }


                endwhile;

                wp_reset_query();
            else:


                $response['subscriber_found'] = 'no';


            endif;






            echo json_encode($response);
            exit(0);
        }

    }


    public function create_subscriber($args){

		$response = array();

        $formFieldData = isset($args['formFieldData']) ? ($args['formFieldData']) : array();

		$subscriber_email = isset($args['subscriber_email']) ? sanitize_email($args['subscriber_email']) : '';
		$subscriber_status = isset($args['subscriber_status']) ? sanitize_text_field($args['subscriber_status']) : '';
        $subscriber_list = isset($args['subscriber_list']) ? mail_picker_recursive_sanitize_arr( $args['subscriber_list'] ) : array();


		
		if(!empty($subscriber_email)):
		
			$post_data = array(
				'post_author' => 1,
				'post_status' => 'publish',
				'post_type' => 'subscriber',
			);
			
			$post_id = wp_insert_post($post_data);
			
			
			$post_data = array(
			  'ID'           => $post_id,
			  'post_title'   => '#'.$post_id,
			 // 'post_content' => 'This is the updated content.',
			);
			
			// Update the post into the database
			wp_update_post( $post_data );	


			foreach ($formFieldData as $formFieldIndex => $formFieldValue){

                update_post_meta( $post_id, $formFieldIndex, $formFieldValue );


            }

			update_post_meta( $post_id, 'subscriber_status', $subscriber_status );


            wp_set_post_terms( $post_id, $subscriber_list, 'subscriber_list' );

	
			if(!empty($meta_data))
			foreach($meta_data as $meta_key=>$meta_value){
				update_post_meta( $post_id, $meta_key, $meta_value );
			}
							
			$response['message'] = __('Subscriber created.', 'mail-picker');
			$response['status'] = 'success';
            $response['subscriber_id'] = $post_id;

            do_action('mail_picker_subscriber_created', $post_id);


        else:
			$response['message'] = __('Subscriber create failed.', 'mail-picker');
			$response['status'] = 'fail';

            do_action('mail_picker_subscriber_create_failed', $response);


        endif;


		return $response;

	}





}

new class_mail_picker_manage_subscriber();