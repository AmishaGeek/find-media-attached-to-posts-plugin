<?php


/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://geekcodelab.com/
 * @since      1.0.0
 *
 * @package    Find_Media_Attached_To_Posts
 * @subpackage Find_Media_Attached_To_Posts/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Find_Media_Attached_To_Posts_Admin_Media_Column {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
    /** Add Field In Grid Mode */
    public function attachment_fields_to_edit( $form_fields,$attachment) {
        $screen = get_current_screen();
        if ( $screen->parent_base != 'upload' ) {
            $form_fields['used_in'] = array(
                'label' => __( 'Used In', $this->plugin_name ),
                'input' => 'html',
                'html'  => $this->get_posts_using_attachment($attachment->ID),
            );
        }
        return $form_fields;
	}

    /** Add Meta Box In List Mode Edit Screen */
    public function add_attachment_metaboxes() {
        add_meta_box( 'fma_attachment_metaboxes', __( 'Attachment Used In', $this->plugin_name ), array($this, 'custom_attachment_metaboxes_func'), 'attachment', 'side' );
    }
    /** Meta Box Calllback */
    function custom_attachment_metaboxes_func(){
        $attachment_id = get_the_ID();
        $html = $this->get_posts_using_attachment($attachment_id);
        _e($html,$this->plugin_name);
    }
    /** Add Column in List Mode */
	function manage_media_columns( $columns ) {
		$filtered_columns = array();

		foreach ( $columns as $key => $column ) {
			$filtered_columns[ $key ] = $column;

			if ( 'parent' === $key ) {
				$filtered_columns['used_in'] = __( 'Attachment Used In', $this->plugin_name );
			}
		}

		return $filtered_columns;
	}
    /** Manage Column */
    function manage_media_custom_column( $column_name) {
        $attachment_id = get_the_ID();

		switch ( $column_name ) {
			case 'used_in':
				echo $this->get_posts_using_attachment( $attachment_id);
				break;
		}
	}
    /** Prevent Delete Media In List Mode */
    function prevent_media_delete($attachment_id){
        $option = (!empty(get_option('fma_display_options'))) ? get_option('fma_display_options') : '' ;
        $prevent_media = (isset($option['prevent_media']) && !empty($option['prevent_media'])) ? $option['prevent_media'] : '';
        if ($prevent_media == 1) {            
            $html = $this->get_posts_using_attachment($attachment_id);
            if (!empty($html) && $html != '(Unused)') {
                wp_die( 'You cannot delete this attachment because its associated with following post/page <br>'.$html.'', $this->plugin_name );
            }
        }
    }
    /** Prevent Delete Media In Grid Mode */
    function fma_prevent_delete_attachment_func(){
        $attachment_id = $_POST['id'];
        if (empty($attachment_id)) {
            return;
        }

        if (!empty($attachment_id)) {
            $option = (!empty(get_option('fma_display_options'))) ? get_option('fma_display_options') : '' ;
            $prevent_media = (isset($option['prevent_media']) && !empty($option['prevent_media'])) ? $option['prevent_media'] : '';
            if ($prevent_media == 1) {    
                $html = $this->get_posts_using_attachment($attachment_id);
                if (!empty($html) && $html != '(Unused)') {
                    wp_send_json( array(
                        'result' => true,
                        'message' => 'You cannot delete this attachment because its associated with post/page.'
                    )); 
                   
                }
            }
        }
        else{
            wp_send_json( array(
                'result' => false,
                'message' => 'attachment Id not found.'
            ));
        }
       
        wp_die();
    }

    function get_posts_using_attachment( $attachment_id ) {
		$post_ids = $this->get_posts_by_attachment_id( $attachment_id );
		$output = '';

        if (isset($post_ids['featured_and_content_disable'])) {
            $output ='Please Check <a href="upload.php?page=fma-options">Settings</a>';
        }
        else{
            $posts = array_merge( $post_ids['thumbnail'], $post_ids['content'] );
            $posts = array_unique( $posts );   
    
            $item_format   = '<strong>%1$s</strong> %3$s<br />';
            $output_format = '%s';
            foreach ( $posts as $post_id ) {
                $post = get_post( $post_id );
                if ( ! $post ) {
                    continue;
                }
    
                $post_title = _draft_or_post_title( $post );
                $post_type  = get_post_type_object( $post->post_type );
                if ( $post_type && $post_type->show_ui && current_user_can( 'edit_post', $post_id ) ) {
                    $link = sprintf( '<a href="%s">%s</a>', get_edit_post_link( $post_id ), $post_title );
                } else {
                    $link = $post_title;
                }
                if ( in_array( $post_id, $post_ids['thumbnail'] ) && in_array( $post_id, $post_ids['content'] ) ) {
                    $usage_context = __( '(As Featured Image and In Content)', $this->plugin_name );
                } elseif ( in_array( $post_id, $post_ids['thumbnail'] ) ) {
                    $usage_context = __( '(As Featured Image)', $this->plugin_name );
                } else {
                    $usage_context = __( '(In content)', $this->plugin_name );
                }
    
                $output .= sprintf( $item_format, $link, get_the_time( __( 'Y/m/d', $this->plugin_name ) ), $usage_context );
            }
    
            if ( ! $output ) {
                $output = __( '(Unused)', $this->plugin_name );
            }
            $output = sprintf( $output_format, $output );
        }



		return $output;
	}
    
    function get_posts_by_attachment_id( $attachment_id ) {
		$used_as_thumbnail = array();
        $option = (!empty(get_option('fma_display_options'))) ? get_option('fma_display_options') : '' ;
        $general_option = (isset($option['general_option']) && !empty($option['general_option'])) ? explode(",",$option['general_option']) : [];
        $post_types_option  = (isset($option['post_types']) && !empty($option['post_types'])) ?  $option['post_types'] : 'any' ;
        $splittedPostType = explode(",", $post_types_option);

        if (in_array('featured_image',$general_option)) {
            if ( wp_attachment_is_image( $attachment_id ) ) {
                $thumbnail_query = new WP_Query( array(
                    'meta_key'       => '_thumbnail_id',
                    'meta_value'     => $attachment_id,
                    'post_type'      => $splittedPostType,	
                    'fields'         => 'ids',
                    'no_found_rows'  => true,
                    'posts_per_page' => -1,
                ) );
    
                $used_as_thumbnail = $thumbnail_query->posts;
            } 
        }
		
		$attachment_urls = array( wp_get_attachment_url( $attachment_id ) );

		if ( wp_attachment_is_image( $attachment_id ) ) {
			foreach ( get_intermediate_image_sizes() as $size ) {
				$intermediate = image_get_intermediate_size( $attachment_id, $size );
				if ( $intermediate ) {
					$attachment_urls[] = $intermediate['url'];
				}
			}
		}

        $used_in_content = array();
        if (in_array('in_content',$general_option)) {
            foreach ( $attachment_urls as $attachment_url ) {
                $content_query = new WP_Query( array(
                    's'              => $attachment_url,
                    'post_type'      => $splittedPostType,	
                    'fields'         => 'ids',
                    'no_found_rows'  => true,
                    'posts_per_page' => -1,
                ) );
    
                $used_in_content = array_merge( $used_in_content, $content_query->posts );
            }
            $used_in_content = array_unique( $used_in_content );
        }
        if (!in_array('featured_image',$general_option) && !in_array('in_content',$general_option)) {
            $posts = array(
                'featured_and_content_disable' => '1'
            );
        }
        else{

            $posts = array(
                'thumbnail' => $used_as_thumbnail,
                'content'   => $used_in_content,
            );
        }

		return $posts;
	
	}
}