<?php
if (!defined('ABSPATH'))
    exit();



class MailPickerRest
{
    function __construct()
    {
        add_action('rest_api_init', array($this, 'register_routes'));
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
    }

    /**
     * check_subscriber
     *
     * @since 1.0.0
     * @param WP_REST_Request $post_data Post data.
     */
    public function check_subscriber($post_data)
    {

        $email = isset($post_data['email']) ? $post_data['email'] : '';

        $response = [];
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
            $response['subscriber_found'] = 'yes';
            while ($wp_query->have_posts()) : $wp_query->the_post();

                $subscriber_id = get_the_ID();
                $email = get_post_meta($subscriber_id, 'email', true);
                $response['email'] = $email;
                $response['subscriber_id'] = $subscriber_id;
            endwhile;
            wp_reset_query();
        else :
            $response['subscriber_found'] = 'no';
        endif;
        die(wp_json_encode($response));
    }

    /**
     * check_subscriber
     *
     * @since 1.0.0
     * @param WP_REST_Request $post_data Post data.
     */
    public function add_subscriber($post_data)
    {


        $email = isset($post_data['email']) ? $post_data['email'] : '';
        $first_name = isset($post_data['first_name']) ? $post_data['first_name'] : '';
        $last_name = isset($post_data['last_name']) ? $post_data['last_name'] : '';
        $subscriber_status = isset($post_data['subscriber_status']) ? $post_data['subscriber_status'] : '';
        $subscriber_list = isset($post_data['subscriber_list']) ? $post_data['subscriber_list'] : [];

        $response = [];


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

            $response['subscriber_found'] = 'yes';

            while ($wp_query->have_posts()) : $wp_query->the_post();

                $subscriber_id = get_the_ID();
                $email = get_post_meta($subscriber_id, 'email', true);


                $response['email'] = $email;
                $response['subscriber_id'] = $subscriber_id;


            endwhile;


            $response['message'] = __('Subscriber already exist.', 'mail-picker');
            $response['status'] = 'exist';

            wp_reset_query();
        else :

            // $args['formFieldData'] = $formFieldData;

            $args['email'] = $email;
            $args['first_name'] = $first_name;
            $args['last_name'] = $last_name;
            $args['subscriber_status'] = $subscriber_status;
            $args['subscriber_list'] = $subscriber_list;



            $response = $this->create_subscriber($args);;


        endif;


        die(wp_json_encode($response));
    }

