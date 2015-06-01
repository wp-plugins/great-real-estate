<p>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'heading' ) ); ?>"><?php _e( 'Title:', 'greatrealestate' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $widget->get_field_id( 'heading' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'heading' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['heading'] ); ?>" />
</p>

<p>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'type' ) ); ?>"><?php _e( 'Layout: ', 'greatrealestate' ); ?></label>
    <select name="<?php echo esc_attr( $widget->get_field_name( 'type' ) ); ?>" id="<?php echo esc_attr( $widget->get_field_id( 'type' ) ); ?>" class="widefat">
        <option value="basic"<?php selected( $instance['type'], 'basic' ); ?>><?php _e( 'Thumbnail list', 'greatrealestate' ); ?></option>
        <option value="text"<?php selected( $instance['type'], 'text' ); ?>><?php _e( 'Text links', 'greatrealestate' ); ?></option>
    </select>
</p>

<p>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'numtoshow' ) ); ?>"><?php _e('Number of homes: ','greatrealestate'); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $widget->get_field_id( 'numtoshow' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'numtoshow' ) ); ?>" type="text" value="<?php echo $instance['numtoshow']; ?>" />
</p>

<p>
    <label for="<?php echo esc_attr( $widget->get_field_id( 'sort' ) ); ?>"><?php _e( 'Sort by:', 'greatrealestate' ); ?></label>
    <select name="<?php echo esc_attr( $widget->get_field_name( 'sort' ) ); ?>" id="<?php echo esc_attr( $widget->get_field_id( 'sort' ) ); ?>" class="widefat">
        <option value="title"<?php selected( $instance['sort'], 'title' ); ?>><?php _e( 'Title (alphabetically)', 'greatrealestate' ); ?></option>
        <option value="highprice"<?php selected( $instance['sort'], 'highprice' ); ?>><?php _e( 'Price (higher prices first)', 'greatrealestate' ); ?></option>
        <option value="lowprice"<?php selected( $instance['sort'], 'lowprice' ); ?>><?php _e( 'Price (lower prices first)', 'greatrealestate' ); ?></option>
        <option value="listdate"<?php selected( $instance['sort'], 'listdate' ); ?>><?php _e( 'List date (most recent on top)', 'greatrealestate' ); ?></option>
        <option value="random"<?php selected( $instance['sort'], 'random' ); ?>><?php _e( 'Random order', 'greatrealestate' ); ?></option>
    </select>
</p>
