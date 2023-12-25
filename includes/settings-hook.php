<?php

//use underDEV\AdvancedCronManager\Cron\Element\Event;

if ( ! defined('ABSPATH')) exit;  // if direct access

add_action('mail_picker_settings_content_general', 'mail_picker_settings_content_general');

function mail_picker_settings_content_general(){
    $settings_tabs_field = new settings_tabs_field();

    $mail_picker_settings = get_option('mail_picker_settings');

    //delete_option('mail_picker_settings');


    $recurrence_interval = isset($mail_picker_settings['recurrence_interval']) ? $mail_picker_settings['recurrence_interval'] : array();
    $site_logo = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';
    $recaptcha_site_key = isset($mail_picker_settings['recaptcha_site_key']) ? $mail_picker_settings['recaptcha_site_key'] : '';
    $recaptcha_secret_key = isset($mail_picker_settings['recaptcha_secret_key']) ? $mail_picker_settings['recaptcha_secret_key'] : '';


    //echo '<pre>'.var_export($recurrence_interval, true).'</pre>';

    ?>
    <div class="section">
        <div class="section-title"><?php echo __('Email verification', 'mail-picker'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for email verification.', 'mail-picker'); ?></p>

        <?php




        $meta_fields = array(

            array(
                'id'		=> 'display',
                'title'		=> __('Recurrence title','mail-picker'),
                'details'	=> __('Write recurrence title here.','mail-picker'),
                'type'		=> 'text',
                'value'		=> '',
                'default'		=> '',
                'placeholder'		=> '15 Minutes',
            ),
            array(
                'id'		=> 'interval',
                'title'		=> __('Interval','mail-picker'),
                'details'	=> __('Set interval in second. 15*60 = 900','mail-picker'),
                'type'		=> 'text',
                'value'		=> '',
                'default'		=> '',
                'placeholder'		=> '900',
            ),


        );


        $args = array(
            'id'		=> 'recurrence_interval',
            'parent'		=> 'mail_picker_settings',
            'title'		=> __('Custom intervals','text-domain'),
            'details'	=> __('Add custom intervals','text-domain'),
            'collapsible'=> true,
            'type'		=> 'repeatable',
            'limit'		=> 10,
            'title_field'		=> 'display',
            'value'		=> $recurrence_interval,
            'fields'    => $meta_fields,
        );

        $settings_tabs_field->generate_field($args);



        $args = array(
            'id'		=> 'site_logo',
            'parent'		=> 'mail_picker_settings',
            'title'		=> __('Site logo','mail-picker'),
            'details'	=> __('Select site logo image.','mail-picker'),
            'type'		=> 'media',
            'value'		=> $site_logo,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'		=> 'recaptcha_site_key',
            'parent'		=> 'mail_picker_settings',
            'title'		=> __('reCAPTCHA site key','mail-picker'),
            'details'	=> __('Google reCAPTCHA site key, please register here <a href="https://www.google.com/recaptcha/admin/">https://www.google.com/recaptcha/admin/</a>','mail-picker'),
            'type'		=> 'text',
            'value'		=> $recaptcha_site_key,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'		=> 'recaptcha_secret_key',
            'parent'		=> 'mail_picker_settings',
            'title'		=> __('reCAPTCHA secret key','mail-picker'),
            'details'	=> __('Google reCAPTCHA secret key, please register here <a href="https://www.google.com/recaptcha/admin/">https://www.google.com/recaptcha/admin/</a>','mail-picker'),
            'type'		=> 'text',
            'value'		=> $recaptcha_secret_key,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args);








        ?>

    </div>



    <?php
}


add_action('mail_picker_settings_content_test_mail', 'mail_picker_settings_content_test_mail');

if(!function_exists('mail_picker_settings_content_test_mail')) {
    function mail_picker_settings_content_test_mail(){

        $settings_tabs_field = new settings_tabs_field();
        $mail_picker_settings = get_option('mail_picker_settings');

        $smtp_from_email = isset($mail_picker_settings['smtp_from_email']) ? $mail_picker_settings['smtp_from_email'] : '';


        $content = 'This is test mail sent from '.get_option('blogname').'
Powered by Mail Picker';

        ?>
        <div class="section">
            <div class="section-title"><?php echo __('Test the mail', 'user-verification'); ?></div>
            <p class="description section-description"><?php echo __('Drive a test mail.', 'user-verification'); ?></p>


            <div class="test-mail">


                <?php


                $args = array(
                    'id'		=> 'to_email',
                    'css_id'		=> 'test_mail_email',

                    'parent'		=> 'mail_picker_test_mail',
                    'title'		=> __('Send To','user-verification'),
                    'details'	=> __('Write to email','user-verification'),
                    'type'		=> 'text',
                    'value'		=> get_option('admin_email'),
                    'default'		=> '',
                    'placeholder'		=> '',
                );

                $settings_tabs_field->generate_field($args);


                $args = array(
                    'id'		=> 'content',
                    'css_id'		=> 'test_mail_content',

                    'parent'		=> 'mail_picker_test_mail',
                    'title'		=> __('Message','user-verification'),
                    'details'	=> __('Write mail message','user-verification'),
                    'type'		=> 'textarea',
                    'value'		=> $content,
                    'default'		=> '',
                    'placeholder'		=> '',
                );

                $settings_tabs_field->generate_field($args);



                ?>

            </div>

            <div id="send-test-mail" class="button">Send test mail</div>
            <div id="send-test-mail-status" class=""></div>

        </div>
        <style>
            #send-test-mail-status{
                display:none;
            }

            #send-test-mail-status.active{
                display:block;
            }
        </style>
        <?php



    }
}

add_action('mail_picker_settings_content_smtp', 'mail_picker_settings_content_smtp');

