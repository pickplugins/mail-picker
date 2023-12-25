<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 


add_filter( 'mail_picker_send_mail_via_api_smtp2go', 'mail_picker_send_mail_via_api_smtp2go', 10, 2 );

function mail_picker_send_mail_via_api_smtp2go($email_data, $api_data ) {

    $api_key = isset($api_data['api_key']) ? $api_data['api_key'] : '';

    $mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
    $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
    $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
    $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

    $mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
    $email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
    $mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
    $mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
    $mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';

//    error_log(serialize($api_data));
//    error_log(serialize($email_data));

    // API query parameters
    $body = array(
        'api_key' => $api_key,
        'to' => [$mail_to],
        'sender' => "$email_from_name <$mail_from>",
        'subject' => $mail_subject,
        'text_body' => wp_strip_all_tags($mail_body),
        'html_body' => $mail_body,

    );


    $endpoint = 'https://api.smtp2go.com/v3/email/send/';



    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];

    $response = wp_remote_post( $endpoint, $options );



    // Send query to the license manager server
    //$response = wp_remote_post(add_query_arg($api_params, 'https://api.smtp2go.com/v3/email/send/'), array('timeout' => 20, 'sslverify' => false));

    // Check for error in the response
    if (is_wp_error($response)){
        //echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
        return false;
    }
    else{

        return true;

        //echo '<pre>'.var_export($response, true).'</pre>';

    }



    ///return ;
}


add_filter( 'mail_picker_send_mail_via_api_mandrill', 'mail_picker_send_mail_via_api_mandrill', 10, 2 );

function mail_picker_send_mail_via_api_mandrill($email_data, $api_data ) {

    $api_key = isset($api_data['api_key']) ? $api_data['api_key'] : '';

    $mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
    $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
    $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
    $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

    $mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
    $email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
    $mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
    $mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
    $mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';

    error_log(serialize($api_data));
    error_log(serialize($email_data));


    // API query parameters
    $body = array(
        'message' => array(
            'html' => $mail_body,
            'text' => wp_strip_all_tags($mail_body),

            'subject' => $mail_subject,
            'from_email' => $mail_from,
            'from_name' => $email_from_name,
            'to' => [$mail_to],


        ),

        'key' => $api_key,

    );


    $endpoint = 'https://mandrillapp.com/api/1.0/messages/send';



    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];

    $response = wp_remote_post( $endpoint, $options );



    // Send query to the license manager server

    // Check for error in the response
    if (is_wp_error($response)){
        //echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
        return false;

    }
    else{

        //echo '<pre>'.var_export($response, true).'</pre>';
        return true;

    }




    ///return ;
}


add_filter( 'mail_picker_send_mail_via_api_sendpulse', 'mail_picker_send_mail_via_api_sendpulse', 10, 2 );

function mail_picker_send_mail_via_api_sendpulse($email_data, $api_data ) {

    $api_key = isset($api_data['api_key']) ? $api_data['api_key'] : '';

    $mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
    $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
    $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
    $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

    $mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
    $email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
    $mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
    $mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
    $mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';

    error_log(serialize($api_data));
    error_log(serialize($email_data));



    // API query parameters
    $body = array(
        'email' => array(
            'html' => base64_encode($mail_body),
            'text' => wp_strip_all_tags($mail_body),
            'subject' => $mail_subject,
            'from' => array(
                'name' => $email_from_name,
                'email' => $mail_from,
            ),

            'to' =>array(
                array(
                    'name' => '',
                    'email' => $mail_to,
                )
            ),


        ),



    );


    $endpoint = 'https://api.sendpulse.com/smtp/emails';



    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];

    $response = wp_remote_post( $endpoint, $options );



    // Send query to the license manager server

    // Check for error in the response
    if (is_wp_error($response)){
        //echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
        return false;
    }
    else{

        //echo '<pre>'.var_export($response, true).'</pre>';
        return  true;

    }


}

add_filter( 'mail_picker_send_mail_via_api_mailjet', 'mail_picker_send_mail_via_api_mailjet', 10, 2 );

