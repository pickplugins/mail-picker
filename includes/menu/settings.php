<?php	
if ( ! defined('ABSPATH')) exit;  // if direct access


$current_tab = isset($_REQUEST['tab']) ? sanitize_text_field($_REQUEST['tab']) : 'smtp';

$mail_picker_settings_tab = array();

$mail_picker_settings_tab[] = array(
    'id' => 'general',
    'title' => sprintf(__('%s General','mail-picker'),'<i class="far fa-envelope"></i>'),
    'priority' => 1,
    'active' => ($current_tab == 'general') ? true : false,
);


$mail_picker_settings_tab[] = array(
    'id' => 'subscriber_source',
    'title' => sprintf(__('%s Form Submission','mail-picker'),'<i class="fab fa-wpforms"></i>'),
    'priority' => 5,
    'active' => ($current_tab == 'subscriber_source') ? true : false,
);


$mail_picker_settings_tab[] = array(
    'id' => 'smtp',
    'title' => sprintf(__('%s SMTP Services','mail-picker'),'<i class="fas fa-mail-bulk"></i>'),
    'priority' => 5,
    'active' => ($current_tab == 'smtp') ? true : false,
);



//$mail_picker_settings_tab[] = array(
//    'id' => 'cron_list',
//    'title' => sprintf(__('%s Cron List','mail-picker'),'<i class="far fa-question-circle"></i>'),
//    'priority' => 90,
//    'active' => ($current_tab == 'cron_list') ? true : false,
//);


$mail_picker_settings_tab[] = array(
    'id' => 'test_mail',
    'title' => sprintf(__('%s Test mail','mail-picker'), '<i class="far fa-paper-plane"></i>'),
    'priority' => 90,
    'active' => ($current_tab == 'test_mail') ? true : false,
);

$mail_picker_settings_tab[] = array(
    'id' => 'help_support',
    'title' => sprintf(__('%s Help & support','mail-picker'), '<i class="far fa-question-circle"></i>'),
    'priority' => 90,
    'active' => ($current_tab == 'help_support') ? true : false,
);





//$mail_picker_settings_tab[] = array(
//    'id' => 'buy_pro',
//    'title' => sprintf(__('%s Buy Pro','mail-picker'),'<i class="fas fa-hands-helping"></i>'),
//    'priority' => 95,
//    'active' => ($current_tab == 'buy_pro') ? true : false,
//);







$mail_picker_settings_tab = apply_filters('mail_picker_settings_tabs', $mail_picker_settings_tab);

$tabs_sorted = array();

if(!empty($mail_picker_settings_tab))
foreach ($mail_picker_settings_tab as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
array_multisort($tabs_sorted, SORT_ASC, $mail_picker_settings_tab);



$mail_picker_settings = get_option('mail_picker_settings');

?>
<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div><h2><?php echo sprintf(__('%s Settings', 'mail-picker'), mail_picker_plugin_name)?></h2>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', esc_url_raw($_SERVER['REQUEST_URI'])); ?>">
	        <input type="hidden" name="mail_picker_hidden" value="Y">
            <input type="hidden" name="tab" value="<?php echo esc_attr($current_tab); ?>">
            <?php
            if(!empty($_POST['mail_picker_hidden'])){
                $nonce = isset($_POST['_wpnonce']) ? sanitize_text_field($_POST['_wpnonce']) : '';
                if(wp_verify_nonce( $nonce, 'mail_picker_nonce' ) && $_POST['mail_picker_hidden'] == 'Y') {
                    do_action('mail_picker_settings_save');
                    ?>
                    <div class="updated notice  is-dismissible"><p><strong><?php _e('Changes Saved.', 'mail-picker' ); ?></strong></p></div>
                    <?php
                }
            }
            ?>
            <div class="settings-tabs-loading" style="">Loading...</div>
            <div class="settings-tabs vertical has-right-panel" style="display: none">
                <div class="settings-tabs-right-panel">
                    <?php
                    if(!empty($mail_picker_settings_tab))
                    foreach ($mail_picker_settings_tab as $tab) {
                        $id = $tab['id'];
                        $active = $tab['active'];
                        ?>
                        <div class="right-panel-content <?php if($active) echo 'active';?> right-panel-content-<?php echo $id; ?>">
                            <?php
                            do_action('mail_picker_settings_tabs_right_panel_'.$id);
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <ul class="tab-navs">
                    <?php
                    if(!empty($mail_picker_settings_tab))
                    foreach ($mail_picker_settings_tab as $tab){
                        $id = $tab['id'];
                        $title = $tab['title'];
                        $active = $tab['active'];
                        $data_visible = isset($tab['data_visible']) ? $tab['data_visible'] : '';
                        $hidden = isset($tab['hidden']) ? $tab['hidden'] : false;
                        $is_pro = isset($tab['is_pro']) ? $tab['is_pro'] : false;
                        $pro_text = isset($tab['pro_text']) ? $tab['pro_text'] : '';
                        ?>
                        <li <?php if(!empty($data_visible)):  ?> data_visible="<?php echo $data_visible; ?>" <?php endif; ?> class="tab-nav <?php if($hidden) echo 'hidden';?> <?php if($active) echo 'active';?>" data-id="<?php echo $id; ?>">
                            <?php echo $title; ?>
                            <?php
                            if($is_pro):
                                ?><span class="pro-feature"><?php echo $pro_text; ?></span> <?php
                            endif;
                            ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
                if(!empty($mail_picker_settings_tab))
                foreach ($mail_picker_settings_tab as $tab){
                    $id = $tab['id'];
                    $title = $tab['title'];
                    $active = $tab['active'];
                    ?>
                    <div class="tab-content <?php if($active) echo 'active';?>" id="<?php echo $id; ?>">
                        <?php
                        do_action('mail_picker_settings_content_'.$id, $tab);
                        ?>
                    </div>
                    <?php
                }
                ?>
                <div class="clear clearfix"></div>
                <p class="submit">
                    <?php wp_nonce_field( 'mail_picker_nonce' ); ?>
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes','mail-picker' ); ?>" />
                </p>
            </div>
		</form>
</div>