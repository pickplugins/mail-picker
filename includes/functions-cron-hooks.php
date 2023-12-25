<?php

if ( ! defined('ABSPATH')) exit;  // if direct access


add_shortcode('mail_picker_subscriber_source_check', 'mail_picker_subscriber_source_check');
add_action('mail_picker_subscriber_source_check', 'mail_picker_subscriber_source_check');

function mail_picker_subscriber_source_check(){

    $responses = array();
    $meta_query = array();

    $gmt_offset = get_option('gmt_offset');


    $meta_query[] = array(
        'key' => 'subscriber_source_check',
        'value' => 'yes',
        'compare' => '=',
    );

    $campaign_query = new WP_Query(
        array (
            'post_type' => 'subscriber_source',
            'post_status' => 'publish',
            'orderby' => 'date',
            //'meta_query' => $meta_query,
            'order' => 'DESC',
            'posts_per_page' => 1,
            'paged' => 1,

        )
    );

    if ($campaign_query->have_posts()):

        $responses['subscriber_source_found'] = 'yes';

        while ( $campaign_query->have_posts() ) :

            $campaign_query->the_post();

            $post_id = get_the_ID();
            $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));
            $last_check_datetime = get_post_meta($post_id, 'last_check_datetime', true);
            $last_check_datetime = !empty($last_check_datetime) ? $last_check_datetime : date('Y-m-d H:i:s');
            $next_check_datetime = get_post_meta($post_id, 'next_check_datetime', true);


            $recurrence_interval = get_post_meta($post_id, 'recurrence_interval', true);
            $recurrence_interval = !empty($recurrence_interval) ? $recurrence_interval : 'hourly';

            //var_dump($current_datetime);

            $schedules = wp_get_schedules();


            $interval = $schedules[$recurrence_interval]['interval'];
            $interval = $interval.' seconds';


            //if(strtotime($next_check_datetime) < strtotime($current_datetime) ){

                $active_source = get_post_meta($post_id, 'active_source', true);

                //var_dump($active_source);


                do_action('mail_picker_subscriber_source_check_'.$active_source, $post_id);


                $last_check_datetime = date('Y-m-d H:i:s', strtotime($last_check_datetime));
                $next_check_datetime = date('Y-m-d H:i:s', strtotime($last_check_datetime . ' + '.$interval));

                update_post_meta($post_id, 'last_check_datetime', $current_datetime);
                update_post_meta($post_id, 'next_check_datetime', $next_check_datetime);

            //}




        endwhile;

        wp_reset_query();
    else:

        $responses['subscriber_source_found'] = 'no';


    endif;




}


add_action('mail_picker_subscriber_source_check_registered_users', 'mail_picker_subscriber_source_check_registered_users');

function mail_picker_subscriber_source_check_registered_users($post_id){


    $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
    $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);
    $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
    $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
    $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
    $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
    $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

    $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
    $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);
    $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);

    $gmt_offset = get_option('gmt_offset');
    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));


    $active_source = get_post_meta($post_id, 'active_source', true);
    $meta_query = array();

    $meta_query[] = array(
        'key' => 'subscriber_source_check_'.$post_id,
        'compare' => 'NOT EXISTS',
    );


    $users = get_users(
        array(
        //'role'    => 'administrator',
        'orderby' => 'ID',
        'order'   => 'ASC',
        'number'  => 1,
        'paged'   => 1,
        'meta_query' => $meta_query,

        )
    );



    foreach($users as $user){
        $user_id = $user->ID;
        $user_email = $user->user_email;
        $display_name = $user->display_name;




        $formFieldData = array();
        $formFieldData['subscriber_email'] = $user_email;
        $formFieldData['first_name'] = $display_name;
        $formFieldData['subscriber_status'] = $subscriber_status;

        $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
        $formFieldData = base64_encode(serialize($formFieldData));

        // API query parameters
        $api_params = array(
            'mail_picker_action' => 'add_subscriber',
            'formFieldData' => $formFieldData,
            'subscriber_list' => $subscriber_list,
        );

        // Send query to the license manager server

        $query_url = add_query_arg($api_params, mail_picker_server_url);



        $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



        // Check for error in the response
        if (is_wp_error($response)){
            echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
        }else{
            // data.

            $subscriber_data = json_decode(wp_remote_retrieve_body($response));

            //echo '<pre>'.var_export($subscriber_data, true).'</pre>';


            $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
            $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';

            $total_submission = get_post_meta($post_id, 'total_submission', true);
            $total_submission = !empty($total_submission) ? $total_submission : 0;

            update_post_meta($post_id, 'last_submission_date', $current_datetime);
            update_post_meta($post_id, 'total_submission',(int) $total_submission + 1);


            if($status == 'success'){
                $subscriber_id = isset($subscriber_data->subscriber_id)? $subscriber_data->subscriber_id : '';


                if($send_confirmation_mail == 'yes'){

                    $mail_picker_settings = get_option('mail_picker_settings');
                    $site_logo_id = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';


                    $class_mail_picker_emails = new class_mail_picker_emails();
                    $subscribe_confirm_url = get_bloginfo('url').'?mail_picker_action=confirm_subscribe&subscriber_id='.$subscriber_id.'&redirect="';


                    $mail_template_data = get_post( $confirmation_mail_template );

                    $mail_template_content	= $mail_template_data->post_content;

                    $mail_template_content = do_shortcode($mail_template_content);
                    $mail_template_content = wpautop($mail_template_content);

                    $admin_email = get_option('admin_email');
                    $site_name = get_bloginfo('name');
                    $site_description = get_bloginfo('description');
                    $site_url = get_bloginfo('url');
                    $site_logo_url = wp_get_attachment_url($site_logo_id);

                    $vars = array(
                        '{site_name}'=> esc_html($site_name),
                        '{site_description}' => esc_html($site_description),
                        '{site_url}' => esc_url_raw($site_url),
                        '{site_logo_url}' => esc_url_raw($site_logo_url),

                        '{subscriber_email}' => esc_html($user_email),
                        '{subscriber_name}' => esc_html($display_name),

                        '{subscribe_confirm_url}' => esc_url_raw($subscribe_confirm_url),

                    );



                    $vars = apply_filters('mail_picker_mail_vars', $vars);


                    $email_data['mail_to'] =  $user_email;
                    $email_data['mail_bcc'] =  $confirmation_mail_reply_to_email;
                    $email_data['mail_from'] = $confirmation_mail_from_email ;
                    $email_data['mail_from_name'] = $confirmation_mail_from_name;
                    $email_data['reply_to'] = $confirmation_mail_reply_to_email;
                    $email_data['reply_to_name'] = $confirmation_mail_reply_to_name;

                    $email_data['mail_subject'] = strtr($confirmation_mail_subject, $vars);
                    $email_data['mail_body'] = strtr($mail_template_content, $vars);
                    $email_data['attachments'] = array();


                    $mail_status = $class_mail_picker_emails->send_email($email_data);



                }



            }

        }



        update_user_meta($user_id, 'subscriber_source_check_'.$post_id, 'done');

    }





}


