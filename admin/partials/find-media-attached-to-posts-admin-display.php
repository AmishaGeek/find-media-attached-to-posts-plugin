<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://https://geekcodelab.com/
 * @since      1.0.0
 *
 * @package    Find_Media_Attached_To_Posts
 * @subpackage Find_Media_Attached_To_Posts/admin/partials
 */

class Find_Media_Attached_To_Posts_Admin_Display
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    /**
     * Provides default values for the Display Options.
     *
     * @return array
     */
    public function default_display_options()
    {
        $post_types = get_post_types(array('public' => true), 'names', 'and');
        unset($post_types['attachment']);
        $post_types = implode(",",$post_types);
        $defaults = array(
            'featured_image'        =>    '1',
            'in_content'        =>    '1',
            'post_types'        =>    $post_types,
            'prevent_media' =>''
        );

        return $defaults;
    }
    public function run()
    { ?>
        <div class="fma-wrap">
            <h1 class="fma-h1-title"><?php _e('Find Media Attached To Posts', $this->plugin_name); ?></h1>
            <div class="fma-inner fma-row">

                <?php settings_errors(); ?>
                <form method="post" action="options.php" class="fma-form">
                    <?php
                    settings_fields('fma_display_options');
                    do_settings_sections('fma_display_options');
                    submit_button();
                    ?>
                </form>
            </div>

        </div>
    <?php
    }
    public function register_admin_settings()
    {

        // If the theme options don't exist, create them.
        if (false == get_option('fma_display_options')) {
            $default_array = $this->default_display_options();
            add_option('fma_display_options', $default_array);
        }

        add_settings_section(
            'general_settings_section',                        // ID used to identify this section and with which to register options
            __('', $this->plugin_name),                // Title to be displayed on the administration page
            array($this, 'general_options_callback'),        // Callback used to render the description of the section
            'fma_display_options',                        // Page on which to add this section of options
        );

        // Next, we'll introduce the fields for toggling the visibility of content elements.
        add_settings_field(
            'featured_image',                                // ID used to identify the field throughout the theme
            __('Display Option', $this->plugin_name),                    // The label to the left of the option interface element
            array($this, 'display_options_checkbox_callback'),    // The name of the function responsible for rendering the option interface
            'fma_display_options',                // The page on which this option will be displayed
            'general_settings_section',                    // The name of the section to which this field belongs
            array(
                'name'    => 'general_option',                        // The array of arguments to pass to the callback. In this case, just a description.
                'data'    => array(
                    'featured_image' => 'Featured Image',
                    'in_content' => 'Content',
                ),  
                'class' => 'fma-tr'
            )
        );

        // add_settings_field(
        //     'in_content',
        //     __('Content', $this->plugin_name),
        //     array($this, 'checkbox_callback'),
        //     'fma_display_options',
        //     'general_settings_section',
        //     array(
        //         'name' => 'in_content',
        //         'note'      =>'Enable if find attached image in content also'
        //     )
        // );

          add_settings_field(
            'post_types',                                
            __('Post Types', $this->plugin_name),                    
            array($this, 'post_type_checkbox_callback'),    
            'fma_display_options',                
            'general_settings_section',                    
            array(
                'name'    => 'post_types'   ,  
                'class' => 'fma-tr'                   
            )
        );
          add_settings_field(
            'prevent_media',                                
            __('Prevent Media', $this->plugin_name),                    
            array($this, 'prevent_checkbox_callback'),    
            'fma_display_options',                
            'general_settings_section',                    
            array(
                'name'    => 'prevent_media',                        
                'note'      =>'Enable if not to delete image that is associated with any page/post',
                'class' => 'fma-tr'
            )
        );

        register_setting(
			'fma_display_options',
			'fma_display_options',
			array( $this, 'validate_input_examples')
		);
       
    }
    public function general_options_callback()
    {
        // echo '<p>' . __('Select which areas of content you wish to display.', $this->plugin_name) . '</p>';
    } // end general_options_callback

    /**
     * This function renders the interface elements for toggling the visibility of the checkbox element.
     *
     * It accepts an array or arguments and expects the first element in the array to be the description
     * to be displayed next to the checkbox.
     */
    public function display_options_checkbox_callback($args){
        $options = get_option('fma_display_options');
        $data = $args['data'];
        $general_option_arr = (!empty($options[$args['name']])) ? explode(",", $options[$args['name']]) : [] ;
        if (!empty($data)) { ?>
        <div class="fma_chk_wp">
                <div class="fma-chk">
                    <label for="all"><input class="fmacheckAll" type="checkbox" value="">All</label>
                </div>
                <?php
                    foreach ($data as $key => $value) { ?>
                    <div class="fma-chk">
                        <label for="<?php esc_attr_e($key) ?>">
                            <input type="checkbox" class="fma_chk fma_chk_js" name="fma_display_options[<?php esc_attr_e($args['name']) ?>][]" value="<?php esc_attr_e($key) ?>" <?php _e((in_array($key, $general_option_arr)) ? 'checked' : ''); ?>><?php esc_attr_e(ucwords($value)) ?>
                        </label>                    
                    </div>
                        <?php
                    }
                ?>
        </div>
            <?php
        }
    
        if (isset($args['note'])) { ?>
                <p class="fsm_note"><?php esc_attr_e($args['note']) ?></p>
            <?php
        }
    }

    public function post_type_checkbox_callback($args)
    {
        $options = get_option('fma_display_options');
        $post_types_arr =  explode(",", $options['post_types']);
        $post_types = get_post_types(array('public' => true), 'names', 'and');
        unset($post_types['attachment']);?>
                <div class="fma_chk_wp">
                    <div class="fma-chk">
                        <label for="all"><input class="fmacheckAll" type="checkbox" value="">All</label>
                    </div>
                    <?php
                    if ($post_types) { // If there are any custom public post types.
                        foreach ($post_types  as $post_type) { ?>
                        <div class="fma-chk">
                            <label for="<?php esc_attr_e($post_type) ?>">
                                <input type="checkbox" class="fma_chk fma_chk_js" name="fma_display_options[<?php esc_attr_e($args['name']) ?>][]" value="<?php esc_attr_e($post_type) ?>" <?php _e((in_array($post_type, $post_types_arr)) ? 'checked' : ''); ?>><?php esc_attr_e(ucwords($post_type)) ?>
                            </label>
                        </div>
                        <?php
                        }
                    }
                    ?>
                </div>
            <?php
    } // end checkbox_callback

    public function prevent_checkbox_callback($args)
    {
        $options = get_option('fma_display_options');
        $name = $args['name'];?>
        <div class="fma_chk_wp">
            <div class="fma-chk">
                <label for="fma_display_options[<?php esc_attr_e($name) ?>]">
                    <input type="checkbox" id="fma_display_options[<?php esc_attr_e($name) ?>]" name="fma_display_options[<?php esc_attr_e($name) ?>]" value="1" <?php _e((isset($options[$name]) && $options[$name] == '1') ? 'checked' : '') ?>/>
                </label>
            </div>
        </div>
        <?php
        if (isset($args['note'])) { ?>
                <p class="fsm_note"><?php esc_attr_e($args['note']) ?></p>
            <?php
        }
    } // end checkbox_callback

    public function validate_input_examples($input)
    {
    
        // Create our array for storing the validated options
        $new_input = array();
        if( isset( $input['general_option'] )  && !empty($input['general_option'])) {
            $title=implode(",",$input['general_option']);
            $new_input['general_option'] = sanitize_text_field($title);
        }
        if( isset( $input['post_types'] )  && !empty($input['post_types'])) {
            $title=implode(",",$input['post_types']);
            $new_input['post_types'] = sanitize_text_field($title);
        }
        else{
            $post_types = get_post_types(array('public' => true), 'names', 'and');
            unset($post_types['attachment']);
            $title = implode(",",$post_types);
            $new_input['post_types'] = sanitize_text_field($title);
        }

        if( isset( $input['prevent_media'] )  && !empty($input['prevent_media'])) {
            $new_input['prevent_media'] = sanitize_text_field($input['prevent_media']);
        }
        return $new_input;
    } // end validate_input_examples

    }
    ?>