<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 

add_action( 'wp_mail_failed', 'mail_picker_mail_error', 10, 1 );
function mail_picker_mail_error( $wp_error ) {

    error_log(serialize($wp_error));
    error_log( $wp_error->get_error_message() );


}



add_filter( 'wp_mail_from', 'mail_picker_sender_email' );

// Function to change email address
function mail_picker_sender_email( $email_address ) {

    $mail_picker_settings = get_option('mail_picker_settings');
    $smtp_from_email = isset($mail_picker_settings['smtp_from_email']) ? $mail_picker_settings['smtp_from_email'] : $email_address;

    $email_address = !empty($smtp_from_email) ? $smtp_from_email : $email_address;

    return $email_address;
}


add_filter( 'wp_mail_from_name', 'mail_picker_sender_name' );

// Function to change sender name
function mail_picker_sender_name( $email_from ) {

    $mail_picker_settings = get_option('mail_picker_settings');
    $smtp_from_name = isset($mail_picker_settings['smtp_from_name']) ? $mail_picker_settings['smtp_from_name'] : $email_from;

    $email_from = !empty($smtp_from_name) ? $smtp_from_name : $email_from;


    return $email_from;
}


function mail_picker_ajax_send_test_mail() {

    $mail_picker_settings = get_option('mail_picker_settings');
    $smtp_list = isset($mail_picker_settings['smtp']) ? $mail_picker_settings['smtp'] : array();
    $active_smtp = isset($mail_picker_settings['active_smtp']) ? $mail_picker_settings['active_smtp'] : 'other_smtp';
    $smtp_data = isset($smtp_list[$active_smtp]) ? $smtp_list[$active_smtp] : array();
    $smtp_data['id'] = $active_smtp;
    //error_log(serialize($smtp_data));


    $sendTo = isset($_POST['sendTo']) ? sanitize_email($_POST['sendTo']) : '';
    $content = isset($_POST['content']) ? wp_kses_post($_POST['content']) : '';

    $response = array();
    $subject = 'Mail Picker Test Mail';



    $class_mail_picker_emails = new class_mail_picker_emails();

    $email_data['mail_to'] =  $sendTo;
    $email_data['mail_from'] = get_option('blog_name') ;
    $email_data['mail_from_name'] = get_option('admin_email');


    $email_data['mail_subject'] = $subject;
    $email_data['mail_body'] = $content;
    $email_data['attachments'] = array();


    $status = $class_mail_picker_emails->send_email($email_data);



    if($status){
        $response['status'] = 'success';
        $response['message'] = 'Mail has been sent successfully';
    }else{
        $response['status'] = 'failed';
        $response['message'] = 'Unable to sent mail.';

    }





    echo json_encode($response);

    die();


}

add_action('wp_ajax_mail_picker_ajax_send_test_mail', 'mail_picker_ajax_send_test_mail');
//add_action('wp_ajax_nopriv_mail_picker_ajax_send_test_mail', 'mail_picker_ajax_send_test_mail');






add_action( 'phpmailer_init', 'mail_picker_phpmailer' );

