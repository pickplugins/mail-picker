<?php


if ( ! defined('ABSPATH')) exit;  // if direct access 
	

add_action('mail_picker_form', 'mail_picker_form');

function mail_picker_form($atts){

    $form_id = isset($atts['id']) ? $atts['id'] : '';

    $send_confirmation_mail 	= get_post_meta( $form_id, 'send_confirmation_mail', true);
    $enable_recaptcha	= get_post_meta( $form_id, 'enable_recaptcha', true);
    $subscriber_status 	= get_post_meta( $form_id, 'subscriber_status', true);
    $subscriber_list 	= get_post_meta( $form_id, 'subscriber_list', true);
    $layout_elements_data = get_post_meta($form_id,'layout_elements_data', true);

    //var_dump($subscriber_list);

    $gmt_offset = get_option('gmt_offset');

    $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));





    if(!empty($_POST)){
        $error = new WP_Error();

        if(empty($_POST['subscriber_email'])){
            $error->add( 'subscriber_email', __( 'ERROR: subscriber email should not empty.', 'mail-picker' ) );
        }

        if(! isset( $_POST['mail_picker_nonce'] ) || ! wp_verify_nonce( $_POST['mail_picker_nonce'], 'mail_picker_nonce' ) ){

            $error->add( '_wpnonce', __( 'ERROR: security test failed.', 'mail-picker' ) );
        }

        if(empty($_POST['g-recaptcha-response']) && $enable_recaptcha == 'yes'){

            $error->add( 'g-recaptcha-response', __( 'ERROR: reCaptcha test failed.', 'mail-picker' ) );
        }


        $errors = apply_filters( 'mail_picker_form_submit_errors', $error );

        //echo '<pre>'.var_export($errors, true).'</pre>';


        if ( !$errors->has_errors() ) {


            $formFieldData = array();




            foreach ($layout_elements_data as  $fieldIndex =>$fieldData){
                foreach ($fieldData as $fieldId => $field){



                    if(is_array($_POST[$field['name']])){
                        $field_data = mail_picker_recursive_sanitize_arr($_POST[$field['name']]);


                        $formFieldData[$field['name']] = serialize($field_data);

                    }else{


                        $formFieldData[$field['name']] =   sanitize_text_field($_POST[$field['name']]);

                    }



                }
            }

            $subscriber_email = isset($formFieldData['subscriber_email']) ? sanitize_email($formFieldData['subscriber_email']) : '';


            $formFieldData = mail_picker_recursive_sanitize_arr($formFieldData);
            $formFieldData = base64_encode(serialize($formFieldData));

            // API query parameters
            $api_params = array(
                'mail_picker_action' => 'add_subscriber',
                'formFieldData' => $formFieldData,
                'subscriber_status' => $subscriber_status,
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


                $total_submission = get_post_meta($form_id, 'total_submission', true);
                $total_submission = !empty($total_submission) ? $total_submission : 0;


                do_action('mail_picker_subscriber_submitted_'.$status, $subscriber_data, $atts);

                update_post_meta($form_id, 'last_submission_date',$current_datetime);
                update_post_meta($form_id, 'total_submission',(int) $total_submission + 1);


            }

        }else{

            $error_messages = $error->get_error_messages();

            ?>
            <div class="errors">
                <?php

                if(!empty($error_messages))
                    foreach ($error_messages as $message){
                        ?>
                        <p class="job-bm-error"><?php echo $message; ?></p>
                        <?php
                    }
                ?>
            </div>
            <?php
        }




    }






    ?>
    <form method="post">
        <?php do_action('mail_picker_form_main', $atts); ?>
    </form>

    <?php

}









add_action('mail_picker_subscriber_submitted_success', 'mail_picker_subscriber_submitted_success', 99999, 2);


