<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 

add_action('subscriber_source_options_registered_users', 'subscriber_source_options_registered_users', 10);


function subscriber_source_options_registered_users($source){

    $post_id =  $source['post_id'];

    $registered_users = get_post_meta($post_id, 'registered_users', true);

    $registered_users = !empty($registered_users) ? $registered_users : array();

    $user_role = isset($registered_users['user_role']) ? $registered_users['user_role'] : '';
    $user_email_search = isset($registered_users['user_email_search']) ? $registered_users['user_email_search'] : '';




    $settings_tabs_field = new settings_tabs_field();


    $wp_roles = new WP_Roles();

    //var_dump($wp_roles);
    $roles = $wp_roles->get_names();

    // Below code will print the all list of roles.
    //echo '<pre>'.var_export($registered_users, true).'</pre>';



    $args = array(
        'id'		=> 'user_role',
        'parent'		=> 'registered_users',
        'title'		=> __('User role','mail-picker'),
        'details'	=> __('Choose user role.','mail-picker'),
        'type'		=> 'select',
        'value'		=> $user_role,
        'default'		=> array(),
        'args'		=> $roles,
    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'user_email_search',
        'parent'		=> 'registered_users',
        'title'		=> __('User email search','mail-picker'),
        'details'	=> __('You can following string match, use comma to separate. <ul><li><b>^useremail</b> : String start with <b><i>useremail</i></b></li><li><b>useremail$</b> : String end by <b><i>useremail</i></b></li><li><b>useremail</b> : String contain <b><i>useremail</i></b></b></li></ul>','mail-picker'),
        'type'		=> 'text',
        'value'		=> $user_email_search,
        'default'		=> '',
    );

    $settings_tabs_field->generate_field($args);





}


add_action('subscriber_source_meta_boxes_save', 'mail_picker_subscriber_source_meta_boxes_save_registered_users', 10);


function mail_picker_subscriber_source_meta_boxes_save_registered_users($post_id){


    $registered_users = mail_picker_recursive_sanitize_arr($_POST['registered_users']);

    $registered_users = isset($_POST['registered_users']) ? $registered_users : array();

    update_post_meta( $post_id, 'registered_users', $registered_users );

}





add_action('mail_picker_subscriber_source_options_comments', 'mail_picker_subscriber_source_options_comments', 10);


function mail_picker_subscriber_source_options_comments($source){

    $post_id =  $source['post_id'];

    $comments = get_post_meta($post_id, 'comments', true);

    $comments = !empty($comments) ? $comments : array();

    $comment_status = isset($comments['comment_status']) ? $comments['comment_status'] : '';

    $settings_tabs_field = new settings_tabs_field();



    //var_dump($comments);

    $args = array(
        'id'		=> 'comment_status',
        'parent'		=> 'comments',
        'title'		=> __('Comment status','mail-picker'),
        'details'	=> __('Choose comment status.','mail-picker'),
        'type'		=> 'select',
        'value'		=> $comment_status,
        'default'		=> array(),
        'args'		=> array(''=> __('All status','mail-picker'), '1'=>__('Approved','mail-picker'), '0'=>__('Pending','mail-picker') ,
            'spam'=>__('Spam')),
    );

    $settings_tabs_field->generate_field($args);



}





add_action('subscriber_source_meta_boxes_save', 'mail_picker_subscriber_source_meta_boxes_save_comments', 10);


function mail_picker_subscriber_source_meta_boxes_save_comments($post_id){


    $comments = mail_picker_recursive_sanitize_arr($_POST['comments']);

    $comments = isset($_POST['comments']) ? $comments : array();

    update_post_meta( $post_id, 'comments', $comments );

}





add_action('subscriber_source_options_woo_orders', 'subscriber_source_options_woo_orders', 10);


