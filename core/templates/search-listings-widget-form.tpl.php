<p>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'greatrealestate' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $widget->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
</p>

<p>
    <input type="hidden" name="<?php echo esc_attr( $widget->get_field_name( 'show_min_price_field' ) ); ?>" value="0">
    <input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'show_min_price_field' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'show_min_price_field' ) ); ?>" value="1" <?php echo $instance['show_min_price_field'] ? 'checked="checked"' : ''; ?>>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'show_min_price_field' ) ); ?>"><?php _e( 'Show Min Price field', 'greatrealestate' ); ?></label>
</p>

<p>
    <input type="hidden" name="<?php echo esc_attr( $widget->get_field_name( 'show_max_price_field' ) ); ?>" value="0">
    <input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'show_max_price_field' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'show_max_price_field' ) ); ?>" value="1" <?php echo $instance['show_max_price_field'] ? 'checked="checked"' : ''; ?>>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'show_max_price_field' ) ); ?>"><?php _e( 'Show Max Price field', 'greatrealestate' ); ?></label>
</p>

<p>
    <input type="hidden" name="<?php echo esc_attr( $widget->get_field_name( 'show_bedrooms_field' ) ); ?>" value="0">
    <input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'show_bedrooms_field' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'show_bedrooms_field' ) ); ?>" value="1" <?php echo $instance['show_bedrooms_field'] ? 'checked="checked"' : ''; ?>>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'show_bedrooms_field' ) ); ?>"><?php _e( 'Show Bedrooms field', 'greatrealestate' ); ?></label>
</p>

<p>
    <input type="hidden" name="<?php echo esc_attr( $widget->get_field_name( 'show_bathrooms_field' ) ); ?>" value="0">
    <input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'show_bathrooms_field' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'show_bathrooms_field' ) ); ?>" value="1" <?php echo $instance['show_bathrooms_field'] ? 'checked="checked"' : ''; ?>>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'show_bathrooms_field' ) ); ?>"><?php _e( 'Show Bathrooms field', 'greatrealestate' ); ?></label>
</p>

<p>
    <input type="hidden" name="<?php echo esc_attr( $widget->get_field_name( 'show_property_status_field' ) ); ?>" value="0">
    <input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'show_property_status_field' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'show_property_status_field' ) ); ?>" value="1" <?php echo $instance['show_property_status_field'] ? 'checked="checked"' : ''; ?>>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'show_property_status_field' ) ); ?>"><?php _e( 'Show Property Status field', 'greatrealestate' ); ?></label>
</p>

<p>
    <input type="hidden" name="<?php echo esc_attr( $widget->get_field_name( 'show_property_type_field' ) ); ?>" value="0">
    <input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'show_property_type_field' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'show_property_type_field' ) ); ?>" value="1" <?php echo $instance['show_property_type_field'] ? 'checked="checked"' : ''; ?>>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'show_property_type_field' ) ); ?>"><?php _e( 'Show Property Type field', 'greatrealestate' ); ?></label>
</p>
