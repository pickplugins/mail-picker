<?php
if ( ! defined('ABSPATH')) exit;  // if direct access



add_action('mail_picker_form_element_option_wrapper_start','mail_picker_form_element_option_wrapper_start');


function mail_picker_form_element_option_wrapper_start($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $wrapper_id = isset($element_data['wrapper_id']) ? $element_data['wrapper_id'] : '';
    $wrapper_class = isset($element_data['wrapper_class']) ? $element_data['wrapper_class'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';


    $css = isset($element_data['css']) ? $element_data['css'] : '';
    $css_hover = isset($element_data['css_hover']) ? $element_data['css_hover'] : '';


    ?>
    <div class="item wrapper_start">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Wrapper start','mail-picker'); ?></span>

            <span class="handle-start"><i class="fas fa-level-up-alt"></i></span>

        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'wrapper_id',
                'parent' => $input_name.'[wrapper_start]',
                'title'		=> __('Wrapper id','mail-picker'),
                'details'	=> __('Write wrapper id, ex: my-unique-id.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $wrapper_id,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'wrapper_class',
                'parent' => $input_name.'[wrapper_start]',
                'title'		=> __('Wrapper class','mail-picker'),
                'details'	=> __('Write wrapper class, ex: layer-thumbnail','mail-picker'),
                'type'		=> 'text',
                'value'		=> $wrapper_class,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[wrapper_start]',
                'title'		=> __('Margin','mail-picker'),
                'details'	=> __('Set margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);


            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}




add_action('mail_picker_form_element_wrapper_start', 'mail_picker_form_element_wrapper_start', 10);
function mail_picker_form_element_wrapper_start($args){

    $index = isset($args['index']) ? $args['index'] : '';
    $element_class = !empty($index) ? 'element_'.$index : '';

    //echo '<pre>'.var_export($args, true).'</pre>';
    $element = isset($args['element']) ? $args['element'] : array();
    $wrapper_class = isset($element['wrapper_class']) ? $element['wrapper_class'] : '';
    $wrapper_id = isset($element['wrapper_id']) ? $element['wrapper_id'] : '';



    ?>
    <div class="<?php echo esc_attr($wrapper_class); ?> <?php echo esc_attr($element_class); ?>" id="<?php echo esc_attr($wrapper_id); ?>">
    <?php

}


add_action('mail_picker_form_element_css_wrapper_start', 'mail_picker_form_element_css_wrapper_start', 10);
function mail_picker_form_element_css_wrapper_start($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
<style type="text/css">
.layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
<?php if(!empty($color)): ?>
    color: <?php echo esc_attr($color); ?>;
<?php endif; ?>
<?php if(!empty($font_size)): ?>
    font-size: <?php echo esc_attr($font_size); ?>;
<?php endif; ?>
<?php if(!empty($font_family)): ?>
    font-family: <?php echo esc_attr($font_family); ?>;
<?php endif; ?>
<?php if(!empty($margin)): ?>
    margin: <?php echo esc_attr($margin); ?>;
<?php endif; ?>
<?php if(!empty($text_align)): ?>
    text-align: <?php echo esc_attr($text_align); ?>;
<?php endif; ?>

</style>
    <?php
}





add_action('mail_picker_form_element_option_wrapper_end','mail_picker_form_element_option_wrapper_end');


function mail_picker_form_element_option_wrapper_end($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();

    $wrapper_id = isset($element_data['wrapper_id']) ? $element_data['wrapper_id'] : '';

    ?>
    <div class="item wrapper_end">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Wrapper end','mail-picker'); ?></span>
            <span class="handle-end"><i class="fas fa-level-down-alt"></i></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'wrapper_id',
                'wraper_class'		=> 'hidden',

                'parent' => $input_name.'[wrapper_end]',
                'title'		=> __('Wrapper id','mail-picker'),
                'details'	=> __('Write wrapper id, ex: div, p, span.','mail-picker'),
                'type'		=> 'hidden',
                'value'		=> $wrapper_id,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);





            ?>

        </div>
    </div>
    <?php

}



add_action('mail_picker_form_element_wrapper_end', 'mail_picker_form_element_wrapper_end', 10);
function mail_picker_form_element_wrapper_end($args){


    ?>
    </div>
    <?php

}




add_action('mail_picker_form_element_option_input_text','mail_picker_form_element_option_input_text');
function mail_picker_form_element_option_input_text($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : '';
    $name = isset($element_data['name']) ? $element_data['name'] : '';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Input text','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[input_text]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[input_text]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[input_text]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[input_text]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_input_text',
                'parent' => $input_name.'[input_text]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[input_text]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[input_text]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[input_text]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_input_text', 'mail_picker_form_element_input_text');
function mail_picker_form_element_input_text($args){

    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';


    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="" type="text" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_input_text', 'mail_picker_form_element_css_input_text', 10);
function mail_picker_form_element_css_input_text($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
<style type="text/css">
.layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
<?php if(!empty($color)): ?>
    color: <?php echo esc_attr($color); ?>;
<?php endif; ?>
<?php if(!empty($font_size)): ?>
    font-size: <?php echo esc_attr($font_size); ?>;
<?php endif; ?>
<?php if(!empty($font_family)): ?>
    font-family: <?php echo esc_attr($font_family); ?>;
<?php endif; ?>
<?php if(!empty($margin)): ?>
    margin: <?php echo esc_attr($margin); ?>;
<?php endif; ?>
<?php if(!empty($text_align)): ?>
    text-align: <?php echo esc_attr($text_align); ?>;
<?php endif; ?>
}
</style>
    <?php
}




add_action('mail_picker_form_element_option_input_email','mail_picker_form_element_option_input_email');
function mail_picker_form_element_option_input_email($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : '';
    $name = isset($element_data['name']) ? $element_data['name'] : '';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Input email','mail-picker'); ?>  - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[input_email]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[input_email]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[input_email]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[input_email]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_input_email',
                'parent' => $input_name.'[input_email]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[input_email]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[input_email]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[input_email]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_input_email', 'mail_picker_form_element_input_email');
function mail_picker_form_element_input_email($args){


    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="" type="email" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_input_email', 'mail_picker_form_element_css_input_email', 10);
function mail_picker_form_element_css_input_email($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}






add_action('mail_picker_form_element_option_input_number','mail_picker_form_element_option_input_number');
function mail_picker_form_element_option_input_number($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : '';
    $name = isset($element_data['name']) ? $element_data['name'] : '';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Input number','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[input_number]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[input_number]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[input_number]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[input_number]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_input_number',
                'parent' => $input_name.'[input_number]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[input_number]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[input_number]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[input_number]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_input_number', 'mail_picker_form_element_input_number');
function mail_picker_form_element_input_number($args){

    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="element  " type="number" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_input_number', 'mail_picker_form_element_css_input_number', 10);
function mail_picker_form_element_css_input_number($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}


add_action('mail_picker_form_element_option_input_select','mail_picker_form_element_option_input_select');
function mail_picker_form_element_option_input_select($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : '';
    $name = isset($element_data['name']) ? $element_data['name'] : '';
    $options = isset($element_data['options']) ? $element_data['options'] : '';



    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Input select','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[input_select]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[input_select]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'options',
                'css_id'		=> $element_index.'_options',
                'parent' => $input_name.'[input_select]',
                'title'		=> __('Input options','mail-picker'),
                'details'	=> __('Set options.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $options,
                'placeholder'		=> 'option1|Option 1, options2|Options 2',
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[input_select]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_input_select',
                'parent' => $input_name.'[input_select]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[input_select]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[input_select]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[input_select]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index) ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_input_select', 'mail_picker_form_element_input_select');
function mail_picker_form_element_input_select($args){

    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';
    $options = isset($args['options']) ? $args['options'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    $options = explode(',', $options);


    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <select class=""  name="<?php echo esc_attr($field_name); ?>" >

                <?php

                foreach ($options as $option){
                    $option_data = explode('|', $option);
                    ?>
                    <option <?php if($default ==$option_data[0] ) echo 'selected';?>  value="<?php echo esc_attr($option_data[0]); ?>"><?php echo esc_html($option_data[1]); ?></option>
                    <?php
                }

                ?>
            </select>
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_input_select', 'mail_picker_form_element_css_input_select', 10);
function mail_picker_form_element_css_input_select($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}




add_action('mail_picker_form_element_option_input_checkbox','mail_picker_form_element_option_input_checkbox');
function mail_picker_form_element_option_input_checkbox($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : '';
    $name = isset($element_data['name']) ? $element_data['name'] : '';
    $options = isset($element_data['options']) ? $element_data['options'] : '';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Input checkbox','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[input_checkbox]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[input_checkbox]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'options',
                'css_id'		=> $element_index.'_options',
                'parent' => $input_name.'[input_checkbox]',
                'title'		=> __('Input options','mail-picker'),
                'details'	=> __('Set options.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $options,
                'placeholder'		=> 'option1|Option 1, options2|Options 2',
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[input_checkbox]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_input_checkbox',
                'parent' => $input_name.'[input_checkbox]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[input_checkbox]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[input_checkbox]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[input_checkbox]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_input_checkbox', 'mail_picker_form_element_input_checkbox');
function mail_picker_form_element_input_checkbox($args){

    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
    $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';
    $options = isset($args['options']) ? $args['options'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';


    $options = explode(',', $options);
    $defaults = explode(',', $default);

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index) ; ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">

            <?php

            foreach ($options as $option){
                $option_data = explode('|', $option);
                ?>
                    <label>
                        <input type="checkbox" value="<?php echo esc_attr($option_data[0]); ?>" <?php if(in_array($option_data[0], $defaults)) echo 'checked'; ?>  name="<?php echo esc_attr($field_name); ?>[]"/><?php echo esc_html($option_data[1]); ?>

                    </label>
                <?php
            }

            ?>
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_input_checkbox', 'mail_picker_form_element_css_input_checkbox', 10);
function mail_picker_form_element_css_input_checkbox($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}




add_action('mail_picker_form_element_option_input_radio','mail_picker_form_element_option_input_radio');
function mail_picker_form_element_option_input_radio($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : '';
    $name = isset($element_data['name']) ? $element_data['name'] : '';
    $options = isset($element_data['options']) ? $element_data['options'] : '';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Input radio','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[input_radio]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[input_radio]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'		=> 'options',
                'css_id'		=> $element_index.'_options',
                'parent' => $input_name.'[input_radio]',
                'title'		=> __('Input options','mail-picker'),
                'details'	=> __('Set options.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $options,
                'placeholder'		=> 'option1|Option 1, options2|Options 2',
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[input_radio]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_input_radio',
                'parent' => $input_name.'[input_radio]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[input_radio]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[input_radio]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[input_radio]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_input_radio', 'mail_picker_form_element_input_radio');
function mail_picker_form_element_input_radio($args){

    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
    $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';
    $options = isset($args['options']) ? $args['options'] : '';
    $options = explode(',', $options);

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';



    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">

            <?php

            foreach ($options as $option){
                $option_data = explode('|', $option);
                ?>
                <label>
                    <input type="radio" value="<?php echo esc_attr($option_data[0]); ?>" <?php if($default ==$option_data[0] ) echo 'checked';?>  name="<?php echo esc_attr($field_name); ?>"/><?php echo esc_html($option_data[1]); ?>

                </label>
                <?php
            }

            ?>

        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_input_radio', 'mail_picker_form_element_css_input_radio', 10);
function mail_picker_form_element_css_input_radio($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}







add_action('mail_picker_form_element_option_subscriber_email','mail_picker_form_element_option_subscriber_email');
function mail_picker_form_element_option_subscriber_email($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : '';
    $name = isset($element_data['name']) ? $element_data['name'] : 'subscriber_email';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Subscriber email','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[subscriber_email]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[subscriber_email]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'readonly'		=> true,
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[subscriber_email]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[subscriber_email]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_subscriber_email',
                'parent' => $input_name.'[subscriber_email]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[subscriber_email]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[subscriber_email]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[subscriber_email]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_subscriber_email', 'mail_picker_form_element_subscriber_email');
function mail_picker_form_element_subscriber_email($args){


    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="" type="email" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_subscriber_email', 'mail_picker_form_element_css_subscriber_email', 10);
function mail_picker_form_element_css_subscriber_email($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}






add_action('mail_picker_form_element_option_subscriber_phone','mail_picker_form_element_option_subscriber_phone');
function mail_picker_form_element_option_subscriber_phone($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : __('Subscriber phone','mail-picker');
    $name = isset($element_data['name']) ? $element_data['name'] : 'subscriber_phone';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Phone','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[subscriber_phone]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[subscriber_phone]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'readonly'		=> true,
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[subscriber_phone]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[subscriber_phone]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_subscriber_phone',
                'parent' => $input_name.'[subscriber_phone]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[subscriber_phone]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[subscriber_phone]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[subscriber_phone]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_subscriber_phone', 'mail_picker_form_element_subscriber_phone');
function mail_picker_form_element_subscriber_phone($args){


    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="" type="text" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_subscriber_phone', 'mail_picker_form_element_css_subscriber_phone', 10);
function mail_picker_form_element_css_subscriber_phone($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}




add_action('mail_picker_form_element_option_subscriber_country','mail_picker_form_element_option_subscriber_country');
function mail_picker_form_element_option_subscriber_country($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : __('Subscriber country','mail-picker');
    $name = isset($element_data['name']) ? $element_data['name'] : 'subscriber_country';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Country','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[subscriber_country]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[subscriber_country]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'readonly'		=> true,
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[subscriber_country]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[subscriber_country]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_subscriber_country',
                'parent' => $input_name.'[subscriber_country]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[subscriber_country]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[subscriber_country]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[subscriber_country]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_subscriber_country', 'mail_picker_form_element_subscriber_country');
function mail_picker_form_element_subscriber_country($args){


    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="" type="text" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_subscriber_country', 'mail_picker_form_element_css_subscriber_country', 10);
function mail_picker_form_element_css_subscriber_country($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}



add_action('mail_picker_form_element_option_first_name','mail_picker_form_element_option_first_name');
function mail_picker_form_element_option_first_name($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : __('First name','mail-picker');
    $name = isset($element_data['name']) ? $element_data['name'] : 'first_name';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('First name','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[first_name]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[first_name]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'readonly'		=> true,
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[first_name]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[first_name]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_first_name',
                'parent' => $input_name.'[first_name]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[first_name]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[first_name]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[first_name]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_first_name', 'mail_picker_form_element_first_name');
function mail_picker_form_element_first_name($args){


    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="" type="text" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_first_name', 'mail_picker_form_element_css_first_name', 10);
function mail_picker_form_element_css_first_name($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';

    $css = isset($element['css']) ? $element['css'] : '';
    $css_hover = isset($element['css_hover']) ? $element['css_hover'] : '';

    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}




add_action('mail_picker_form_element_option_last_name','mail_picker_form_element_option_last_name');
function mail_picker_form_element_option_last_name($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : __('Last name','mail-picker');
    $name = isset($element_data['name']) ? $element_data['name'] : 'last_name';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Last name','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[last_name]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[last_name]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'readonly'		=> true,
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[last_name]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[last_name]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_last_name',
                'parent' => $input_name.'[last_name]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[last_name]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[last_name]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[last_name]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_last_name', 'mail_picker_form_element_last_name');
function mail_picker_form_element_last_name($args){


    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="" type="text" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_last_name', 'mail_picker_form_element_css_last_name', 10);
function mail_picker_form_element_css_last_name($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';


    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}



add_action('mail_picker_form_element_option_subscriber_list','mail_picker_form_element_option_subscriber_list');
function mail_picker_form_element_option_subscriber_list($parameters){

    $settings_tabs_field = new settings_tabs_field();

    $input_name = isset($parameters['input_name']) ? $parameters['input_name'] : '{input_name}';
    $element_data = isset($parameters['element_data']) ? $parameters['element_data'] : array();
    $element_index = isset($parameters['index']) ? $parameters['index'] : '';

    $label = isset($element_data['label']) ? $element_data['label'] : __('Last name','mail-picker');
    $name = isset($element_data['name']) ? $element_data['name'] : 'subscriber_list';

    $default = isset($element_data['default']) ? $element_data['default'] : '';
    $placeholder = isset($element_data['placeholder']) ? $element_data['placeholder'] : '';

    $color = isset($element_data['color']) ? $element_data['color'] : '';
    $font_size = isset($element_data['font_size']) ? $element_data['font_size'] : '';
    $font_family = isset($element_data['font_family']) ? $element_data['font_family'] : '';
    $margin = isset($element_data['margin']) ? $element_data['margin'] : '';

    ?>
    <div class="item">
        <div class="element-title header ">
            <span class="remove" onclick="jQuery(this).parent().parent().remove()"><i class="fas fa-times"></i></span>
            <span class="sort"><i class="fas fa-sort"></i></span>

            <span class="expand"><?php echo __('Subscriber list','mail-picker'); ?> - <code><?php echo $name; ?></code></span>
        </div>
        <div class="element-options options">

            <?php

            $args = array(
                'id'		=> 'label',
                'css_id'		=> $element_index.'_label',
                'parent' => $input_name.'[subscriber_list]',
                'title'		=> __('Input label','mail-picker'),
                'details'	=> __('Set custom field label.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $label,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'name',
                'css_id'		=> $element_index.'_name',
                'parent' => $input_name.'[subscriber_list]',
                'title'		=> __('Input name','mail-picker'),
                'details'	=> __('Set custom name.','mail-picker'),
                'type'		=> 'text',
                'readonly'		=> true,
                'value'		=> $name,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'placeholder',
                'css_id'		=> $element_index.'_placeholder',
                'parent' => $input_name.'[subscriber_list]',
                'title'		=> __('Input placeholder','mail-picker'),
                'details'	=> __('Set custom placeholder.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $placeholder,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'default',
                'css_id'		=> $element_index.'_default',
                'parent' => $input_name.'[subscriber_list]',
                'title'		=> __('Input default value','mail-picker'),
                'details'	=> __('Write field default value.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $default,
                'default'		=> '',
                'placeholder'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'color',
                'css_id'		=> $element_index.'_subscriber_list',
                'parent' => $input_name.'[subscriber_list]',
                'title'		=> __('Input text color','mail-picker'),
                'details'	=> __('Input text color.','mail-picker'),
                'type'		=> 'colorpicker',
                'value'		=> $color,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'		=> 'font_size',
                'css_id'		=> $element_index.'_font_size',
                'parent' => $input_name.'[subscriber_list]',
                'title'		=> __('Input font size','mail-picker'),
                'details'	=> __('Set font size.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_size,
                'default'		=> '',
                'placeholder'		=> '14px',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'font_family',
                'css_id'		=> $element_index.'_font_family',
                'parent' => $input_name.'[subscriber_list]',
                'title'		=> __('Input font family','mail-picker'),
                'details'	=> __('Set font family.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $font_family,
                'default'		=> '',
                'placeholder'		=> 'Open Sans',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'		=> 'margin',
                'css_id'		=> $element_index.'_margin',
                'parent' => $input_name.'[subscriber_list]',
                'title'		=> __('Input margin','mail-picker'),
                'details'	=> __('Set input margin.','mail-picker'),
                'type'		=> 'text',
                'value'		=> $margin,
                'default'		=> '',
                'placeholder'		=> '5px 0',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();
            ?>
            <textarea readonly type="text"  onclick="this.select();">.element_<?php echo esc_attr($element_index); ?>{}</textarea>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'use_css',
                'title'		=> __('Use of CSS','mail-picker'),
                'details'	=> __('Use following class selector to add custom CSS for this element.','mail-picker'),
                'type'		=> 'custom_html',
                'html'		=> $html,

            );

            $settings_tabs_field->generate_field($args);

            ?>

        </div>
    </div>
    <?php

}


add_action('mail_picker_form_element_subscriber_list', 'mail_picker_form_element_subscriber_list');
function mail_picker_form_element_subscriber_list($args){


    $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
    $default = isset($args['default']) ? $args['default'] : '';
    $label = isset($args['label']) ? $args['label'] : '';
    $index = isset($args['index']) ? $args['index'] : '';
    $field_name = isset($args['name']) ? $args['name'] : '';

    $custom_class = isset($args['custom_class']) ? $args['custom_class'] : '';

    ?>


    <div class="field-wrap element_<?php echo esc_attr($index); ?> <?php echo esc_attr($custom_class); ?>">
        <div class="field-label"><?php echo $label; ?></div>
        <div class="input-wrap">
            <input class="" type="text" name="<?php echo esc_attr($field_name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($default); ?>" />
        </div>
    </div>
    <?php
}



add_action('mail_picker_form_element_css_subscriber_list', 'mail_picker_form_element_css_subscriber_list', 10);
function mail_picker_form_element_css_subscriber_list($args){


    $index = isset($args['index']) ? $args['index'] : '';
    $element = isset($args['element']) ? $args['element'] : array();
    $layout_id = isset($args['layout_id']) ? $args['layout_id'] : '';

    $color = isset($element['color']) ? $element['color'] : '';
    $font_size = isset($element['font_size']) ? $element['font_size'] : '';
    $font_family = isset($element['font_family']) ? $element['font_family'] : '';
    $margin = isset($element['margin']) ? $element['margin'] : '';
    $text_align = isset($element['text_align']) ? $element['text_align'] : '';


    ?>
    <style type="text/css">
        .layout-<?php echo esc_attr($layout_id); ?> .element_<?php echo esc_attr($index); ?>{
        <?php if(!empty($color)): ?>
            color: <?php echo esc_attr($color); ?>;
        <?php endif; ?>
        <?php if(!empty($font_size)): ?>
            font-size: <?php echo esc_attr($font_size); ?>;
        <?php endif; ?>
        <?php if(!empty($font_family)): ?>
            font-family: <?php echo esc_attr($font_family); ?>;
        <?php endif; ?>
        <?php if(!empty($margin)): ?>
            margin: <?php echo esc_attr($margin); ?>;
        <?php endif; ?>
        <?php if(!empty($text_align)): ?>
            text-align: <?php echo esc_attr($text_align); ?>;
        <?php endif; ?>
        }
    </style>
    <?php
}
