<?php 

class Magic_Metaboxes {
    
    public static $metaboxes;

    /**
     * @return void
     */
    public function init()
    {
        //self::$metaboxes = apply_filters('magic_metaboxes', self::$metaboxes);
        add_action('add_meta_boxes', array(__CLASS__, 'metaboxes'));
        add_action('save_post', array(__CLASS__, 'save'));
    }

    /**
     * metabox - Adds the large metabox to the post edit form.
     *
     * Edited by Eddie Moya to include the metabox in pages and products.
     * 
     * @return void
     */
    
    public function add_metabox($metabox_name, $metabox){
        self::$metaboxes[$metabox_name] = $metabox;
    }
    
    public function metaboxes()
    {
       
        
        foreach((array) self::$metaboxes as $metabox){
            
            foreach( (array) $metabox['post_type'] as $post_type ){
                add_meta_box($metabox['metabox_slug'], $metabox['metabox_title'], array(__CLASS__, 'metabox_fields'), $post_type, 'side', 'low', $metabox );
            }
        }
    }

    /**
     *
     * @param object $post = NULL
     * @return void
     */
    public function metabox_fields($post, $metabox)
    {
        $mb = $metabox['args'];
        //print_pre(get_post_custom($post->ID));
        
      foreach((array)$mb['fields'] as $field){
          
          $id = $mb['metabox_slug'] . '-' . $field['id'];
          
          $is_checkbox = ($field['type'] == 'checkbox');
          $val = get_post_meta($post->ID, $id, true);
          $value = ($is_checkbox) ? checked(!empty($val), true, false ) : 'value="' . esc_attr(get_post_meta($post->ID, $id, true)) .'"';
          
          ?>
            <p>
              
            <?php if (isset($field['label'])) : ?>
              
                <label for="<?php echo $id; ?> <?php if($is_checkbox) echo "class='selectit'"; ?>">
                    <?php echo $field['label']; ?>
                </label>
              
            <?php endif; ?>
              
            <input type="<?php echo $field['type']; ?>" id="<?php echo $id; ?>" <?php echo $value; ?> name="<?php echo $id; ?>" size="25"/>
            </p>
          <?php
      }
      
    }
    
    function save( $post_id ) {
        
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;
      
      //print_r($_POST);

      $metaboxes = self::$metaboxes;
      
      foreach(  $metaboxes as $metabox){
          foreach((array) $metabox['fields'] as $field){
            $id = $metabox['metabox_slug'] . '-' . $field['id'];
            $value = $_POST[$id];
            update_post_meta($post_id, $id, $value); 
          }
          
      }
    }

}

Magic_Metaboxes::init();


function get_magic_metabox($metabox_name, $post_id) {
    global $post;

    //$custom = get_post_custom($post_id);
    $metaboxes = Magic_Metaboxes::$metaboxes;
    $metabox = $metaboxes[$metabox_name];
    
    $meta = get_post_custom();
//    $values = array();
////    
//    foreach ((array)$metabox['fields'] as $field) {
//        $field_id = $metabox['metabox_slug'] . '-' . $field['id'];
//        $values[$field['id']] = get_post_meta($post->ID, $field_id, true);
//    }

    return $meta;
}