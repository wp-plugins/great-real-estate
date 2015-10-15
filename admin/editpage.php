<?php
/* Addition to Simple Edit in Admin screen for Pages
	# Note: anyone with edit privs can change things here
	# Sections: 
	# 	- Basic information
	# 		- Status (for sale, for rent, pending sale, pending lease, sold, rented)
	# 		- List Price
	#		- Sales Price (if sold)
	# 		- Listed Date
	# 		- Closed Date
	# 		- Price Change Date (reduced / increased)
	# 		- Listed by who? (choose Author or Someone else)
	# 	- gallery/video/maps information 
	# 		- select NextGen Gallery
	# 		- select 360 pics
	# 		- Geocode for Google Maps (latitude, longitude)
	# 		- select downloadable attachments
	# 		- Neighborhood / tags / links / etc
	#
#
# Changelog:
# [2008-07-27] 	Added tabindex to fields (starting at 101 to avoid conflicts)
  */
?>
<div id="pagepost-realestate" class="postbox">
<h3 class="hndle"><?php _e('Real Estate - Property Information','greatrealestate'); ?></h3>

<div class="inside">

<fieldset id="listings1-set">
    <legend><?php _e('Great Real Estate Controls','greatrealestate'); ?></legend>

    <div>
        <input tabindex="101" type="checkbox" name="listings_featured" id="listings_featured" value="featured" <?php echo ( isset( $listing->featured ) && $listing->featured == 'featured') ? 'checked="checked"' : ""; ?> />
        <label for="listings_featured"><?php echo __( 'Featured', 'greatrealestate' ); ?></label>
        <br>
        <em><?php echo __( 'Check this option to have the listing show up in the Featured Widget and above other listings in searches.', 'greatrealestate' ); ?></em>
    </div>
</fieldset>

<fieldset id="listings2-set">
    <legend><?php _e('Listing Information (Pricing and Sales Information)','greatrealestate'); ?></legend>

<div>

<p>
<select tabindex="102" name="listings_status" class="status-input required" 
id="listings_status">
<option value=""><?php _e('Select a Status','greatrealestate'); ?></option>
<?php re_status_dropdown( isset( $listing->status ) ? $listing->status : 1 ); ?></select>
<label for="listings_status" class="selectit"><?php _e('Property Status','greatrealestate'); ?></label>
</p>

<p>
<input tabindex="103" type="text" name="listings_listprice" class="price-input number" 
id="listings_listprice" size="10" value="<?php echo isset( $listing->listprice ) ? $listing->listprice : ''; ?>" />
<label for="listings_listprice"><?php _e('List Price','greatrealestate'); ?></label>