function mail_picker_phpmailer( $phpmailer ) {


    $mail_picker_settings = get_option('mail_picker_settings');
    $smtp_list = isset($mail_picker_settings['smtp']) ? $mail_picker_settings['smtp'] : array();
    $active_smtp = isset($mail_picker_settings['active_smtp']) ? $mail_picker_settings['active_smtp'] : 'other_smtp';


    if($active_smtp != 'other_smtp') return;

    $smtp_from_email = isset($mail_picker_settings['smtp_from_email']) ? $mail_picker_settings['smtp_from_email'] : '';
    $smtp_from_name = isset($mail_picker_settings['smtp_from_name']) ? $mail_picker_settings['smtp_from_name'] : '';

    $smtp_data = isset($smtp_list[$active_smtp]) ? $smtp_list[$active_smtp] : array();

    $host = isset($smtp_data['host']) ? $smtp_data['host'] : '';
    $encryption = isset($smtp_data['encryption']) ? $smtp_data['encryption'] : 'none';
    $autotls = isset($smtp_data['autotls']) ? $smtp_data['autotls'] : 'yes';
    $auth = (isset($smtp_data['auth']) && $smtp_data['auth'] == 'yes') ? true : false;
    $port = isset($smtp_data['port']) ? (int) $smtp_data['port'] : 25;
    $user = isset($smtp_data['user']) ? $smtp_data['user'] : '';
    $pass = isset($smtp_data['pass']) ? $smtp_data['pass'] : '';

    if(empty($host) || empty($user) || empty($pass)){
        return;

    }

    $mailer = 'smtp';

//    error_log('mail send via smtp');
//
//    error_log(serialize($smtp_data));
//    error_log($auth);
//    error_log($port);
//    error_log($autotls);


    //$phpmailer->isSMTP();
    $phpmailer->Mailer = $mailer;
    $phpmailer->Sender = $smtp_from_email;
    $phpmailer->Host = $host;
    $phpmailer->Port = $port;

    if($auth){
        $phpmailer->SMTPAuth = true; // Ask it to use authenticate using the Username and Password properties
        $phpmailer->Username = $user;
        $phpmailer->Password = $pass;
    }


    if ( $encryption !== 'tls' && $autotls != 'yes' ) {
        $phpmailer->SMTPAutoTLS = false;
    }



    // Additional settings…
    //$phpmailer->SMTPSecure = $encryption; // Choose 'ssl' for SMTPS on port 465, or 'tls' for SMTP+STARTTLS on port 25 or 587
    $phpmailer->SMTPSecure = $encryption; // Choose 'ssl' for SMTPS on port 465, or 'tls' for SMTP+STARTTLS on port 25 or 587

    if(!empty($smtp_from_email))
    $phpmailer->From = $smtp_from_email;

    if(!empty($smtp_from_name))
    $phpmailer->FromName = $smtp_from_name;
}




function mail_picker_recursive_sanitize_arr($array) {

    foreach ( $array as $key => &$value ) {
        if ( is_array( $value ) ) {
            $value = mail_picker_recursive_sanitize_arr($value);
        }
        else {
            $value = sanitize_text_field( $value );
        }
    }

    return $array;
}



add_shortcode('mail_picker_campaign_check', 'mail_picker_campaign_check');
add_action('mail_picker_campaign_check', 'mail_picker_campaign_check');

function mail_picker_campaign_check(){

    $responses = array();
    $meta_query = array();

    $gmt_offset = get_option('gmt_offset');


    $meta_query[] = array(
        'key' => 'campaign_status',
        'value' => 'active',
        'compare' => '=',
    );

    $campaign_query = new WP_Query(
        array (
            'post_type' => 'mail_campaign',
            'post_status' => 'publish',
            'orderby' => 'date',
            'meta_query' => $meta_query,
            'order' => 'DESC',
            'posts_per_page' => -1,
        )
    );

    if ($campaign_query->have_posts()):

        $responses['campaign_found'] = 'yes';

        while ( $campaign_query->have_posts() ) :

            $campaign_query->the_post();

            $post_id = get_the_ID();
            $current_datetime = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));
            $last_check_datetime = get_post_meta($post_id, 'last_check_datetime', true);
            $last_check_datetime = !empty($last_check_datetime) ? $last_check_datetime : date('Y-m-d H:i:s');
            $next_check_datetime = get_post_meta($post_id, 'next_check_datetime', true);


            $recurrence_interval = get_post_meta($post_id, 'recurrence_interval', true);
            $recurrence_interval = !empty($recurrence_interval) ? $recurrence_interval : 'hourly';


            $schedules = wp_get_schedules();


            $interval = $schedules[$recurrence_interval]['interval'];
            $interval = $interval.' seconds';


            if(strtotime($next_check_datetime) < strtotime($current_datetime) ){

                do_action('mail_picker_campaign_running', $post_id);


                $last_check_datetime = date('Y-m-d H:i:s', strtotime($last_check_datetime));
                $next_check_datetime = date('Y-m-d H:i:s', strtotime($last_check_datetime . ' + '.$interval));

                update_post_meta($post_id, 'last_check_datetime', $current_datetime);
                update_post_meta($post_id, 'next_check_datetime', $next_check_datetime);

            }




        endwhile;

        wp_reset_query();
    else:

        $responses['campaign_found'] = 'no';


    endif;




}