function mail_picker_subscriber_submitted_success($subscriber_data, $atts){
    $form_id = isset($atts['id']) ? $atts['id'] : '';
    $subscriber_id = isset($subscriber_data->subscriber_id)? $subscriber_data->subscriber_id : '';


    $after_submit_action 	= get_post_meta( $form_id, 'after_submit_action', true);
    $redirect_link 	= get_post_meta( $form_id, 'redirect_link', true);
    $send_confirmation_mail 	= get_post_meta( $form_id, 'send_confirmation_mail', true);
    $mail_template_id 	= get_post_meta( $form_id, 'confirmation_mail_template', true);
    $mail_sent_success	= get_post_meta( $form_id, 'mail_sent_success', true);
    $mail_sent_fail	= get_post_meta( $form_id, 'mail_sent_fail', true);
    $subscriber_status	= get_post_meta( $form_id, 'subscriber_status', true);

    update_post_meta($subscriber_id, 'subscriber_status', $subscriber_status);
    update_post_meta($subscriber_id, 'is_confirm', 'no');

    //var_dump($send_confirmation_mail);


    if($send_confirmation_mail == 'yes'){

        $subscribe_confirm_url = get_bloginfo('url').'?mail_picker_action=confirm_subscribe&subscriber_form_id='.$form_id.'&subscriber_id='.$subscriber_id.'&redirect=$1"';

        $mail_picker_settings = get_option('mail_picker_settings');
        $site_logo_id = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';



        $class_mail_picker_emails = new class_mail_picker_emails();


        $mail_subject 	= get_post_meta( $form_id, 'confirmation_mail_subject', true);
        $from_email 	= get_post_meta( $form_id, 'confirmation_mail_from_email', true);
        $from_name 	= get_post_meta( $form_id, 'confirmation_mail_from_name', true);
        $reply_to_email 	= get_post_meta( $form_id, 'confirmation_mail_reply_to_email', true);
        $reply_to_name 	= get_post_meta( $form_id, 'confirmation_mail_reply_to_name', true);



        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        $site_description = get_bloginfo('description');
        $site_url = get_bloginfo('url');
        $site_logo_url = wp_get_attachment_url($site_logo_id);

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

        $mail_template_data = get_post( $mail_template_id );

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

            '{subscribe_confirm_url}' => $subscribe_confirm_url,
        );

        $vars_args = array();
        $vars_args['subscriber_id'] = $subscriber_id;
        $vars_args['form_id'] = $form_id;
        $vars_args['confirmation_mail_template'] = $mail_template_id;

        $vars = apply_filters('mail_picker_confirm_mail_vars', $vars, $vars_args);


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



        do_action('mail_picker_subscribed_mail_sent_'.$status, $vars_args);


        if($status){
            mail_picker_update_post_meta($subscriber_id, 'mail_sent_'.$form_id, 'success');

            update_post_meta($form_id, 'mail_sent_success', (int)$mail_sent_success+1);

        }else{
            mail_picker_update_post_meta($subscriber_id, 'mail_sent_'.$form_id, 'fail');

            update_post_meta($form_id, 'mail_sent_fail', (int)$mail_sent_fail+1);


        }



    }


    if($after_submit_action == 'redirect_link'){


        ?>
        <script>
            jQuery(document).ready(function($) {
                window.location.href = '<?php echo esc_url_raw($redirect_link); ?>';
            })
        </script>
        <?php
        //wp_safe_redirect($redirect_link);
    }else{
        $success_message 	= get_post_meta( $form_id, 'success_message', true);

        ?>
        <p><?php echo $success_message; ?></p>
        <?php
    }


}


add_action('mail_picker_subscriber_submitted_exist', 'mail_picker_subscriber_submitted_exist', 10, 2);


function mail_picker_subscriber_submitted_exist($subscriber_data, $atts){

    $form_id = isset($atts['id']) ? $atts['id'] : '';


    $already_exist_message 	= get_post_meta( $form_id, 'already_exist_message', true);
    ?>
    <p><?php echo $already_exist_message; ?></p>
    <?php
}





add_action('mail_picker_form_main', 'mail_picker_form_main_input_fields');


function mail_picker_form_main_input_fields($atts){

    $form_id = isset($atts['id']) ? $atts['id'] : '';

    $enable_recaptcha	= get_post_meta( $form_id, 'enable_recaptcha', true);
    $mail_picker_settings = get_option('mail_picker_settings');
    $recaptcha_site_key = isset($mail_picker_settings['recaptcha_site_key']) ? $mail_picker_settings['recaptcha_site_key'] : '';



    $layout_elements_data = get_post_meta($form_id,'layout_elements_data', true);

    ?>
    <div class="layout-<?php echo $form_id; ?>">

        <?php
        foreach ($layout_elements_data as  $fieldIndex =>$fieldData){
            foreach ($fieldData as $fieldId => $field){

                //echo '<pre>'.var_export($fieldIndex, true).'</pre>';
                $field['index'] = $fieldIndex;

                do_action('mail_picker_form_element_'.$fieldId, $field);

            }
        }



        foreach ($layout_elements_data as  $fieldIndex =>$fieldData){
            foreach ($fieldData as $fieldId => $field){

                //echo '<pre>'.var_export($fieldIndex, true).'</pre>';
                $fieldArgs = array('element'=> $field, 'layout_id'=> $form_id,'index'=> $fieldIndex);

                do_action('mail_picker_form_element_css_'.$fieldId, $fieldArgs);

            }
        }


        if($enable_recaptcha == 'yes'):
            ?>
            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
            <?php

            wp_enqueue_style('google-recaptcha');
        endif;

        ?>
        <p>
            <input class="" type="submit" name="Submit" />
            <?php wp_nonce_field( 'mail_picker_nonce','mail_picker_nonce' ); ?>
        </p>

    </div>
    <?php


}




add_action('mail_picker_form_main', 'mail_picker_form_main_scripts');


function mail_picker_form_main_scripts($atts){

}

