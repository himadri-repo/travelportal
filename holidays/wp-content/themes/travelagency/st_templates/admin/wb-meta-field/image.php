<?php
/**
 * Created by travelagency.
 * Developer: nasanji
 * Date: 11/24/2017
 * Version: 1.0
 */


$old_data = esc_html( $data['std'] );

if(!empty($data['custom_name'])){
    if(isset($data['custom_data'])) $old_data=$data['custom_data'];
}else{
    $meta_data = get_post_meta( $post_id, esc_html( $data['id'] ), true);
    if(!empty($meta_data)){
        $old_data = $meta_data;
    }
}

$class = ' wpbooking-form-group ';
$data_class = '';
if(!empty($data['condition'])){
    $class .= ' wpbooking-condition ';
    $data_class .= ' data-condition='.$data['condition'].' ' ;
}

$data_class.=' width-'.$data['width'];

$name = isset( $data['custom_name'] ) ? esc_html( $data['custom_name'] ) : esc_html( $data['id'] );

$field = '<div class="st-metabox-content-wrapper wpbooking-settings"><div class="form-group">';

$field .= '<input type="text" class="fg_metadata" value="'. esc_html( $old_data ) .'" name="'. $name .'">
        <br>
        <div class="img-demo-upload">';
if( !empty( $old_data ) ){
    $field .= '<img src="'.$old_data.'" class="demo-image settings-demo-image" >';
}else{
    $class .= ' wpbooking-no-image ';
}

$field .= '</div>';

$field .= '<button id="" class="btn_upload_image mr10" type="button" name="">'. esc_html__("Add Image","travelagency").'</button>';

$field .= '<button class="btn_remove_demo_image_2 '.(!empty($old_data)?'':'hidden').'" type="button" name="">'.esc_html__("Delete","travelagency").'</button>';
$field .= '</div></div>';

?>
<div class="form-table wpbooking-settings <?php echo esc_html( $class ); ?> wpbooking-image" <?php echo esc_html( $data_class ); ?>>
    <div class="st-metabox-left">
        <label for="<?php echo esc_html( $data['id'] ); ?>"><?php echo esc_html( $data['label'] ); ?></label>
    </div>
    <div class="st-metabox-right">
        <?php echo do_shortcode($field); ?>
        <i class="wpbooking-desc"><?php echo do_shortcode( $data['desc'] ) ?></i>
    </div>
</div>