add_action('mail_picker_subscriber_source_check_comments', 'mail_picker_subscriber_source_check_comments');

function mail_picker_subscriber_source_check_comments($post_id){

    $active_source = get_post_meta($post_id, 'active_source', true);

    $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
    $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);
    $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
    $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
    $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
    $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
    $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

    $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
    $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);
    $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);


    $meta_query = array();


    $meta_query[] = array(
        'key' => 'subscriber_source_check_'.$post_id,
        'compare' => 'NOT EXISTS',
    );


    $args = array(
        'order' 	=> 'DESC',
        //'status'	=> 'approve',
        'number' => 1,
        'meta_query' => $meta_query,
    );

    //var_dump($query_args);
    $comments_query = new WP_Comment_Query;
    $comments = $comments_query->query( $args );

    //var_dump($comments);
    $gmt_offset = get_option('gmt_offset');
    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));




    foreach( $comments as $comment ) {

        $comment_ID = $comment->comment_ID;


        $comment_date = new DateTime($comment->comment_date);
        $author_email = $comment->comment_author_email;
        $author_name = $comment->comment_author;

        $formFieldData = array();
        $formFieldData['subscriber_email'] = $author_email;
        $formFieldData['first_name'] = $author_name;
        $formFieldData['subscriber_status'] = $subscriber_status;


        $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
        $formFieldData = base64_encode(serialize($formFieldData));


        // API query parameters
        $api_params = array(
            'mail_picker_action' => 'add_subscriber',
            'formFieldData' => $formFieldData,
            'subscriber_list' => $subscriber_list,
        );

        // Send query to the license manager server

        $query_url = add_query_arg($api_params, mail_picker_server_url);



        $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



        // Check for error in the response
        if (is_wp_error($response)){
            echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
        }else{
            // data.

            $subscriber_data = json_decode(wp_remote_retrieve_body($response));



            $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
            $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';

            $total_submission = get_post_meta($post_id, 'total_submission', true);
            $total_submission = !empty($total_submission) ? $total_submission : 0;

            update_post_meta($post_id, 'last_submission_date', $current_datetime);
            update_post_meta($post_id, 'total_submission',(int) $total_submission + 1);


            if($status == 'success'){
                $subscriber_id = isset($subscriber_data->subscriber_id)? $subscriber_data->subscriber_id : '';
                $mail_picker_settings = get_option('mail_picker_settings');
                $site_logo_id = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';


                if($send_confirmation_mail == 'yes'){



                    $class_mail_picker_emails = new class_mail_picker_emails();
                    $subscribe_confirm_url = get_bloginfo('url').'?mail_picker_action=confirm_subscribe&subscriber_id='.$subscriber_id.'&redirect=$1"';


                    $mail_template_data = get_post( $confirmation_mail_template );

                    $mail_template_content	= $mail_template_data->post_content;

                    $mail_template_content = do_shortcode($mail_template_content);
                    $mail_template_content = wpautop($mail_template_content);

                    $admin_email = get_option('admin_email');
                    $site_name = get_bloginfo('name');
                    $site_description = get_bloginfo('description');
                    $site_url = get_bloginfo('url');
                    $site_logo_url = wp_get_attachment_url($site_logo_id);

                    $vars = array(
                        '{site_name}'=> esc_html($site_name),
                        '{site_description}' => esc_html($site_description),
                        '{site_url}' => esc_url_raw($site_url),
                        '{site_logo_url}' => esc_url_raw($site_logo_url),

                        '{subscriber_email}' => esc_html($author_email),
                        '{subscriber_name}' => esc_html($author_name),

                        '{subscribe_confirm_url}' => esc_url_raw($subscribe_confirm_url),

                    );



                    $vars = apply_filters('mail_picker_mail_vars', $vars);


                    $email_data['mail_to'] =  $author_email;
                    $email_data['mail_bcc'] =  $confirmation_mail_reply_to_email;
                    $email_data['mail_from'] = $confirmation_mail_from_email ;
                    $email_data['mail_from_name'] = $confirmation_mail_from_name;
                    $email_data['reply_to'] = $confirmation_mail_reply_to_email;
                    $email_data['reply_to_name'] = $confirmation_mail_reply_to_name;

                    $email_data['mail_subject'] = strtr($confirmation_mail_subject, $vars);
                    $email_data['mail_body'] = strtr($mail_template_content, $vars);
                    $email_data['attachments'] = array();


                    $mail_status = $class_mail_picker_emails->send_email($email_data);



                }



            }

        }







        //var_dump($comment);


        update_comment_meta($comment_ID, 'subscriber_source_check_'.$post_id, 'done');


    }

}

add_action('mail_picker_subscriber_source_check_woo_orders', 'mail_picker_subscriber_source_check_woo_orders');

