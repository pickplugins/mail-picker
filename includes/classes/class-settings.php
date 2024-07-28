<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_mail_picker_settings{

	public function __construct(){
		
		add_action('admin_menu', array( $this, '_menu_init' ));
		
		}


    public function _menu_init() {

        add_menu_page(__('Mail Picker', 'mail-picker'), __('Mail Picker', 'mail-picker'), 'manage_options', 'mail_picker', array( $this, 'settings' ), 'dashicons-email');
        //add_submenu_page( 'mail_picker', __( 'Mail Templates Library', 'mail-picker' ), __( 'Mail Templates Library', 'mail-picker' ), 'manage_options', 'mail_templates', array( $this, 'mail_templates' ) );

        $link_subscriber = 'edit.php?post_type=subscriber';
        add_submenu_page('mail_picker', 'Subscribers', 'Subscribers', 'manage_options', $link_subscriber);

        $link_subscriber_list = 'edit-tags.php?taxonomy=subscriber_list&post_type=subscriber';
        add_submenu_page('mail_picker', 'Subscriber lists', 'Subscriber lists', 'manage_options', $link_subscriber_list);

        $link_subscriber_form = 'edit.php?post_type=subscriber_form';
        add_submenu_page('mail_picker', 'Subscribers form', 'Subscribers form', 'manage_options', $link_subscriber_form);

        $link_subscriber_source = 'edit.php?post_type=subscriber_source';
        add_submenu_page('mail_picker', 'Subscribers source', 'Subscribers source', 'manage_options', $link_subscriber_source);

        $link_mail_template = 'edit.php?post_type=mail_template';
        add_submenu_page('mail_picker', 'Mail templates', 'Mail templates', 'manage_options', $link_mail_template);

        $link_mail_campaign = 'edit.php?post_type=mail_campaign';
        add_submenu_page('mail_picker', 'Mail campaign', 'Mail campaign', 'manage_options', $link_mail_campaign);

        //$link_sms_campaign = 'edit.php?post_type=sms_campaign';
        //add_submenu_page('mail_picker', 'SMS campaign', 'SMS campaign', 'manage_options', $link_sms_campaign);


    }

	public function settings(){
		include(mail_picker_plugin_dir.'includes/menu/settings.php');
	}


    public function mail_templates(){
        include(mail_picker_plugin_dir.'includes/menu/mail_templates.php');
    }


	
}
	
new class_mail_picker_settings();


