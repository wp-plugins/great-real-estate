<?php

class GRE_Listings_Widget extends WP_Widget {

    public function widget( $args, $instance ) {
        extract( $this->sanitize_args( $args ), EXTR_SKIP );

        echo $before_widget;

        // do not show empty titles
        $title = apply_filters( 'widget_title', $instance['heading'] );
        echo !empty( $title ) ? $before_title . $title . $after_title : '';

        $listings = $this->get_listings( $instance['numtoshow'], $instance['sort'] );

        echo '<div class="widget featuredlistings-widget">';
        echo $this->render_listings( $listings, $instance['type'] );
        echo '</div>';

        echo $after_widget;
    }

    private function sanitize_args( $args ) {
        if ( is_numeric( $args ) ) {
            $args = array( 'number' => $args );
        }

        return wp_parse_args( $args, array( 'number' => -1 ) );
    }

    protected function get_listings( $count, $orderby ) {
        return array();
    }

    private function render_listings( $listings, $layout ) {
        $params = array(
            'listings' => $listings,
            'layout' => $layout,
        );

        $template = GRE_FOLDER . 'core/templates/listings-widget.tpl.php';

        return gre_render_template( $template, $params );
    }

    public function form( $instance ) {
        $instance = wp_parse_args( $instance, array(
            'numtoshow' => '',
            'heading' => '',
            'type' => '',
            'sort' => '',
        ) );

        $template = GRE_FOLDER . 'core/templates/featured-listings-widget-form.tpl.php';
        $params = array( 'widget' => $this, 'instance' => $instance );

        echo gre_render_template( $template, $params );
    }

    public function update( $new_instance, $old_instance ) {
        $instance[ 'numtoshow' ] = absint( $new_instance['numtoshow'] );
        $instance[ 'heading' ] = sanitize_text_field( $new_instance['heading'] );
        $instance[ 'sort' ] = sanitize_text_field( $new_instance['sort'] );
        $instance[ 'type' ] = sanitize_text_field( $new_instance['type'] );

        if ( ! in_array( $instance['sort'] , array( 'random', 'highprice', 'lowprice', 'title', 'listdate' ) ) ) {
            $instance['sort'] = 'random';
        }

        if ( ! in_array( $instance['type'], array( 'basic', 'text' ) ) ) {
            $instance['type'] = 'basic';
        }

        return $instance;
    }
}