function mail_picker_subscriber_source_check_woo_orders($post_id){

    $woo_orders = get_post_meta($post_id, 'woo_orders', true);

    $order_status = isset($woo_orders['order_status']) ? $woo_orders['order_status'] : 'any';
    $product_ids = isset($woo_orders['product_ids']) ? $woo_orders['product_ids'] : '';

    $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
    $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);
    $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);

    $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
    $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);
    $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
    $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
    $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
    $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
    $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

    $posts_per_page = 1;
    $paged = 1;

    $gmt_offset = get_option('gmt_offset');
    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));


    $meta_query[] = array(
        'key' => 'subscriber_source_check_'.$post_id,
        'compare' => 'NOT EXISTS',
    );



    $wp_query = new WP_Query(
        array (
            'post_type' => 'shop_order',
            'post_status' => $order_status,
            'orderby' => 'date',
            'meta_query' => $meta_query,
            //'tax_query' => $tax_query,
            'order' => 'DESC',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,

        ) );




    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();

            $order_id 	= get_the_ID();
            $license_key 	= get_post_meta( $order_id, 'license_key', true);

            $order           = new WC_Order( $order_id );
            $billing_email   = $order->get_billing_email();
            $first_name      = $order->get_billing_first_name();
            $last_name       = $order->get_billing_last_name();
            $billing_country = $order->get_billing_country();
            $billing_phone = $order->get_billing_phone();



            $formFieldData = array();
            $formFieldData['subscriber_email'] = $billing_email;
            $formFieldData['first_name'] = $first_name;
            $formFieldData['last_name'] = $last_name;
            $formFieldData['subscriber_status'] = $subscriber_status;
            $formFieldData['subscriber_phone'] = $billing_phone;


            $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
            $formFieldData = base64_encode(serialize($formFieldData));

            // API query parameters
            $api_params = array(
                'mail_picker_action' => 'add_subscriber',
                'formFieldData' => $formFieldData,
                'subscriber_list' => $subscriber_list,
            );

            // Send query to the license manager server

            $query_url = add_query_arg($api_params, mail_picker_server_url);



            $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



            // Check for error in the response
            if (is_wp_error($response)){
                echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
            }else {
                // data.

                $subscriber_data = json_decode(wp_remote_retrieve_body($response));



                $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
                $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';



                $total_submission = get_post_meta($post_id, 'total_submission', true);
                $total_submission = !empty($total_submission) ? $total_submission : 0;

                update_post_meta($post_id, 'last_submission_date', $current_datetime);
                update_post_meta($post_id, 'total_submission',(int) $total_submission + 1);


                if($status == 'success'){
                    $subscriber_id = isset($subscriber_data->subscriber_id)? $subscriber_data->subscriber_id : '';


                    if($send_confirmation_mail == 'yes'){
                        $mail_picker_settings = get_option('mail_picker_settings');
                        $site_logo_id = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';



                        $class_mail_picker_emails = new class_mail_picker_emails();
                        $subscribe_confirm_url = get_bloginfo('url').'?mail_picker_action=confirm_subscribe&subscriber_id='.$subscriber_id.'&form_id='.$post_id.'&redirect="';


                        $mail_template_data = get_post( $confirmation_mail_template );

                        $mail_template_content	= $mail_template_data->post_content;

                        $mail_template_content = do_shortcode($mail_template_content);
                        $mail_template_content = wpautop($mail_template_content);

                        $admin_email = get_option('admin_email');
                        $site_name = get_bloginfo('name');
                        $site_description = get_bloginfo('description');
                        $site_url = get_bloginfo('url');
                        $site_logo_url = wp_get_attachment_url($site_logo_id);

                        $vars = array(
                            '{site_name}'=> esc_html($site_name),
                            '{site_description}' => esc_html($site_description),
                            '{site_url}' => esc_url_raw($site_url),
                            '{site_logo_url}' => esc_url_raw($site_logo_url),

                            '{subscriber_email}' => esc_url_raw($billing_email),
                            '{first_name}' => esc_html($first_name),
                            '{last_name}' => esc_html($last_name),

                            '{subscribe_confirm_url}' => esc_url_raw($subscribe_confirm_url),

                        );



                        $vars = apply_filters('mail_picker_mail_vars', $vars);


                        $email_data['mail_to'] =  $billing_email;
                        $email_data['mail_bcc'] =  $confirmation_mail_reply_to_email;
                        $email_data['mail_from'] = $confirmation_mail_from_email ;
                        $email_data['mail_from_name'] = $confirmation_mail_from_name;
                        $email_data['reply_to'] = $confirmation_mail_reply_to_email;
                        $email_data['reply_to_name'] = $confirmation_mail_reply_to_name;

                        $email_data['mail_subject'] = strtr($confirmation_mail_subject, $vars);
                        $email_data['mail_body'] = strtr($mail_template_content, $vars);
                        $email_data['attachments'] = array();


                        $mail_status = $class_mail_picker_emails->send_email($email_data);



                    }



                }



            }


            update_post_meta($order_id, 'subscriber_source_check_'.$post_id, 'done');


        endwhile;

        wp_reset_query();
    else:

        echo __('No license found', 'mail-picker');

    endif;

}

add_action('mail_picker_subscriber_source_check_evf_entries', 'mail_picker_subscriber_source_check_evf_entries');

function mail_picker_subscriber_source_check_evf_entries($post_id){

    $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
    $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);
    $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);

    $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
    $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);
    $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
    $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
    $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
    $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
    $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

    $posts_per_page = 1;
    $paged = 1;

    $gmt_offset = get_option('gmt_offset');
    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));


    $meta_query[] = array(
        'key' => 'subscriber_source_check_'.$post_id,
        'compare' => 'NOT EXISTS',
    );

    $wp_query = new WP_Query(
        array (
            'post_type' => 'everest_form',
            'post_status' => 'any',
            'orderby' => 'date',
            'meta_query' => $meta_query,
            //'tax_query' => $tax_query,
            'order' => 'DESC',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,

        ) );




    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();

            $order_id 	= get_the_ID();
            $license_key 	= get_post_meta( $order_id, 'license_key', true);

            $order           = new WC_Order( $order_id );
            $billing_email   = $order->get_billing_email();
            $first_name      = $order->get_billing_first_name();
            $last_name       = $order->get_billing_last_name();
            $billing_country = $order->get_billing_country();
            $billing_phone = $order->get_billing_phone();



            $formFieldData = array();
            $formFieldData['subscriber_email'] = $billing_email;
            $formFieldData['first_name'] = $first_name;
            $formFieldData['last_name'] = $last_name;
            $formFieldData['subscriber_status'] = $subscriber_status;
            $formFieldData['subscriber_phone'] = $billing_phone;

            $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
            $formFieldData = base64_encode(serialize($formFieldData));

            // API query parameters
            $api_params = array(
                'mail_picker_action' => 'add_subscriber',
                'formFieldData' => $formFieldData,
                'subscriber_list' => $subscriber_list,
            );

            // Send query to the license manager server

            $query_url = add_query_arg($api_params, mail_picker_server_url);



            $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



            // Check for error in the response
            if (is_wp_error($response)){
                echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
            }else {
                // data.

                $subscriber_data = json_decode(wp_remote_retrieve_body($response));



                $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
                $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';



                $total_submission = get_post_meta($post_id, 'total_submission', true);
                $total_submission = !empty($total_submission) ? $total_submission : 0;

                update_post_meta($post_id, 'last_submission_date', $current_datetime);
                update_post_meta($post_id, 'total_submission',(int) $total_submission + 1);


                if($status == 'success'){
                    $subscriber_id = isset($subscriber_data->subscriber_id)? $subscriber_data->subscriber_id : '';


                    if($send_confirmation_mail == 'yes'){
                        $mail_picker_settings = get_option('mail_picker_settings');
                        $site_logo_id = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';



                        $class_mail_picker_emails = new class_mail_picker_emails();
                        $subscribe_confirm_url = get_bloginfo('url').'?mail_picker_action=confirm_subscribe&subscriber_id='.$subscriber_id.'&form_id='.$post_id.'&redirect="';


                        $mail_template_data = get_post( $confirmation_mail_template );

                        $mail_template_content	= $mail_template_data->post_content;

                        $mail_template_content = do_shortcode($mail_template_content);
                        $mail_template_content = wpautop($mail_template_content);

                        $admin_email = get_option('admin_email');
                        $site_name = get_bloginfo('name');
                        $site_description = get_bloginfo('description');
                        $site_url = get_bloginfo('url');
                        $site_logo_url = wp_get_attachment_url($site_logo_id);

                        $vars = array(
                            '{site_name}'=> $site_name,
                            '{site_description}' => esc_html($site_description),
                            '{site_url}' => esc_url_raw($site_url),
                            '{site_logo_url}' => esc_url_raw($site_logo_url),

                            '{subscriber_email}' => esc_html($billing_email),
                            '{first_name}' => esc_html($first_name),
                            '{last_name}' => esc_html($last_name),

                            '{subscribe_confirm_url}' => esc_url_raw($subscribe_confirm_url),

                        );



                        $vars = apply_filters('mail_picker_mail_vars', $vars);


                        $email_data['mail_to'] =  $billing_email;
                        $email_data['mail_bcc'] =  $confirmation_mail_reply_to_email;
                        $email_data['mail_from'] = $confirmation_mail_from_email ;
                        $email_data['mail_from_name'] = $confirmation_mail_from_name;
                        $email_data['reply_to'] = $confirmation_mail_reply_to_email;
                        $email_data['reply_to_name'] = $confirmation_mail_reply_to_name;

                        $email_data['mail_subject'] = strtr($confirmation_mail_subject, $vars);
                        $email_data['mail_body'] = strtr($mail_template_content, $vars);
                        $email_data['attachments'] = array();


                        $mail_status = $class_mail_picker_emails->send_email($email_data);



                    }



                }



            }


            update_post_meta($order_id, 'subscriber_source_check_'.$post_id, 'done');


        endwhile;

        wp_reset_query();
    else:

        echo __('No license found', 'mail-picker');

    endif;

}

