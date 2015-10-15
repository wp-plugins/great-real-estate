<?php

class GRE_Search_Listings_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'gre-search-listings',
            __( 'Search Listings (Great Real Estate)', 'greatrealestate' ),
            array(
                'description' => __( 'Search Real Estate Listings', 'greatrealestate' ),
                'classname' => 'gre-search-listings-widget',
            )
        );
    }

    public function widget( $args, $instance ) {
        extract( $args );

        echo $before_widget;

        // do not show empty titles
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo ! empty( $title ) ? $before_title . $title . $after_title : '';

        echo '<div class="widget gre-search-listings-widget">';
        echo gre_search_listings_form()->render( gre_get_search_listings_page_url(), $instance, array() );
        echo '</div>';

        echo $after_widget;
    }

    public function form( $instance ) {
        $instance = wp_parse_args( $instance, array(
            'title' => __( 'Search Listings', 'greatrealestate' ),
            'show_min_price_field' => true,
            'show_max_price_field' => true,
            'show_bedrooms_field' => true,
            'show_bathrooms_field' => true,
            'show_property_status_field' => true,
            'show_property_type_field' => true,
        ) );

        $template = GRE_FOLDER . 'core/templates/search-listings-widget-form.tpl.php';
        $params = array( 'widget' => $this, 'instance' => $instance );

        echo gre_render_template( $template, $params );
    }

    public function update( $new_instance, $old_instance ) {
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['show_min_price_field'] = absint( $new_instance['show_min_price_field'] );
        $instance['show_max_price_field'] = absint( $new_instance['show_max_price_field'] );
        $instance['show_bedrooms_field'] = absint( $new_instance['show_bedrooms_field'] );
        $instance['show_bathrooms_field'] = absint( $new_instance['show_bathrooms_field'] );
        $instance['show_property_status_field'] = absint( $new_instance['show_property_status_field'] );
        $instance['show_property_type_field'] = absint( $new_instance['show_property_type_field'] );

        return $instance;
    }
}