add_action('mail_picker_campaign_running', 'mail_picker_campaign_running');
function mail_picker_campaign_running($campaign_id){

    $subscriber_list	= get_post_meta( $campaign_id, 'subscriber_list', true);
    $paged	= get_post_meta( $campaign_id, 'query_paged', true);
    $max_send_limit	= get_post_meta( $campaign_id, 'max_send_limit', true);


    $paged = !empty($paged) ? $paged : 1;
    $max_send_limit = !empty($max_send_limit) ? (int)$max_send_limit : 10;

    //var_dump($subscriber_list);



    $response = array();
    $tax_query = array();


    $tax_query[] = array(
        'taxonomy' => 'subscriber_list',
        'field'    => 'term_id',
        'terms'    => $subscriber_list,
        //'operator'    => '',
    );

    $subscriber_query = new WP_Query(
        array (
            'post_type' => 'subscriber',
            'post_status' => 'publish',
            'orderby' => 'date',
            'tax_query' => $tax_query,
            'order' => 'DESC',
            'posts_per_page' => $max_send_limit,
            'paged' => $paged,

        )
    );

    if ($subscriber_query->have_posts()):

        $response['subscriber_found'] = 'yes';

        while ( $subscriber_query->have_posts() ) :

            $subscriber_query->the_post();

            $subscriber_id = get_the_ID();
            $send_mail_args['subscriber_id'] = $subscriber_id;
            $send_mail_args['campaign_id'] = $campaign_id;


            do_action('mail_picker_campaign_send_mail',  $send_mail_args);



        endwhile;

        $paged = (int)$paged + 1;

        update_post_meta($campaign_id, 'query_paged', $paged);

        wp_reset_query();
    else:

        $response['subscriber_found'] = 'no';

        update_post_meta($campaign_id, 'campaign_status', 'finished');

    endif;


    //echo '<pre>'.var_export($response, true).'</pre>';



}



