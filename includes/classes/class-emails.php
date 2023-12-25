<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_mail_picker_emails{
	
	public function __construct(){


		}



	public function send_email($email_data){


        $mail_picker_settings = get_option('mail_picker_settings');
        $smtp_list = isset($mail_picker_settings['smtp']) ? $mail_picker_settings['smtp'] : array();
        $active_smtp = isset($mail_picker_settings['active_smtp']) ? $mail_picker_settings['active_smtp'] : 'other_smtp';
        $api_data = isset($smtp_list[$active_smtp]) ? $smtp_list[$active_smtp] : array();



        if(!empty($active_smtp) && $active_smtp != 'other_smtp') {

            //$smtp_data['id'] = $active_smtp;

            return apply_filters('mail_picker_send_mail_via_api_'.$active_smtp, $email_data, $api_data);
            //return $this->send_email_via_api($email_data, $smtp_data);

        }



		$mail_to = isset($email_data['mail_to']) ? $email_data['mail_to'] : '';
        $mail_bcc = isset($email_data['mail_bcc']) ? $email_data['mail_bcc'] : '';
        $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : '';
        $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

		$mail_from = isset($email_data['mail_from']) ? $email_data['mail_from'] : get_option('admin_email');
		$email_from_name = isset($email_data['mail_from_name']) ? $email_data['mail_from_name'] : get_bloginfo('name');
		$mail_subject = isset($email_data['mail_subject']) ? $email_data['mail_subject'] : '';
		$mail_body = isset($email_data['mail_body']) ? $email_data['mail_body'] : '';
		$mail_attachments = isset($email_data['mail_attachments']) ? $email_data['mail_attachments'] : '';
					

		$headers = array();
		$headers[] = "From: ".esc_html($email_from_name)." <".esc_html($mail_from).">";
        if(!empty($reply_to)){
            $headers[] = "Reply-To: ".esc_html($reply_to_name)." <".esc_html($reply_to).">";
        }

        $headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=UTF-8";
        if(!empty($email_bcc)){
            $headers[] = "Bcc: ".esc_html($mail_bcc);
        }

        $mail_headers = apply_filters('mail_picker_mail_headers', $headers);

		$status = wp_mail($mail_to, $mail_subject, $mail_body, $mail_headers, $mail_attachments);

        return $status;

	}
}
	
new class_mail_picker_emails();