if(!function_exists('mail_picker_settings_content_smtp')) {
    function mail_picker_settings_content_smtp(){


        $settings_tabs_field = new settings_tabs_field();
        $mail_picker_settings = get_option('mail_picker_settings');

        $smtp_from_email = isset($mail_picker_settings['smtp_from_email']) ? $mail_picker_settings['smtp_from_email'] : '';
        $smtp_from_name = isset($mail_picker_settings['smtp_from_name']) ? $mail_picker_settings['smtp_from_name'] : '';


        ?>
        <div class="section">
            <div class="section-title"><?php echo __('SMTP info', 'user-verification'); ?></div>
            <p class="description section-description"><?php echo __('Write SMTP information.', 'user-verification'); ?></p>


            <?php

            $args = array(
                'id'		=> 'smtp_from_email',
                'parent'		=> 'mail_picker_settings',
                'title'		=> __('SMTP from email','user-verification'),
                'details'	=> __('Write from email','user-verification'),
                'type'		=> 'text',
                'value'		=> $smtp_from_email,
                'default'		=> '',
                'placeholder'		=> '',


            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'smtp_from_name',
                'parent'		=> 'mail_picker_settings',
                'title'		=> __('SMTP from name','user-verification'),
                'details'	=> __('Write from name','user-verification'),
                'type'		=> 'text',
                'value'		=> $smtp_from_name,
                'default'		=> '',
                'placeholder'		=> '',


            );

            $settings_tabs_field->generate_field($args);


            ?>

        </div>
            <?php







        $smtp_list = array(

            'other_smtp' => array(
                'name'=> __('Other SMTP', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),
//
//            'gmail' => array(
//                'name'=> __('GMail', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'amazonses' => array(
//                'name'=> __('Amazon SES', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'outlook' => array(
//                'name'=> __('Outlook', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'smtpcom' => array(
//                'name'=> __('SMTP.com', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'zohomail' => array(
//                'name'=> __('Zoho Mail', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//
//            'sendgrid' => array(
//                'name'=> __('SendGrid', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'mailgun' => array(
//                'name'=> __('Mailgun', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'sendinblue' => array(
//                'name'=> __('SendInBlue', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
////            'sparkpost' => array(
////                'name'=> __('SparkPost', 'mail-picker'),
////                'enable'=> 'yes',
////                'description'=> '',
////            ),
//
//            'pepipost' => array(
//                'name'=> __('PepiPost', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'postmark' => array(
//                'name'=> __('Postmark', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'mailjet' => array(
//                'name'=> __('MailJet', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'sendpulse' => array(
//                'name'=> __('SendPulse', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//            'mandrill' => array(
//                'name'=> __('Mandrill', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
////            'moosend' => array(
////                'name'=> __('Moosend', 'mail-picker'),
////                'enable'=> 'yes',
////                'description'=> '',
////            ),
//
////            'constantcontact' => array(
////                'name'=> __('constantcontact', 'mail-picker'),
////                'enable'=> 'yes',
////                'description'=> '',
////            ),
//
//            'turbosmtp' => array(
//                'name'=> __('turboSMTP', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
////            'mailify' => array(
////                'name'=> __('Mailify', 'mail-picker'),
////                'enable'=> 'yes',
////                'description'=> '',
////            ),
//
            'smtp2go' => array(
                'name'=> __('smtp2go.com', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),
//
//            'inboxroad' => array(
//                'name'=> __('inboxroad', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'elasticemail' => array(
//                'name'=> __('Elastic Email', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'mailersend' => array(
//                'name'=> __('MailerSend', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'mailmarketer' => array(
//                'name'=> __('Mail Marketer', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),
//
//            'smtpprovider' => array(
//                'name'=> __('SMTP provider', 'mail-picker'),
//                'enable'=> 'yes',
//                'description'=> '',
//            ),



        );

        $smtp_list_saved = isset($mail_picker_settings['smtp']) ? $mail_picker_settings['smtp'] : $smtp_list;
        $active_smtp = isset($mail_picker_settings['active_smtp']) ? $mail_picker_settings['active_smtp'] : 'other_smtp';


        //var_dump($mail_picker_settings);
        //var_dump($active_smtp);

        ?>
        <div class="section">
            <div class="section-title"><?php echo __('SMTP Services', 'user-verification'); ?></div>
            <p class="description section-description"><?php echo __('Write SMTP user information.', 'user-verification'); ?></p>

            <?php


            ob_start();


            ?>
            <div class="expandable">
                <?php




                if(!empty($smtp_list))
                    foreach($smtp_list as $key=> $templates){

                        $smtp_data = isset($smtp_list_saved[$key]) ? $smtp_list_saved[$key] : $templates;

                        $enable = isset($smtp_data['enable']) ? $smtp_data['enable'] : '';
                        $description = isset($smtp_data['description']) ? $smtp_data['description'] : '';



                        //echo '<pre>'.var_export($enable).'</pre>';

                        ?>
                        <div class="item template <?php echo $key; ?>">
                            <div class="header">
                                <span title="<?php echo __('Click to expand', 'user-verification'); ?>" class="expand ">
                                    <i class="fa fa-expand"></i>
                                    <i class="fa fa-compress"></i>
                                </span>

                                <input <?php if($active_smtp == $key) echo 'checked'; ?>  type="radio" name="mail_picker_settings[active_smtp]" value="<?php echo $key; ?>">


                                <span class="expand"><?php echo $templates['name']; ?></span>

                            </div>
                            <input type="hidden" name="mail_picker_settings[smtp][<?php echo esc_attr($key); ?>][name]" value="<?php echo esc_attr($templates['name']); ?>" />
                            <div class="options">
                                <div class="description"><?php echo esc_html($description); ?></div>


                                <?php


                                do_action('mail_picker_smtp_'.$key, $smtp_data);

                                ?>


                            </div>

                        </div>
                        <?php

                    }


                ?>


            </div>
            <?php


            $html = ob_get_clean();




            $args = array(
                'id'		=> 'smtp',
                //'parent'		=> '',
                'title'		=> __('SMTP services list','user-verification'),
                'details'	=> __('List of SMTP services & providers.','user-verification'),
                'type'		=> 'custom_html',
                //'multiple'		=> true,
                'html'		=> $html,
            );

            $settings_tabs_field->generate_field($args);




            ?>


        </div>
        <?php


    }
}


add_action('mail_picker_smtp_other_smtp', 'mail_picker_smtp_other_smtp');


function mail_picker_smtp_other_smtp($smtp_data){

    $settings_tabs_field = new settings_tabs_field();

    $host = isset($smtp_data['host']) ? $smtp_data['host'] : '';
    $encryption = isset($smtp_data['encryption']) ? $smtp_data['encryption'] : 'none';
    $autotls = isset($smtp_data['autotls']) ? $smtp_data['autotls'] : 'yes';
    $auth = isset($smtp_data['auth']) ? $smtp_data['auth'] : 'yes';


    $port = isset($smtp_data['port']) ? $smtp_data['port'] : '';
    $user = isset($smtp_data['user']) ? $smtp_data['user'] : '';
    $pass = isset($smtp_data['pass']) ? $smtp_data['pass'] : '';




    $key = 'other_smtp';

    $args = array(
        'id'		=> 'host',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('SMTP Host','user-verification'),
        'details'	=> __('Write smtp host or server address','user-verification'),
        'type'		=> 'text',
        'value'		=> $host,
        'default'		=> '',
        'placeholder'		=> '',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'encryption',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Encryption?','user-verification'),
        'details'	=> __('Set SMTP encryption.','user-verification'),
        'type'		=> 'radio',
        'value'		=> $encryption,
        'default'		=> 'yes',
        'args'		=> array('none'=>__('None','user-verification'), 'ssl'=>__('SSL','user-verification'), 'tls'=>__('TLS','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'autotls',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Auto TLS?','user-verification'),
        'details'	=> __('Set SMTP Auto TLS.','user-verification'),
        'type'		=> 'radio',
        'value'		=> $autotls,
        'default'		=> array('yes'),
        'args'		=> array('yes'=>__('Enable','user-verification'), 'no'=>__('Disable','user-verification'), ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'auth',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Authentication?','user-verification'),
        'details'	=> __('Set SMTP Authentication.','user-verification'),
        'type'		=> 'radio',
        'value'		=> $auth,
        'default'		=> array('yes'),
        'args'		=> array('yes'=>__('Enable','user-verification'), 'no'=>__('Disable','user-verification'), ),

    );

    $settings_tabs_field->generate_field($args);




    $args = array(
        'id'		=> 'port',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('SMTP port','user-verification'),
        'details'	=> __('Write smtp port, Usually is 25, 465, 587','user-verification'),
        'type'		=> 'text',
        'value'		=> $port,
        'default'		=> '',
        'placeholder'		=> '',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'user',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('SMTP user','user-verification'),
        'details'	=> __('Write smtp user','user-verification'),
        'type'		=> 'text',
        'value'		=> $user,
        'default'		=> '',
        'placeholder'		=> '',


    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'pass',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('SMTP password','user-verification'),
        'details'	=> __('Write smtp password','user-verification'),
        'type'		=> 'text',
        'value'		=> $pass,
        'default'		=> '',
        'placeholder'		=> '',


    );

    $settings_tabs_field->generate_field($args);


}





add_action('mail_picker_smtp_sendgrid', 'mail_picker_smtp_sendgrid');


function mail_picker_smtp_sendgrid($smtp_data){

    $settings_tabs_field = new settings_tabs_field();

    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';




    $key = 'sendgrid';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write sendgrid api key, get your api key here <a href="%s">%s</a>', 'https://app.sendgrid.com/settings/api_keys', 'https://app.sendgrid.com/settings/api_keys'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',


    );

    $settings_tabs_field->generate_field($args);


//
//    // API query parameters
//    $body = array(
//        'subject' => "Hello from Postmark",
//        'content' => array(
//            'type' => "text/plain",
//            'value' => "<strong>Hello</strong> dear Postmark user.",
//        ),
//        'from' => array(
//            'email' => 'support@pickplugins.com',
//
//        ),
//        'personalizations' => array(
//            'to' => array(
//                array(
//                    'email' => 'public.nurhasan@gmail.com',
//                )
//            ),
//        ),
//
//
//
//
//    );
//
//
//    $endpoint = 'https://api.sendgrid.com/v3/mail/send';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            'Authorization' => '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',
//
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
//
//
//    // Send query to the license manager server
//    //$response = wp_remote_post(add_query_arg($api_params, 'https://api.smtp2go.com/v3/email/send/'), array('timeout' => 20, 'sslverify' => false));
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }


}



add_action('mail_picker_smtp_sendinblue', 'mail_picker_smtp_sendinblue');


function mail_picker_smtp_sendinblue($smtp_data){

    $settings_tabs_field = new settings_tabs_field();

    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';




    $key = 'sendinblue';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write sendinblue api key, get your api key here <a href="%s">%s</a>', 'https://account.sendinblue.com/advanced/api', 'https://account.sendinblue.com/advanced/api'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',


    );

    $settings_tabs_field->generate_field($args);






//
//    // API query parameters
//    $body = array(
//        'subject' => "Hello from Postmark",
//        'htmlContent' => "<strong>Hello</strong> dear Postmark user.",
//        'sender' => array(
//            'name' => 'PickPlugins',
//            'email' => 'support@pickplugins.com',
//
//        ),
//        'to' => array(
//                array(
//                    'name' => 'PickPlugins',
//                    'email' => 'public.nurhasan@gmail.com',
//                )
//        ),
//
//
//
//    );
//
//
//    $endpoint = 'https://api.sendinblue.com/v3/smtp/email';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            'api-key' => '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',
//
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }










}