add_action('mail_picker_campaign_send_mail', 'mail_picker_campaign_send_mail');
function mail_picker_campaign_send_mail($send_mail_args){

    $subscriber_id = $send_mail_args['subscriber_id'];
    $campaign_id = $send_mail_args['campaign_id'];
    $mail_sent_success	= get_post_meta( $campaign_id, 'mail_sent_success', true);
    $mail_sent_fail	= get_post_meta( $campaign_id, 'mail_sent_fail', true);

    $mail_template_id	= get_post_meta( $campaign_id, 'mail_template_id', true);
    $mail_open_tracking	= get_post_meta( $campaign_id, 'mail_open_tracking', true);
    $utm_tracking	= get_post_meta( $campaign_id, 'utm_tracking', true);
    $utm_tracking_param	= get_post_meta( $campaign_id, 'utm_tracking_param', true);
    $link_tracking	= get_post_meta( $campaign_id, 'link_tracking', true);


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
    $unsubscribe_url = '';
    $subscribe_manage_url = '';

    $mail_subject 	= get_post_meta( $campaign_id, 'mail_subject', true);
    $from_name	= get_post_meta( $campaign_id, 'from_name', true);
    $from_email	= get_post_meta( $campaign_id, 'from_email', true);
    $reply_to_email	= get_post_meta( $campaign_id, 'reply_to_email', true);
    $reply_to_name	= get_post_meta( $campaign_id, 'reply_to_name', true);


    $mail_template_data = get_post( $mail_template_id );

    $mail_template_content	= $mail_template_data->post_content;

    $mail_template_content = do_shortcode($mail_template_content);
    $mail_template_content = wpautop($mail_template_content);

    if($mail_open_tracking == 'yes'){

        $file_url = get_bloginfo('url').'/mail-track-open-'.$campaign_id.'-'.$subscriber_id.'.png';
        $mail_template_content .= '<img width="0" height="0" border="0" src="'.$file_url.'"/>';
    }


    if($link_tracking == 'yes'){

        $mail_template_content = preg_replace('/href="(?!http:\/\/)([^"]+)"/', 'href="'.get_bloginfo('url').'?mail_picker_action=link_click&campaign_id='.$campaign_id.'&subscriber_id='.$subscriber_id.'&redirect=$1"', $mail_template_content);

    }



    if($utm_tracking == 'yes'){
        $utm_source = isset($utm_tracking_param['utm_source']) ? sanitize_text_field($utm_tracking_param['utm_source']) : '';
        $utm_medium = isset($utm_tracking_param['utm_medium']) ? sanitize_text_field($utm_tracking_param['utm_medium']) : '';
        $utm_campaign = isset($utm_tracking_param['utm_campaign']) ? sanitize_text_field($utm_tracking_param['utm_campaign']) : '';
        $utm_content = isset($utm_tracking_param['utm_content']) ? sanitize_text_field($utm_tracking_param['utm_content']) : '';
        $utm_term = isset($utm_tracking_param['utm_term']) ? sanitize_text_field($utm_tracking_param['utm_term']) : '';


        $link = add_query_arg( array('utm_source' => $utm_source, 'utm_medium' => $utm_medium, 'utm_campaign' => $utm_campaign, 'utm_content' => $utm_content, 'utm_term' => $utm_term,), '$1' );

        $mail_template_content = preg_replace('/href="(?!http:\/\/)([^"]+)"/', 'href="'.$link.'"', $mail_template_content);


    }




    $class_mail_picker_emails = new class_mail_picker_emails();


    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $site_logo_url = get_bloginfo('url');

    $vars = array(
        '{site_name}'=> esc_html($site_name),
        '{site_description}' => esc_html($site_description),
        '{site_url}' => esc_url_raw($site_url),
        '{site_logo_url}' => esc_url_raw($site_logo_url),

        '{subscriber_email}' => esc_html($subscriber_email),
        '{first_name}' => esc_html($first_name),
        '{last_name}' => esc_html($last_name),
        '{subscriber_name}' => esc_html($subscriber_name),
        '{subscriber_phone}' => esc_html($subscriber_phone),
        '{subscriber_country}' => esc_html($subscriber_country),
        '{subscriber_avatar}' => esc_html($subscriber_avatar),
        '{subscriber_rating}' => esc_html($subscriber_rating),
        '{subscriber_status}' => esc_html($subscriber_status),

        '{unsubscribe_url}' => esc_url_raw($unsubscribe_url),
        '{subscribe_manage_url}' => esc_url_raw($subscribe_manage_url),
    );


    $vars_args = array();
    $vars_args['subscriber_id'] = $subscriber_id;
    $vars_args['campaign_id'] = $campaign_id;
    $vars_args['mail_template_id'] = $mail_template_id;

    $vars = apply_filters('mail_picker_campaign_mail_vars', $vars, $vars_args);


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



    mail_picker_update_post_meta($subscriber_id, 'mail_sent_'.$campaign_id, 'success');

    do_action('mail_picker_campaign_mail_sent_'.$status, $vars_args);


    if($status){

        update_post_meta($campaign_id, 'mail_sent_success', (int)$mail_sent_success+1);

    }else{

        update_post_meta($campaign_id, 'mail_sent_fail', (int)$mail_sent_fail+1);

    }

}



function mail_picker_update_post_meta($object_id, $meta_key, $meta_value){

    global $wpdb;
    $table = $wpdb->prefix .'mail_picker_postmeta';



    $meta_ids = $wpdb->get_var($wpdb->prepare("SELECT meta_id FROM $table WHERE meta_key = %s AND post_id = %d", $meta_key, $object_id));

//    var_dump($meta_ids);
//    var_dump($object_id);


    if(empty($meta_ids)){
        $wpdb->query($wpdb->prepare("INSERT INTO $table ( meta_id, post_id, meta_key, meta_value) VALUES ( %d, %d, %s, %s)", array('', $object_id, $meta_key,  $meta_value )));

    }else{
        $wpdb->update( $table, array( "meta_value" => $meta_value ), array( 'meta_id' => $object_id ) );

    }






}








add_filter( 'manage_edit-mail_campaign_sortable_columns', 'mail_picker_smashing_mail_campaign_sortable_columns');
function mail_picker_smashing_mail_campaign_sortable_columns( $columns ) {
    $columns['mail_sent'] = 'mail_sent';
    return $columns;
}


add_filter( 'manage_mail_campaign_posts_columns', function( $columns ) {
    $columns['mail_sent'] = __( 'Send stats', 'textdomain' );


    return $columns;
} );




