<div id="gre-listing-downloads" class="gre-listing-section" data-listing-id="<?php echo $listing_id; ?>">

<h4><?php _ex( 'Downloads', 'admin/listings', 'greatrealestate' ); ?></h4>

<p class="no-downloads-msg" style="<?php echo ( ! empty( $downloads ) ? 'display: none;' : '' ); ?>">
<?php _ex( 'No downloads.', 'admin/listings', 'greatrealestate' ); ?>
</p>

<ul class="downloads">
    <?php foreach ( $downloads as $d ): ?>
        <?php echo gre_render( GRE_FOLDER . 'admin/templates/downloads-item.tpl.php', array( 'download' => $d ) ); ?>
    <?php endforeach; ?>
</ul>

<a href="#TB_inline?width=200&height=200&inlineId=gre-listing-downloads-add" class="add-file button thickbox"><?php _ex( '↑ Upload File', 'admin/listings', 'greatrealestate' ); ?></a>
<div id="gre-listing-downloads-add" style="display: none;">
    <div class="gre-listing-downloads-add">
        <h3><?php _ex( 'Upload File', 'admin/listings', 'greatrealestate' ); ?></h3>

        <div>
            <label for="gre-listing-downloads-file">
                <?php _ex( 'File:', 'admin/listings', 'greatrealestate' ); ?>
            </label>

            <input type="file" id="gre-listing-downloads-file" data-url="<?php echo admin_url( 'admin-ajax.php?action=gre-listing-file-upload&listing_id=' . $listing_id ); ?>" />

            <span id="gre-listing-downloads-filename"></span>
        </div>

        <div>
            <label for="gre-listing-downloads-description">
                <?php _ex( 'Description:', 'admin/listings', 'greatrealestate' ); ?>
            </label>

            <input type="text" id="gre-listing-downloads-description" class="input-description" value="" />
        </div>

        <p>
            <input type="button" class="button-primary button upload-button" value="<?php _ex( 'Upload', 'admin/listings', 'greatrealestate' ); ?>" data-i18n-def="<?php _ex( 'Upload', 'admin/listings', 'greatrealestate' ); ?>" data-i18n-working="<?php _ex( 'Uploading...', 'admin/listings', 'greatrealestate' ); ?>" />
        </p>
    </div>
</div>

</div>
