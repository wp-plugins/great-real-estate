<?php

if ( ! class_exists( 'WP_List_Table' ) && file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

function gre_listings_table() {
    return new GRE_Listings_Table();
}

class GRE_Listings_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct( array(
            'plural' => 'gre-listings',
            'singular' => 'gre-listing',
        ) );
    }

    public function prepare_items() {
        if ( isset( $_REQUEST['filter'] ) ) {
            $filter = sanitize_text_field( $_REQUEST['filter'] );
        } else {
            $filter = null;
        }

        $items_per_page = $this->get_items_per_page( 'gre-listings-table-items-per-page' );

        $query = array(
            'post_status' => null,
            'filter' => $filter,
            'orderby' => 'listdate',
            'offset' => ( $this->get_pagenum() - 1 ) * $items_per_page,
            'limit' => $items_per_page,
        );

        $this->items = gre_get_listings( $query );

        $this->set_pagination_args( array(
            'total_items' => gre_count_listings( $query ),
            'per_page' => $items_per_page,
        ) );

        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
    }

    protected function get_views() {
        return array(
            'active' => $this->get_view_link(
                __( 'Active', 'greatrealestate' ),
                add_query_arg( 'filter', 'active' ),
                gre_count_listings( array( 'post_status' => null, 'filter' => 'active' ) ),
                isset( $_REQUEST['filter'] ) && $_REQUEST['filter'] == 'active'
            ),
            'pending' => $this->get_view_link(
                __( 'Pending', 'greatrealestate' ),
                add_query_arg( 'filter', 'pending' ),
                gre_count_listings( array( 'post_status' => null, 'filter' => 'pending' ) ),
                isset( $_REQUEST['filter'] ) && $_REQUEST['filter'] == 'pending'
            ),
            'sold' => $this->get_view_link(
                __( 'Sold', 'greatrealestate' ),
                add_query_arg( 'filter', 'sold' ),
                gre_count_listings( array( 'post_status' => null, 'filter' => 'sold' ) ),
                isset( $_REQUEST['filter'] ) && $_REQUEST['filter'] == 'sold'
            ),
            'all' => $this->get_view_link(
                __( 'All', 'greatrealestate' ),
                add_query_arg( 'filter', null ),
                gre_count_listings( array( 'post_status' => null ) ),
                ! isset( $_REQUEST['filter'] ) || $_REQUEST['filter'] == 'all'
            ),
        );
    }

    private function get_view_link( $name, $url, $items_count = 0, $is_active = false ) {
        if ( $is_active ) {
            $template = '<a href="%s"><strong>%s</strong> <span class="count">(%d)</span></a>';
        } else {
            $template = '<a href="%s">%s <span class="count">(%d)</span></a>';
        }

        return sprintf( $template, esc_url( $url ), $name, $items_count );
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'featured' => __( 'Featured', 'greatrealestate' ),
            'thumbnail' => __( 'Thumbnail', 'greatrealestate' ),
            'title' => __( 'Title', 'greatrealestate' ),
            'status' => __( 'Status', 'greatrealestate' ),
            'listed' => __( 'Listed', 'greatrealestate' ),
            'sold' => __( 'Sold', 'greatrealestate' ),
        );
    }

    public function get_sortable_columns() {
        return array();
    }

    protected function get_table_classes() {
        return array_merge( parent::get_table_classes(), array( 'gre-listings-table' ) );
    }

    public function single_row( $item ) {
        setup_listingdata( $item );
        return parent::single_row( $item );
    }

    public function column_cb( $item ) {
        return '<input type="checkbox" value="' . $item->id . '" name="selected[]" />';
    }

    public function column_featured( $item ) {
        if ( $item->featured == 'featured' ) {
            echo '<span class="is-featured">' . __( 'Featured', 'greatrealestate' ) . '</span><br />';
        }

        echo '<span class="published-status">';
        if ( $item->post_status == 'publish' ) {
            echo __( 'Published', 'greatrealestate' );
        } else {
            echo __( 'Unpublished', 'greatrealestate' );
        }
        echo '</span><br />';

        echo '<a href="' . get_permalink( $item ) . '">' . __( 'View','greatrealestate' ) . '</a><br />';
        echo '<a href="' . get_option( 'wpurl' ) . "post.php?action=edit&amp;post=" . $item->ID . '">' . __( 'Edit','greatrealestate' ) . '</a>';
    }

    public function column_thumbnail( $item ) {
        if ( $item->galleryid ) {
            the_listing_thumbnail();
        } else {
            # TODO - link to "No Picture" gif
            if ( class_exists( 'nggallery' ) || class_exists( 'nggGallery' ) ) {
                printf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=ngg_addgallery' ), __( 'Create a Gallery', 'greatrealestate' ) );
            }
        }
    }

    public function column_title( $item ) {
        return $item->post_title;
    }

    public function column_status( $item ) {
        the_listing_status();
    }

    public function column_listed( $item ) {
        the_listing_listdate();
        echo "<br />";
        the_listing_listprice();
    }

    public function column_sold( $item ) {
        the_listing_saledate();
        echo "<br />";
        the_listing_saleprice();
    }
}