add_action('mail_picker_subscriber_source_check_flamingo_inbound', 'mail_picker_subscriber_source_check_flamingo_inbound');

function mail_picker_subscriber_source_check_flamingo_inbound($post_id){

    $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
    $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);
    $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);

    $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
    $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);
    $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
    $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
    $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
    $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
    $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

    $posts_per_page = 1;
    $paged = 1;

    $gmt_offset = get_option('gmt_offset');
    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));


    $meta_query[] = array(
        'key' => 'subscriber_source_check_'.$post_id,
        'compare' => 'NOT EXISTS',
    );

    $wp_query = new WP_Query(
        array (
            'post_type' => 'flamingo_inbound',
            'post_status' => 'any',
            'orderby' => 'date',
            'meta_query' => $meta_query,
            //'tax_query' => $tax_query,
            'order' => 'DESC',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,

        ) );




    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();

            $order_id 	= get_the_ID();
            $_from_email = get_post_meta( $order_id, '_from_email', true);
            $_from_name = get_post_meta( $order_id, '_from_name', true);





            $formFieldData = array();
            $formFieldData['subscriber_email'] = $_from_email;
            $formFieldData['first_name'] = ($_from_name);
            $formFieldData['subscriber_status'] = $subscriber_status;

            $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
            $formFieldData = base64_encode(serialize($formFieldData));


            // API query parameters
            $api_params = array(
                'mail_picker_action' => 'add_subscriber',
                'formFieldData' =>$formFieldData,
                'subscriber_list' => $subscriber_list,
            );

            // Send query to the license manager server

            $query_url = add_query_arg($api_params, mail_picker_server_url);



            $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



            // Check for error in the response
            if (is_wp_error($response)){
                echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
            }else {
                // data.

                $subscriber_data = json_decode(wp_remote_retrieve_body($response));



                $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
                $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';



                $total_submission = get_post_meta($post_id, 'total_submission', true);
                $total_submission = !empty($total_submission) ? $total_submission : 0;

                update_post_meta($post_id, 'last_submission_date', $current_datetime);
                update_post_meta($post_id, 'total_submission',(int) $total_submission + 1);


                if($status == 'success'){
                    $subscriber_id = isset($subscriber_data->subscriber_id)? $subscriber_data->subscriber_id : '';


                    if($send_confirmation_mail == 'yes'){
                        $mail_picker_settings = get_option('mail_picker_settings');
                        $site_logo_id = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';



                        $class_mail_picker_emails = new class_mail_picker_emails();
                        $subscribe_confirm_url = get_bloginfo('url').'?mail_picker_action=confirm_subscribe&subscriber_id='.$subscriber_id.'&form_id='.$post_id.'&redirect="';


                        $mail_template_data = get_post( $confirmation_mail_template );

                        $mail_template_content	= $mail_template_data->post_content;

                        $mail_template_content = do_shortcode($mail_template_content);
                        $mail_template_content = wpautop($mail_template_content);

                        $admin_email = get_option('admin_email');
                        $site_name = get_bloginfo('name');
                        $site_description = get_bloginfo('description');
                        $site_url = get_bloginfo('url');
                        $site_logo_url = wp_get_attachment_url($site_logo_id);

                        $vars = array(
                            '{site_name}'=> esc_html($site_name),
                            '{site_description}' => esc_html($site_description),
                            '{site_url}' => esc_url_raw($site_url),
                            '{site_logo_url}' => esc_url_raw($site_logo_url),

                            '{subscriber_email}' => esc_html($_from_email),
                            '{first_name}' => esc_html($_from_name),

                            '{subscribe_confirm_url}' => esc_url_raw($subscribe_confirm_url),

                        );



                        $vars = apply_filters('mail_picker_mail_vars', $vars);


                        $email_data['mail_to'] =  $_from_email;
                        $email_data['mail_bcc'] =  $confirmation_mail_reply_to_email;
                        $email_data['mail_from'] = $confirmation_mail_from_email ;
                        $email_data['mail_from_name'] = $confirmation_mail_from_name;
                        $email_data['reply_to'] = $confirmation_mail_reply_to_email;
                        $email_data['reply_to_name'] = $confirmation_mail_reply_to_name;

                        $email_data['mail_subject'] = strtr($confirmation_mail_subject, $vars);
                        $email_data['mail_body'] = strtr($mail_template_content, $vars);
                        $email_data['attachments'] = array();


                        $mail_status = $class_mail_picker_emails->send_email($email_data);



                    }



                }



            }


            update_post_meta($order_id, 'subscriber_source_check_'.$post_id, 'done');


        endwhile;

        wp_reset_query();
    else:

        echo __('No license found', 'mail-picker');

    endif;

}


add_action('mail_picker_subscriber_source_check_ninjaform_sub', 'mail_picker_subscriber_source_check_ninjaform_sub');

