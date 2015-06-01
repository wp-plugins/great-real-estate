<?php /* extracted from wp-admin/includes/meta-boxes.php */ ?>
<div class="gre-listing-publish-action">
    <span class="spinner"></span>
    <?php
    if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
        if ( $can_publish ) :
            if ( !empty($post->post_date_gmt) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) : ?>
            <input name="original_publish" type="hidden" value="<?php esc_attr_e('Schedule') ?>" />
            <?php submit_button( __( 'Schedule' ), 'primary button-large', 'publish', false ); ?>
    <?php   else : ?>
            <input name="original_publish" type="hidden" value="<?php esc_attr_e('Publish') ?>" />
            <?php submit_button( __( 'Publish' ), 'primary button-large', 'publish', false ); ?>
    <?php   endif;
        else : ?>
            <input name="original_publish" type="hidden" value="<?php esc_attr_e('Submit for Review') ?>" />
            <?php submit_button( __( 'Submit for Review' ), 'primary button-large', 'publish', false ); ?>
    <?php
        endif;
    } else { ?>
            <input name="original_publish" type="hidden" value="<?php esc_attr_e('Update') ?>" />
            <input name="save" type="submit" class="button button-primary button-large" id="publish" value="<?php esc_attr_e( 'Update' ) ?>" />
    <?php
    } ?>
</div>
