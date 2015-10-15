<form method="GET" class="gre-search-listings-form" action="<?php echo esc_url( $url ); ?>">
    <?php if ( $options['show_min_price_field'] ): ?>
    <div class="gre-search-listings-form-min-price-field">
        <label for=""><?php echo __( 'Min Price', 'greatrealestate' ); ?></label>
        <input type="text" class="field" name="gre_min_price" value="<?php echo esc_attr( $form['gre_min_price'] ); ?>">
    </div>
    <?php endif; ?>

    <?php if ( $options['show_max_price_field'] ): ?>
    <div class="gre-search-listings-form-max-price-field">
        <label for=""><?php echo __( 'Max Price', 'greatrealestate' ); ?></label>
        <input type="text" class="field" name="gre_max_price" value="<?php echo esc_attr( $form['gre_max_price'] ); ?>">
    </div>
    <?php endif; ?>

    <?php if ( $options['show_bedrooms_field'] ): ?>
    <div class="gre-search-listings-form-bedrooms-field">
        <label for=""><?php echo __( 'Beds', 'greatrealestate' ); ?></label>
        <input type="text" class="field" name="gre_bedrooms" value="<?php echo esc_attr( $form['gre_bedrooms'] ); ?>">
    </div>
    <?php endif; ?>

    <?php if ( $options['show_bathrooms_field'] ): ?>
    <div class="gre-search-listings-form-bathrooms-field">
        <label for=""><?php echo __( 'Baths', 'greatrealestate' ); ?></label>
        <input type="text" class="field" name="gre_bathrooms" value="<?php echo esc_attr( $form['gre_bathrooms'] ); ?>">
    </div>
    <?php endif; ?>

    <?php if ( $options['show_property_status_field'] ): ?>
    <div class="gre-search-listings-form-property-status-field">
        <label for=""><?php echo __( 'Property Status', 'greatrealestate' ); ?></label>
        <?php
            $property_statuses = array(
                'any' => _x( 'Any', 'property status', 'greatrealestate' ),
                'for-sale' => __( 'For Sale', 'greatrealestate' ),
                'pending-sale' => __( 'Sale Pending', 'greatrealestate' ),
                'sold' => __( 'Sold', 'greatrealestate' ),
                'for-rent' => __( 'For Rent', 'greatrealestate' ),
                'pending-lease' => __( 'Lease Pending', 'greatrealestate' ),
                'rented' => __( 'Rented', 'greatrealestate' ),
            );
        ?>
        <select name="gre_property_status">
        <?php foreach ( $property_statuses as $value => $label ): ?>
            <option value="<?php echo esc_attr( $value ); ?>"<?php echo $form['gre_property_status'] == $value ? 'selected="selected"' : ''; ?>><?php echo esc_html( $label ); ?></option>
        <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>

    <?php if ( $options['show_property_type_field'] ): ?>
    <div class="gre-search-listings-form-property-type-field">
    <label for=""><?php echo __( 'Property Type', 'greatrealestate' ); ?></label>
    <?php
        $property_statuses = array(
            'any' => _x( 'Any', 'property type', 'greatrealestate' ),
            'office' => __( 'Office', 'greatrealestate' ),
            'apartment' => __( 'Apartment', 'greatrealestate' ),
            'villa' => __( 'Villa', 'greatrealestate' ),
            'home' => __( 'Home', 'greatrealestate' ),
        );
    ?>
    <select name="gre_property_type">
    <?php foreach ( $property_statuses as $value => $label ): ?>
        <option value="<?php echo esc_attr( $value ); ?>"<?php echo $form['gre_property_type'] == $value ? 'selected="selected"' : ''; ?>><?php echo esc_html( $label ); ?></option>
    <?php endforeach; ?>
    </select>
    </div>
    <?php endif; ?>

    <input type="hidden" name="gre_search_listings" value="1">

    <div class="gre-search-listings-form-submit-button">
        <input type="submit" class="submit" value="<?php echo esc_attr( __( 'Search Listings', 'greatrealestate' ) ); ?>">
    </div>
</form>
