<?php
/*
Plugin Name: Mail Picker
Plugin URI: http://pickplugins.com/item/mail-picker
Description: Send newsletter and build email subscriber.
Version: 1.0.14
Text Domain: mail-picker
Author: PickPlugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;  // if direct access 


class MailPicker
{

    public function __construct()
    {

        $this->define_constants();
        $this->declare_classes();
        $this->loading_script();
        $this->declare_functions();
        $this->declare_shortcodes();


        register_activation_hook(__FILE__, array($this, 'activation'));
        register_deactivation_hook(__FILE__, array($this, '_deactivation'));

        add_action('plugins_loaded', array($this, '_textdomain'));
        add_filter('cron_schedules', array($this, 'cron_recurrence_interval'));
        add_action('admin_enqueue_scripts', 'wp_enqueue_media');
    }


    public function _textdomain()
    {

        $locale = apply_filters('plugin_locale', get_locale(), 'mail-picker');
        load_textdomain('mail-picker', WP_LANG_DIR . '/mail-picker/mail-picker-' . $locale . '.mo');

        load_plugin_textdomain('mail-picker', false, plugin_basename(dirname(__FILE__)) . '/languages/');
    }



    public function define_constants()
    {

        $this->define('mail_picker_plugin_url', plugins_url('/', __FILE__));
        $this->define('mail_picker_plugin_dir', plugin_dir_path(__FILE__));
        $this->define('mail_picker_plugin_name', __('Mail Picker', 'mail-picker'));
        $this->define('mail_picker_server_url', home_url());
    }

    private function define($name, $value)
    {
        if ($name && $value)
            if (!defined($name)) {
                define($name, $value);
            }
    }




    function cron_recurrence_interval($schedules)
    {

        $mail_picker_settings = get_option('mail_picker_settings');
        $recurrence_interval = isset($mail_picker_settings['recurrence_interval']) ? $mail_picker_settings['recurrence_interval'] : array();


        $schedules['1minute'] = array(
            'interval'  => 60,
            'display'   => __('1 Minute', 'textdomain')
        );
        //
        $schedules['5minute'] = array(
            'interval'  => 300,
            'display'   => __('5 Minute', 'textdomain')
        );
        //
        //        $schedules['10minute'] = array(
        //            'interval'  => 600,
        //            'display'   => __( '10 Minute', 'textdomain' )
        //        );


        $schedules = array_merge($recurrence_interval, $schedules);



        return $schedules;
    }




    public function activation()
    {


        if (!wp_next_scheduled('mail_picker_campaign_check')) {
            wp_schedule_event(time(), '1minute', 'mail_picker_campaign_check');
        }

        if (!wp_next_scheduled('mail_picker_subscriber_source_check')) {
            wp_schedule_event(time(), '1minute', 'mail_picker_subscriber_source_check');
        }


        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_postmeta = $wpdb->prefix . 'mail_picker_postmeta';


        /*
         * action
         * mail_open, link_click, subscribe, unsubscribe, bounced, forward
         *
         * */

        $sql1 = "CREATE TABLE IF NOT EXISTS " . $table_postmeta . " (
			meta_id bigint(20) NOT NULL AUTO_INCREMENT,
			post_id bigint(20)	NOT NULL,
			meta_key VARCHAR( 255 )	NOT NULL,
			meta_value longtext	NOT NULL,
			UNIQUE KEY meta_id (meta_id)
		) $charset_collate;";





        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql1);

        do_action('mail_picker_activation');
    }

    public function _deactivation()
    {

        wp_clear_scheduled_hook('mail_picker_campaign_check');
        wp_clear_scheduled_hook('mail_picker_subscriber_source_check');


        /*
         * Custom action hook for plugin deactivation.
         * Action hook: mail_picker_deactivation
         * */
        do_action('mail_picker_deactivation');
    }




    public function loading_script()
    {

        add_action('wp_enqueue_scripts', array($this, 'front_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }


    public function declare_shortcodes()
    {

        require_once(mail_picker_plugin_dir . 'includes/shortcodes/class-shortcodes.php');
    }


    public function declare_functions()
    {

        require_once(mail_picker_plugin_dir . 'includes/functions.php');
        require_once(mail_picker_plugin_dir . 'includes/functions-send-mail.php');
    }

    public function declare_classes()
    {

        require_once(mail_picker_plugin_dir . 'includes/layout-elements.php');

        require_once(mail_picker_plugin_dir . 'templates/mail-picker-form/mail-picker-form.php');


        require_once(mail_picker_plugin_dir . 'includes/classes/class-post-types.php');
        require_once(mail_picker_plugin_dir . 'includes/classes/class-post-meta.php');
        require_once(mail_picker_plugin_dir . 'includes/classes/class-settings.php');
        require_once(mail_picker_plugin_dir . 'includes/classes/class-manage-subscriber.php');

        require_once(mail_picker_plugin_dir . 'includes/classes/class-settings-tabs.php');
        require_once(mail_picker_plugin_dir . 'includes/classes/class-emails.php');


        require_once(mail_picker_plugin_dir . 'includes/functions-hooks.php');
        require_once(mail_picker_plugin_dir . 'includes/functions-cron-hooks.php');
        require_once(mail_picker_plugin_dir . 'includes/settings-hook.php');
    }







    public function front_scripts()
    {

        do_action('mail_picker_front_scripts');

        wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
    }

    public function admin_scripts()
    {
        $screen = get_current_screen();

        wp_enqueue_script('mail_picker_js', mail_picker_plugin_url . '/assets/admin/js/scripts.js', array('jquery'));
        wp_localize_script('mail_picker_js', 'mail_picker_ajax', array('mail_picker_ajaxurl' => admin_url('admin-ajax.php')));



        wp_register_style('jquery-ui', mail_picker_plugin_url . 'assets/admin/css/jquery-ui.css');

        wp_register_style('settings-tabs', mail_picker_plugin_url . 'assets/settings-tabs/settings-tabs.css');
        wp_register_script('settings-tabs', mail_picker_plugin_url . 'assets/settings-tabs/settings-tabs.js', array('jquery'));

        wp_register_style('font-awesome-4', mail_picker_plugin_url . 'assets/global/css/font-awesome-4.css');
        wp_register_style('font-awesome-5', mail_picker_plugin_url . 'assets/global/css/font-awesome-5.css');

        wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');

        //var_dump($screen);

        wp_register_script('jquery.lazy', mail_picker_plugin_url . 'assets/admin/js/jquery.lazy.js', array('jquery'));


        if ($screen->id == 'edit-subscriber' || $screen->id == 'edit-mail_campaign') {

            wp_enqueue_style('font-awesome-5');
        }



        if ($screen->id == 'subscriber' || $screen->id == 'subscriber_form' || $screen->id == 'mail_campaign' || $screen->id == 'subscriber_source' || $screen->id == 'toplevel_page_mail_picker') {

            $settings_tabs_field = new settings_tabs_field();
            $settings_tabs_field->admin_scripts();
        }


        do_action('mail_picker_admin_scripts');
    }
}

new MailPicker();