function mail_campaign_posts_search_count_display( $column, $post_id ) {
    if ($column == 'mail_sent'){

        $mail_sent_success = get_post_meta($post_id, 'mail_sent_success', true);
        $mail_sent_success = !empty($mail_sent_success) ? $mail_sent_success : 0;

        $mail_sent_fail = get_post_meta($post_id, 'mail_sent_fail', true);

        $mail_sent_fail = !empty($mail_sent_fail) ? $mail_sent_fail : 0;

        $total_mail_open = get_post_meta($post_id, 'total_mail_open', true);
        $total_mail_open = !empty($total_mail_open) ? $total_mail_open : 0;

        $total_link_click = get_post_meta($post_id, 'total_link_click', true);
        $total_link_click = !empty($total_link_click) ? $total_link_click : 0;

        ?>
        <p>Success: <?php echo $mail_sent_success; ?></p>
        <p>Failed: <?php echo $mail_sent_fail; ?></p>
        <p>Mail Open: <?php echo $total_mail_open; ?></p>
        <p>Link click: <?php echo $total_link_click; ?></p>



        <?php


    }
}

add_action( 'mail_picker_mail_campaign_posts_column' , 'mail_campaign_posts_search_count_display', 10, 2 );



















function mail_campaign_campaign_status_column( $columns ) {

    $columns['campaign_status'] = __( 'Campaign info', 'textdomain' );

    $date = $columns['date'];

    unset($columns['date']);

    $columns['date'] = $date;


    return $columns;

}
add_filter( 'manage_mail_campaign_posts_columns' , 'mail_campaign_campaign_status_column' );


function mail_picker_mail_campaign_posts_column( $column, $post_id ) {
    if ($column == 'campaign_status'){

        $campaign_status = get_post_meta($post_id,'campaign_status', true);
        $recurrence_interval = get_post_meta($post_id,'recurrence_interval', true);

        

        $last_check_datetime = get_post_meta($post_id,'last_check_datetime', true);
        $next_check_datetime = get_post_meta($post_id,'next_check_datetime', true);

        $schedules = wp_get_schedules();


        ?>
        <p>
            Status:
            <?php

            if($campaign_status == 'active'){
                echo '<i class="fas fa-bolt"></i> ';
                echo ucfirst($campaign_status);
            }elseif($campaign_status == 'paused'){
                echo '<i class="far fa-pause-circle"></i> ';
                echo ucfirst($campaign_status);
            }elseif($campaign_status == 'finished'){
                echo '<i class="far fa-check-circle"></i> ';
                echo ucfirst($campaign_status);
            }else{
                echo '<i class="fas fa-flag-checkered"></i> ';
                echo __('Not set');
            }


            ?>
        </p>
        <p>Interval: <?php echo isset($schedules[$recurrence_interval]['display']) ?
                $schedules[$recurrence_interval]['display'] : ''; ?></p>
        <?php

        if($campaign_status =='active'):
            ?>
            <p>Last check: <?php echo $last_check_datetime; ?></p>
            <p>Next check: <?php echo $next_check_datetime; ?></p>
            <?php
        endif;

        ?>


        <?php


    }
}

add_action( 'mail_picker_mail_campaign_posts_column' , 'mail_picker_mail_campaign_posts_column', 10, 2 );




function mail_picker_subscriber_campaign_status_column( $columns ) {

    $columns['subscriber_status'] = __( 'Status?', 'textdomain' );

    $columns['is_confirm'] = __( 'Confirmed?', 'textdomain' );
    $columns['last_active'] = __( 'Last active', 'textdomain' );


    $date = $columns['date'];

    unset($columns['date']);

    $columns['date'] = $date;


    return $columns;

}
add_filter( 'manage_subscriber_posts_columns' , 'mail_picker_subscriber_campaign_status_column' );