function mail_picker_subscriber_source_check_ninjaform_sub($post_id){

    $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
    $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);
    $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);

    $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
    $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);
    $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
    $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
    $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
    $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
    $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

    $posts_per_page = 1;
    $paged = 1;

    $gmt_offset = get_option('gmt_offset');
    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));


    $meta_query[] = array(
        'key' => 'subscriber_source_check_'.$post_id,
        'compare' => 'NOT EXISTS',
    );

    $wp_query = new WP_Query(
        array (
            'post_type' => 'nf_sub',
            'post_status' => 'any',
            'orderby' => 'date',
            'meta_query' => $meta_query,
            //'tax_query' => $tax_query,
            'order' => 'DESC',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,

        ) );




    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();

            $order_id 	= get_the_ID();


            $form_id = get_post_meta( $order_id, '_form_id', TRUE );
            $ninjaform_sub = get_post_meta( $order_id, 'ninjaform_sub', TRUE );
            $ninjaform_sub = !empty($ninjaform_sub) ? $ninjaform_sub : array();
            $email_label = isset($ninjaform_sub['email_label']) ? $ninjaform_sub['email_label'] : 'Email';

            $sub = Ninja_Forms()->form()->get_sub( $order_id );

            $fields = Ninja_Forms()->form( $form_id )->get_fields();
            $hidden_field_types = apply_filters( 'nf_sub_hidden_field_types', array() );


            $subscriber_email = '';

?>
            <?php
            foreach( $fields as $field ):

                if( in_array( $field->get_setting( 'type' ), $hidden_field_types ) ) continue;

                if( ! isset( Ninja_Forms()->fields[ $field->get_setting( 'type' ) ] ) ) continue;

                $field_class = Ninja_Forms()->fields[ $field->get_setting( 'type' ) ];

                if( ! $field_class ) continue;


                $admin_label = ( $field->get_setting( 'admin_label' ) ) ? $field->get_setting( 'admin_label' ) : $field->get_setting( 'label' ) ;
                $field_value = $sub->get_field_value( $field->get_id() );

                if($email_label == $admin_label){
                    $subscriber_email = $field_value;
                }


            endforeach; ?>
<?php



        if(empty($subscriber_email)) return;



            $formFieldData = array();
            $formFieldData['subscriber_email'] = $subscriber_email;
            $formFieldData['subscriber_status'] = $subscriber_status;


            $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
            $formFieldData = base64_encode(serialize($formFieldData));

            // API query parameters
            $api_params = array(
                'mail_picker_action' => 'add_subscriber',
                'formFieldData' => $formFieldData,
                'subscriber_list' => $subscriber_list,
            );

            // Send query to the license manager server

            $query_url = add_query_arg($api_params, mail_picker_server_url);



            $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



            // Check for error in the response
            if (is_wp_error($response)){
                echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
            }else {
                // data.

                $subscriber_data = json_decode(wp_remote_retrieve_body($response));



                $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
                $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';



                $total_submission = get_post_meta($post_id, 'total_submission', true);
                $total_submission = !empty($total_submission) ? $total_submission : 0;

                update_post_meta($post_id, 'last_submission_date', $current_datetime);
                update_post_meta($post_id, 'total_submission',(int) $total_submission + 1);


                if($status == 'success'){
                    $subscriber_id = isset($subscriber_data->subscriber_id)? $subscriber_data->subscriber_id : '';


                    if($send_confirmation_mail == 'yes'){
                        $mail_picker_settings = get_option('mail_picker_settings');
                        $site_logo_id = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';



                        $class_mail_picker_emails = new class_mail_picker_emails();
                        $subscribe_confirm_url = get_bloginfo('url').'?mail_picker_action=confirm_subscribe&subscriber_id='.$subscriber_id.'&form_id='.$post_id.'&redirect="';


                        $mail_template_data = get_post( $confirmation_mail_template );

                        $mail_template_content	= $mail_template_data->post_content;

                        $mail_template_content = do_shortcode($mail_template_content);
                        $mail_template_content = wpautop($mail_template_content);

                        $admin_email = get_option('admin_email');
                        $site_name = get_bloginfo('name');
                        $site_description = get_bloginfo('description');
                        $site_url = get_bloginfo('url');
                        $site_logo_url = wp_get_attachment_url($site_logo_id);

                        $vars = array(
                            '{site_name}'=> esc_html($site_name),
                            '{site_description}' => esc_html($site_description),
                            '{site_url}' => esc_url_raw($site_url),
                            '{site_logo_url}' => esc_url_raw($site_logo_url),

                            '{subscriber_email}' => esc_html($subscriber_email),
//                            '{first_name}' => $_from_name,

                            '{subscribe_confirm_url}' => esc_url_raw($subscribe_confirm_url),

                        );



                        $vars = apply_filters('mail_picker_mail_vars', $vars);


                        $email_data['mail_to'] =  $subscriber_email;
                        $email_data['mail_bcc'] =  $confirmation_mail_reply_to_email;
                        $email_data['mail_from'] = $confirmation_mail_from_email ;
                        $email_data['mail_from_name'] = $confirmation_mail_from_name;
                        $email_data['reply_to'] = $confirmation_mail_reply_to_email;
                        $email_data['reply_to_name'] = $confirmation_mail_reply_to_name;

                        $email_data['mail_subject'] = strtr($confirmation_mail_subject, $vars);
                        $email_data['mail_body'] = strtr($mail_template_content, $vars);
                        $email_data['attachments'] = array();


                        $mail_status = $class_mail_picker_emails->send_email($email_data);



                    }



                }



            }


            //update_post_meta($order_id, 'subscriber_source_check_'.$post_id, 'done');


        endwhile;

        wp_reset_query();
    else:

        echo __('No license found', 'mail-picker');

    endif;

}




add_shortcode('mail_picker_subscriber_source_check_newsletter_subscribers', 'mail_picker_subscriber_source_check_newsletter_subscribers');

add_action('mail_picker_subscriber_source_check_newsletter_subscribers', 'mail_picker_subscriber_source_check_newsletter_subscribers');

