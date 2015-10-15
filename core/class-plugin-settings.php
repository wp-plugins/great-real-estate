<?php

class GRE_Plugin_Settings {

    public function register_settings( $settings ) {
        $group = $settings->add_group( 'general', __( 'General', 'greatrealestate' ) );

        $section = $settings->add_section( $group, 'listings-features', __( 'Listings Features', 'greatrealestate' ) );

        $settings->add_setting(
            $section,
            'pageforlistings',
            __( 'Select the Page to be used as the index to your Listings', 'greatrealestate' ),
            'custom',
            null, // default value
            __( 'You will need to make all your Listings Pages subpages of this Page', 'greatrealestate' ),
            array(),
            null,
            array( $this, 'render_pages_dropdown' )
        );

        $settings->add_setting(
            $section,
            'active-listings-title',
            __( 'Active Listings Title', 'greatrealestate' ),
            'text',
            __( 'My Active Listings', 'greatrealestate' ),
            ''
        );

        $settings->add_setting(
            $section,
            'pending-sale-listings-title',
            __( 'Pending Sale Listings Title', 'greatrealestate' ),
            'text',
            __( 'Pending Sale', 'greatrealestate' ),
            ''
        );

        $settings->add_setting(
            $section,
            'sold-listings-title',
            __( 'Sold Listings Title', 'greatrealestate' ),
            'text',
            __( 'Sold or Leased', 'greatrealestate' ),
            ''
        );

        $settings->add_setting(
            $section,
            'genindex',
            __( 'Listings Summary', 'greatrealestate' ),
            'boolean',
            true,
            __( 'Check to generate a default summary of your listings on your Listings Page (uncheck if you are using a custom template for your index page)', 'greatrealestate' )
        );

        $settings->add_setting(
            $section,
            'genlistpage',
            __('Individual Listings Pages','greatrealestate'),
            'boolean',
            true,
            __('Check to generate the default tabbed interface on each Listing Page (uncheck if you are using a custom template for your listings)','greatrealestate')
        );

        $settings->add_setting(
            $section,
            'default-images-display-type',
            __( 'Show gallery of pictures or a slideshow when the user first visits the listing?', 'greatrealestate' ),
            'choice',
            'gallery',
            '',
            array(
                'choices' => array(
                    array( 'gallery', __( 'Gallery', 'greatrealestate' ) ),
                    array( 'slideshow', __( 'Slideshow', 'greatrealestate' ) ),
                ),
            )
        );

        // size => 5
        $settings->add_setting(
            $section,
            'maxfeatured',
            __( 'Maximum Listings Featured', 'greatrealestate' ),
            'text',
            5,
            __( 'The default maximum number of featured listings, if not specified elsewhere', 'greatrealestate' )
        );

        $this->register_feeds_settings( $settings );

        $group = $settings->add_group( 'advanced', __( 'Advanced', 'greatrealestate' ) );

        $section = $settings->add_section( $group, 'presentation', __( 'Presentation', 'greatrealestate' ) );

        $settings->add_setting(
            $section,
            'usecss',
            __( 'Include default stylesheet for listings pages', 'greatrealestate' ),
            'boolean',
            true,
            __( "If this setting is checked, the plugin's default stylesheets for listings index page and individual listings pages will be included in frontend pages. Uncheck if your theme already includes fully customized stylesheets (CSS rules) for the listings directory.", 'greatrealestate' )
        );

        $settings->add_setting(
            $section,
            'nobrand',
            __('Enable "No Branding" option','greatrealestate'),
            'boolean',
            false,
            __('Check to allow <code>/nobrand/something/</code> to be added to a Page URL to remove navigation (Please copy <code>nobrand.css</code> to your theme directory and customize it)','greatrealestate')
        );

        $section = $settings->add_section( $group, 'google-maps', __( 'Google Maps', 'greatrealestate' ) );

        // size => 80
        $settings->add_setting(
            $section,
            'googleAPIkey',
            __( 'Google API Key','greatrealestate' ),
            'text',
            '',
            __( 'Optional. By default, Google will allow up to 100 requests for map data per day. Paste your domain\'s <a title="Get a Google API key" href="https://developers.google.com/maps/signup">Google API key</a> here to allow for higher limits or monitoring of the API.', 'greatrealestate' )
        );
    }

    public function render_pages_dropdown( $setting, $value ) {
        echo wp_dropdown_pages( array(
            'name' => 'greatrealestate_pageforlistings',
            'echo' => 0,
            'show_option_none' => __( '- Select -', 'greatrealestate' ),
            'selected' => $value,
        ) );

        echo '<span class="description">' . $setting->help_text . '</span>';
    }

