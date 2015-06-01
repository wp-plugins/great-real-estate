<?php

function gre_edit_listing_submit_button() {
    global $post;

    $post_type = $post->post_type;
    $post_type_object = get_post_type_object( $post_type );

    $params = array(
        'post' => $post,
        'can_publish' => current_user_can( $post_type_object->cap->publish_posts ),
    );

    $template = GRE_FOLDER . 'admin/templates/edit-listing-submit-button.tpl.php';

    return gre_render_template( $template, $params );
}
