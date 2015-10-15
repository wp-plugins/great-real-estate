    <div class="prop-box-avail gre-listings-list-item gre-clearfix">
        <div class="prop-thumb gre-listings-list-item-thumbnail"><?php the_listing_thumbnail(); ?></div>
        <div class="gre-listings-list-item-info">
            <h3 class="gre-listings-list-item-title">
                <a href="<?php the_permalink() ?>" title="<?php _e( 'More about ', 'greatrealestate' ); ?><?php the_title(); ?>"><?php the_title(); ?></a>
            </h3>

            <div class="gre-listing-status">
                <?php the_listing_status(); ?>
                <?php if ( get_listing_listprice() ) { _e( '- Offered at ', 'greatrealestate' ); the_listing_listprice(); } ?>
            </div>

            <div class="gre-listing-blurb"><?php the_listing_blurb(); ?></div>

            <?php
                $bedrooms = get_listing_bedrooms();
                $bathrooms = get_listing_bathrooms();
                $halfbaths = get_listing_halfbaths();
                $garage = get_listing_garage();

                $acsf = get_listing_acsf();
                $tsf = get_listing_totsf();
                $acres = get_listing_acres();

                $has_pool = get_listing_haspool();
                $has_waterfront = get_listing_haswater();
                $has_golf = get_listing_hasgolf();
                $has_condo = get_listing_hascondo();
                $has_town_home = get_listing_hastownhome();

                $first_line_attributes = array();

                if ( $bedrooms ) {
                    $first_line_attributes[] = $bedrooms . ' ' . __( 'Bedrooms', 'greatrealestate' );
                }

                if ( $bathrooms ) {
                    $first_line_attributes[] = $bathrooms . ' ' . __( 'Bathrooms', 'greatrealestate' );
                }

                if ( $halfbaths ) {
                    $first_line_attributes[] = $halfbaths . ' &amp; ' . __( 'Half Baths', 'greatrealestate' );
                }

                if ( $garage ) {
                    $first_line_attributes[] = $garage . ' ' . __( 'Garage Spaces', 'greatrealestate' );
                }

                $second_line_attributes = array();

                if ( $acsf ) {
                    $second_line_attributes[] = $acsf . ' ' . __( 'Sq/Ft Under Air', 'greatrealestate' );
                }

                if ( $tsf ) {
                    $second_line_attributes[] = $tsf . ' ' . __( 'Sq/Ft Total', 'greatrealestate' );
                }

                if ( $acres ) {
                    $second_line_attributes[] = $acres . ' ' . __( 'Acres', 'greatrealestate' );
                }

                $third_line_attributes = array();

                if ( $has_pool ) {
                    $third_line_attributes[] = __( 'Private Pool', 'greatrealestate' );
                }

                if ( $has_waterfront ) {
                    $third_line_attributes[] = __( 'Waterfront', 'greatrealestate' );
                }

                if ( $has_golf ) {
                    $third_line_attributes[] = __( 'On Golf Course', 'greatrealestate' );
                }

                if ( $has_condo ) {
                    $third_line_attributes[] = __( 'Condominium', 'greatrealestate' );
                }

                if ( $has_town_home ) {
                    $third_line_attributes[] = __( 'Townhome', 'greatrealestate' );
                }
            ?>

            <?php if ( ! empty( $first_line_attributes ) || ! empty( $second_line_attributes ) || ! empty( $third_line_attributes ) ): ?>
            <div class="propdata gre-listing-property-data">
                <?php if ( ! empty( $first_line_attributes ) ): ?>
                <div class="propdata-line">
                    <span><?php echo implode( '</span><span>', $first_line_attributes ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( ! empty( $second_line_attributes ) ): ?>
                <div class="propdata-line">
                    <span><?php echo implode( '</span><span>', $second_line_attributes ); ?></span>
                </div>
                <?php endif; ?>

                <?php if ( ! empty( $third_line_attributes ) ): ?>
                <div class="propdata-line propfeatures">
                    <span><?php echo implode( '</span><span>', $third_line_attributes ); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