<input tabindex="104" type="text" name="listings_listdate" class="date-input date" 
id="listings_listdate" size="10" value="<?php echo get_listing_listdate(); ?>" />
<label for="listings_listdate"><?php _e('List Date','greatrealestate'); ?></label> 
<em><?php _e('mm/dd/yyyy E.G.: 04/01/2008','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="105" type="text" name="listings_saleprice" class="price-input number" 
id="listings_saleprice" size="10" value="<?php echo isset( $listing->saleprice ) ? $listing->saleprice : ''; ?>" />
<label for="listings_saleprice" class="selectit"><?php _e('Sale Price (if sold)','greatrealestate'); ?></label>

<input tabindex="106" type="text" name="listings_saledate" class="date-input date" 
id="listings_saledate" size="10" value="<?php echo get_listing_saledate(); ?>" />
<label for="listings_saledate" class="selectit"><?php _e('Sale Date (if sold)','greatrealestate'); ?></label> 
<em><?php _e('mm/dd/yyyy E.G.: 10/31/2008','greatrealestate'); ?></em>
</p>

<p><input tabindex="107" type="text" name="listings_blurb" class="blurb-input" 
id="listings_blurb" size="60" value="<?php echo isset( $listing->blurb ) ? $listing->blurb : ''; ?>" />
<label for="listings_blurb"><?php _e('Brief Blurb','greatrealestate'); ?></label>
<em><?php _e('e.g., "Nice 4BR home west of Lantana"','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="108" type="text" name="listings_address" 
id="listings_address" size="60" value="<?php echo isset( $listing->address ) ? $listing->address : ''; ?>" />
<label for="listings_address"><?php _e('Street Address','greatrealestate'); ?></label>
<em><?php _e('e.g., "123 Main St"','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="109" type="text" name="listings_city" 
id="listings_city" size="30" value="<?php echo isset( $listing->city ) ? $listing->city : ''; ?>" />
<label for="listings_city"><?php _e('City','greatrealestate'); ?></label>
<em><?php _e('e.g., "Anytown"','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="110" type="text" name="listings_state" 
id="listings_state" size="15" value="<?php echo isset( $listing->state ) ? $listing->state : ''; ?>" />
<label for="listings_state"><?php _e('State','greatrealestate'); ?></label>
<em><?php _e('2 letter abbreviation, e.g. FL for Florida','greatrealestate'); ?></em></p>

<p>
<input tabindex="111" type="text" name="listings_postcode" 
id="listings_postcode" size="5" value="<?php echo isset( $listing->postcode ) ? $listing->postcode : ''; ?>" />
<label for="listings_postcode"><?php _e('Zip Code','greatrealestate'); ?></label>
<em><?php _e('e.g., "33462"','greatrealestate'); ?></em>
</p>

<p><input tabindex="112" type="text" name="listings_mlsid" 
id="listings_mlsid" size="10" value="<?php echo isset( $listing->mlsid ) ? $listing->mlsid : ''; ?>" />
<label for="listings_mlsid"><?php _e('MLS ID','greatrealestate'); ?></label>
<em><?php _e ('The listing\'s MLS ID, e.g., "R2916712"','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="113" type="text" class="number" name="listings_bedrooms" 
id="listings_bedrooms" size="2" value="<?php echo isset( $listing->bedrooms ) ? $listing->bedrooms : ''; ?>" />
<label for="listings_bedrooms"><?php _e('Bedrooms','greatrealestate'); ?></label>&nbsp;

<input 
tabindex="114" type="text" class="number" name="listings_bathrooms" 
id="listings_bathrooms" size="2" value="<?php echo isset( $listing->bathrooms ) ? $listing->bathrooms : ''; ?>" />
<label for="listings_bathrooms"><?php _e('Full Baths','greatrealestate'); ?></label>&nbsp;

<input tabindex="115" type="text" class="number" name="listings_halfbaths" 
id="listings_halfbaths" size="2" value="<?php echo isset( $listing->halfbaths ) ? $listing->halfbaths : ''; ?>" />
<label for="listings_halfbaths"><?php _e('Half Baths','greatrealestate'); ?></label>&nbsp;

<input tabindex="116" type="text" class="number" name="listings_garage" 
id="listings_garage" size="2" value="<?php echo isset( $listing->garage ) ? $listing->garage : ''; ?>" />
<label for="listings_garage"><?php _e('Garage Spaces','greatrealestate'); ?></label>
</p>

<p>
<input tabindex="117" type="text" class="number" name="listings_acsf" 
id="listings_acsf" size="5" value="<?php echo isset( $listing->acsf ) ? $listing->acsf : ''; ?>" />
<label for="listings_acsf"><?php _e('Sqft (Living)','greatrealestate'); ?></label>&nbsp;

<input tabindex="118" type="text" class="number" name="listings_totsf" 
id="listings_totsf" size="5" value="<?php echo isset( $listing->totsf ) ? $listing->totsf : ''; ?>" />
<label for="listings_totsf"><?php _e('Sqft (Total)','greatrealestate'); ?></label>&nbsp;

<input tabindex="119" type="text" class="number" name="listings_acres" 
id="listings_acres" size="5" value="<?php echo isset( $listing->acres ) ? $listing->acres : ''; ?>" />
<label for="listings_acres"><?php _e('Acres','greatrealestate'); ?></label>
</p>

        <p>
            <?php
                $property_type = gre_get_listing_field( $post->ID, 'property-type', 'apartment' );

                echo gre_html_label( array(
                    'attributes' => array(
                        'for' => 'gre-listing-property-type',
                    ),
                    'text' => esc_html( __( 'Property Type', 'greatrealestate' ) ) . ':',
                ) );

                echo gre_html_select( array(
                    'attributes' => array(
                        'id' => 'gre-listing-property-type',
                        'name' => 'listing_property_type',
                        'tabindex' => 120,
                    ),
                    'options' => array(
                        'apartment' => esc_html( __( 'Apartment', 'greatrealestate' ) ),
                        'home' => esc_html( __( 'Home', 'greatrealestate' ) ),
                        'office' => esc_html( __( 'Office', 'greatrealestate' ) ),
                        'villa' => esc_html( __( 'Villa', 'greatrealestate' ) ),
                    ),
                    'selected' => $property_type,
                ) );
            ?>
        </p>

<p>
<select tabindex="120" name="listings_featureid[]" multiple="multiple" 
id="listings_featureid" style="height: 10em;" size="5">
<option value="0"><?php _e('Select Feature(s)','greatrealestate'); ?></option>
<?php get_listing_featuredropdown( isset( $listing->featureid ) ? $listing->featureid : '' ); ?>
</select>
<label for="listings_featureid"><?php _e('Features','greatrealestate'); ?></label>
<br />
<em><?php _e('Select one or more','greatrealestate'); ?></em>
</p>

</div>
</fieldset>

<fieldset id="listings3-div">
    <legend>
        <?php _e('Multimedia Content (Video, Photos, Brochures, etc)','greatrealestate'); ?>
    </legend>
<div>

<p>
<?php if ( class_exists('nggallery') || class_exists('nggGallery') ): ?>
    <select tabindex="121" id="listings_galleryid" name="listings_galleryid" class="gallery-input" >
        <option value=""><?php _e('Select a Gallery','greatrealestate'); ?></option>
        <?php get_listing_gallerydropdown( isset( $listing->galleryid ) ? $listing->galleryid : '' ); ?>
    </select>
    <label for="listings_galleryid"><?php _e('NextGen Gallery','greatrealestate'); ?></label>
    <a target="_blank" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=nggallery-manage-gallery" title="<?php _e('Leave page and manage galleries','greatrealestate'); ?>"><?php _e('Manage Galleries','greatrealestate'); ?></a>
<?php else : ?>
    <?php echo str_replace( '<a>',
                            '<a href="https://wordpress.org/plugins/nextgen-gallery/" target="_blank">',
                            __( '• Install <a>NextGen Gallery</a> to create image galleries for listings.', 'greatrealestate' ) );
    ?>
<?php endif; ?>
</p>

<div>
<?php if ( defined( 'PP_APP_NAME' ) ): ?>
    <h4><label for="listings_panoid"><?php _e('Panoramas','greatrealestate'); ?></label></h4>
    <p><?php _e( "Use the Add Media button at the top of the page to upload QTVR files. The panorama files selected in the dropdown below will be shown in the listing's page.", 'greatrealestate' ); ?></p>
    <select tabindex="124" id="listings_panoid" name="listings_panoid[]" multiple="multiple" style="height: 10em; width: 60%;" size="5">
        <!--<option value="0"><?php _e( 'Select Panorama(s)', 'greatrealestate' ); ?></option>-->
        <?php get_listing_panodropdown( isset( $listing->panoid ) ? $listing->panoid : ''); ?>
    </select>
<?php else: ?>
    <?php echo str_replace( '<a>',
                            '<a href="https://wordpress.org/plugins/panopress/" target="_blank">',
                            __( '• Install <a>PanoPress</a> to attach panoramic videos to your listings.', 'greatrealestate' ) );
    ?>
<?php endif; ?>
</div>

<div class="gre-edit-listing-location-fields gre-listing-section">
    <h4><?php _e( 'Location Map', 'greatrealestate' ); ?></h4>

    <p>
        <label for="listings_latitude"><?php _e('Latitude: ','greatrealestate'); ?></label>
        <input tabindex="125" id="listings_latitude" type="text" name="listings_latitude" class="geo-input" size="15" value="<?php echo isset( $listing->latitude ) ? $listing->latitude : ''; ?>" placeholder="26.123456" />
        <br>
        <label for="listings_longitude" class="selectit"><?php _e('Longitude:','greatrealestate'); ?></label>
        <input tabindex="126" id="listings_longitude" type="text" name="listings_longitude" class="geo-input" size="15" value="<?php echo isset( $listing->longitude ) ? $listing->longitude : ''; ?>" placeholder="-80.123456" />
    </p>
    <p>
        <?php echo __( 'The Lat/Long location of the marker that identifies this property on a Google Map. Use this Lat/Long location if the address isn’t accurately located on a Google Map automatically. Otherwise, leave it blank.', 'greatrealestate' ); ?>
    </p>
</div>

</div>

<?php
echo gre_render( GRE_FOLDER . 'admin/templates/downloads.tpl.php', array( 'listing_id' => get_the_ID(),
                                                                          'downloads' => gre_get_listing_downloads() ) );
?>
</fieldset>

<p><?php _e('This information about the property listing will be used for custom display and searching. You should provide as much information as possible.','greatrealestate'); ?><br />
<em><?php _e('If you did not intend for this page to be a property listing, change Page Parent (below) and save, and this section should disappear.','greatrealestate'); ?></em></p>

    <?php echo gre_edit_listing_submit_button(); ?>

</div>
</div>

