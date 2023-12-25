<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mail_picker_post_metabox{
	
	public function __construct(){

		add_action('add_meta_boxes', array($this, 'subscriber_meta_boxes'));
		add_action('save_post', array($this, 'subscriber_meta_boxes_save'));

        add_action('add_meta_boxes', array($this, 'subscriber_form_meta_boxes'));
        add_action('save_post', array($this, 'subscriber_form_meta_boxes_save'));

        add_action('add_meta_boxes', array($this, 'mail_template_meta_boxes'));
        //add_action('save_post', array($this, 'mail_template_meta_boxes_save'));

        add_action('add_meta_boxes', array($this, 'mail_campaign_meta_boxes'));
        add_action('save_post', array($this, 'mail_campaign_meta_boxes_save'));

        add_action('add_meta_boxes', array($this, 'subscriber_source_meta_boxes'));
        add_action('save_post', array($this, 'subscriber_source_meta_boxes_save'));




    }
	
	public function subscriber_meta_boxes($post_type) {
		
		$post_types = array('subscriber');
		if (in_array($post_type, $post_types)) {


            add_meta_box('subscriber_metabox', __( 'Subscriber Data', 'subscriber-manager' ), array($this, 'subscriber_metabox'), $post_type, 'normal', 'high');
		}

	}



    public function subscriber_form_meta_boxes($post_type) {

        $post_types = array('subscriber_form');
        if (in_array($post_type, $post_types)) {

            add_meta_box('subscriber_form_metabox', __( 'Subscriber Form Data', 'subscriber-manager' ), array($this, 'subscriber_form_metabox'), $post_type, 'normal', 'high');
        }

    }


    public function mail_template_meta_boxes($post_type) {

        $post_types = array('mail_template');
        if (in_array($post_type, $post_types)) {


            add_meta_box('mail_template_metabox_side', __( 'Templates tags', 'subscriber-manager' ), array($this, 'mail_template_metabox_side'), $post_type, 'side', 'low');
        }

    }

    public function mail_campaign_meta_boxes($post_type) {

        $post_types = array('mail_campaign');
        if (in_array($post_type, $post_types)) {

            add_meta_box('mail_campaign_stats_metabox', __( 'Mail Campaign Stats', 'subscriber-manager' ), array($this, 'mail_campaign_stats_metabox'), $post_type, 'normal', 'high');

            add_meta_box('mail_campaign_metabox', __( 'Mail Campaign Data', 'subscriber-manager' ), array($this, 'mail_campaign_metabox'), $post_type, 'normal', 'high');


        }

    }



    public function subscriber_source_meta_boxes($post_type) {

        $post_types = array('subscriber_source');
        if (in_array($post_type, $post_types)) {

            add_meta_box('subscriber_source_metabox', __( 'Mail Campaign Stats', 'subscriber-manager' ), array($this, 'subscriber_source_metabox'), $post_type, 'normal', 'high');


        }

    }





	
	public function subscriber_metabox($post) {
 
        wp_nonce_field('subscriber_nonce_check', 'subscriber_nonce_check_value');
        $post_id = $post->ID;

		
        $subscriber_email 	= get_post_meta( $post_id, 'subscriber_email', true);
        $subscriber_phone 	= get_post_meta( $post_id, 'subscriber_phone', true);
        $subscriber_country_code 	= get_post_meta( $post_id, 'subscriber_country_code', true);

        $first_name 	= get_post_meta( $post_id, 'first_name', true);
        $last_name 	= get_post_meta( $post_id, 'last_name', true);
        $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
        $subscriber_rating 	= get_post_meta( $post_id, 'subscriber_rating', true);

		//var_dump($domains_list);
        $settings_tabs_field = new settings_tabs_field();


        ?>
        <div class="settings-tabs">

            <?php

            $args = array(
                'id'		=> 'subscriber_email',
                //'parent'		=> '',
                'title'		=> __('Subscriber email','mail-picker'),
                'details'	=> __('Write subscriber email address.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $subscriber_email,
                'default'		=> '',
                'placeholder'		=> 'hello@dummy.com',

            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'subscriber_phone',
                //'parent'		=> '',
                'title'		=> __('Subscriber phone','mail-picker'),
                'details'	=> __('Write subscriber phone number.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $subscriber_phone,
                'default'		=> '',
                'placeholder'		=> '',

            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'subscriber_country_code',
                //'parent'		=> '',
                'title'		=> __('Subscriber country','mail-picker'),
                'details'	=> __('Write subscriber country code.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $subscriber_country_code,
                'default'		=> '',
                'placeholder'		=> 'USA',

            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'		=> 'first_name',
                //'parent'		=> '',
                'title'		=> __('First name','mail-picker'),
                'details'	=> __('Write subscriber first name.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $first_name,
                'default'		=> '',
                'placeholder'		=> 'First name',

            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'last_name',
                //'parent'		=> '',
                'title'		=> __('Last name','mail-picker'),
                'details'	=> __('Write subscriber last name.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $last_name,
                'default'		=> '',
                'placeholder'		=> 'Last name',

            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'		=> 'subscriber_status',
                //'parent'		=> '',
                'title'		=> __('Subscriber status','mail-picker'),
                'details'	=> __('Select subscriber status.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $subscriber_status,
                'default'		=> '',
                'args'		=> array('pending' => 'Pending','active' => 'Active', 'blocked' => 'Blocked','unsubscribed' => 'Unsubscribed',),

            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'subscriber_rating',
                //'parent'		=> '',
                'title'		=> __('Subscriber rating','mail-picker'),
                'details'	=> __('Select subscriber rating. 5 = Best, 1 = Poor','mail-picker'),
                'type'		=> 'select',
                'value'		=> $subscriber_rating,
                'default'		=> '',
                'args'		=> array('1' => 'Poor','2' => 'Nice', '3' => 'Good','4' => 'Better','5' => 'Best',),

            );

            $settings_tabs_field->generate_field($args);


            ?>


        </div>
        
        
        <?php

   	}



    public function subscriber_meta_boxes_save($post_id){

        if (!isset($_POST['subscriber_nonce_check_value'])) return $post_id;
        $nonce = isset($_POST['subscriber_nonce_check_value']) ? sanitize_text_field($_POST['subscriber_nonce_check_value']) : '';
        if (!wp_verify_nonce($nonce, 'subscriber_nonce_check')) return $post_id;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) return $post_id;
        } else {
            if (!current_user_can('edit_post', $post_id)) return $post_id;
        }


        $subscriber_email = isset($_POST['subscriber_email']) ? sanitize_email( $_POST['subscriber_email'] ) : '';
        update_post_meta( $post_id, 'subscriber_email', $subscriber_email );

        $first_name = isset($_POST['first_name']) ? sanitize_text_field( $_POST['first_name'] ) : '';
        update_post_meta( $post_id, 'first_name', $first_name );


        $last_name = isset($_POST['last_name']) ? sanitize_text_field( $_POST['last_name'] ) : '';
        update_post_meta( $post_id, 'last_name', $last_name );

        $subscriber_status = isset( $_POST['subscriber_status']) ? sanitize_text_field( $_POST['subscriber_status'] ) : '';
        update_post_meta( $post_id, 'subscriber_status', $subscriber_status );

        $subscriber_rating = isset($_POST['subscriber_rating']) ? sanitize_text_field( $_POST['subscriber_rating'] ) : '';
        update_post_meta( $post_id, 'subscriber_rating', $subscriber_rating );
    }





    public function subscriber_form_metabox($post) {

        wp_nonce_field('subscriber_form_nonce_check', 'subscriber_form_nonce_check_value');

        $post_id = $post->ID;
        $form_id = $post->ID;

        $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
        $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);

        $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
        $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
        $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
        $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
        $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

        $welcome_mail_subject 	= get_post_meta( $post_id, 'welcome_mail_subject', true);
        $welcome_mail_from_email 	= get_post_meta( $post_id, 'welcome_mail_from_email', true);
        $welcome_mail_from_name 	= get_post_meta( $post_id, 'welcome_mail_from_name', true);
        $welcome_mail_reply_to_email 	= get_post_meta( $post_id, 'welcome_mail_reply_to_email', true);
        $welcome_mail_reply_to_name 	= get_post_meta( $post_id, 'welcome_mail_reply_to_name', true);



        $enable_recaptcha	= get_post_meta( $post_id, 'enable_recaptcha', true);
        $success_message 	= get_post_meta( $post_id, 'success_message', true);
        $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
        $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);
        $send_welcome_mail 	= get_post_meta( $post_id, 'send_welcome_mail', true);
        $welcome_mail_template 	= get_post_meta( $post_id, 'welcome_mail_template', true);


        $error_message 	= get_post_meta( $post_id, 'error_message', true);

        $already_exist_message 	= get_post_meta( $post_id, 'already_exist_message', true);

        $subscriber_list 	= get_post_meta( $post_id, 'subscriber_list', true);
        $after_submit_action 	= get_post_meta( $post_id, 'after_submit_action', true);
        $redirect_link 	= get_post_meta( $post_id, 'redirect_link', true);


        $layout_elements_data = get_post_meta($post_id,'layout_elements_data', true);


        $settings_tabs_field = new settings_tabs_field();




        //var_dump($subscriber_list);



        ?>
        <div class="settings-tabs">

            <div class="setting-field">
                <p class="field-lable"><?php echo __('Shortcode', 'subscriber-manager'); ?></p>
                <div class="field-input">
                    <input type="text" onclick="this.select()" value="[mail_picker_form id='<?php echo esc_attr($post_id);  ?>']">
                </div>

            </div>

            <?php


            $args = array(
                'id'		=> 'send_confirmation_mail',
                //'parent'		=> '',
                'title'		=> __('Send confirmation mail?','mail-picker'),
                'details'	=> __('Select to send confirmation mail after form submit.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $send_confirmation_mail,
                'default'		=> 'yes',
                'args'		=> array('yes'=>'Yes', 'no'=>'No'),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'confirmation_mail_subject',
                //'parent'		=> '',
                'title'		=> __('confirmation mail subject','mail-picker'),
                'details'	=> __('Write mail subject for confirmation.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $confirmation_mail_subject,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'confirmation_mail_from_email',
                //'parent'		=> '',
                'title'		=> __('confirmation mail from email','mail-picker'),
                'details'	=> __('Write mail from email for confirmation.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $confirmation_mail_from_email,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'confirmation_mail_from_name',
                //'parent'		=> '',
                'title'		=> __('confirmation mail from name','mail-picker'),
                'details'	=> __('Write mail from name for confirmation.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $confirmation_mail_from_name,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'confirmation_mail_reply_to_email',
                //'parent'		=> '',
                'title'		=> __('confirmation mail reply to email','mail-picker'),
                'details'	=> __('Write mail reply to email for confirmation.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $confirmation_mail_reply_to_email,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'confirmation_mail_reply_to_name',
                //'parent'		=> '',
                'title'		=> __('confirmation mail reply to name','mail-picker'),
                'details'	=> __('Write mail reply to name for confirmation.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $confirmation_mail_reply_to_name,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $posts_args = get_posts(
                array(
                    'numberposts'      => -1,
                    'post_type'        => 'mail_template',

                )
            );

            $mail_template_args = array();

            $mail_template_args[''] = __('Select template');


            foreach ($posts_args as $post_loop){

                $post_id = $post_loop->ID;
                $post_title = $post_loop->post_title;

                $mail_template_args[$post_id] = $post_title;

            }

            $args = array(
                'id'		=> 'confirmation_mail_template',
                //'parent'		=> '',
                'title'		=> __('Confirmation mail template','mail-picker'),
                'details'	=> __('Select confirmation mail template.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $confirmation_mail_template,
                'default'		=> array(),
                'args'		=> $mail_template_args,
            );

            $settings_tabs_field->generate_field($args);




            $args = array(
                'id'		=> 'subscriber_status',
                //'parent'		=> '',
                'title'		=> __(' Subscriber default status','mail-picker'),
                'details'	=> __('Select subscriber status before confirm.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $subscriber_status,
                'default'		=> 'pending',
                'args'		=> array('pending'=>'Pending', 'active'=>'Active', 'blocked'=>'Blocked', 'unsubscribed'=>'Unsubscribed'),
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'		=> 'subscriber_status_after_confirm',
                //'parent'		=> '',
                'title'		=> __('Subscriber status after confirm','mail-picker'),
                'details'	=> __('Select subscriber status after confirm.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $subscriber_status_after_confirm,
                'default'		=> 'pending',
                'args'		=> array('pending'=>'Pending', 'active'=>'Active', 'blocked'=>'Blocked', 'canceled'=>'Canceled'),
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'		=> 'send_welcome_mail',
                //'parent'		=> '',
                'title'		=> __('Send welcome mail?','mail-picker'),
                'details'	=> __('Select subscriber status before submit.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $send_welcome_mail,
                'default'		=> 'yes',
                'args'		=> array('yes'=>'Yes', 'no'=>'No'),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'welcome_mail_template',
                //'parent'		=> '',
                'title'		=> __('Welcome mail template','mail-picker'),
                'details'	=> __('Select welcome mail template.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $welcome_mail_template,
                'default'		=> array(),
                'args'		=> $mail_template_args,
            );

            $settings_tabs_field->generate_field($args);




            $args = array(
                'id'		=> 'welcome_mail_subject',
                //'parent'		=> '',
                'title'		=> __('Welcome mail subject','mail-picker'),
                'details'	=> __('Write mail subject for welcome.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $welcome_mail_subject,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'welcome_mail_from_email',
                //'parent'		=> '',
                'title'		=> __('welcome mail from email','mail-picker'),
                'details'	=> __('Write mail from email for welcome.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $welcome_mail_from_email,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'welcome_mail_from_name',
                //'parent'		=> '',
                'title'		=> __('welcome mail from name','mail-picker'),
                'details'	=> __('Write mail from name for welcome.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $welcome_mail_from_name,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'welcome_mail_reply_to_email',
                //'parent'		=> '',
                'title'		=> __('welcome mail reply to email','mail-picker'),
                'details'	=> __('Write mail reply to email for welcome.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $welcome_mail_reply_to_email,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'welcome_mail_reply_to_name',
                //'parent'		=> '',
                'title'		=> __('welcome mail reply to name','mail-picker'),
                'details'	=> __('Write mail reply to name for welcome.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $welcome_mail_reply_to_name,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);







            $args = array(
                'id'		=> 'after_submit_action',
                //'parent'		=> '',
                'title'		=> __('After submit action','mail-picker'),
                'details'	=> __('Select action after form submit.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $after_submit_action,
                'default'		=> 'display_message',
                'args'		=> array('display_message'=>'Display Message', 'redirect_link'=>'Redirect to link'),
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'		=> 'redirect_link',
                //'parent'		=> '',
                'title'		=> __('Redirect link','mail-picker'),
                'details'	=> __('Put redirect link.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $redirect_link,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);






            $args = array(
                'id'		=> 'success_message',
                //'parent'		=> '',
                'title'		=> __('Thank you message','mail-picker'),
                'details'	=> __('Custom success message after form submit.','mail-picker'),
                'type'		=> 'textarea',
                'value'		=> $success_message,
                'default'		=> '',
                'placeholder'		=> 'Thank you for subscribe',

            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'error_message',
                //'parent'		=> '',
                'title'		=> __('Error message','mail-picker'),
                'details'	=> __('Custom error message after form submit.','mail-picker'),
                'type'		=> 'textarea',
                'value'		=> $error_message,
                'default'		=> '',
                'placeholder'		=> 'There is an error.',

            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'already_exist_message',
                //'parent'		=> '',
                'title'		=> __('Subscriber exist message','mail-picker'),
                'details'	=> __('Custom error message for subscriber already exist.','mail-picker'),
                'type'		=> 'textarea',
                'value'		=> $already_exist_message,
                'default'		=> '',
                'placeholder'		=> __('Subscriber already exist.','mail-picker'),

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
                //'parent'		=> '',
                'title'		=> __('Subscriber list','mail-picker'),
                'details'	=> __('Select subscriber list.','mail-picker'),
                'type'		=> 'checkbox',
                'value'		=> $subscriber_list,
                'default'		=> array(),
                'args'		=> $subscriber_list_args,
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'enable_recaptcha',
                //'parent'		=> '',
                'title'		=> __('Enable recaptcha?','mail-picker'),
                'details'	=> __('Select to enable recaptcha under form.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $enable_recaptcha,
                'default'		=> 'yes',
                'args'		=> array('yes'=>'Yes', 'no'=>'No'),
            );

            $settings_tabs_field->generate_field($args);

            ?>





        <div class="section">
            <div class="section-title"><?php echo __('Form Builder', 'mail-picker'); ?></div>
            <p class="description section-description"><?php echo __('Customize form settings.', 'mail-picker'); ?></p>
            <div class="setting-field ">

                <?php



                $elements_group['general'] = array(
                    'group_title'=>'General',
                    'items'=>array(
                        'subscriber_email'=>array('name' =>__('Subscriber email','mail-picker')),
                        'subscriber_phone'=>array('name' =>__('Subscriber phone','mail-picker')),
                        'subscriber_country'=>array('name' =>__('Subscriber country','mail-picker')),
                        'first_name'=>array('name' =>__('First name','mail-picker')),
                        'last_name'=>array('name' =>__('Last name','mail-picker')),
                        //'subscriber_list'=>array('name' =>__('Subscriber lists','mail-picker')),

                    ),
                );

                $elements_group['custom'] = array(
                    'group_title'=>'Custom',
                    'items'=>array(
                        'wrapper_start'=>array('name' =>__('Wrapper start','mail-picker')),
                        'wrapper_end'=>array('name' =>__('Wrapper end','mail-picker')),
                        'input_text'=>array('name' =>__('Input Text','mail-picker')),
                        'input_email'=>array('name' =>__('Input Email','mail-picker')),
                        'input_number'=>array('name' =>__('Input Number','mail-picker')),
                        'input_select'=>array('name' =>__('Input Select','mail-picker')),
                        'input_checkbox'=>array('name' =>__('Input Checkbox','mail-picker')),
                        'input_radio'=>array('name' =>__('Input Radio','mail-picker')),

                        //'collapsible_icon'=>array('name' =>__('Collapsible icon','mail-picker')),

                    ),
                );




                $elements_group = apply_filters('mail_picker_layout_elements', $elements_group);


                $layout_elements_option = array();

                if(!empty($elements_group))
                    foreach ($elements_group as $group_index => $element_group):


                        $group_items = isset($element_group['items']) ? $element_group['items'] : array();

                        foreach ($group_items as $elementIndex => $element):
                            ob_start();

                            do_action('mail_picker_form_element_option_'.$elementIndex);

                            $layout_elements_option[$elementIndex] = ob_get_clean();
                        endforeach;
                    endforeach;


                ?>

                <script>
                    jQuery(document).ready(function($){
                        layout_elements_option = <?php echo json_encode($layout_elements_option); ?>;

                        $(document).on('click','.layout-tags .element_index',function(){
                            tag_id = $(this).attr('tag_id');
                            input_name = $(this).attr('input_name');
                            id = $.now();

                            console.log(id);

                            tag_options_html = layout_elements_option[tag_id];
                            var res = tag_options_html.replace(/{input_name}/g, input_name+'['+id+']');

                            $('.layout-elements').append(res);

                        })
                    })
                </script>

                <div class="layout-builder">
                    <div class="layout-tags expandable">



                        <?php

                        if(!empty($elements_group))
                            foreach ($elements_group as $group_index => $element_group):

                                $group_title = isset($element_group['group_title']) ? $element_group['group_title'] : '';
                                $group_items = isset($element_group['items']) ? $element_group['items'] : array();
                                //$group_items = apply_filters('post_grid_layout_group_'.$group_index, $group_items);

                                if(empty($group_items)) continue;
                                ?>
                                <div class="item">
                                    <div class="element-title header ">
                                        <span class="expand"><i class="fas fa-expand"></i><i class="fas fa-compress"></i></span>
                                        <span class="expand"><?php echo $group_title; ?></span>
                                    </div>
                                    <div class="element-options options active">
                                        <?php
                                        foreach ($group_items as $elementIndex => $element):
                                            $element_name = isset($element['name']) ? $element['name'] : '';
                                            ?>
                                            <span class="element_index" input_name="<?php echo 'layout_elements_data'; ?>"  tag_id="<?php echo esc_attr($elementIndex); ?>"><?php echo $element_name; ?></span>
                                        <?php
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                        ?>
                    </div>

                    <div class="layout-elements expandable sortable">

                        <?php

                        if(!empty($layout_elements_data)):
                            foreach ($layout_elements_data as $index => $item_data){
                                foreach ($item_data as $elementIndex => $element_data){

                                    //echo '<pre>'.var_export($elementIndex, true).'</pre>';

                                    $args = array('input_name'=> 'layout_elements_data['.$index.']', 'element_data'=> $element_data, 'index'=>$index);
                                    do_action('mail_picker_form_element_option_'.$elementIndex, $args);
                                }


                            }
                        else:
                            ?>
                            <div class="empty-element">
                                <?php echo sprintf(__('%s Click to add tags.','mail-picker'), '<i class="far fa-hand-point-up"></i>') ?>
                            </div>
                        <?php
                        endif;

                        ?>

                    </div>


                </div>

                <style type="text/css">
                    .layout-builder{
                        clear: both;
                    }


                    .layout-builder .layout-elements .item{

                    }
                    .layout-tags{
                        margin-bottom: 20px;
                        position: sticky;
                        top: 32px;
                        z-index: 999;
                        background: #fff;
                        padding: 5px 5px;
                        display: inline-block;
                        width: 360px;
                        float: left;
                    }
                    .layout-tags .element_index{
                        background: #fff;
                        padding: 3px 7px;
                        display: inline-block;
                        margin: 2px 2px;
                        border-radius: 3px;
                        border: 1px solid #616161;
                        cursor: pointer;
                        font-size: 13px;
                    }

                    .layout-tags .element_index:hover{
                        background: #e0e0e0;

                    }

                    .layout-elements{
                        margin-left: 390px;
                    }
                    @media (max-width: 1550px){
                        .layout-elements {
                            margin-left: 0px;
                        }
                        .layout-tags {
                            display: block;
                            width: 100%;
                            float: none;
                        }
                    }


                </style>

            </div>
            <div class="clear"></div>

            <?php

            //var_dump($layout_elements_data);


            ob_start();



            $layout_id = get_the_id();
            $args['layout_id'] = $layout_id;

            ?>
            <div class="layout-preview">

                <div class="elements-wrapper layout-<?php echo $layout_id; ?>">
                    <?php
                    if(!empty($layout_elements_data))
                        foreach ($layout_elements_data as $elementGroupIndex => $elementGroupData){
                            foreach ($elementGroupData as $elementIndex => $elementData){

                                $elementData['index'] = $elementGroupIndex;

                                //var_dump($elementGroupIndex);

                                do_action('mail_picker_form_element_'.$elementIndex, $elementData);
                            }
                        }

                    //echo '<pre>'.var_export($args, true).'</pre>';

                    ?>
                </div>







            </div>

            <?php

            if(!empty($layout_elements_data))
                foreach ($layout_elements_data as $elementGroupIndex => $elementGroupData){
                    foreach ($elementGroupData as $elementIndex => $elementData){



                        $fieldArgs = array('element'=> $elementData, 'layout_id'=> $form_id,'index'=> $elementGroupIndex);

                        //echo $elementIndex;
                        do_action('mail_picker_form_element_css_'.$elementIndex, $fieldArgs);
                    }
                }

            $custom_scripts = get_post_meta($layout_id,'custom_scripts', true);
            $custom_css = isset($custom_scripts['custom_css']) ? $custom_scripts['custom_css'] : '';

            ?>
            <style type="text/css">
                .layout-preview{
                    background: url(<?php echo mail_picker_plugin_url; ?>assets/admin/css/images/tile.png);
                    padding: 20px;
                }
                .layout-preview .elements-wrapper{
                    width: 400px;
                    overflow: hidden;
                    margin: 0 auto;
                }
                .layout-preview img{
                    width: 100%;
                    height: auto;
                }
                <?php
                echo str_replace('__ID__', 'layout-'.$layout_id, $custom_css);
                ?>
            </style>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'layout_preview',
                //'parent'		=> '',
                'title'		=> __('Layout preview','mail-picker'),
                'details'	=> __('Layout preview, some layout require featured image set on post.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);



            ?>
        </div>

        </div>
        <?php

    }


    public function mail_campaign_metabox($post) {

        wp_nonce_field('mail_campaign_nonce_check', 'mail_campaign_nonce_check_value');

        $post_id = get_the_id();

        $mail_subject 	= get_post_meta( $post_id, 'mail_subject', true);
        $from_name	= get_post_meta( $post_id, 'from_name', true);
        $from_email	= get_post_meta( $post_id, 'from_email', true);
        $reply_to_email	= get_post_meta( $post_id, 'reply_to_email', true);
        $reply_to_name	= get_post_meta( $post_id, 'reply_to_name', true);

        $campaign_status	= get_post_meta( $post_id, 'campaign_status', true);
        $recurrence_interval	= get_post_meta( $post_id, 'recurrence_interval', true);

        $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);
        $mail_template_id	= get_post_meta( $post_id, 'mail_template_id', true);
        $utm_tracking	= get_post_meta( $post_id, 'utm_tracking', true);
        $utm_tracking_param	= get_post_meta( $post_id, 'utm_tracking_param', true);

        $link_tracking	= get_post_meta( $post_id, 'link_tracking', true);
        $mail_open_tracking	= get_post_meta( $post_id, 'mail_open_tracking', true);
        $max_send_limit	= get_post_meta( $post_id, 'max_send_limit', true);




        //var_dump($mail_subject);


        $settings_tabs_field = new settings_tabs_field();


        ?>
        <div class="settings-tabs">

            <div class="setting-field">
                <p class="field-lable"><?php echo __('Mail subject', 'subscriber-manager'); ?></p>
                <div class="field-input">
                    <input id="mail_subject"  placeholder="Write your mail subject" class="mail_subject" type="text" value="<?php echo esc_attr($mail_subject); ?>" name="mail_subject" />
                </div>

            </div>


            <div class="setting-field">
                <p class="field-lable"><?php echo __('From name', 'subscriber-manager'); ?></p>
                <div class="field-input">
                    <input id="subscriber_email"  placeholder="" class="from_name" type="text" value="<?php echo esc_attr($from_name); ?>" name="from_name" />
                </div>

            </div>


            <div class="setting-field">
                <p class="field-lable"><?php echo __('From email', 'subscriber-manager'); ?></p>
                <div class="field-input">
                    <input id="subscriber_email"  placeholder="" class="from_email" type="text" value="<?php echo esc_attr($from_email); ?>" name="from_email" />
                </div>

            </div>


            <div class="setting-field">
                <p class="field-lable"><?php echo __('Reply to email', 'subscriber-manager'); ?></p>
                <div class="field-input">
                    <input id="subscriber_email"  placeholder="" class="reply_to_email" type="text" value="<?php echo esc_attr($reply_to_email); ?>" name="reply_to_email" />
                </div>

            </div>

            <div class="setting-field">
                <p class="field-lable"><?php echo __('Reply to name', 'subscriber-manager'); ?></p>
                <div class="field-input">
                    <input id="reply_to_name"  placeholder="" class="reply_to_name" type="text" value="<?php echo esc_attr($reply_to_name); ?>" name="reply_to_name" />
                </div>

            </div>


            <div class="setting-field">
                <p class="field-lable"><?php echo __('Campaign status', 'subscriber-manager'); ?></p>
                <div class="field-input">
                    <select name="campaign_status" >
                        <option <?php if($campaign_status=='') echo 'selected'; ?>  value="">Select status</option>
                        <option <?php if($campaign_status=='active') echo 'selected'; ?> value="active">Active</option>
                        <option <?php if($campaign_status=='paused') echo 'selected'; ?> value="paused">Paused</option>
                        <option <?php if($campaign_status=='finished') echo 'selected'; ?> value="finished">Finished</option>

                    </select>
                </div>
            </div>

            <?php

            //echo '<pre>'.var_export(wp_get_schedules(), true).'</pre>';

            $schedules = wp_get_schedules();

            foreach ($schedules as $scheduleIndex => $schedule){

                $schedules_args[$scheduleIndex] = $schedule['display'];

            }

            $args = array(
                'id'		=> 'recurrence_interval',
                //'parent'		=> '',
                'title'		=> __('Recurrence interval','mail-picker'),
                'details'	=> __('Set recurrence interval.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $recurrence_interval,
                'default'		=> '',
                'args'		=> $schedules_args,
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
                //'parent'		=> '',
                'title'		=> __('Subscriber list','mail-picker'),
                'details'	=> __('Select subscriber list.','mail-picker'),
                'type'		=> 'checkbox',
                'value'		=> $subscriber_list,
                'default'		=> array(),
                'args'		=> $subscriber_list_args,
            );

            $settings_tabs_field->generate_field($args);



            $posts_args = get_posts(
                array(
                'numberposts'      => -1,
                'post_type'        => 'mail_template',

                )
            );

            $mail_template_args = array();

            $mail_template_args[''] = __('Select template');


            foreach ($posts_args as $post_loop){

                $post_id = $post_loop->ID;
                $post_title = $post_loop->post_title;

                $mail_template_args[$post_id] = $post_title;

            }

            $args = array(
                'id'		=> 'mail_template_id',
                //'parent'		=> '',
                'title'		=> __('Mail template','mail-picker'),
                'details'	=> __('Select mail template.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $mail_template_id,
                'default'		=> array(),
                'args'		=> $mail_template_args,
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'max_send_limit',
                //'parent'		=> '',
                'title'		=> __('Max send limit','mail-picker'),
                'details'	=> __('Maximum email send per execution, do not exceed the limit that allowed by your server, otherwise you may face mail server banned issue.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $max_send_limit,
                'default'		=> 10,
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'mail_open_tracking',
                //'parent'		=> '',
                'title'		=> __('Open tracking','mail-picker'),
                'details'	=> __('enable mail open tracking.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $mail_open_tracking,
                'default'		=> '',
                'args'		=> array('yes'=>'Yes', 'no'=>'No'),
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'link_tracking',
                //'parent'		=> '',
                'title'		=> __('Link click Tracking','mail-picker'),
                'details'	=> __('enable link click tracking.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $link_tracking,
                'default'		=> '',
                'args'		=> array('yes'=>'Yes', 'no'=>'No'),
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'utm_tracking',
                //'parent'		=> '',
                'title'		=> __('UTM tracking','mail-picker'),
                'details'	=> __('enable utm tracking.','mail-picker'),
                'type'		=> 'select',
                'value'		=> $utm_tracking,
                'default'		=> array(),
                'args'		=> array('yes'=>'Yes', 'no'=>'No'),
            );

            $settings_tabs_field->generate_field($args);


            $utm_tracking_param_args = array(
                array(
                    'id'		=> 'utm_source',
                    'parent'		=> 'utm_tracking_param',
                    'title'		=> __('UTM source','mail-picker'),
                    'details'	=> __('Write utm source.','mail-picker'),
                    'type'		=> 'text',
                    'value'		=> isset($utm_tracking_param['utm_source']) ? $utm_tracking_param['utm_source'] : '',
                ),

                array(
                    'id'		=> 'utm_medium',
                    'parent'		=> 'utm_tracking_param',
                    'title'		=> __('UTM medium','mail-picker'),
                    'details'	=> __('Write utm medium.','mail-picker'),
                    'type'		=> 'text',
                    'value'		=> isset($utm_tracking_param['utm_medium']) ? $utm_tracking_param['utm_medium'] : '',
                ),

                array(
                    'id'		=> 'utm_campaign',
                    'parent'		=> 'utm_tracking_param',
                    'title'		=> __('UTM campaign','job-board-manager'),
                    'details'	=> __('Write utm campaign.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> isset($utm_tracking_param['utm_campaign']) ? $utm_tracking_param['utm_campaign'] : '',
                ),

                array(
                    'id'		=> 'utm_content',
                    'parent'		=> 'utm_tracking_param',
                    'title'		=> __('UTM content','job-board-manager'),
                    'details'	=> __('Write utm content.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> isset($utm_tracking_param['utm_content']) ? $utm_tracking_param['utm_content'] : '',
                ),

                array(
                    'id'		=> 'utm_term',
                    'parent'		=> 'utm_tracking_param',
                    'title'		=> __('UTM term','job-board-manager'),
                    'details'	=> __('Write utm term.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> isset($utm_tracking_param['utm_term']) ? $utm_tracking_param['utm_term'] : '',
                ),




            );



            $args = array(
                'id'		=> 'utm_tracking_param',
                //'parent'		=> '',
                'title'		=> __('UTM tracking parameter','job-board-manager'),
                'details'	=> __('Write UTM tracking parameter.','job-board-manager'),
                'type'		=> 'option_group',
                'options'		=> $utm_tracking_param_args,
            );

            $settings_tabs_field->generate_field($args);









            ?>



        </div>


        <?php

    }




    public function mail_campaign_meta_boxes_save($post_id){

        if (!isset($_POST['mail_campaign_nonce_check_value'])) return $post_id;
        $nonce = isset($_POST['mail_campaign_nonce_check_value']) ? sanitize_text_field($_POST['mail_campaign_nonce_check_value']) : '';
        if (!wp_verify_nonce($nonce, 'mail_campaign_nonce_check')) return $post_id;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) return $post_id;
        } else {
            if (!current_user_can('edit_post', $post_id)) return $post_id;
        }


        $mail_subject = isset($_POST['mail_subject']) ? sanitize_text_field( $_POST['mail_subject'] ) : '';
        update_post_meta( $post_id, 'mail_subject', $mail_subject );

        $from_name = isset($_POST['from_name']) ? sanitize_text_field( $_POST['from_name'] ) : '';
        update_post_meta( $post_id, 'from_name', $from_name );

        $from_email = isset($_POST['from_email']) ? sanitize_email( $_POST['from_email'] ) : '';
        update_post_meta( $post_id, 'from_email', $from_email );

        $reply_to_email = isset($_POST['reply_to_email']) ? sanitize_email( $_POST['reply_to_email'] ) : '';
        update_post_meta( $post_id, 'reply_to_email', $reply_to_email );

        $reply_to_name = isset($_POST['reply_to_name']) ? sanitize_text_field( $_POST['reply_to_name'] ) : '';
        update_post_meta( $post_id, 'reply_to_name', $reply_to_name );

        $campaign_status = isset($_POST['campaign_status']) ? sanitize_text_field( $_POST['campaign_status'] ) : '';
        update_post_meta( $post_id, 'campaign_status', $campaign_status );

        $recurrence_interval = isset($_POST['recurrence_interval']) ? sanitize_text_field( $_POST['recurrence_interval'] ) : '';
        update_post_meta( $post_id, 'recurrence_interval', $recurrence_interval );

        $subscriber_list = isset($_POST['subscriber_list']) ? mail_picker_recursive_sanitize_arr(  $_POST['subscriber_list'] ) : array();

        update_post_meta( $post_id, 'subscriber_list', $subscriber_list );

        $mail_template_id = isset($_POST['mail_template_id']) ? sanitize_text_field( $_POST['mail_template_id'] ) : '';
        update_post_meta( $post_id, 'mail_template_id', $mail_template_id );

        $utm_tracking = isset($_POST['utm_tracking']) ? sanitize_text_field( $_POST['utm_tracking'] ) : '';
        update_post_meta( $post_id, 'utm_tracking', $utm_tracking );

        $utm_tracking_param = isset($_POST['utm_tracking_param']) ? mail_picker_recursive_sanitize_arr($_POST['utm_tracking_param'] ) : array();
        update_post_meta( $post_id, 'utm_tracking_param', $utm_tracking_param );

        $link_tracking = isset($_POST['link_tracking']) ? sanitize_text_field( $_POST['link_tracking'] ) : '';
        update_post_meta( $post_id, 'link_tracking', $link_tracking );

        $mail_open_tracking = isset($_POST['mail_open_tracking']) ? sanitize_text_field( $_POST['mail_open_tracking'] ) : '';
        update_post_meta( $post_id, 'mail_open_tracking', $mail_open_tracking );

        $max_send_limit = isset($_POST['max_send_limit']) ? sanitize_text_field( $_POST['max_send_limit'] ) : '';
        update_post_meta( $post_id, 'max_send_limit', $max_send_limit );



    }





    public function mail_campaign_stats_metabox($post) {

        wp_nonce_field('subscriber_nonce_check', 'subscriber_nonce_check_value');


        $post_id = get_the_ID();

        $mail_sent_success = get_post_meta($post_id, 'mail_sent_success', true);
        $mail_sent_success = !empty($mail_sent_success) ? $mail_sent_success : 0;

        $mail_sent_fail = get_post_meta($post_id, 'mail_sent_fail', true);

        $mail_sent_fail = !empty($mail_sent_fail) ? $mail_sent_fail : 0;

        $total_mail_open = get_post_meta($post_id, 'total_mail_open', true);
        $total_mail_open = !empty($total_mail_open) ? $total_mail_open : 0;

        $total_mail_open_rate = !empty($total_mail_open_rate) ? $total_mail_open_rate : 0;

        $total_mail_bounced = !empty($total_mail_bounced) ? $total_mail_bounced : 0;


        $total_link_click = get_post_meta($post_id, 'total_link_click', true);
        $total_link_click = !empty($total_link_click) ? $total_link_click : 0;

        $total_unsubscribe = get_post_meta($post_id, 'unsubscribe', true);
        $total_unsubscribe = !empty($total_unsubscribe) ? $total_unsubscribe : 0;

        $subscriber_resend = isset($_GET['subscriber_resend']) ? sanitize_text_field($_GET['subscriber_resend']) : '';



        ?>

            <div class="mail-campaign-stats">

                <div class="hero-box-list">


                    <div class="hero-box purple">
                        <div class="hero-box-count"><?php echo $mail_sent_success; ?></div>
                        <div class="hero-box-title">Total mail sent successful</div>
                    </div>

                    <div class="hero-box  red">
                        <div class="hero-box-count"><?php echo $mail_sent_fail; ?></div>
                        <div class="hero-box-title">Total mail sent failed</div>
                    </div>


                    <div class="hero-box">
                        <div class="hero-box-count"><?php echo $total_mail_open; ?></div>
                        <div class="hero-box-title">Total mail open</div>
                    </div>
                    <div class="hero-box green">
                        <div class="hero-box-count"><?php echo $total_mail_open_rate; ?></div>
                        <div class="hero-box-title">Open rate</div>
                    </div>
                    <div class="hero-box blue">
                        <div class="hero-box-count"><?php echo $total_mail_bounced; ?></div>
                        <div class="hero-box-title">Total mail bounced</div>
                    </div>



                    <div class="hero-box ash">
                        <div class="hero-box-count"><?php echo $total_link_click; ?></div>
                        <div class="hero-box-title">Total link click</div>
                    </div>

                    <div class="hero-box ash">
                        <div class="hero-box-count"><?php echo $total_unsubscribe; ?></div>
                        <div class="hero-box-title">Total unsubscribe</div>
                    </div>

                </div>

            </div>


        <style type="text/css">
            .mail-campaign-stats{}
            .mail-campaign-stats .hero-box-list{
                text-align: center;
            }
            .mail-campaign-stats .hero-box{
                display: inline-block;
                width: 250px;
                background: #2196F3;
                margin: 10px;
                padding: 30px 10px;
                text-align: center;
                color: #fff;
            }

            .mail-campaign-stats .hero-box.purple{
                background: #673AB7;
            }
            .mail-campaign-stats .hero-box.green{
                background: #009688;
            }

            .mail-campaign-stats .hero-box.blue{
                background: #3F51B5;
            }
            .mail-campaign-stats .hero-box.ash{
                background: #607D8B;
            }
            .mail-campaign-stats .hero-box.red{
                background: #FF5722;
            }

            .mail-campaign-stats .hero-box-count{
                font-size: 27px;
                font-weight: bold;
            }
            .mail-campaign-stats .hero-box-title{
                font-size: 16px;
            }



        </style>
        <?php

    }


    public function subscriber_source_metabox($post) {

        wp_nonce_field('subscriber_source_nonce_check', 'subscriber_source_nonce_check_value');
        $settings_tabs_field = new settings_tabs_field();


        $post_id = get_the_ID();
        $active_source = get_post_meta($post_id, 'active_source', true);

        $active_source = !empty($active_source) ? $active_source : 'registered_users';

        $send_confirmation_mail 	= get_post_meta( $post_id, 'send_confirmation_mail', true);
        $confirmation_mail_template 	= get_post_meta( $post_id, 'confirmation_mail_template', true);
        $confirmation_mail_subject 	= get_post_meta( $post_id, 'confirmation_mail_subject', true);
        $confirmation_mail_from_email 	= get_post_meta( $post_id, 'confirmation_mail_from_email', true);
        $confirmation_mail_from_name 	= get_post_meta( $post_id, 'confirmation_mail_from_name', true);
        $confirmation_mail_reply_to_email 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_email', true);
        $confirmation_mail_reply_to_name 	= get_post_meta( $post_id, 'confirmation_mail_reply_to_name', true);

        $subscriber_status 	= get_post_meta( $post_id, 'subscriber_status', true);
        $subscriber_status_after_confirm 	= get_post_meta( $post_id, 'subscriber_status_after_confirm', true);

        $send_welcome_mail 	= get_post_meta( $post_id, 'send_welcome_mail', true);
        $welcome_mail_subject 	= get_post_meta( $post_id, 'welcome_mail_subject', true);
        $welcome_mail_from_email 	= get_post_meta( $post_id, 'welcome_mail_from_email', true);
        $welcome_mail_from_name 	= get_post_meta( $post_id, 'welcome_mail_from_name', true);
        $welcome_mail_reply_to_email 	= get_post_meta( $post_id, 'welcome_mail_reply_to_email', true);
        $welcome_mail_reply_to_name 	= get_post_meta( $post_id, 'welcome_mail_reply_to_name', true);
        $welcome_mail_template 	= get_post_meta( $post_id, 'welcome_mail_template', true);

        $subscriber_list	= get_post_meta( $post_id, 'subscriber_list', true);
        $recurrence_interval	= get_post_meta( $post_id, 'recurrence_interval', true);


        $source_list = array();

        $source_list['registered_users'] = array(
            'source_title'=>__('Registered users','mail-picker'),
        );

        $source_list['comments'] = array(
            'source_title'=>__('Comments','mail-picker'),

        );

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

            $source_list['woo_orders'] = array(
                'source_title'=>__('WooCommerce orders','mail-picker'),

            );
        }



        $source_list['flamingo_inbound'] = array(
            'source_title'=>__('Flamingo Inbound Messages','mail-picker'),

        );

        $source_list['ninjaform_sub'] = array(
            'source_title'=>__('Ninja Forms Submissions','mail-picker'),

        );


        $source_list['evf_entries'] = array(
            'source_title'=>__('Everest Forms Entries','mail-picker'),

        );

        $source_list['newsletter_subscribers'] = array(
            'source_title'=>__('Newsletter subscribers','mail-picker'),

        );




//        $source_list['contact-form-cfdb7'] = array(
//            'source_title'=>'Contact Form 7 Database',
//        );


//
//        $source_list['contact_form_7'] = array(
//            'source_title'=>'Contact Form 7',
//        );
//
//        $source_list['wpforms'] = array(
//            'source_title'=>'Contact Form by WPForms',
//        );
//
//        $source_list['caldera_forms'] = array(
//            'source_title'=>'Caldera Forms',
//        );
//
//        $source_list['weforms'] = array(
//            'source_title'=>'weForms',
//        );
//
//        $source_list['ninja_forms'] = array(
//            'source_title'=>'Ninja Forms',
//        );


        //var_dump($active_source);

        ?>

        <div class="settings-tabs">

        <div class="section">
            <div class="section-title"><?php echo __('Subscriber source', 'mail-picker'); ?></div>
            <p class="description section-description"><?php echo __('Customize subscriber source settings.', 'mail-picker'); ?></p>

            <div class="expandable">

                <?php

                if(!empty($source_list))
                    foreach ($source_list as $source_index => $source_data):

                        $source_title = isset($source_data['source_title']) ? $source_data['source_title'] : '';

                        ?>
                        <div class="item">
                            <div class="element-title header ">
                                <span class="expand"><i class="fas fa-expand"></i><i class="fas fa-compress"></i></span>
                                <input type="radio" name="active_source" <?php if($active_source == $source_index) echo 'checked';?>  value="<?php echo esc_attr($source_index); ?>">
                                <span class="expand"><?php echo esc_html($source_title); ?></span>
                            </div>
                            <div class="element-options options">
                                <?php
                                $source = array();

                                $source['post_id'] =  $post_id;
                                $source['source_data'] =  $source_data;

                                do_action('subscriber_source_options_'.$source_index, $source);

                                ?>
                            </div>
                        </div>
                    <?php
                    endforeach;
                ?>
            </div>

        </div>



        <div class="section">
            <div class="section-title"><?php echo __('Confirmation mail', 'mail-picker'); ?></div>
            <p class="description section-description"><?php echo __('Customize confirmation mail settings.', 'mail-picker'); ?></p>





                <?php



                $args = array(
                    'id'		=> 'send_confirmation_mail',
                    //'parent'		=> '',
                    'title'		=> __('Send confirmation mail?','job-board-manager'),
                    'details'	=> __('Select to send confirmation mail after form submit.','job-board-manager'),
                    'type'		=> 'select',
                    'value'		=> $send_confirmation_mail,
                    'default'		=> 'yes',
                    'args'		=> array('yes'=>'Yes', 'no'=>'No'),
                );

                $settings_tabs_field->generate_field($args);


                $args = array(
                    'id'		=> 'confirmation_mail_subject',
                    //'parent'		=> '',
                    'title'		=> __('Confirmation mail subject','job-board-manager'),
                    'details'	=> __('Write mail subject for confirmation.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $confirmation_mail_subject,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);

                $args = array(
                    'id'		=> 'confirmation_mail_from_email',
                    //'parent'		=> '',
                    'title'		=> __('Confirmation mail from email','job-board-manager'),
                    'details'	=> __('Write mail from email for confirmation.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $confirmation_mail_from_email,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);


                $args = array(
                    'id'		=> 'confirmation_mail_from_name',
                    //'parent'		=> '',
                    'title'		=> __('Confirmation mail from name','job-board-manager'),
                    'details'	=> __('Write mail from name for confirmation.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $confirmation_mail_from_name,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);

                $args = array(
                    'id'		=> 'confirmation_mail_reply_to_email',
                    //'parent'		=> '',
                    'title'		=> __('Confirmation mail reply to email','job-board-manager'),
                    'details'	=> __('Write mail reply to email for confirmation.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $confirmation_mail_reply_to_email,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);


                $args = array(
                    'id'		=> 'confirmation_mail_reply_to_name',
                    //'parent'		=> '',
                    'title'		=> __('Confirmation mail reply to name','job-board-manager'),
                    'details'	=> __('Write mail reply to name for confirmation.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $confirmation_mail_reply_to_name,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);


                $posts_args = get_posts(
                    array(
                        'numberposts'      => -1,
                        'post_type'        => 'mail_template',

                    )
                );

                $mail_template_args = array();

                $mail_template_args[''] = __('Select template');


                foreach ($posts_args as $post_loop){

                    $post_id = $post_loop->ID;
                    $post_title = $post_loop->post_title;

                    $mail_template_args[$post_id] = $post_title;

                }

                $args = array(
                    'id'		=> 'confirmation_mail_template',
                    //'parent'		=> '',
                    'title'		=> __('Confirmation mail template','job-board-manager'),
                    'details'	=> __('Select confirmation mail template.','job-board-manager'),
                    'type'		=> 'select',
                    'value'		=> $confirmation_mail_template,
                    'default'		=> array(),
                    'args'		=> $mail_template_args,
                );

                $settings_tabs_field->generate_field($args);




                $args = array(
                    'id'		=> 'subscriber_status',
                    //'parent'		=> '',
                    'title'		=> __(' Subscriber default status','job-board-manager'),
                    'details'	=> __('Select subscriber status before confirm.','job-board-manager'),
                    'type'		=> 'select',
                    'value'		=> $subscriber_status,
                    'default'		=> 'pending',
                    'args'		=> array('pending'=>'Pending', 'active'=>'Active', 'blocked'=>'Blocked', 'unsubscribed'=>'Unsubscribed'),
                );

                $settings_tabs_field->generate_field($args);



                $args = array(
                    'id'		=> 'subscriber_status_after_confirm',
                    //'parent'		=> '',
                    'title'		=> __('Subscriber status after confirm','job-board-manager'),
                    'details'	=> __('Select subscriber status after confirm.','job-board-manager'),
                    'type'		=> 'select',
                    'value'		=> $subscriber_status_after_confirm,
                    'default'		=> 'pending',
                    'args'		=> array('pending'=>'Pending', 'active'=>'Active', 'blocked'=>'Blocked', 'canceled'=>'Canceled'),
                );

                $settings_tabs_field->generate_field($args);

                ?>

                <div class="section">
                    <div class="section-title"><?php echo __('Welcome mail', 'mail-picker'); ?></div>
                    <p class="description section-description"><?php echo __('Customize welcome mail settings.', 'mail-picker'); ?></p>


                <?php


                $args = array(
                    'id'		=> 'send_welcome_mail',
                    //'parent'		=> '',
                    'title'		=> __('Send welcome mail?','job-board-manager'),
                    'details'	=> __('Select subscriber status before submit.','job-board-manager'),
                    'type'		=> 'select',
                    'value'		=> $send_welcome_mail,
                    'default'		=> 'yes',
                    'args'		=> array('yes'=>'Yes', 'no'=>'No'),
                );

                $settings_tabs_field->generate_field($args);


                $args = array(
                    'id'		=> 'welcome_mail_subject',
                    //'parent'		=> '',
                    'title'		=> __('Welcome mail subject','job-board-manager'),
                    'details'	=> __('Write mail subject for welcome.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $welcome_mail_subject,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);

                $args = array(
                    'id'		=> 'welcome_mail_from_email',
                    //'parent'		=> '',
                    'title'		=> __('Welcome mail from email','job-board-manager'),
                    'details'	=> __('Write mail from email for welcome.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $welcome_mail_from_email,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);


                $args = array(
                    'id'		=> 'welcome_mail_from_name',
                    //'parent'		=> '',
                    'title'		=> __('Welcome mail from name','job-board-manager'),
                    'details'	=> __('Write mail from name for welcome.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $welcome_mail_from_name,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);

                $args = array(
                    'id'		=> 'welcome_mail_reply_to_email',
                    //'parent'		=> '',
                    'title'		=> __('Welcome mail reply to email','job-board-manager'),
                    'details'	=> __('Write mail reply to email for welcome.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $welcome_mail_reply_to_email,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);


                $args = array(
                    'id'		=> 'welcome_mail_reply_to_name',
                    //'parent'		=> '',
                    'title'		=> __('Welcome mail reply to name','job-board-manager'),
                    'details'	=> __('Write mail reply to name for welcome.','job-board-manager'),
                    'type'		=> 'text',
                    'value'		=> $welcome_mail_reply_to_name,
                    'default'		=> '',
                );

                $settings_tabs_field->generate_field($args);



                $args = array(
                    'id'		=> 'welcome_mail_template',
                    //'parent'		=> '',
                    'title'		=> __('Welcome mail template','job-board-manager'),
                    'details'	=> __('Select welcome mail template.','job-board-manager'),
                    'type'		=> 'select',
                    'value'		=> $welcome_mail_template,
                    'default'		=> array(),
                    'args'		=> $mail_template_args,
                );

                $settings_tabs_field->generate_field($args);

                ?>


                </div>

                <?php

                $schedules = wp_get_schedules();

                //var_dump($schedules);

                foreach ($schedules as $scheduleIndex => $schedule){

                    $schedules_args[$scheduleIndex] = $schedule['display'];

                }

                $args = array(
                    'id'		=> 'recurrence_interval',
                    //'parent'		=> '',
                    'title'		=> __('Recurrence interval','job-board-manager'),
                    'details'	=> __('Set recurrence interval.','job-board-manager'),
                    'type'		=> 'select',
                    'value'		=> $recurrence_interval,
                    'default'		=> '',
                    'args'		=> $schedules_args,
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
                    //'parent'		=> '',
                    'title'		=> __('Subscriber list','job-board-manager'),
                    'details'	=> __('Select subscriber list.','job-board-manager'),
                    'type'		=> 'checkbox',
                    'value'		=> $subscriber_list,
                    'default'		=> array(),
                    'args'		=> $subscriber_list_args,
                );

                $settings_tabs_field->generate_field($args);

                ?>
            </div>
        </div>


        <style type="text/css">
            .mail-campaign-stats{}
            .mail-campaign-stats .hero-box-list{
                text-align: center;
            }

        </style>
        <?php

    }




    public function subscriber_source_meta_boxes_save($post_id){

        if (!isset($_POST['subscriber_source_nonce_check_value'])) return $post_id;
        $nonce = isset($_POST['subscriber_source_nonce_check_value']) ? sanitize_text_field($_POST['subscriber_source_nonce_check_value']) : '';
        if (!wp_verify_nonce($nonce, 'subscriber_source_nonce_check')) return $post_id;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) return $post_id;
        } else {
            if (!current_user_can('edit_post', $post_id)) return $post_id;
        }


        do_action('subscriber_source_meta_boxes_save', $post_id);

        $active_source = isset($_POST['active_source']) ? sanitize_text_field( $_POST['active_source'] ) : '';
        update_post_meta( $post_id, 'active_source', $active_source );

        $send_confirmation_mail = sanitize_text_field( $_POST['send_confirmation_mail'] );
        update_post_meta( $post_id, 'send_confirmation_mail', $send_confirmation_mail );



        $confirmation_mail_subject = sanitize_text_field( $_POST['confirmation_mail_subject'] );
        update_post_meta( $post_id, 'confirmation_mail_subject', $confirmation_mail_subject );

        $confirmation_mail_from_email = sanitize_email( $_POST['confirmation_mail_from_email'] );
        update_post_meta( $post_id, 'confirmation_mail_from_email', $confirmation_mail_from_email );

        $confirmation_mail_from_name = sanitize_text_field( $_POST['confirmation_mail_from_name'] );
        update_post_meta( $post_id, 'confirmation_mail_from_name', $confirmation_mail_from_name );

        $confirmation_mail_reply_to_email = sanitize_email( $_POST['confirmation_mail_reply_to_email'] );
        update_post_meta( $post_id, 'confirmation_mail_reply_to_email', $confirmation_mail_reply_to_email );

        $confirmation_mail_reply_to_name = sanitize_text_field( $_POST['confirmation_mail_reply_to_name'] );
        update_post_meta( $post_id, 'confirmation_mail_reply_to_name', $confirmation_mail_reply_to_name );

        $confirmation_mail_template = sanitize_text_field( $_POST['confirmation_mail_template'] );
        update_post_meta( $post_id, 'confirmation_mail_template', $confirmation_mail_template );



        $subscriber_status = sanitize_text_field( $_POST['subscriber_status'] );
        update_post_meta( $post_id, 'subscriber_status', $subscriber_status );

        $subscriber_status_after_confirm = sanitize_text_field( $_POST['subscriber_status_after_confirm'] );
        update_post_meta( $post_id, 'subscriber_status_after_confirm', $subscriber_status_after_confirm );


        $send_welcome_mail = sanitize_text_field( $_POST['send_welcome_mail'] );
        update_post_meta( $post_id, 'send_welcome_mail', $send_welcome_mail );

        $welcome_mail_subject = sanitize_text_field( $_POST['welcome_mail_subject'] );
        update_post_meta( $post_id, 'welcome_mail_subject', $welcome_mail_subject );

        $welcome_mail_from_email = sanitize_email( $_POST['welcome_mail_from_email'] );
        update_post_meta( $post_id, 'welcome_mail_from_email', $welcome_mail_from_email );

        $welcome_mail_from_name = sanitize_text_field( $_POST['welcome_mail_from_name'] );
        update_post_meta( $post_id, 'welcome_mail_from_name', $welcome_mail_from_name );

        $welcome_mail_reply_to_email = sanitize_email( $_POST['welcome_mail_reply_to_email'] );
        update_post_meta( $post_id, 'welcome_mail_reply_to_email', $welcome_mail_reply_to_email );

        $welcome_mail_reply_to_name = sanitize_text_field( $_POST['welcome_mail_reply_to_name'] );
        update_post_meta( $post_id, 'welcome_mail_reply_to_name', $welcome_mail_reply_to_name );

        $welcome_mail_template = sanitize_text_field( $_POST['welcome_mail_template'] );
        update_post_meta( $post_id, 'welcome_mail_template', $welcome_mail_template );

        $recurrence_interval = sanitize_text_field( $_POST['recurrence_interval'] );
        update_post_meta( $post_id, 'recurrence_interval', $recurrence_interval );


        $subscriber_list = isset($_POST['subscriber_list']) ? mail_picker_recursive_sanitize_arr(   $_POST['subscriber_list'] ) : array();

        update_post_meta( $post_id, 'subscriber_list', $subscriber_list );





    }









    public function subscriber_form_meta_boxes_save($post_id){

        if (!isset($_POST['subscriber_form_nonce_check_value'])) return $post_id;
        $nonce = isset($_POST['subscriber_form_nonce_check_value']) ? sanitize_text_field($_POST['subscriber_form_nonce_check_value'] ) : '';
        if (!wp_verify_nonce($nonce, 'subscriber_form_nonce_check')) return $post_id;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) return $post_id;
        } else {
            if (!current_user_can('edit_post', $post_id)) return $post_id;
        }


        $send_confirmation_mail = sanitize_text_field( $_POST['send_confirmation_mail'] );
        update_post_meta( $post_id, 'send_confirmation_mail', $send_confirmation_mail );



        $confirmation_mail_subject = sanitize_text_field( $_POST['confirmation_mail_subject'] );
        update_post_meta( $post_id, 'confirmation_mail_subject', $confirmation_mail_subject );

        $confirmation_mail_from_email = sanitize_email( $_POST['confirmation_mail_from_email'] );
        update_post_meta( $post_id, 'confirmation_mail_from_email', $confirmation_mail_from_email );

        $confirmation_mail_from_name = sanitize_text_field( $_POST['confirmation_mail_from_name'] );
        update_post_meta( $post_id, 'confirmation_mail_from_name', $confirmation_mail_from_name );

        $confirmation_mail_reply_to_email = sanitize_email( $_POST['confirmation_mail_reply_to_email'] );
        update_post_meta( $post_id, 'confirmation_mail_reply_to_email', $confirmation_mail_reply_to_email );

        $confirmation_mail_reply_to_name = sanitize_text_field( $_POST['confirmation_mail_reply_to_name'] );
        update_post_meta( $post_id, 'confirmation_mail_reply_to_name', $confirmation_mail_reply_to_name );

        $confirmation_mail_template = sanitize_text_field( $_POST['confirmation_mail_template'] );
        update_post_meta( $post_id, 'confirmation_mail_template', $confirmation_mail_template );

        $welcome_mail_subject = sanitize_text_field( $_POST['welcome_mail_subject'] );
        update_post_meta( $post_id, 'welcome_mail_subject', $welcome_mail_subject );

        $welcome_mail_from_email = sanitize_email( $_POST['welcome_mail_from_email'] );
        update_post_meta( $post_id, 'welcome_mail_from_email', $welcome_mail_from_email );

        $welcome_mail_from_name = sanitize_text_field( $_POST['welcome_mail_from_name'] );
        update_post_meta( $post_id, 'welcome_mail_from_name', $welcome_mail_from_name );

        $welcome_mail_reply_to_email = sanitize_email( $_POST['welcome_mail_reply_to_email'] );
        update_post_meta( $post_id, 'welcome_mail_reply_to_email', $welcome_mail_reply_to_email );

        $welcome_mail_reply_to_name = sanitize_text_field( $_POST['welcome_mail_reply_to_name'] );
        update_post_meta( $post_id, 'welcome_mail_reply_to_name', $welcome_mail_reply_to_name );

        $welcome_mail_template = sanitize_text_field( $_POST['welcome_mail_template'] );
        update_post_meta( $post_id, 'welcome_mail_template', $welcome_mail_template );


        $after_submit_action = sanitize_text_field( $_POST['after_submit_action'] );
        update_post_meta( $post_id, 'after_submit_action', $after_submit_action );

        $redirect_link = sanitize_text_field( $_POST['redirect_link'] );
        update_post_meta( $post_id, 'redirect_link', $redirect_link );



        $enable_recaptcha = sanitize_text_field( $_POST['enable_recaptcha'] );
        update_post_meta( $post_id, 'enable_recaptcha', $enable_recaptcha );

        $subscriber_status = sanitize_text_field( $_POST['subscriber_status'] );
        update_post_meta( $post_id, 'subscriber_status', $subscriber_status );

        $subscriber_status_after_confirm = sanitize_text_field( $_POST['subscriber_status_after_confirm'] );
        update_post_meta( $post_id, 'subscriber_status_after_confirm', $subscriber_status_after_confirm );



        $success_message = sanitize_text_field( $_POST['success_message'] );
        update_post_meta( $post_id, 'success_message', $success_message );

        $error_message = sanitize_text_field( $_POST['error_message'] );
        update_post_meta( $post_id, 'error_message', $error_message );


        $already_exist_message = sanitize_text_field( $_POST['already_exist_message'] );
        update_post_meta( $post_id, 'already_exist_message', $already_exist_message );

        $subscriber_list = isset($_POST['subscriber_list']) ? mail_picker_recursive_sanitize_arr( $_POST['subscriber_list'] ) : array();

        update_post_meta( $post_id, 'subscriber_list', $subscriber_list );


        $layout_elements_data = mail_picker_recursive_sanitize_arr($_POST['layout_elements_data']);

        $layout_elements_data = isset($_POST['layout_elements_data']) ? $layout_elements_data : array();

        update_post_meta( $post_id, 'layout_elements_data', $layout_elements_data );

    }











    public function mail_template_metabox_side($post) {

        wp_nonce_field('mail_template_nonce_check', 'mail_template_nonce_check_value');



        $post_id = get_the_ID();

        ?>

        <h3>Subscriber parameter</h3>
        <ul>
            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscriber_email}">
                <p>Subscriber email address</p>
            </li>
            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{first_name}">
                <p>Subscriber first name</p>
            </li>
            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{last_name}">
                <p>Subscriber last name</p>
            </li>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscriber_name}">
                <p>Subscriber name</p>
            </li>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscriber_phone}">
                <p>Subscriber phone</p>
            </li>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscriber_country}">
                <p>Subscriber country</p>
            </li>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscriber_avatar}">
                <p>Subscriber avatar</p>
            </li>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscriber_rating}">
                <p>Subscriber rating</p>
            </li>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscriber_status}">
                <p>Subscriber status</p>
            </li>

        </ul>


        <h3>Site parameter</h3>
        <ul>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{site_name}">
                <p>This site title</p>
            </li>
            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{site_url}">
                <p>This site home URL</p>
            </li>
            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{site_description}">
                <p>This site description</p>

            </li>
            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{site_logo_url}">
                <p>Logo URL</p>
            </li>
        </ul>

        <h3>Welcome Mail</h3>
        <ul>
            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscribe_confirm_url}">
                <p>Subscriber confirm URL</p>
            </li>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{unsubscribe_url}">
                <p>Subscriber confirm URL</p>
            </li>

            <li>
                <input style="border:none;background: beige" type="text" onclick="this.select()" value="{subscribe_manage_url}">
                <p>Subscriber confirm URL</p>
            </li>


        </ul>


        <style type="text/css">
            input[type=text]{
                width: 100% !important;
            }
        </style>


        <?php

    }



















} 

new class_mail_picker_post_metabox();