    private function register_feeds_settings( $settings ) {
        $group = $settings->add_group( 'listing-feeds', __( 'Listings Feeds', 'greatrealestate' ) );

        $description = __( 'Google Base feed is no longer supported because <google-link>Google no longer allows promotion of immovable properties</a>.', 'greatrealestate' );
        $description = str_replace( '<google-link>', '<a href="https://support.google.com/merchants/answer/2731539?hl=en#immovable">', $description );

        $section = $settings->add_section( $group, 'feeds', __( 'Feeds', 'greatrealestate' ), $description );

        $description =  __( 'Follow <instructions-link>Trulia instructions</a> to submit your listings feed to their service. Feed URL: <feed-link>.', 'greatrealestate' );
        $description = str_replace( '<feed-link>', sprintf( '<a href="%1$s">%1$s</a>', esc_url( home_url( '/feed/trulia' ) ) ), $description );
        $description = str_replace( '<instructions-link>', '<a href="http://www.trulia.com/submit_listings/">', $description );

        $settings->add_setting(
            $section,
            'enable-trulia-feed',
            __( 'Enable Trulia feed', 'greatrealestate' ),
            'boolean',
            false,
           $description
        );

        $description =  __( 'Follow <instructions-link>these instructions</a> to add a feed of your listings to your My Yahoo! account. Feed URL: <feed-link>.', 'greatrealestate' );
        $description = str_replace( '<feed-link>', sprintf( '<a href="%1$s">%1$s</a>', esc_url( home_url( '/feed/yahoomedia' ) ) ), $description );
        $description = str_replace( '<instructions-link>', '<a href="https://help.yahoo.com/kb/my-yahoo/add-rss-feeds-sln4558.html">', $description );

        $settings->add_setting(
            $section,
            'enable-yahoomedia-feed',
            __( 'Enable Yahoo! Media feed', 'greatrealestate' ),
            'boolean',
            false,
           $description
        );

        $description =  __( 'Follow <instructions-link>Zillow instructions</a> to submit your feed to their service. Feed URL: <feed-link>.', 'greatrealestate' );
        $description = str_replace( '<feed-link>', sprintf( '<a href="%1$s">%1$s</a>', esc_url( home_url( '/feed/zillow' ) ) ), $description );
        $description = str_replace( '<instructions-link>', '<a href="http://www.zillow.com/feeds/Feeds.htm">', $description );

        $settings->add_setting(
            $section,
            'enable-zillow-feed',
            __( 'Enable Zillow feed', 'greatrealestate' ),
            'boolean',
            false,
           $description
        );

        $settings->add_setting(
            $section,
            'enable-googlemaps-feed',
            __( 'Enable Google Maps feed', 'greatrealestate' ),
            'boolean',
            false,
           __( 'This feed is used to render the listings in the Google Maps shown in your website. There is no need to post this feed elsewhere, the plugin already knows its URL.', 'greatrealestate' )
        );

        $section = $settings->add_section( $group, 'feed-attributes', __( 'Feed Attributes', 'greatrealestate' ), '' );

        // size => 60
        $settings->add_setting(
            $section,
            'listfeedtitle',
            __( 'Feed Title','greatrealestate' ),
            'text',
            __( 'Listings', 'greatrealestate' ),
            __( 'e.g.: John Smith Listings, John Smith Homes For Sale, etc.','greatrealestate')
        );

        // size => 80
        $settings->add_setting(
            $section,
            'listfeeddesc',
            __( 'Description','greatrealestate' ),
            'text',
            '',
            __('A snappy summary of what is in the listings feed','greatrealestate')
        );

        $settings->add_setting(
            $section,
            'broker',
            __( 'Brokerage Name', 'greatrealestate' ),
            'text',
            '',
            __( 'The name of the real estate brokerage holding the listings', 'greatrealestate' )
        );

        $settings->add_setting(
            $section,
            'agent',
            __( 'Agent Name','greatrealestate'),
            'text',
            '',
            __('The name of the listing real estate agent','greatrealestate')
        );

        $settings->add_setting(
            $section,
            'agentphone',
            __( 'Agent Phone', 'greatrealestate' ),
            'text',
            '',
            __( 'The listing real estate agent\'s phone number', 'greatrealestate' )
        );

        $settings->add_setting(
            $section,
            'mls',
            __( 'MLS Name', 'greatrealestate' ),
            'text',
            '',
            __('The name of the Multiple Listing Service','greatrealestate')
        );
    }
}