function subscriber_source_options_woo_orders($source){

    $post_id =  $source['post_id'];

    $woo_orders = get_post_meta($post_id, 'woo_orders', true);
    $woo_orders = !empty($woo_orders) ? $woo_orders : array();
    $order_status = isset($woo_orders['order_status']) ? $woo_orders['order_status'] : array();
    $product_ids = isset($woo_orders['product_ids']) ? $woo_orders['product_ids'] : '';

    $settings_tabs_field = new settings_tabs_field();


    $order_statuses = wc_get_order_statuses();

    $order_statuses[] = __('All status','mail-picker');

    $args = array(
        'id'		=> 'order_status',
        'parent'		=> 'woo_orders',
        'title'		=> __('Order status','mail-picker'),
        'details'	=> __('Choose order status.','mail-picker'),
        'type'		=> 'select',
        'value'		=> $order_status,
        'default'		=> array(),
        'args'		=> $order_statuses,
    );

    $settings_tabs_field->generate_field($args);


    $args = array(
        'id'		=> 'product_ids',
        'parent'		=> 'woo_orders',
        'title'		=> __('Product ids','mail-picker'),
        'details'	=> __('Write product ids, use comma to separate.','mail-picker'),
        'type'		=> 'text',
        'value'		=> $product_ids,
        'default'		=> '',
        'placeholder'		=> '110,112',
    );

    $settings_tabs_field->generate_field($args);


}






add_action('subscriber_source_meta_boxes_save', 'subscriber_source_meta_boxes_save_woo_orders', 10);


function subscriber_source_meta_boxes_save_woo_orders($post_id){


    $woo_orders = mail_picker_recursive_sanitize_arr($_POST['woo_orders']);

    $woo_orders = isset($_POST['woo_orders']) ? $woo_orders : array();

    update_post_meta( $post_id, 'woo_orders', $woo_orders );



}


add_action('subscriber_source_options_ninjaform_sub', 'subscriber_source_options_ninjaform_sub', 10);


function subscriber_source_options_ninjaform_sub($source){

    $post_id =  $source['post_id'];

    $ninjaform_sub = get_post_meta($post_id, 'ninjaform_sub', true);
    $ninjaform_sub = !empty($ninjaform_sub) ? $ninjaform_sub : array();
    $email_label = isset($ninjaform_sub['email_label']) ? $ninjaform_sub['email_label'] : '';

    $settings_tabs_field = new settings_tabs_field();


    $args = array(
        'id'		=> 'email_label',
        'parent'		=> 'ninjaform_sub',
        'title'		=> __('Email field label','mail-picker'),
        'details'	=> __('Write ninja forms email field label.','mail-picker'),
        'type'		=> 'text',
        'value'		=> $email_label,
        'default'		=> '',
        'placeholder'		=> 'Email',

    );

    $settings_tabs_field->generate_field($args);



}




add_action('subscriber_source_meta_boxes_save', 'subscriber_source_meta_boxes_save_ninjaform_sub', 10);


function subscriber_source_meta_boxes_save_ninjaform_sub($post_id){


    $ninjaform_sub = mail_picker_recursive_sanitize_arr($_POST['ninjaform_sub']);

    $ninjaform_sub = isset($_POST['ninjaform_sub']) ? $ninjaform_sub : array();

    update_post_meta( $post_id, 'ninjaform_sub', $ninjaform_sub );

}





add_action('subscriber_source_options_newsletter_subscribers', 'subscriber_source_options_newsletter_subscribers', 10);


function subscriber_source_options_newsletter_subscribers($source){

    $post_id =  $source['post_id'];

    $newsletter_subscribers = get_post_meta($post_id, 'newsletter_subscribers', true);
    $newsletter_subscribers = !empty($newsletter_subscribers) ? $newsletter_subscribers : array();
    $status = isset($newsletter_subscribers['status']) ? $newsletter_subscribers['status'] : '';

    $settings_tabs_field = new settings_tabs_field();




    $args = array(
        'id'		=> 'status',
        'parent'		=> 'comments',
        'title'		=> __('Subscriber status','mail-picker'),
        'details'	=> __('Select subscriber status.','mail-picker'),
        'type'		=> 'select',
        'value'		=> $status,
        'default'		=> array(),
        'args'		=> array(''=> __('All status','mail-picker'), 'C'=>__('Confirmed','mail-picker'), 'S'=>__('Not confirmed','mail-picker'), 'U'=>__('Unsubscribed','mail-picker')),
    );

    $settings_tabs_field->generate_field($args);


}




add_action('subscriber_source_meta_boxes_save', 'mail_picker_subscriber_source_meta_boxes_save_newsletter_subscribers', 10);


function mail_picker_subscriber_source_meta_boxes_save_newsletter_subscribers($post_id){


    $newsletter_subscribers = mail_picker_recursive_sanitize_arr($_POST['newsletter_subscribers']);

    $newsletter_subscribers = isset($_POST['newsletter_subscribers']) ? $newsletter_subscribers : array();

    update_post_meta( $post_id, 'newsletter_subscribers', $newsletter_subscribers );

}