function mail_picker_send_mail_via_api_mailjet($email_data, $api_data ) {

    $api_key = isset($api_data['api_key']) ? $api_data['api_key'] : '';
    $secret_key = isset($api_data['secret_key']) ? $api_data['secret_key'] : '';

    $mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
    $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
    $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
    $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

    $mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
    $email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
    $mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
    $mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
    $mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';

    error_log(serialize($api_data));
    error_log(serialize($email_data));


    //include 'includes/library/mailjet/php-mailjet-v3-simple.class.php';
    include( mail_picker_plugin_dir . 'includes/library/mailjet/php-mailjet-v3-simple.class.php');
    //require_once( mail_picker_plugin_dir . 'includes/library/mailjet/php-mailjet-v3-simple.class.php');


    $mj = new Mailjet( $api_key, $secret_key );


    //$mj = new Mailjet();
    $params = array(
        "method" => "POST",
        "from" => $mail_from,
        "to" => $mail_to,
        "subject" => $mail_subject,
        "text" => $mail_body
    );

    $result = $mj->sendEmail($params);

    error_log($mj->_response_code);


    if ($mj->_response_code == 200){
        //echo "success - email sent";
        return  true;
    }


    else{
        //echo "error - ".$mj->_response_code;
        return false;
    }





    //return $result;



//
//    // API query parameters
//    $body = array(
//        'user' => "$api_key:$secret_key",
//        'Messages' => array(
//
//            'TextPart' => wp_strip_all_tags($mail_body),
//            'HTMLPart' => $mail_body,
//
//            'subject' => $mail_subject,
//            'From' => array(
//                'Name' => $email_from_name,
//                'Email' => $mail_from,
//            ),
//
//            'To' =>array(
//                array(
//                    'Name' => '',
//                    'Email' => $mail_to,
//                )
//            ),
//
//
//        ),
//
//
//
//    );
//
//
//    $endpoint = 'https://api.mailjet.com/v3/send';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            'user' => "$api_key:$secret_key",
//        ],
//        'timeout'     => 60,
//        'redirection' => 5,
//        'blocking'    => true,
//        'httpversion' => '1.0',
//        'sslverify'   => false,
//        'data_format' => 'body',
//    ];
//
//    $response = wp_remote_post( $endpoint, $options );
//
//    error_log(serialize($response));
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        //echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//        return false;
//    }
//    else{
//
//        //echo '<pre>'.var_export($response, true).'</pre>';
//        return  true;
//
//    }



}

add_filter( 'mail_picker_send_mail_via_api_postmark', 'mail_picker_send_mail_via_api_postmark', 10, 2 );

function mail_picker_send_mail_via_api_postmark($email_data, $api_data ) {

    $api_key = isset($api_data['api_key']) ? $api_data['api_key'] : '';
    $secret_key = isset($api_data['secret_key']) ? $api_data['secret_key'] : '';

    $mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
    $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
    $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
    $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

    $mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
    $email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
    $mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
    $mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
    $mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';

    error_log(serialize($api_data));
    error_log(serialize($email_data));



    // API query parameters
    $body = array(
        'Subject' => $mail_subject,
        'HtmlBody' => $mail_body,
        'From' => $mail_from,
        'To' => $mail_to,


    );


    $endpoint = 'https://api.postmarkapp.com/email';



    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => '027529eb-f93d-4e5e-998b-10382a95fc1e',

        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];

    $response = wp_remote_post( $endpoint, $options );



    // Send query to the license manager server


    // Check for error in the response
    if (is_wp_error($response)){
        //echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
        return false;
    }
    else{

        //echo '<pre>'.var_export($response, true).'</pre>';
        return  true;

    }



}

add_filter( 'mail_picker_send_mail_via_api_pepipost', 'mail_picker_send_mail_via_api_pepipost', 10, 2 );

function mail_picker_send_mail_via_api_pepipost($email_data, $api_data ) {

    $api_key = isset($api_data['api_key']) ? $api_data['api_key'] : '';
    $secret_key = isset($api_data['secret_key']) ? $api_data['secret_key'] : '';

    $mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
    $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
    $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
    $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

    $mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
    $email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
    $mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
    $mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
    $mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';

    error_log(serialize($api_data));
    error_log(serialize($email_data));




    // API query parameters
    $body = array(
        'content' => array('type' => 'html', 'value' => $mail_body),
        'subject' => $mail_subject,
        'from' => array(
            'name' => $email_from_name,
            'email' => $mail_from,
        ),
        'personalizations' => array(
            'to' =>array('email'=>$mail_to, 'name'=>''),
        ),



    );


    $endpoint = 'https://api.pepipost.com/v5/mail/send';



    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
            'api_key' => $api_key,

        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];

    $response = wp_remote_post( $endpoint, $options );


    error_log(serialize($response));


    // Check for error in the response
    if (is_wp_error($response)){
        //echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
        return false;
    }
    else{

        //echo '<pre>'.var_export($response, true).'</pre>';
        return  true;

    }



}