add_action('mail_picker_smtp_postmark', 'mail_picker_smtp_postmark');

function mail_picker_smtp_postmark($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';
    $key = 'postmark';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write postmark api key, get your api key here <a href="%s">%s</a>', 'https://account.postmarkapp.com/servers', 'https://account.postmarkapp.com/servers'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);


//
//    // API query parameters
//    $body = array(
//        'Subject' => "Hello from Postmark",
//        'HtmlBody' => "<strong>Hello</strong> dear Postmark user.",
//        'From' => "support@pickplugins.com",
//        'To' => "public.nurhasan@gmail.com",
//
//
//    );
//
//
//    $endpoint = 'https://api.postmarkapp.com/email';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            'X-Postmark-Server-Token' => '027529eb-f93d-4e5e-998b-10382a95fc1e',
//
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }












}

add_action('mail_picker_smtp_pepipost', 'mail_picker_smtp_pepipost');

function mail_picker_smtp_pepipost($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';
    $key = 'pepipost';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write pepipost api key, get your api key here <a href="%s">%s</a>', 'https://app.pepipost.com/app/settings/integration', 'https://app.pepipost.com/app/settings/integration'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);


//
//    // API query parameters
//    $body = array(
//        'content' => array('type' => 'html', 'value' => 'Hello Lionel, Your flight for Barcelona is confirmed.'),
//        'subject' => "Example subject",
//        'from' => array(
//            'name' => 'pickplugins',
//            'email' => 'support@pickplugins.com',
//        ),
//
//        'recipient' =>array('public.nurhasan@gmail.com'),
//
//
//        'personalizations' => array(
//
//            'to' =>array('email'=>'public.nurhasan@gmail.com', 'name'=>'Lionel Messi'),
//
//
//        ),
//
//
//
//    );
//
//
//    $endpoint = 'https://api.pepipost.com/v5/mail/send';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            'api_key' => 'b2d7b2eb495c7727d0c4a03d46bf4812',
//
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }


}

add_action('mail_picker_smtp_sparkpost', 'mail_picker_smtp_sparkpost');

function mail_picker_smtp_sparkpost($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';
    $region = isset($smtp_data['region']) ? $smtp_data['region'] : 'all';

    $key = 'sparkpost';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write sparkpost api key, get your api key here <a href="%s">%s</a>', 'https://app.sparkpost.com/account/api-keys', 'https://app.sparkpost.com/account/api-keys'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'region',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API Location','user-verification'),
        'details'	=> __('Choose location','user-verification'),
        'type'		=> 'radio',
        'value'		=> $region,
        'args'		=> array('all'=>__('All','user-verification'), 'eu'=>__('EU','user-verification')  ),
        'default'		=> 'all',
    );

    $settings_tabs_field->generate_field($args);


}


add_action('mail_picker_smtp_mailgun', 'mail_picker_smtp_mailgun');