function mail_picker_subscriber_posts_column( $column, $post_id ) {

    if ($column == 'subscriber_status'){

        $subscriber_status = get_post_meta($post_id,'subscriber_status', true);
        ?>
        <p>
            <?php
            if($subscriber_status == 'active'){
                echo sprintf(__('%s Active'), '<i class="far fa-grin"></i>');
            }elseif($subscriber_status == 'pending'){
                echo sprintf(__('%s Pending'), '<i class="far fa-frown-open"></i>');
            }elseif($subscriber_status == 'blocked'){
                echo sprintf(__('%s Blocked'), '<i class="far fa-grimace"></i>');
            }elseif($subscriber_status == 'unsubscribed'){
                echo sprintf(__('%s Blocked'), '<i class="far fa-dizzy"></i>');
            }



            ?>
        </p>
        <?php
    }


    if ($column == 'is_confirm'){

        $is_confirm = get_post_meta($post_id,'is_confirm', true);
        ?>
        <p>
            <?php
            if($is_confirm == 'yes'){
                echo '<i class="fas fa-check"></i> ';
                echo 'Yes';
            }else{
                echo '<i class="fas fa-times"></i> ';
                echo __('No');
            }
            ?>
        </p>
        <?php
    }

    if ($column == 'last_active'){

        $last_active = get_post_meta($post_id,'last_active', true);
        ?>
        <p>
            <?php

            echo $last_active;

            ?>
        </p>
        <?php
    }


}

add_action( 'mail_picker_subscriber_posts_column' , 'mail_picker_subscriber_posts_column', 10, 2 );



function mail_picker_subscriber_form_status_column( $columns ) {

    $columns['total_submission'] = __( 'Total submission', 'textdomain' );
    $columns['total_confirm'] = __( 'Total confirmed', 'textdomain' );

    $columns['last_submission_date'] = __( 'Last submission date', 'textdomain' );


    $date = $columns['date'];

    unset($columns['date']);

    $columns['date'] = $date;


    return $columns;

}
add_filter( 'manage_subscriber_form_posts_columns' , 'mail_picker_subscriber_form_status_column' );


function mail_picker_subscriber_form_posts_column( $column, $post_id ) {

    if ($column == 'total_submission'){

        $total_submission = get_post_meta($post_id,'total_submission', true);

        $total_submission = !empty($total_submission) ? $total_submission : 0;
        ?>
        <p>
            <?php

            echo $total_submission;

            ?>
        </p>
        <?php
    }

    if ($column == 'total_confirm'){

        $total_confirm = get_post_meta($post_id,'total_confirm', true);

        $total_confirm = !empty($total_confirm) ? $total_confirm : 0;
        ?>
        <p>
            <?php

            echo $total_confirm;

            ?>
        </p>
        <?php
    }




    if ($column == 'last_submission_date'){

        $last_submission_date = get_post_meta($post_id,'last_submission_date', true);

        ?>
        <p>
            <?php

            echo $last_submission_date;

            ?>
        </p>
        <?php
    }


}

add_action( 'mail_picker_subscriber_form_posts_column' , 'mail_picker_subscriber_form_posts_column', 10, 2 );




function mail_picker_subscriber_source_status_column( $columns ) {

    $columns['recurrence_interval'] = __( 'Interval?', 'textdomain' );

    $date = $columns['date'];

    unset($columns['date']);

    $columns['date'] = $date;


    return $columns;

}
add_filter( 'manage_subscriber_source_posts_columns' , 'mail_picker_subscriber_source_status_column' );


function mail_picker_manage_subscriber_source_posts_column( $column, $post_id ) {

    if ($column == 'recurrence_interval'){

        $recurrence_interval = get_post_meta($post_id,'recurrence_interval', true);
        $recurrence_interval = !empty($recurrence_interval) ? $recurrence_interval : 'hourly';

        $last_check_datetime = get_post_meta($post_id,'last_check_datetime', true);
        $total_submission = get_post_meta($post_id,'total_submission', true);

        ?>
        <p>
            <?php
            $schedules = wp_get_schedules();


            $interval_name = isset($schedules[$recurrence_interval]['display']) ? $schedules[$recurrence_interval]['display'] : '';

            echo $interval_name;



            ?>
        </p>

        <?php
        if(!empty($last_check_datetime)):
            ?>
            <p>last check:
                <?php
                echo $last_check_datetime;

                ?>
            </p>
        <?php
        endif;

        if(!empty($total_submission)):
            ?>
            <p>Total request:
                <?php
                echo $total_submission;

                ?>
            </p>
        <?php
        endif;



    }





}

add_action( 'mail_picker_manage_subscriber_source_posts_column' , 'mail_picker_manage_subscriber_source_posts_column', 10, 2 );