function mail_picker_subscriber_source_check_newsletter_subscribers($post_id){

    $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
    $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);
    $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);

    $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
    $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);
    $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
    $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
    $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
    $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
    $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

    $posts_per_page = 1;
    $paged = 1;

    $gmt_offset = get_option('gmt_offset');
    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));

    global $wpdb;
    $profile_18 = 'done';
    $status= 'C';

    //$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wp_newsletter WHERE status = %s AND
    // profile_18 = %s", $profile_18, $status ) );



    //var_dump($results);

    if(empty($subscriber_email)) return;



    $formFieldData = array();
    $formFieldData['subscriber_email'] = $subscriber_email;
    $formFieldData['subscriber_status'] = $subscriber_status;

    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));

    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);



    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else {
        // data.

        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';



        $total_submission = get_post_meta($post_id, 'total_submission', true);
        $total_submission = !empty($total_submission) ? $total_submission : 0;

        update_post_meta($post_id, 'last_submission_date', $current_datetime);
        update_post_meta($post_id, 'total_submission',(int) $total_submission + 1);


        if($status == 'success'){
            $subscriber_id = isset($subscriber_data->subscriber_id)? $subscriber_data->subscriber_id : '';


            if($send_confirmation_mail == 'yes'){
                $mail_picker_settings = get_option('mail_picker_settings');
                $site_logo_id = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';



                $class_mail_picker_emails = new class_mail_picker_emails();
                $subscribe_confirm_url = get_bloginfo('url').'?mail_picker_action=confirm_subscribe&subscriber_id='.$subscriber_id.'&form_id='.$post_id.'&redirect="';


                $mail_template_data = get_post( $confirmation_mail_template );

                $mail_template_content	= $mail_template_data->post_content;

                $mail_template_content = do_shortcode($mail_template_content);
                $mail_template_content = wpautop($mail_template_content);

                $admin_email = get_option('admin_email');
                $site_name = get_bloginfo('name');
                $site_description = get_bloginfo('description');
                $site_url = get_bloginfo('url');
                $site_logo_url = wp_get_attachment_url($site_logo_id);

                $vars = array(
                    '{site_name}'=> esc_html($site_name),
                    '{site_description}' =>esc_html( $site_description),
                    '{site_url}' => esc_url_raw($site_url),
                    '{site_logo_url}' => esc_url_raw($site_logo_url),

                    '{subscriber_email}' => esc_html($subscriber_email),
//                            '{first_name}' => $_from_name,

                    '{subscribe_confirm_url}' => esc_url_raw($subscribe_confirm_url),

                );



                $vars = apply_filters('mail_picker_mail_vars', $vars);


                $email_data['mail_to'] =  $subscriber_email;
                $email_data['mail_bcc'] =  $confirmation_mail_reply_to_email;
                $email_data['mail_from'] = $confirmation_mail_from_email ;
                $email_data['mail_from_name'] = $confirmation_mail_from_name;
                $email_data['reply_to'] = $confirmation_mail_reply_to_email;
                $email_data['reply_to_name'] = $confirmation_mail_reply_to_name;

                $email_data['mail_subject'] = strtr($confirmation_mail_subject, $vars);
                $email_data['mail_body'] = strtr($mail_template_content, $vars);
                $email_data['attachments'] = array();


                $mail_status = $class_mail_picker_emails->send_email($email_data);



            }



        }



    }


    //update_post_meta($order_id, 'subscriber_source_check_'.$post_id, 'done');




}








if( ! function_exists( 'mail_picker_wpcf7_submit' ) ) {
    function mail_picker_wpcf7_submit( $contact_form ) {

        $mail_picker_settings = get_option('mail_picker_settings');
        $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
        $cf7 = isset($subscriber_source['cf7']) ? $subscriber_source['cf7'] : array();

        $enable = isset($cf7['enable']) ? $cf7['enable'] : 'no';

        if($enable != 'yes') return;

        $contact_form_id    = isset($_POST['_wpcf7']) ? sanitize_text_field($_POST['_wpcf7']) : '';

        if( $contact_form_id != $contact_form->id() ) return;

        $email_field_attr = (isset($cf7['email_field_attr']) && !empty($cf7['email_field_attr'])) ? $cf7['email_field_attr'] : 'your-email';
        $name_field_attr = (isset($cf7['name_field_attr']) && !empty($cf7['name_field_attr'])) ? $cf7['name_field_attr'] : 'your-name';
        $subscriber_list = (isset($cf7['subscriber_list'])  && !empty($cf7['subscriber_list'])) ? $cf7['subscriber_list'] : array();
        $subscriber_status = (isset($cf7['subscriber_status']) && !empty($cf7['subscriber_status'])) ? $cf7['subscriber_status'] : 'pending';


        $email_value = isset($_POST[$email_field_attr]) ? sanitize_email($_POST[$email_field_attr]) : '';
        $name_value = isset($_POST[$name_field_attr]) ? sanitize_text_field($_POST[$name_field_attr]) : '';

        $formFieldData = array();
        $formFieldData['subscriber_email'] = $email_value;
        $formFieldData['first_name'] = $name_value;
        $formFieldData['subscriber_status'] = $subscriber_status;


        $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
        $formFieldData = base64_encode(serialize($formFieldData));

        // API query parameters
        $api_params = array(
            'mail_picker_action' => 'add_subscriber',
            'formFieldData' => $formFieldData,
            'subscriber_list' => $subscriber_list,
        );

        // Send query to the license manager server

        $query_url = add_query_arg($api_params, mail_picker_server_url);

        //echo '<pre>'.var_export($query_url, true).'</pre>';


        $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



        // Check for error in the response
        if (is_wp_error($response)){
            echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
        }else{
            // data.
            $subscriber_data = json_decode(wp_remote_retrieve_body($response));



            $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
            $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


            //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
            //echo '<pre>'.var_export($status, true).'</pre>';


            //$total_submission = get_post_meta($form_id, 'total_submission', true);
            //$total_submission = !empty($total_submission) ? $total_submission : 0;


            //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

            //update_post_meta($form_id, 'last_submission_date',$current_datetime);
            //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


        }


//
//        $contact_form_id    = isset($_POST['_wpcf7']) ? sanitize_text_field($_POST['_wpcf7']) : '';
//
//        if( $contact_form_id != $contact_form->id() ) return;
//
//        $subscriber_id = wp_insert_post( array(
//            'post_title'    => 'Response',
//            'post_content'  => '',
//            'post_type'     => 'subscriber',
//            'post_status'   => 'publish',
//        ), true );
//
//        if( is_wp_error( $subscriber_id ) ) return;
//
//
//        $email_value = isset($_POST[$email_field_attr]) ? sanitize_email($_POST[$email_field_attr]) : '';
//        $name_value = isset($_POST[$name_field_attr]) ? sanitize_text_field($_POST[$name_field_attr]) : '';
//
//
//        wp_update_post( array(
//            'ID'            => $subscriber_id,
//            'post_title'    => '#' . $subscriber_id,
//        ) );
//
//        update_post_meta( $subscriber_id, 'subscriber_email', $email_value );
//        update_post_meta( $subscriber_id, 'first_name', $name_value );



    }
}
add_action( 'wpcf7_submit', 'mail_picker_wpcf7_submit', 10, 1 );




function mail_picker_wpforms_process_complete( $fields, $entry, $form_data, $entry_id ) {

    // Optional, you can limit to specific forms. Below, we restrict output to
    // form #5.


    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $wpforms = isset($subscriber_source['wpforms']) ? $subscriber_source['wpforms'] : array();

    $enable = isset($wpforms['enable']) ? $wpforms['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_id = (isset($wpforms['email_field_id'])) ? $wpforms['email_field_id'] : '';
    $name_field_id = (isset($wpforms['name_field_id'])) ? $wpforms['name_field_id'] : '';
    $subscriber_list = (isset($wpforms['subscriber_list'])  && !empty($wpforms['subscriber_list'])) ? $wpforms['subscriber_list'] : array();
    $subscriber_status = (isset($wpforms['subscriber_status']) && !empty($wpforms['subscriber_status'])) ? $wpforms['subscriber_status'] : 'pending';


    $email_value = isset($entry['fields'][$email_field_id]) ? sanitize_email($entry['fields'][$email_field_id]) : '';
    $name_value = isset($entry['fields'][$name_field_id]) ? sanitize_text_field($entry['fields'][$name_field_id]) : '';

//
//    error_log($email_value);
//    error_log($name_value);

    //error_log(serialize($entry['fields']));



    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;

    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));


    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);

    //echo '<pre>'.var_export($query_url, true).'</pre>';


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }





