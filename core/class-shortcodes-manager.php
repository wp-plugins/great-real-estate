<?php

function gre_shortcodes_manager() {
    return new GRE_Shortcodes_Manager();
}

class GRE_Shortcodes_Manager {

    private $shortcodes = array();

    public function add_shortcode( $tag, $handler_constructor ) {
        if ( ! isset( $shortcodes[ $tag ] ) ) {
            add_shortcode( $tag, array( $this, 'do_shortcode' ) );
        }

        $this->shortcodes[ $tag ] = $handler_constructor;
    }

    public function do_shortcode( $atts, $extra, $tag ) {
        if ( ! isset( $this->shortcodes[ $tag ] ) || ! is_callable( $this->shortcodes[ $tag ] ) ) {
            return '';
        }

        $handler = call_user_func( $this->shortcodes[ $tag ] );

        if ( ! is_object( $handler ) ) {
            return '';
        }

        return $handler->do_shortcode( $atts, $extra, $tag );
    }
}
