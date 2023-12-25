<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mail_picker_shortcodes{
	
    public function __construct(){
		add_shortcode( 'mail_picker_form', array( $this, 'mail_picker_form' ) );
   	}	
	
	public function mail_picker_form($atts, $content = null ) {
			
		$atts = shortcode_atts(
		    array(
		        'id'=>''
					

		        ),
            $atts);

		$id = isset($atts['id']) ? $atts['id'] : '';


		ob_start();


		do_action('mail_picker_form', $atts);

		return ob_get_clean();
	}
	
} new class_mail_picker_shortcodes();