//    $subscriber_id = wp_insert_post( array(
//        'post_title'    => 'Response',
//        'post_content'  => '',
//        'post_type'     => 'subscriber',
//        'post_status'   => 'publish',
//    ), true );
//
//    if( is_wp_error( $subscriber_id ) ) return;


//    error_log(serialize($fields));
//    error_log(serialize($entry));









        //update_post_meta( $subscriber_id, 'subscriber_email', $subscriber_id );



}
add_action( 'wpforms_process_complete', 'mail_picker_wpforms_process_complete', 10, 4 );







add_action('frm_after_create_entry', 'mail_picker_frm_after_create_entry', 30, 2);
function mail_picker_frm_after_create_entry($entry_id, $form_id){


    $item_meta = mail_picker_recursive_sanitize_arr($_POST['item_meta']);

    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $formidable = isset($subscriber_source['formidable']) ? $subscriber_source['formidable'] : array();

    $enable = isset($formidable['enable']) ? $formidable['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_id = (isset($formidable['email_field_id'])) ? $formidable['email_field_id'] : '';
    $name_field_id = (isset($formidable['name_field_id'])) ? $formidable['name_field_id'] : '';
    $subscriber_list = (isset($formidable['subscriber_list'])  && !empty($formidable['subscriber_list'])) ? $formidable['subscriber_list'] : array();
    $subscriber_status = (isset($formidable['subscriber_status']) && !empty($formidable['subscriber_status'])) ? $formidable['subscriber_status'] : 'pending';


    $email_value = isset($item_meta[$email_field_id]) ? sanitize_email($item_meta[$email_field_id]) : '';
    $name_value = isset($item_meta[$name_field_id]) ? sanitize_text_field($item_meta[$name_field_id]) : '';


    //error_log($name_value);



    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;

    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));


    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);

    //echo '<pre>'.var_export($query_url, true).'</pre>';


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }



}




add_action('caldera_custom_form_submit_before_set_fields', 'mail_picker_caldera_custom_form_submit_before_set_fields', 10, 3);


function mail_picker_caldera_custom_form_submit_before_set_fields($entry, $form_id, $field_data_array){



    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $caldera = isset($subscriber_source['caldera']) ? $subscriber_source['caldera'] : array();

    $enable = isset($caldera['enable']) ? $caldera['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_id = (isset($caldera['email_field_id'])) ? $caldera['email_field_id'] : '';
    $name_field_id = (isset($caldera['name_field_id'])) ? $caldera['name_field_id'] : '';
    $subscriber_list = (isset($caldera['subscriber_list'])  && !empty($caldera['subscriber_list'])) ? $caldera['subscriber_list'] : array();
    $subscriber_status = (isset($caldera['subscriber_status']) && !empty($caldera['subscriber_status'])) ? $caldera['subscriber_status'] : 'pending';


    $email_value = isset($_POST[$email_field_id]) ? sanitize_email($_POST[$email_field_id]) : '';
    $name_value = isset($_POST[$name_field_id]) ? sanitize_text_field($_POST[$name_field_id]) : '';




    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;

    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));


    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);

    //echo '<pre>'.var_export($query_url, true).'</pre>';


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }




}





add_action('caldera_forms_submit_complete', 'mail_picker_caldera_forms_submit_complete', 10, 3);


function mail_picker_caldera_forms_submit_complete($form, $referrer, $process_id){


    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $caldera = isset($subscriber_source['caldera']) ? $subscriber_source['caldera'] : array();

    $enable = isset($caldera['enable']) ? $caldera['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_id = (isset($caldera['email_field_id'])) ? $caldera['email_field_id'] : '';
    $name_field_id = (isset($caldera['name_field_id'])) ? $caldera['name_field_id'] : '';
    $subscriber_list = (isset($caldera['subscriber_list'])  && !empty($caldera['subscriber_list'])) ? $caldera['subscriber_list'] : array();
    $subscriber_status = (isset($caldera['subscriber_status']) && !empty($caldera['subscriber_status'])) ? $caldera['subscriber_status'] : 'pending';


    $email_value = isset($_POST[$email_field_id]) ? sanitize_email($_POST[$email_field_id]) : '';
    $name_value = isset($_POST[$name_field_id]) ? sanitize_text_field($_POST[$name_field_id]) : '';



    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;

    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));


    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);

    //echo '<pre>'.var_export($query_url, true).'</pre>';


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }




}



add_action('weforms_entry_submission', 'mail_picker_weforms_entry_submission', 10, 4);


function mail_picker_weforms_entry_submission($entry_id, $form_id, $page_id, $form_settings){



    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $weforms = isset($subscriber_source['weforms']) ? $subscriber_source['weforms'] : array();

    $enable = isset($weforms['enable']) ? $weforms['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_attr = (isset($weforms['email_field_attr'])) ? $weforms['email_field_attr'] : '';
    $name_field_attr = (isset($weforms['name_field_attr'])) ? $weforms['name_field_attr'] : '';
    $subscriber_list = (isset($weforms['subscriber_list'])  && !empty($weforms['subscriber_list'])) ? $weforms['subscriber_list'] : array();
    $subscriber_status = (isset($weforms['subscriber_status']) && !empty($weforms['subscriber_status'])) ? $weforms['subscriber_status'] : 'pending';


    $email_value = isset($_POST[$email_field_attr]) ? sanitize_email($_POST[$email_field_attr]) : '';
    $name_value = isset($_POST[$name_field_attr]['first']) ? sanitize_text_field($_POST[$name_field_attr]['first']) : '';




    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;


    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));


    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);

    //echo '<pre>'.var_export($query_url, true).'</pre>';


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }




}






add_filter('kaliforms_before_form_process', 'kaliforms_before_form_process');

function kaliforms_before_form_process($data){


    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $kaliforms = isset($subscriber_source['kaliforms']) ? $subscriber_source['kaliforms'] : array();

    $enable = isset($kaliforms['enable']) ? $kaliforms['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_attr = (isset($kaliforms['email_field_attr'])) ? $kaliforms['email_field_attr'] : '';
    $name_field_attr = (isset($kaliforms['name_field_attr'])) ? $kaliforms['name_field_attr'] : '';
    $subscriber_list = (isset($kaliforms['subscriber_list'])  && !empty($kaliforms['subscriber_list'])) ? $kaliforms['subscriber_list'] : array();
    $subscriber_status = (isset($kaliforms['subscriber_status']) && !empty($kaliforms['subscriber_status'])) ? $kaliforms['subscriber_status'] : 'pending';


    $email_value = isset($data[$email_field_attr]) ? sanitize_email($data[$email_field_attr]) : '';
    $name_value = isset($data[$name_field_attr]) ? sanitize_text_field($data[$name_field_attr]) : '';


    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;

    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));

    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }


    return $data;


}



