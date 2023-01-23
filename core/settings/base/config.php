<?php

namespace Etn\Core\Settings\Base;

use Etn\Utils\Helper as Utils;

defined( 'ABSPATH' ) || exit;

class Config extends Setting_field{
    /**
     * Call base class for generate markup
     */
    public function get_field_markup( $item = null, $key = '', $data = [] )
    {
        return $this->get_field_block(  $item , $key , $data );
    }
}

/**
 * This wrapper class is responsible for
 * Generate Settings option markup
 */
abstract class Setting_field{

    public function get_field_block(  $item = null, $key = '', $data = [] ){
        
        if (is_null($item)) {
            return;
        }
        
        if (!empty($item['type'])) {

            switch ($item['type']) {
                case "text":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "password":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "email":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "url":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "color":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "hidden":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "textarea":
                    return $this->get_text_area_input($item, $key, $data);
                    break;
                case "number":
                    return $this->get_number_input($item, $key, $data);
                    break;
                case "select_single":
                    return $this->get_select_single($item, $key, $data);
                    break;
                case "select2":
                    return $this->get_select2($item, $key, $data);
                    break;
                case "checkbox":
                    return $this->get_checkbox_input($item, $key,$data);
                    break;
                case "wp_editor":
                    return $this->get_wp_editor($item, $key,$data);
                    break;
                case "media":
                    return $this->get_media_input($item, $key,$data);
                    break;

                default:
                    return;
            }
        }
        
        return;
    }