function mail_picker_smtp_mailgun($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';
    $region = isset($smtp_data['region']) ? $smtp_data['region'] : 'all';
    $domain = isset($smtp_data['domain']) ? $smtp_data['domain'] : '';

    $key = 'mailgun';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write sparkpost api key, get your api key here <a href="%s">%s</a>', 'https://app.mailgun.com/app/account/security/api_keys', 'https://app.mailgun.com/app/account/security/api_keys'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'domain',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Domain','user-verification'),
        'details'	=> __(sprintf('Write sparkpost api key, get your api key here <a href="%s">%s</a>', 'https://app.mailgun.com/app/domains', 'https://app.mailgun.com/app/domains'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $domain,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'region',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API Location','user-verification'),
        'details'	=> __('Choose location','user-verification'),
        'type'		=> 'radio',
        'value'		=> $region,
        'args'		=> array('all'=>__('All','user-verification'), 'eu'=>__('EU','user-verification')  ),
        'default'		=> 'all',
    );

    $settings_tabs_field->generate_field($args);




//
//    // API query parameters
//    $body = array(
//        'text' => "Congratulations Nur Hasan, you just sent an email with Mailgun!  You are truly awesome!",
//
//        'subject' => "Example subject",
//
//
//        'to' => 'Nur Hasan <public.nurhasan@gmail.com>',
//        'from' => 'Nur Hasan <support@pickplugins.com>',
//
//
//
//    );
//
//
//    $endpoint = 'https://api.mailgun.net/v3/sandboxd3def5a2492d4474950317f9f9168584.mailgun.org/messages';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            //'api' => 'e31dc3cc-89fe9212',
//            'api' => 'd47d2a7012139cc90404888b9e2f5511-e31dc3cc-89fe9212',
//
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }













}


add_action('mail_picker_smtp_mailjet', 'mail_picker_smtp_mailjet');

function mail_picker_smtp_mailjet($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';
    $secret_key = isset($smtp_data['secret_key']) ? $smtp_data['secret_key'] : '';

    $key = 'mailjet';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write mailjet api key, get your api key here <a href="%s">%s</a>', 'https://app.mailjet.com/account/api_keys', 'https://app.mailjet.com/account/api_keys'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'secret_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Secret key','user-verification'),
        'details'	=> __(sprintf('Write mailjet secret key, get your api key here <a href="%s">%s</a>', 'https://app.mailjet.com/account/api_keys', 'https://app.mailjet.com/account/api_keys'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $secret_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);





//
//    // API query parameters
//    $body = array(
//        'user' => 'dba03d79c59102c66926b323ae104cda:b648195c7ad6c6b6477de1ded6b8b7a8',
//        'Messages' => array(
//
//            'TextPart' => "Example text",
//            'HTMLPart' => "Example text",
//            'CustomID' => "AppGettingStartedTest",
//
//            'subject' => "Example subject",
//            'From' => array(
//                'Name' => 'pickplugins',
//                'Email' => 'support@pickplugins.com',
//            ),
//
//            'To' =>array(
//                array(
//                    'Name' => 'Nur',
//                    'Email' => 'public.nurhasan@gmail.com',
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
//    $endpoint = 'https://api.mailjet.com/v3.1/send';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            'user' => 'dba03d79c59102c66926b323ae104cda:b648195c7ad6c6b6477de1ded6b8b7a8',
//
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }









}



add_action('mail_picker_smtp_sendpulse', 'mail_picker_smtp_sendpulse');

function mail_picker_smtp_sendpulse($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';
    $secret_key = isset($smtp_data['secret_key']) ? $smtp_data['secret_key'] : '';

    $key = 'sendpulse';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write sendpulse api key, get your api key here <a href="%s">%s</a>', 'https://login.sendpulse.com/settings/#api', 'https://login.sendpulse.com/settings/#api'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'secret_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Secret key','user-verification'),
        'details'	=> __(sprintf('Write sendpulse secret key, get your api key here <a href="%s">%s</a>', 'https://login.sendpulse.com/settings/#api', 'https://login.sendpulse.com/settings/#api'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $secret_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);



//
//    // API query parameters
//    $body = array(
//        'email' => array(
//            'html' => base64_encode('support@pickplugins.com'),
//            'text' => "Example text",
//            'subject' => "Example subject",
//            'from' => array(
//                'name' => 'pickplugins',
//                'email' => 'support@pickplugins.com',
//            ),
//
//            'to' =>array(
//                array(
//                    'name' => 'Nur',
//                    'email' => 'public.nurhasan@gmail.com',
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
//    $endpoint = 'https://api.sendpulse.com/smtp/emails';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }



}




add_action('mail_picker_smtp_smtpcom', 'mail_picker_smtp_smtpcom');

function mail_picker_smtp_smtpcom($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';
    $sender = isset($smtp_data['sender']) ? $smtp_data['sender'] : '';

    $key = 'smtpcom';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write smtp.com api key, get your api key here <a href="%s">%s</a>', 'https://my.smtp.com/settings/api', 'https://my.smtp.com/settings/api'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'sender',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Sender Name','user-verification'),
        'details'	=> __(sprintf('Write smtp.com secret key, get your api key here <a href="%s">%s</a>', 'https://my.smtp.com/senders/', 'https://my.smtp.com/senders/'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $sender,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);






//
//
//    // API query parameters
//    $body = array(
//        'subject' => "Hello from Postmark",
//        'fromAddress' => 'support@pickplugins.com',
//        'toAddress' => 'public.nurhasan@gmail.com',
//        'mailFormat' => 'html',
//        'body' => array(
//            'parts' => array(
//                'version' => 'string',
//                'type' => 'string',
//                'charset' => 'string',
//                'encoding' => 'string',
//                'content' => 'Email can never be dead. The most neutral and effective way, that can be used for one to many and two way communication.',
//
//
//            )
//        ),
//
//        'originator' => array(
//            'from' => array(
//                'name' => 'string',
//                'address' => 'string',
//            ),
//            'reply_to' => array(
//                'name' => 'string',
//                'address' => 'string',
//            ),
//
//        ),
//        'recipients' => array(
//            'to' => array(
//                'name' => 'string',
//                'address' => 'string',
//            ),
//            'reply_to' => array(
//                'name' => 'string',
//                'address' => 'string',
//            ),
//
//        ),
//
//
//
//
//    );
//
//
//    $endpoint = 'https://api:{apikey}@api.smtp.com/v4/messages';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            'Authorization' => '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',
//
//        ],
//        'timeout'     => 60,
//        'redirection' => 5,
//        'blocking'    => true,
//        'httpversion' => '1.0',
//        'sslverify'   => false,
//        'data_format' => 'body',t94rIdZSSI68Umc4yag
//    ];
//
//    $response = wp_remote_post( $endpoint, $options );
//
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }















}













add_action('mail_picker_smtp_zohomail', 'mail_picker_smtp_zohomail');

function mail_picker_smtp_zohomail($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $client_id = isset($smtp_data['client_id']) ? $smtp_data['client_id'] : '';
    $client_secret = isset($smtp_data['client_secret']) ? $smtp_data['client_secret'] : '';
    $authorized_redirect_uri = isset($smtp_data['authorized_redirect_uri']) ? $smtp_data['authorized_redirect_uri'] : '';

    $key = 'zohomail';

    $args = array(
        'id'		=> 'client_id',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Client ID','user-verification'),
        'details'	=> '',
        'type'		=> 'text',
        'value'		=> $client_id,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'client_secret',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Client Secret','user-verification'),
        'details'	=> '',
        'type'		=> 'text',
        'value'		=> $client_secret,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'authorized_redirect_uri',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Authorized Redirect URI','user-verification'),
        'details'	=> '',
        'type'		=> 'text',
        'value'		=> $authorized_redirect_uri,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);




//
//    // API query parameters
//    $body = array(
//        'subject' => "Hello from Postmark",
//        'content' => "Email can never be dead. The most neutral and effective way, that can be used for one to many and two way communication.",
//        'fromAddress' => 'support@pickplugins.com',
//        'toAddress' => 'public.nurhasan@gmail.com',
//        'mailFormat' => 'html',
//
//
//
//
//    );
//
//
//    $endpoint = 'https://mail.zoho.com/api/accounts/755272112/messages';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
//            'Authorization' => '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',
//
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }



}