add_filter( 'mail_picker_send_mail_via_api_smtpcom', 'mail_picker_send_mail_via_api_smtpcom', 10, 2 );

function mail_picker_send_mail_via_api_smtpcom($email_data, $api_data ) {

    $api_key = isset($api_data['api_key']) ? $api_data['api_key'] : '';
    $secret_key = isset($api_data['secret_key']) ? $api_data['secret_key'] : '';

    $mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
    $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
    $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
    $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

    $mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
    $email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
    $mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
    $mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
    $mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';

    error_log(serialize($api_data));
    error_log(serialize($email_data));






    // API query parameters
    $body = array(
        'subject' => $mail_subject,
        'fromAddress' => $email_from_name,
        'toAddress' => $mail_to,
        'mailFormat' => 'html',
        'body' => array(
            'parts' => array(
//                'version' => 'string',
                'type' => 'text/html',
//                'charset' => 'string',
//                'encoding' => 'string',
                'content' => $mail_body,


            )
        ),

        'originator' => array(
            'from' => array(
                'name' => $email_from_name,
                'address' => $mail_from,
            ),
            'reply_to' => array(
                'name' =>$email_from_name,
                'address' => $mail_from,
            ),

        ),
        'recipients' => array(
            'to' => array(
                'name' => '',
                'address' => $mail_to,
            ),


        ),




    );


   // $endpoint = 'https://api:b4e5381bbf47d00c4240b1e2745adce048e1dcb1@api.smtp.com/v4/messages';
    $endpoint = 'https://api.smtp.com/v4/v4/messages';



    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
//            'Authorization' => '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',
            'X-SMTPCOM-API' => 'b4e5381bbf47d00c4240b1e2745adce048e1dcb1',
            'Authorization' => 'b4e5381bbf47d00c4240b1e2745adce048e1dcb1',

        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];

    $response = wp_remote_post( $endpoint, $options );

    error_log(serialize($response));

    // Check for error in the response
    if (is_wp_error($response)){
        //echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
        return false;
    }
    else{

        //echo '<pre>'.var_export($response, true).'</pre>';
        return  true;

    }



}

add_filter( 'mail_picker_send_mail_via_api_sendgrid', 'mail_picker_send_mail_via_api_sendgrid', 10, 2 );

function mail_picker_send_mail_via_api_sendgrid($email_data, $api_data ) {

    $api_key = isset($api_data['api_key']) ? $api_data['api_key'] : '';
    $secret_key = isset($api_data['secret_key']) ? $api_data['secret_key'] : '';

    $mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
    $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
    $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
    $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

    $mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
    $email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
    $mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
    $mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
    $mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';

    error_log(serialize($api_data));
    error_log(serialize($email_data));







    // API query parameters
    $body = array(
        'subject' => $mail_subject,
        'content' => array(
            'type' => "text/plain",
            'value' => $mail_body,
        ),
        'from' => array(
            'email' => $mail_from,

        ),
        'personalizations' => array(
            'to' => array(
                array(
                    'email' => $mail_to,
                )
            ),
        ),




    );


    $endpoint = 'https://api.sendgrid.com/v3/mail/send';



    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$api_key,

        ],
        'timeout'     => 60,
//        'redirection' => 5,
//        'blocking'    => true,
//        'httpversion' => '1.0',
        'sslverify'   => false,
//        'data_format' => 'body',
    ];

    $response = wp_remote_post( $endpoint, $options );

    error_log(serialize($response));




    // Check for error in the response
    if (is_wp_error($response)){
        //echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
        return false;
    }
    else{

        //echo '<pre>'.var_export($response, true).'</pre>';
        return  true;

    }



}