    /**
     * Render text input field
     *
     * @param [array] $item
     * @param [string]$key
     * @param [array] $data
     * @return void
     */
    public function get_text_input( $item, $key , $data ) {
        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $class = $key;
        $input_class  = "";

        if (isset($item['attr'])) {
            $class          = !empty($item['attr']['class'])  ? $item['attr']['class'] : '';
            $input_class    = !empty($item['attr']['input_class'])  ? $item['attr']['input_class'] : '';
        }

        $label  = !empty($item['label']) ? $item['label'] : "";
        $desc   = !empty($item['desc']) ? $item['desc'] : "";
        $place_holder  = !empty($item['place_holder']) ? $item['place_holder'] : "";

        $span   = "";
        if ( !empty($item['span']) ) {
            $span_class = !empty($item['span']['class']) ? $item['span']['class'] : "";
            $span_id    = !empty($item['span']['id']) ? $item['span']['id'] : "";
            $span .="<span class={$span_class} id={$span_id}>{$item['span']['html']}</span>";
        }

        $disable_field  = "";
        if ( !empty($item['disable_field']) && $item['disable_field'] == true ) {
            $disable_field  = "disabled";
        }

        $eye_html ="";
        if ($item['type'] =="password") {
           $eye_html .='<span><i class="etn-icon etn-eye-slash eye_toggle_click"></i></span>';
        }


        $html = sprintf(
        '<div class="%s">
                <div class="etn-label">
                    <label for="%s"> %s </label>
                    <div class="etn-desc">  %s  </div>
                </div>
                <div class="etn-meta">
                    <div class="etn-secret-key">
                        <input placeholder="%s" class="%s" type="%s" name="%s" id="%s" value="%s" %s />
                        '. $span . $eye_html .'
                    </div>
                </div>
            </div>', $class, $key, $label, $desc, $place_holder , $input_class, $item['type'], $key, $key, $value , $disable_field);

        echo \Etn\Utils\Helper::render($html);
    }

    /**
     * Render text input field
     *
     * @param [array] $item
     * @param [string]$key
     * @param [array] $data
     * @return void
     */
    public function get_text_area_input( $item, $key , $data ){
        $value  = $data;
        $rows   = 14;
        $cols   = 50;
        $class  = $key;
        $value  = isset($value[$key]) ? $value[$key] : '';

        if (isset($item['attr'])) {
           $rows = isset($item['attr']['row']) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
           $cols = isset($item['attr']['col']) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
           $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] : ' ';
        }
  
        $html = sprintf('<div class="%s form-group"><div class="etn-label"><label for="%s"> %s  </label>
        <div class="etn-desc">  %s  </div>
        </div> <div class="etn-meta"><textarea class="etn-form-control wpc-msg-box" id="%s" rows="%s" cols="%s" name="%s">%s</textarea></div> </div>', $class, $key, $item['label'], $item['desc'], $key, $rows, $cols, $key, $value);
  
        echo Utils::kses($html);
    }

    /**
     * Render number input field
     *
     * @param [array] $item
     * @param [string] $key
     * @param [array] $data
     * @return void
     */
    public function get_number_input( $item, $key, $data ) {

        $value  = $data;
        $value  = isset($value[$key]) ? $value[$key] : '';

        $step   = isset( $item['step'] ) ? $item['step'] : "1";
        $min    = isset( $item['min'] ) ? $item['min'] : "0";
        $max    = isset( $item['max'] ) ? $item['max'] : '';
        $style  ="";

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
        }

        if ( !empty( $item['style'] ) ) {
            $base_attr = !empty($item['style']['attr']) ? $item['style']['attr'] : "";
            $style    .= " style='{$base_attr}'";
        }

        $html = sprintf(
                    '<div class="%s" '.$style.'>
                        <div class="etn-label">
                            <label for="%s"> %s  </label>
                            <div class="etn-desc">%s</div>
                        </div>
                        <div class="etn-meta">
                            <input autocomplete="off" class="etn-form-control" type="%s" name="%s" id="%s" value="%s" step="%s" min="%s" max="%s"/>
                        </div>
                    </div>', $class, $key, $item['label'], $item['desc'], $item['type'], $key, $key, $value, $step, $min, $max );

        echo \Etn\Utils\Helper::render( $html );
    }

    /**
     * Render dropdown select option input
     */
    public function get_select_single( $item, $key, $data ) {
        $value = $data;
        $class = $key;
        $input = '';
        $value = !empty($value[$key]) ? $value[$key] : '';
        $input_class = '';
        $style = '';

        if (isset($item['attr'])) {
            $input_class = !empty($item['attr']['input_class'])  ? $item['attr']['input_class'] : '';
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
            $style = !empty($item['attr']['style']) ? $item['attr']['style'] : "";
        }


        $div_wrap   = "";
        if ( !empty($item['span']) ) {
            $span_class = !empty($item['span']['class']) ? $item['span']['class'] : "";
            $span_id    = !empty($item['span']['id']) ? $item['span']['id'] : "";
            $div_wrap .="<div class={$span_class} id={$span_id}>{$item['span']['html']}</div>";
        }

        $disable_field  = "";
        if ( !empty($item['disable_field']) && $item['disable_field'] == true ) {
            $disable_field  = "disabled";
        }

        if (!isset($item['options']) || !count($item['options'])) {
            $html = sprintf('<div class="%s form-group"> 
            <div class="etn-label"> <label for="%s"> %s </label></div>
            </div>', $class, $key, $item['label']);
            echo Utils::kses($html);
            
            return;
        } elseif (isset($item['options']) && count($item['options'])) {
            $options = $item['options'];
            $input .= sprintf('<select id="%s" %s name="%s" class="etn-form-control wpc_select2 %s">',
            $key , $disable_field , $key , $input_class);

            foreach ($options as $option_key => $option) {
                if ($option_key == $value) {
                    $input .= sprintf('<option selected value="%s"> %s </option>',  $option_key, $option);
                } else {
                    $input .= sprintf(' <option value="%s"> %s </option>',  $option_key, $option);
                }
            }
            $input .= sprintf('</select>');
        }
        
        $html = sprintf('
            <div class="%s" style="%s"> 
                <div class="etn-label"> 
                    <label> %s  </label>
                    <div class="etn-desc">  %s  </div>
                </div>
                <div class="etn-meta">
                %s
                '.$div_wrap.'
            </div></div>', $class, $style, $item['label'], $item['desc'], $input  );
 
        echo \Etn\Utils\Helper::render($html);
    }

    /**
     * Multiple select option
     *
     * @param [array] $item
     * @param [string]$key
     * @return void
     */
    public function get_select2( $item, $key , $data ) {
        $value = $data;
        $class = $key;
        $input = '';
        $value = !empty($value[$key]) ? $value[$key] : [];

        if (isset($item['attr'])) {
           $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
        }
        if (!isset($item['options']) || !count($item['options'])) {
           $html = sprintf('<div class="%s form-group"> 
           <div class="etn-label"> <label for="%s"> %s : </label></div>
          </div>', $class, $key, $item['label'] );
           echo   Utils::kses($html);
           return;
        } elseif (isset($item['options']) && count($item['options'])) {
           $options = $item['options'];
           $input .= sprintf('<select multiple name="%s[]" class="etn-form-control wpc_select2 %s">', $key, $key, $class);
           foreach ($options as $option_key => $option) {
              if (is_array($value) && in_array($option_key, $value)) {
                 $input .= sprintf(' <option %s value="%s"> %s </option>', 'selected', $option_key, $option);
              } else {
                 $input .= sprintf(' <option value="%s"> %s </option>',  $option_key, $option);
              }
           }
           $input .= sprintf('</select>');
        }
        $html = sprintf('
        <div class="%s"> 
           <div class="etn-label"> 
              <label> %s  </label>
              <div class="etn-desc">%s</div>
           </div>
           <div class="etn-meta">
            %s
       </div></div>', $class, $item['label'], $item['desc'] , $input);
  
       echo \Etn\Utils\Helper::render($html);
    }

    /**
     * Checkbox input
     *
     * @param [array] $item
     * @param [string]$key
     * @return void
     */
    public function get_checkbox_input( $item, $key , $data ) {

        $value = $data;
        $class = $key;
        $input = '';
        $value = !empty($value[$key]) ? $value[$key] : '';
        $input_class = "";
        $data_text = "";
        $data_text_alt = "";

        if (isset($item['attr'])) {
           $class           = !empty($item['attr']['class'])  ? $item['attr']['class']  : '';
           $input_class     = !empty($item['attr']['input_class']) ? $item['attr']['input_class']: '';
           $data_text       = !empty($item['attr']['data_text'])  ? $item['attr']['data_text'] : '';
           $data_text_alt   = !empty($item['attr']['data_text_alt'])  ? $item['attr']['data_text_alt'] : '';
        }

        $disable_field  = "";
        if ( !empty($item['disable_field']) && $item['disable_field'] == true ) {
            $disable_field  = "disabled";
        }

        if (!isset($item['options']) || !count($item['options'])) {
           $html = sprintf('<div class="%s"> 
           <label for="%s"> %s </label>
          
          </div>', $class, $key, $item['label']);
  
           echo   Utils::kses($html);
           return;
        } elseif (isset($item['options']) && count($item['options'])) {
           $options = $item['options'];
           $get_data = $value =="checked" ? 'on' : $value;
           $input .= '<div class="etn-meta">';
           foreach ($options as $option_key => $option) {
               if ( count($options) > 1 ) {
                    $hide_class =  $option == "off" ? "hide_field" : "";
                    $input .= sprintf('<input '. esc_attr($get_data == $option ? 'checked' : ''  ).'  value="%s" type="%s" 
                    name="%s" class="%s  %s" '. esc_attr( $option == "on"? 'id=%s' : ''  ).' />
                    ', $option , $item['type'], $key , $hide_class , $input_class, $key );

               } else {
                $checked =  $get_data =="on" ? 'checked' : '';
                $input .= sprintf('
                <input  %s type="%s" '. $disable_field .' name="%s" class="%s" id="%s" />
                 ', $checked, $item['type'], $key , $input_class, $key );
               }
           }
           
           $input .= sprintf('<label for="%s" class="etn_switch_button_label" data-text="%s" data-textalt="%s" ></label>
            ', $key , $data_text , $data_text_alt );

           $input .= '</div>';
           
        }
  
  
        $html = sprintf('<div class="%s"> 
        <div class="etn-label"><label for="%s"> %s  </label>
        <div class="etn-desc">%s</div>
        </div>
            %s
       </div>', $class, $key, $item['label'], $item['desc'] , $input);
  
  
        echo \Etn\Utils\Helper::render($html);
    }

    /**
     * Render wp editor
     *
     * @param [array] $item
     * @param [string]$key
     * @param [array] $data
     * @return void
     */
    public function get_wp_editor( $item, $key , $data ) {
        $value = $data;
        $class = $key;
        $input = "";

        $value = !empty($value[$key]) ? $value[$key] : '';

        if (isset($item['settings']) && is_array($item['settings'])) {
           $settings = $item['settings'];
        }
  
        if (isset($item['attr'])) {
           $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] : '';
        }

         $html = sprintf('<div class="%s"> 
            <div class="etn-label" for="%s">
                <label> %s  </label>
                <div class="etn-desc">%s</div>
            </div>
        ', $class, $key, $item['label'], $item['desc']);
   
         echo \Etn\Utils\Helper::render($html);
         ?>
            <div class='etn-meta'>
                <?php wp_editor($value, $key, $settings); ?>
            </div>
        </div>
         <?php

    }

    
    /**
     * Render audio input
     *
     * @param [array] $item
     * @param [string]$key
     * @param [array] $data
     * @return void
     */
    public function get_media_input( $item, $key , $data ){
        $value  = $data;
        $class      = $key;
        $value  = !empty($value[$key]) ? $value[$key] : '';
        $image      = '">Upload image';
        $image_size = 'full';
        $display    = 'none';

        if ( isset( $item['attr'] ) ) {
            if ( isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ) {
                $class = ' etn_event_meta_field ' . $class . ' ' . $item['attr']['class'];
            } else {
                $class = ' etn_event_meta_field ';
            }
        }

        if ( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {

            $image   = '"><img src="' . $image_attributes[0] . '" alt="'.esc_attr__('logo image', 'eventin').'" style="max-width:95%;display:block;" />';
            $display = 'inline-block';
        }

        echo "<div class='{$class}'>";
        echo '

      <div class="etn-label"> <label>' . $item['label'] . '</label><div class="etn-desc"> ' . $item['desc'] . ' </div></div>
      <div class="etn-meta">
      <a class="etn_event_upload_image_button' . $image . '</a>
      		<input type="hidden" name="' . $key . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />
		<a href="#" class="essential_event_remove_image_button" style="display:inline-block;display:' . $display . '">' . esc_html__( 'Remove image', 'eventin' ) . '</a>
        </div></div>';

    }
}