add_filter('ig_es_add_subscriber_data', 'ig_es_add_subscriber_data', 10);

function ig_es_add_subscriber_data($data){




    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $email_subscribers = isset($subscriber_source['email_subscribers']) ? $subscriber_source['email_subscribers'] : array();

    $enable = isset($email_subscribers['enable']) ? $email_subscribers['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_attr = (isset($email_subscribers['email_field_attr'])) ? $email_subscribers['email_field_attr'] : '';
    $name_field_attr = (isset($email_subscribers['name_field_attr'])) ? $email_subscribers['name_field_attr'] : '';
    $subscriber_list = (isset($email_subscribers['subscriber_list'])  && !empty($email_subscribers['subscriber_list'])) ? $email_subscribers['subscriber_list'] : array();
    $subscriber_status = (isset($email_subscribers['subscriber_status']) && !empty($email_subscribers['subscriber_status'])) ? $email_subscribers['subscriber_status'] : 'pending';


    $email_value = isset($data[$email_field_attr]) ? sanitize_email($data[$email_field_attr]) : '';
    $name_value = isset($data[$name_field_attr]) ? sanitize_text_field($data[$name_field_attr]) : '';


    //error_log($email_field_attr);



    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;


    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));

    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);

    //echo '<pre>'.var_export($query_url, true).'</pre>';


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }


    return $data;




//    error_log(serialize($data));
//
//    $subscriber_id = wp_insert_post( array(
//        'post_title'    => 'Response',
//        'post_content'  => '',
//        'post_type'     => 'subscriber',
//        'post_status'   => 'publish',
//    ), true );




}




add_filter('mailoptin_optin_subscription_request_body', 'mailoptin_optin_subscription_request_body');

function mailoptin_optin_subscription_request_body($optin_data){





    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $email_subscribers = isset($subscriber_source['email_subscribers']) ? $subscriber_source['email_subscribers'] : array();

    $enable = isset($email_subscribers['enable']) ? $email_subscribers['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_attr = (isset($email_subscribers['email_field_attr'])) ? $email_subscribers['email_field_attr'] : 'mo-email';
    $name_field_attr = (isset($email_subscribers['name_field_attr'])) ? $email_subscribers['name_field_attr'] : 'mo-name';
    $subscriber_list = (isset($email_subscribers['subscriber_list'])  && !empty($email_subscribers['subscriber_list'])) ? $email_subscribers['subscriber_list'] : array();
    $subscriber_status = (isset($email_subscribers['subscriber_status']) && !empty($email_subscribers['subscriber_status'])) ? $email_subscribers['subscriber_status'] : 'pending';


    $email_value = isset($optin_data[$email_field_attr]) ? sanitize_email($optin_data[$email_field_attr]) : '';
    $name_value = isset($optin_data[$name_field_attr]) ? sanitize_text_field($optin_data[$name_field_attr]) : '';


    //error_log($email_field_attr);



    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;


    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));


    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);

    //echo '<pre>'.var_export($query_url, true).'</pre>';


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }
//
//    $subscriber_id = wp_insert_post( array(
//        'post_title'    => 'Response',
//        'post_content'  => '',
//        'post_type'     => 'subscriber',
//        'post_status'   => 'publish',
//    ), true );


    return $optin_data;

}



add_filter('s2_confirm_email', 's2_confirm_email');

function s2_confirm_email($confirm_email){

    //error_log($confirm_email);


}




add_filter('newsletter_user_subscribe', 'newsletter_user_subscribe');

function newsletter_user_subscribe($user){


    $mail_picker_settings = get_option('mail_picker_settings');
    $subscriber_source = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : array();
    $email_subscribers = isset($subscriber_source['email_subscribers']) ? $subscriber_source['email_subscribers'] : array();

    $enable = isset($email_subscribers['enable']) ? $email_subscribers['enable'] : 'no';

    if($enable != 'yes') return;


    $email_field_attr = (isset($email_subscribers['email_field_attr'])) ? $email_subscribers['email_field_attr'] : 'email';
    $name_field_attr = (isset($email_subscribers['name_field_attr'])) ? $email_subscribers['name_field_attr'] : 'name';
    $subscriber_list = (isset($email_subscribers['subscriber_list'])  && !empty($email_subscribers['subscriber_list'])) ? $email_subscribers['subscriber_list'] : array();
    $subscriber_status = (isset($email_subscribers['subscriber_status']) && !empty($email_subscribers['subscriber_status'])) ? $email_subscribers['subscriber_status'] : 'pending';


    $email_value = isset($user[$email_field_attr]) ? sanitize_email($user[$email_field_attr]) : '';
    $name_value = isset($user[$name_field_attr]) ? sanitize_text_field($user[$name_field_attr]) : '';





    $formFieldData = array();
    $formFieldData['subscriber_email'] = $email_value;
    $formFieldData['first_name'] = $name_value;
    $formFieldData['subscriber_status'] = $subscriber_status;


    $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
    $formFieldData = base64_encode(serialize($formFieldData));


    // API query parameters
    $api_params = array(
        'mail_picker_action' => 'add_subscriber',
        'formFieldData' => $formFieldData,
        'subscriber_list' => $subscriber_list,
    );

    // Send query to the license manager server

    $query_url = add_query_arg($api_params, mail_picker_server_url);

    //echo '<pre>'.var_export($query_url, true).'</pre>';


    $response = wp_remote_get($query_url, array('timeout' => 20, 'sslverify' => false));



    // Check for error in the response
    if (is_wp_error($response)){
        echo __("Unexpected Error! The query returned with an error.", mail_picker_server_url);
    }else{
        // data.
        $subscriber_data = json_decode(wp_remote_retrieve_body($response));



        $message = isset($subscriber_data->message) ? sanitize_text_field($subscriber_data->message) : '';
        $status = isset($subscriber_data->status) ? sanitize_text_field($subscriber_data->status) : '';


        //echo '<pre>'.var_export($subscriber_data, true).'</pre>';
        //echo '<pre>'.var_export($status, true).'</pre>';


        //$total_submission = get_post_meta($form_id, 'total_submission', true);
        //$total_submission = !empty($total_submission) ? $total_submission : 0;


        //do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

        //update_post_meta($form_id, 'last_submission_date',$current_datetime);
        //update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


    }
//
//    $subscriber_id = wp_insert_post( array(
//        'post_title'    => 'Response',
//        'post_content'  => '',
//        'post_type'     => 'subscriber',
//        'post_status'   => 'publish',
//    ), true );



    return $user;


}