    /**
     * check_subscriber
     *
     * @since 1.0.0
     * @param WP_REST_Request $post_data Post data.
     */
    public function unsubscribe($post_data)
    {

        $email = isset($post_data['email']) ? $post_data['email'] : '';

        $response = [];

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

            $response['subscriber_found'] = 'yes';

            while ($wp_query->have_posts()) : $wp_query->the_post();

                $subscriber_id = get_the_ID();
                $email = get_post_meta($subscriber_id, 'email', true);


                $response['email'] = $email;
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
     * check_subscriber
     *
     * @since 1.0.0
     * @param WP_REST_Request $post_data Post data.
     */
    public function remove_subscriber($post_data)
    {

        $email = isset($post_data['email']) ? $post_data['email'] : '';

        $response = [];


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

            $response['subscriber_found'] = 'yes';

            while ($wp_query->have_posts()) : $wp_query->the_post();

                $subscriber_id = get_the_ID();
                $email = get_post_meta($subscriber_id, 'email', true);

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
     * check_subscriber
     *
     * @since 1.0.0
     * @param WP_REST_Request $post_data Post data.
     */
    public function mail_track_open($post_data)
    {
        if (preg_match('/mail-track-open/', $_SERVER['REQUEST_URI'])) {

            $parts = isset($_SERVER['REQUEST_URI']) ? basename(esc_url_raw($_SERVER['REQUEST_URI'])) : '';


            $parts = explode('-', $parts);

            $campaign_id = isset($parts[3]) ? sanitize_text_field($parts[3]) : '';
            $subscriber_id = isset($parts[4]) ?  sanitize_text_field($parts[4]) : '';

            $subscriber_id = str_replace('.png', '', $subscriber_id);


            if (!empty($campaign_id) && !empty($subscriber_id)) {

                mail_picker_update_post_meta($subscriber_id, 'mail_open_' . $campaign_id, $subscriber_id);

                $total_mail_open    = get_post_meta($campaign_id, 'total_mail_open', true);
                update_post_meta($campaign_id, 'total_mail_open', (int)$total_mail_open + 1);
            }

            die();
        }
    }

    /**
     * check_subscriber
     *
     * @since 1.0.0
     * @param WP_REST_Request $post_data Post data.
     */
    public function link_click_track($post_data)
    {

        $campaign_id = isset($post_data['campaign_id']) ? $post_data['campaign_id'] : '';
        $subscriber_id = isset($post_data['subscriber_id']) ? $post_data['subscriber_id'] : '';
        $redirect = isset($post_data['redirect']) ? $post_data['redirect'] : '';

        mail_picker_update_post_meta($subscriber_id, 'link_click_' . $campaign_id, $subscriber_id);

        $total_link_click    = get_post_meta($campaign_id, 'total_link_click', true);
        update_post_meta($campaign_id, 'total_link_click', (int)$total_link_click + 1);

        wp_safe_redirect($redirect);
        exit;

        //die(wp_json_encode($response));
    }

    /**
     * check_subscriber
     *
     * @since 1.0.0
     * @param WP_REST_Request $post_data Post data.
     */
    public function confirm_subscribe($post_data)
    {

        $subscriber_form_id = isset($post_data['subscriber_form_id']) ? $post_data['subscriber_form_id'] : '';
        $subscriber_id = isset($post_data['subscriber_id']) ? $post_data['subscriber_id'] : '';
        $redirect = isset($post_data['redirect']) ? $post_data['redirect'] : '';
        $subscriber_status_after_confirm    = get_post_meta($subscriber_form_id, 'subscriber_status_after_confirm', true);
        $send_welcome_mail    = get_post_meta($subscriber_form_id, 'send_welcome_mail', true);
        $welcome_mail_template    = get_post_meta($subscriber_form_id, 'welcome_mail_template', true);


        update_post_meta($subscriber_id, 'subscriber_status', $subscriber_status_after_confirm);
        $gmt_offset = get_option('gmt_offset');

        $current_datetime = date('Y-m-d H:i:s', strtotime('+' . $gmt_offset . ' hour'));

        update_post_meta($subscriber_id, 'last_active', $current_datetime);


        mail_picker_update_post_meta($subscriber_id, 'confirm_subscribe_' . $subscriber_form_id, $subscriber_id);
        update_post_meta($subscriber_id, 'is_confirm', 'yes');

        $total_confirm    = get_post_meta($subscriber_form_id, 'total_confirm', true);
        update_post_meta($subscriber_form_id, 'total_confirm', (int)$total_confirm + 1);


        if ($welcome_mail_template == 'yes') {



            $class_mail_picker_emails = new class_mail_picker_emails();


            $mail_subject     = get_post_meta($subscriber_form_id, 'confirmation_mail_subject', true);
            $from_email     = get_post_meta($subscriber_form_id, 'confirmation_mail_from_email', true);
            $from_name     = get_post_meta($subscriber_form_id, 'confirmation_mail_from_name', true);
            $reply_to_email     = get_post_meta($subscriber_form_id, 'confirmation_mail_reply_to_email', true);
            $reply_to_name     = get_post_meta($subscriber_form_id, 'confirmation_mail_reply_to_name', true);



            $admin_email = get_option('admin_email');
            $site_name = get_bloginfo('name');
            $site_description = get_bloginfo('description');
            $site_url = get_bloginfo('url');
            $site_logo_url = get_bloginfo('url');

            $email    = get_post_meta($subscriber_id, 'email', true);
            $subscriber_phone     = get_post_meta($subscriber_id, 'subscriber_phone', true);
            $subscriber_country_code     = get_post_meta($subscriber_id, 'subscriber_country_code', true);
            $subscriber_country = '';
            $first_name     = get_post_meta($subscriber_id, 'first_name', true);
            $last_name     = get_post_meta($subscriber_id, 'last_name', true);
            $subscriber_name = $first_name . ' ' . $last_name;
            $subscriber_avatar = get_avatar($email, '50');
            $subscriber_rating     = get_post_meta($subscriber_id, 'subscriber_rating', true);
            $subscriber_status     = get_post_meta($subscriber_id, 'subscriber_status', true);

            $mail_template_data = get_post($welcome_mail_template);

            $mail_template_content    = $mail_template_data->post_content;

            $mail_template_content = do_shortcode($mail_template_content);
            $mail_template_content = wpautop($mail_template_content);


            $vars = array(
                '{site_name}' => $site_name,
                '{site_description}' => $site_description,
                '{site_url}' => $site_url,
                '{site_logo_url}' => $site_logo_url,

                '{email}' => $email,
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


            $email_data['mail_to'] =  $email;
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


    public function create_subscriber($args)
    {

        $response = array();

        $formFieldData = isset($args['formFieldData']) ? ($args['formFieldData']) : array();

        $email = isset($args['email']) ? sanitize_email($args['email']) : '';
        $subscriber_status = isset($args['subscriber_status']) ? sanitize_text_field($args['subscriber_status']) : '';

        $subscriber_list = !empty($args['subscriber_list']) ? sanitize_text_field($args['subscriber_list']) : '';


        $first_name = isset($args['first_name']) ? $args['first_name'] : '';
        $last_name = isset($args['last_name']) ? $args['last_name'] : '';


        if (empty($subscriber_list)) {

            $response['message'] = __('Subscriber list empty.', 'mail-picker');
            $response['status'] = 'fail';

            do_action('mail_picker_subscriber_create_failed', $response);

            return $response;
        }



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

            // Update the post into the database
            wp_update_post($post_data);


            // foreach ($formFieldData as $formFieldIndex => $formFieldValue) {

            //     update_post_meta($post_id, $formFieldIndex, $formFieldValue);
            // }

            update_post_meta($post_id, 'subscriber_status', $subscriber_status);
            update_post_meta($post_id, 'email', $email);
            update_post_meta($post_id, 'first_name', $first_name);
            update_post_meta($post_id, 'last_name', $last_name);

            $subscriber_list = explode(',', $subscriber_list);

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

$BlockPostGrid = new MailPickerRest();