add_action('mail_picker_smtp_outlook', 'mail_picker_smtp_outlook');

function mail_picker_smtp_outlook($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $client_id = isset($smtp_data['client_id']) ? $smtp_data['client_id'] : '';
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';
    $authorized_redirect_uri = isset($smtp_data['authorized_redirect_uri']) ? $smtp_data['authorized_redirect_uri'] : '';

    $key = 'outlook';

    $args = array(
        'id'		=> 'client_id',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Client ID','user-verification'),
        'details'	=> '',
        'type'		=> 'text',
        'value'		=> $client_id,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> "",
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'authorized_redirect_uri',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Authorized Redirect URI','user-verification'),
        'details'	=> '',
        'type'		=> 'text',
        'value'		=> $authorized_redirect_uri,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);



}


add_action('mail_picker_smtp_amazonses', 'mail_picker_smtp_amazonses');

function mail_picker_smtp_amazonses($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $access_key = isset($smtp_data['access_key']) ? $smtp_data['access_key'] : '';
    $secret_key = isset($smtp_data['secret_key']) ? $smtp_data['secret_key'] : '';
    $region = isset($smtp_data['region']) ? $smtp_data['region'] : '';

    $key = 'amazonses';

    $args = array(
        'id'		=> 'access_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Access key','user-verification'),
        'details'	=> __(sprintf('Write amazon ses api key, get your api key here <a href="%s">%s</a>', 'https://console.aws.amazon.com/iam/home?region=us-east-1#/security_credentials', 'https://console.aws.amazon.com/iam/home?region=us-east-1#/security_credentials'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $access_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'secret_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('Secret key','user-verification'),
        'details'	=> __(sprintf('Write amazon ses secret key, get your api key here <a href="%s">%s</a>', 'https://console.aws.amazon.com/iam/home?region=us-east-1#/security_credentials', 'https://console.aws.amazon.com/iam/home?region=us-east-1#/security_credentials'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $secret_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'region',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Region','user-verification'),
        'details'	=> __('Choose region.','user-verification'),
        'type'		=> 'select',
        'value'		=> $region,
        'default'		=> '',
        'args'		=> array(

            'us-east-1'=>__('US East (N. Virginia)', 'user-verification'),
            'us-east-2'=>__('US East (Ohio)','user-verification'),
            'us-west-2'=>__('US West (Oregon)','user-verification'),
            'ca-central-1'=>__('Canada (Central)','user-verification'),
            'eu-west-1'=>__('EU (Ireland)','user-verification'),
            'eu-west-2'=>__('EU (London)','user-verification'),
            'eu-central-1'=>__('EU (Frankfurt)','user-verification'),
            'ap-south-1'=>__('Asia Pacific (Mumbai)','user-verification'),
            'ap-northeast-2'=>__('Asia Pacific (Seoul)','user-verification'),
            'ap-southeast-1'=>__('Asia Pacific (Singapore)','user-verification'),
            'ap-southeast-2'=>__('Asia Pacific (Sydney)','user-verification'),
            'ap-northeast-1'=>__('Asia Pacific (Tokyo)','user-verification'),
            'sa-east-1'=>__('South America (São Paulo)','user-verification'),



        ),

    );

    $settings_tabs_field->generate_field($args);

}







add_action('mail_picker_smtp_turbosmtp', 'mail_picker_smtp_turbosmtp');

function mail_picker_smtp_turbosmtp($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $user_mail = isset($smtp_data['user_mail']) ? $smtp_data['user_mail'] : '';
    $user_pass = isset($smtp_data['user_pass']) ? $smtp_data['user_pass'] : '';

    $key = 'turbosmtp';


    $args = array(
        'id'		=> 'user_mail',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('User email','user-verification'),
        'details'	=> __('Write user email address','user-verification'),
        'type'		=> 'text',
        'value'		=> $user_mail,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'user_pass',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('User password','user-verification'),
        'details'	=> __('Write user password','user-verification'),
        'type'		=> 'text',
        'value'		=> $user_pass,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);



//
//    // API query parameters
//    $body = array(
//        'authuser' => '',
//        'authpass' => '',
//
//        'api_key' => 'api-F316C5B2E27B11EB93CBF23C91C88F4E',
//        'to' => ["Test Person <public.nurhasan@gmail.com>"],
//        'from' => "Test Persons Friend <support@pickplugins.com>",
//        'subject' => "Hello Test Person",
//        'content' => "plain / text content",
//        'html_body' => "<h1>You're my favorite test person ever</h1>",
//
//    );
//
//
//    $endpoint = 'https://dashboard.serversmtp.com/api/authorize';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }


}


add_action('mail_picker_smtp_mandrill', 'mail_picker_smtp_mandrill');

function mail_picker_smtp_mandrill($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';

    $key = 'mandrill';

    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> "",
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

//
//    // API query parameters
//    $body = array(
//        'message' => array(
//            'html' => 'Hello text',
//            'text' => 'Hello text',
//
//            'subject' => 'Hello test mail',
//            'from_name' => 'Test Persons',
//            'to' => ["Test Person <public.nurhasan@gmail.com>"],
//            'from_email' => "Test Persons Friend <support@pickplugins.com>",
//
//        ),
//
//        'key' => 'QLqoScVDtI0prm0ZpTytHg',
//
//    );
//
//
//    $endpoint = 'https://mandrillapp.com/api/1.0/messages/send';
//
////    {"key":"","message":{"html":"","text":"","subject":"","from_email":"","from_name":"","to":[],"headers":{},"important":false,"track_opens":false,"track_clicks":false,"auto_text":false,"auto_html":false,"inline_css":false,"url_strip_qs":false,"preserve_recipients":false,"view_content_link":false,"bcc_address":"","tracking_domain":"","signing_domain":"","return_path_domain":"","merge":false,"merge_language":"mailchimp","global_merge_vars":[],"merge_vars":[],"tags":[],"subaccount":"","google_analytics_domains":[],"google_analytics_campaign":"","metadata":{"website":""},"recipient_metadata":[],"attachments":[],"images":[]},"async":false,"ip_pool":"","send_at":""}
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
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
//
//
//    // Send query to the license manager server
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }


}


add_action('mail_picker_smtp_mailify', 'mail_picker_smtp_mailify');

function mail_picker_smtp_mailify($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $access_key = isset($smtp_data['access_key']) ? $smtp_data['access_key'] : '';
    $secret_key = isset($smtp_data['secret_key']) ? $smtp_data['secret_key'] : '';
    $region = isset($smtp_data['region']) ? $smtp_data['region'] : '';

    $key = 'mailify';








}








add_action('mail_picker_smtp_smtp2go', 'mail_picker_smtp_smtp2go');

function mail_picker_smtp_smtp2go($smtp_data){

    $settings_tabs_field = new settings_tabs_field();
    $api_key = isset($smtp_data['api_key']) ? $smtp_data['api_key'] : '';


    $key = 'smtp2go';


    $args = array(
        'id'		=> 'api_key',
        'parent'		=> 'mail_picker_settings[smtp]['.$key.']',
        'title'		=> __('API key','user-verification'),
        'details'	=> __(sprintf('Write amazon ses api key, get your api key here <a href="%s">%s</a>', 'https://app.smtp2go.com/settings/apikeys/', 'https://app.smtp2go.com/settings/apikeys/'),'user-verification'),
        'type'		=> 'text',
        'value'		=> $api_key,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);


//
//    // API query parameters
//    $body = array(
//        'api_key' => 'api-F316C5B2E27B11EB93CBF23C91C88F4E',
//        'to' => ["Test Person <public.nurhasan@gmail.com>"],
//        'sender' => "Test Persons Friend <support@pickplugins.com>",
//        'subject' => "Hello Test Person",
//        'text_body' => "You're my favorite test person ever",
//        'html_body' => "<h1>You're my favorite test person ever</h1>",
//
//    );
//
//
//    $endpoint = 'https://api.smtp2go.com/v3/email/send/';
//
//
//
//    $body = wp_json_encode( $body );
//
//    $options = [
//        'body'        => $body,
//        'headers'     => [
//            'Content-Type' => 'application/json',
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
//
//
//    // Send query to the license manager server
//    //$response = wp_remote_post(add_query_arg($api_params, 'https://api.smtp2go.com/v3/email/send/'), array('timeout' => 20, 'sslverify' => false));
//
//    // Check for error in the response
//    if (is_wp_error($response)){
//        echo __("Unexpected Error! The query returned with an error.", 'mail-picker');
//    }
//    else{
//
//        echo '<pre>'.var_export($response, true).'</pre>';
//
//    }








}
















add_action('mail_picker_settings_content_subscriber_source', 'mail_picker_settings_content_subscriber_source');

if(!function_exists('mail_picker_settings_content_subscriber_source')) {
    function mail_picker_settings_content_subscriber_source(){

        $settings_tabs_field = new settings_tabs_field();


        $mail_picker_settings = get_option('mail_picker_settings');



        $subscriber_source_list = array(
            'cf7' => array(
                'name'=> __('Contact Form 7', 'mail-picker'),
				'enable'=> 'yes',
                'description'=> '',
            ),
            'wpforms' => array(
                'name'=> __('WPForms', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),


            'formidable' => array(
                'name'=> __('Formidable Form Builder', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),


            'forminator' => array(
                'name'=> __('Forminator', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),


            'caldera' => array(
                'name'=> __('Caldera Forms', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),


            'weforms' => array(
                'name'=> __('weForms', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),


            'kaliforms' => array(
                'name'=> __('Kaliforms', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),


            'email_subscribers' => array(
                'name'=> __('Email Subscribers', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),

            'mailoptin' => array(
                'name'=> __('MailOptin', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),

            'newsletter' => array(
                'name'=> __('Newsletter', 'mail-picker'),
                'enable'=> 'yes',
                'description'=> '',
            ),





        );

        $subscriber_source_saved = isset($mail_picker_settings['subscriber_source']) ? $mail_picker_settings['subscriber_source'] : $subscriber_source_list;



        ?>
        <div class="section">
            <div class="section-title"><?php echo __('Subscriber sources on form submission', 'user-verification'); ?></div>
            <p class="description section-description"><?php echo __('Customize subscriber sources on form submission settings.', 'user-verification'); ?></p>

            <?php


            ob_start();


            ?>
            <div class="expandable">
                <?php




                if(!empty($subscriber_source_list))
                    foreach($subscriber_source_list as $key=> $templates){

                        $subscriber_source_data = isset($subscriber_source_saved[$key]) ? $subscriber_source_saved[$key] : $templates;


                        $email_field_attr = isset($subscriber_source_data['email_field_attr']) ? $subscriber_source_data['email_field_attr'] : '';
                        $name_field_attr = isset($subscriber_source_data['name_field_attr']) ? $subscriber_source_data['name_field_attr'] : '';
                        $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
                            $subscriber_source_data['subscriber_list'] : array();

                        $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
                        $description = isset($subscriber_source_data['description']) ? $subscriber_source_data['description'] : '';
                        $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
                            $subscriber_source_data['subscriber_status'] : 'pending';



                        //echo '<pre>'.var_export($enable).'</pre>';

                        ?>
                        <div class="item template <?php echo $key; ?>">
                            <div class="header">
                                <span title="<?php echo __('Click to expand', 'user-verification'); ?>" class="expand ">
                                    <i class="fa fa-expand"></i>
                                    <i class="fa fa-compress"></i>
                                </span>

                                <?php
                                if($enable =='yes'):
                                    ?>
                                    <span title="<?php echo __('Enable', 'user-verification'); ?>" class="is-enable ">
                                        <i class="fa fa-check-square"></i>
                                    </span>
                                    <?php
                                else:
                                    ?>
                                    <span title="<?php echo __('Disabled', 'user-verification'); ?>" class="is-enable ">
                                        <i class="fa fa-times-circle"></i>
                                    </span>
                                    <?php
                                endif;
                                ?>
                                <span class="expand"><?php echo $templates['name']; ?></span>

                            </div>
                            <input type="hidden" name="mail_picker_settings[subscriber_source][<?php echo esc_attr($key); ?>][name]" value="<?php echo esc_attr($templates['name']); ?>" />
                            <div class="options">
                                <div class="description"><?php echo esc_html($description); ?></div>


                                <?php


                                do_action('mail_picker_subscriber_source_options_'.$key, $subscriber_source_data);

                                ?>


                            </div>

                        </div>
                        <?php

                    }


                ?>


            </div>
            <?php


            $html = ob_get_clean();




            $args = array(
                'id'		=> 'subscriber_source',
                //'parent'		=> '',
                'title'		=> __('Subscriber source','user-verification'),
                'details'	=> __('Customize subscriber source.','user-verification'),
                'type'		=> 'custom_html',
                //'multiple'		=> true,
                'html'		=> $html,
            );

            $settings_tabs_field->generate_field($args);




            ?>


        </div>
        <?php


    }
}





add_action('mail_picker_subscriber_source_options_wpforms','mail_picker_subscriber_source_options_wpforms');
function mail_picker_subscriber_source_options_wpforms($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'wpforms';

    $email_field_id = isset($subscriber_source_data['email_field_id']) ? $subscriber_source_data['email_field_id']
        : '';
    $name_field_id = isset($subscriber_source_data['name_field_id']) ? $subscriber_source_data['name_field_id'] : '';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);






    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_id',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field id','user-verification'),
        'details'	=> __('Write email field id for WPForms','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_id,
        'default'		=> '',
        'placeholder'		=> '3',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_id',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field id','user-verification'),
        'details'	=> __('Write name field id for WPForms','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_id,
        'default'		=> '',
        'placeholder'		=> '4',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}




add_action('mail_picker_subscriber_source_options_formidable','mail_picker_subscriber_source_options_formidable');
function mail_picker_subscriber_source_options_formidable($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'formidable';

    $email_field_id = isset($subscriber_source_data['email_field_id']) ? $subscriber_source_data['email_field_id']
        : '';
    $name_field_id = isset($subscriber_source_data['name_field_id']) ? $subscriber_source_data['name_field_id'] : '';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_id',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field id','user-verification'),
        'details'	=> __('Write email field id for WPForms','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_id,
        'default'		=> '',
        'placeholder'		=> '3',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_id',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field id','user-verification'),
        'details'	=> __('Write name field id for WPForms','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_id,
        'default'		=> '',
        'placeholder'		=> '4',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}






add_action('mail_picker_subscriber_source_options_forminator','mail_picker_subscriber_source_options_forminator');
function mail_picker_subscriber_source_options_forminator($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'forminator';

    $email_field_id = isset($subscriber_source_data['email_field_id']) ? $subscriber_source_data['email_field_id']
        : '';
    $name_field_id = isset($subscriber_source_data['name_field_id']) ? $subscriber_source_data['name_field_id'] : '';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_id',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field id','user-verification'),
        'details'	=> __('Write email field id for WPForms','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_id,
        'default'		=> '',
        'placeholder'		=> '3',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_id',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field id','user-verification'),
        'details'	=> __('Write name field id for WPForms','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_id,
        'default'		=> '',
        'placeholder'		=> '4',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}






add_action('mail_picker_subscriber_source_options_caldera','mail_picker_subscriber_source_options_caldera');
function mail_picker_subscriber_source_options_caldera($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'caldera';

    $email_field_id = isset($subscriber_source_data['email_field_id']) ? $subscriber_source_data['email_field_id']
        : '';
    $name_field_id = isset($subscriber_source_data['name_field_id']) ? $subscriber_source_data['name_field_id'] : '';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_id',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field id','user-verification'),
        'details'	=> __('Write email field id for WPForms','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_id,
        'default'		=> '',
        'placeholder'		=> 'fld_123',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_id',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field id','user-verification'),
        'details'	=> __('Write name field id for WPForms','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_id,
        'default'		=> '',
        'placeholder'		=> 'fld_124',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}


add_action('mail_picker_subscriber_source_options_weforms','mail_picker_subscriber_source_options_weforms');
function mail_picker_subscriber_source_options_weforms($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'weforms';

    $email_field_attr = isset($subscriber_source_data['email_field_attr']) ? $subscriber_source_data['email_field_attr'] : '';
    $name_field_attr = isset($subscriber_source_data['name_field_attr']) ? $subscriber_source_data['name_field_attr'] : '';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $description = isset($subscriber_source_data['description']) ? $subscriber_source_data['description'] : '';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field name','user-verification'),
        'details'	=> __('Write email field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_attr,
        'default'		=> '',
        'placeholder'		=> 'your-email',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field attribute','user-verification'),
        'details'	=> __('Write name field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_attr,
        'default'		=> '',
        'placeholder'		=> 'your-name',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}


add_action('mail_picker_subscriber_source_options_kaliforms','mail_picker_subscriber_source_options_kaliforms');
function mail_picker_subscriber_source_options_kaliforms($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'kaliforms';

    $email_field_attr = isset($subscriber_source_data['email_field_attr']) ? $subscriber_source_data['email_field_attr'] : '';
    $name_field_attr = isset($subscriber_source_data['name_field_attr']) ? $subscriber_source_data['name_field_attr'] : '';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $description = isset($subscriber_source_data['description']) ? $subscriber_source_data['description'] : '';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field name','user-verification'),
        'details'	=> __('Write email field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_attr,
        'default'		=> '',
        'placeholder'		=> 'email',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field attribute','user-verification'),
        'details'	=> __('Write name field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_attr,
        'default'		=> '',
        'placeholder'		=> 'first-name',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}

add_action('mail_picker_subscriber_source_options_mailoptin','mail_picker_subscriber_source_options_mailoptin');
function mail_picker_subscriber_source_options_mailoptin($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'mailoptin';

    $email_field_attr = isset($subscriber_source_data['email_field_attr']) ? $subscriber_source_data['email_field_attr'] : 'mo-email';
    $name_field_attr = isset($subscriber_source_data['name_field_attr']) ? $subscriber_source_data['name_field_attr'] : 'mo-name';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $description = isset($subscriber_source_data['description']) ? $subscriber_source_data['description'] : '';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field name','user-verification'),
        'details'	=> __('Write email field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_attr,
        'default'		=> '',
        'placeholder'		=> 'mo-email',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field attribute','user-verification'),
        'details'	=> __('Write name field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_attr,
        'default'		=> '',
        'placeholder'		=> 'mo-name',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}

add_action('mail_picker_subscriber_source_options_email_subscribers','mail_picker_subscriber_source_options_email_subscribers');
function mail_picker_subscriber_source_options_email_subscribers($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'email_subscribers';

    $email_field_attr = isset($subscriber_source_data['email_field_attr']) ? $subscriber_source_data['email_field_attr'] : '';
    $name_field_attr = isset($subscriber_source_data['name_field_attr']) ? $subscriber_source_data['name_field_attr'] : '';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $description = isset($subscriber_source_data['description']) ? $subscriber_source_data['description'] : '';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field name','user-verification'),
        'details'	=> __('Write email field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_attr,
        'default'		=> '',
        'placeholder'		=> 'email',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field attribute','user-verification'),
        'details'	=> __('Write name field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_attr,
        'default'		=> '',
        'placeholder'		=> 'first-name',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}

add_action('mail_picker_subscriber_source_options_cf7','mail_picker_subscriber_source_options_cf7');
function mail_picker_subscriber_source_options_cf7($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'cf7';

    $email_field_attr = isset($subscriber_source_data['email_field_attr']) ? $subscriber_source_data['email_field_attr'] : '';
    $name_field_attr = isset($subscriber_source_data['name_field_attr']) ? $subscriber_source_data['name_field_attr'] : '';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $description = isset($subscriber_source_data['description']) ? $subscriber_source_data['description'] : '';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field name','user-verification'),
        'details'	=> __('Write email field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_attr,
        'default'		=> '',
        'placeholder'		=> 'your-email',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field attribute','user-verification'),
        'details'	=> __('Write name field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_attr,
        'default'		=> '',
        'placeholder'		=> 'your-name',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}




add_action('mail_picker_subscriber_source_options_newsletter','mail_picker_subscriber_source_options_newsletter');
function mail_picker_subscriber_source_options_newsletter($subscriber_source_data){

    $settings_tabs_field = new settings_tabs_field();
    $key = 'newsletter';

    $email_field_attr = isset($subscriber_source_data['email_field_attr']) ? $subscriber_source_data['email_field_attr'] : 'email';
    $name_field_attr = isset($subscriber_source_data['name_field_attr']) ? $subscriber_source_data['name_field_attr'] : 'name';
    $subscriber_list = isset($subscriber_source_data['subscriber_list']) ?
        $subscriber_source_data['subscriber_list'] : array();

    $enable = isset($subscriber_source_data['enable']) ? $subscriber_source_data['enable'] : 'yes';
    $description = isset($subscriber_source_data['description']) ? $subscriber_source_data['description'] : '';
    $subscriber_status = isset($subscriber_source_data['subscriber_status']) ?
        $subscriber_source_data['subscriber_status'] : 'pending';


    $args = array(
        'id'		=> 'enable',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Enable?','user-verification'),
        'details'	=> __('Enable or disable this email notification.','user-verification'),
        'type'		=> 'select',
        'value'		=> $enable,
        'default'		=> 'yes',
        'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		=> 'subscriber_status',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber status?','user-verification'),
        'details'	=> __('Set subscriber status.','user-verification'),
        'type'		=> 'select',
        'value'		=> $subscriber_status,
        'default'		=> 'pending',
        'args'		=> array('pending'=>__('Pending','user-verification'), 'active'=>__
        ('Active','user-verification')  ),

    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'email_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Email field name','user-verification'),
        'details'	=> __('Write email field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $email_field_attr,
        'default'		=> '',
        'placeholder'		=> 'email',


    );

    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		=> 'name_field_attr',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Name field attribute','user-verification'),
        'details'	=> __('Write name field attribute for contact form 7','user-verification'),
        'type'		=> 'text',
        'value'		=> $name_field_attr,
        'default'		=> '',
        'placeholder'		=> 'name',


    );

    $settings_tabs_field->generate_field($args);




    $subscriber_list_terms = get_terms(
        array(
            'taxonomy' => 'subscriber_list',
            'hide_empty' => false,
        )
    );

    $subscriber_list_args = array();

    foreach ($subscriber_list_terms as $term){

        $term_id = $term->term_id;
        $term_name = $term->name;
        $term_count = $term->count;

        $subscriber_list_args[$term_id] = $term_name.'('.$term_count.')';

    }


    $args = array(
        'id'		=> 'subscriber_list',
        'parent'		=> 'mail_picker_settings[subscriber_source]['.$key.']',
        'title'		=> __('Subscriber list','mail-picker'),
        'details'	=> __('Select subscriber list.','mail-picker'),
        'type'		=> 'checkbox',
        'value'		=> $subscriber_list,
        'default'		=> array(),
        'args'		=> $subscriber_list_args,
    );

    $settings_tabs_field->generate_field($args);



}




add_action('mail_picker_settings_content_cron_list', 'mail_picker_settings_content_cron_list');

function mail_picker_settings_content_cron_list(){

    $settings_tabs_field = new settings_tabs_field();

    $mail_picker_settings = get_option('mail_picker_settings');

    //delete_option('mail_picker_settings');


    $recurrence_interval = isset($mail_picker_settings['recurrence_interval']) ? $mail_picker_settings['recurrence_interval'] : array();
    $site_logo = isset($mail_picker_settings['site_logo']) ? $mail_picker_settings['site_logo'] : '';
    $recaptcha_site_key = isset($mail_picker_settings['recaptcha_site_key']) ? $mail_picker_settings['recaptcha_site_key'] : '';


    //echo '<pre>'.var_export($recurrence_interval, true).'</pre>';

    ?>
    <div class="section">
        <div class="section-title"><?php echo __('Active cron\'s and process', 'mail-picker'); ?></div>
        <p class="description section-description"><?php echo __('List of active cron\'s and process.', 'mail-picker');
        ?></p>

        <?php


        ob_start();

        $html = ob_get_clean();

        $events_array = array();

        foreach ( _get_cron_array() as $timestamp => $events ) {

            foreach ( $events as $event_hook => $event_args ) {


                foreach ( $event_args as $event ) {

                    $interval       = isset( $event['interval'] ) ? $event['interval'] : 0;

                    //var_dump($event_hook);


//                    $schedule       = empty( $event['schedule'] ) ? $this->schedules->get_single_event_schedule()->slug : $event['schedule'];
//                    $events_array[] = new Element\Event( $event_hook, $schedule, $interval, $event['args'], $timestamp, $protected );

                }
            }
        }




        $args = array(
            'id'		=> 'cron',
            'parent'		=> 'mail_picker_settings',
            'title'		=> __('Active cron\'s','mail-picker'),
            'details'	=> __('List of active process','mail-picker'),
            'type'		=> 'custom_html',
            'html'		=> $html,
        );

        $settings_tabs_field->generate_field($args);











        ?>

    </div>



    <?php
}






add_action('mail_picker_settings_content_help_support', 'mail_picker_settings_content_help_support');

if(!function_exists('mail_picker_settings_content_help_support')) {
    function mail_picker_settings_content_help_support($tab){

        $settings_tabs_field = new settings_tabs_field();


        ?>
        <div class="section">
            <div class="section-title"><?php echo __('Get support', 'mail-picker'); ?></div>
            <p class="description section-description"><?php echo __('Use following to get help and support from our expert team.', 'mail-picker'); ?></p>

            <?php
            ob_start();
            ?>

            <p><?php echo __('Ask question for free on our forum and get quick reply from our expert team members.', 'mail-picker'); ?></p>
            <a class="button" target="_blank" href="https://www.pickplugins.com/create-support-ticket/"><?php echo __('Create support ticket', 'mail-picker'); ?></a>

            <p><?php echo __('Read our documentation before asking your question.', 'mail-picker'); ?></p>
            <a class="button" target="_blank" href="https://pickplugins.com/documentation/mail-picker/"><?php echo __('Documentation', 'mail-picker'); ?></a>

            <p><?php echo __('Watch video tutorials.', 'mail-picker'); ?></p>
            <a class="button" target="_blank" href="https://www.youtube.com/playlist?list=PL0QP7T2SN94b-w3Mpb_T81_SN4oQsLnDw"><i class="fab fa-youtube"></i> <?php echo __('All tutorials', 'mail-picker'); ?></a>





            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'get_support',
                //'parent'		=> '',
                'title'		=> __('Ask question','mail-picker'),
                'details'	=> '',
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);


            ob_start();
            ?>

            <p class="">We wish your 2 minutes to write your feedback about the <b>Mail Picker</b> plugin. give us
                <span
                        style="color: #ffae19"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span></p>

            <a target="_blank" href="https://wordpress.org/support/plugin/mail-picker/reviews/#new-post" class="button"><i class="fab fa-wordpress"></i> Write a review</a>


            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'reviews',
                //'parent'		=> '',
                'title'		=> __('Submit reviews','mail-picker'),
                'details'	=> '',
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);



            ?>


        </div>
        <?php


    }
}






add_action('mail_picker_settings_save', 'mail_picker_settings_save');

function mail_picker_settings_save(){


    $mail_picker_settings = mail_picker_recursive_sanitize_arr($_POST['mail_picker_settings']);

    $mail_picker_settings = isset($_POST['mail_picker_settings']) ? $mail_picker_settings : array();

    update_option('mail_picker_settings', $mail_picker_settings);


}
