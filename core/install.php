<?php
register_activation_hook( __FILE__, 'greatrealestate_activate' );
function greatrealestate_activate( ) {
	# add option defaults
	add_option( 'greatrealestate_maxfeatured', '5' );
	add_option( 'greatrealestate_usecss', 'true' );
	add_option( 'greatrealestate_nobrand', 'false' );
	# add database files if not there
}

/*
 * Database initialization 
 */

class GRE_Installer {

    const DB_VERSION = 2;

    private $installed_version = 0;
    private $block = false;


    function __construct() {
        $this->installed_version = get_option( 'greatrealestate_db_version' );
    }

    function install() {
        if ( $this->installed_version != self::DB_VERSION )
            $this->update_database_schema();

        if ( $this->installed_version ) {
            $updater = new GRE_Database_Updater( $this->installed_version, self::DB_VERSION );
            $updater->update();

            if ( $updater->in_manual_update() ) {
                $this->block = true;
                $updater->manual_update_setup();
                return;
            }
        } else {
            $this->first_setup();
        }

        update_option( 'greatrealestate_db_version', self::DB_VERSION );
    }

    function first_setup() {
    }

    // {{ DB schema.

    function get_database_schema() {
        global $wpdb;

        $schema = array();

        $schema['listings'] = "CREATE TABLE {$wpdb->prefix}greatrealestate_listings (
			id mediumint NOT NULL AUTO_INCREMENT,
			pageid bigint NOT NULL,
			address VARCHAR(100),
			city VARCHAR(50),
			state VARCHAR(40),
			postcode VARCHAR(10),
			mlsid VARCHAR(15),
			status tinyint NOT NULL,
			blurb VARCHAR(255),
			bedrooms VARCHAR(10),
			bathrooms VARCHAR(10),
			halfbaths VARCHAR(10),
			garage VARCHAR(10),
			acsf VARCHAR(10),
			totsf VARCHAR(10),
			acres VARCHAR(10),
			featureid VARCHAR(30),
			listprice int NOT NULL,
			saleprice int,
			listdate date,
			saledate date,
			galleryid VARCHAR(30),
			videoid VARCHAR(30),
			downloadid VARCHAR(30),
			panoid VARCHAR(30),
			latitude VARCHAR(20),
			longitude VARCHAR(20),
			featured VARCHAR(30),
			agentid VARCHAR(20),
			UNIQUE KEY id (id)
		);";

        return $schema;
    }

    function update_database_schema() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        gres_log( 'Updating database schema...' );

        $schema = $this->get_database_schema();

        foreach ( $schema as $table_sql )
            dbDelta( $table_sql );
    }

    // }}
}

class GRE_Database_Updater {
    
    const OK = 1;
    const DONE = 2;


    private $installed_version = 0;
    private $current_version = 0;


    function __construct( $installed_version, $current_version ) {
        $this->installed_version = absint( $installed_version );
        $this->current_version = $current_version;
    }

    function request_manual_update( $v ) {
        update_option( 'gre-manual-update-pending', $v );
    }

    function in_manual_update() {
        return ( false != get_option( 'gre-manual-update-pending', false ) );
    }

    function manual_update_setup() {
        $version = absint( get_option( 'gre-manual-update-pending', false ) );

        if ( ! $version ) {
            delete_option( 'gre-manual-update-pending' );
            return;
        }

        add_action( 'admin_notices', array( &$this, 'manual_update_notice' ) );
        add_action( 'admin_menu', array( &$this, 'manual_update_add_page' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'manual_update_scripts' ) );
        add_action( 'wp_ajax_gre-upgrade-migration', array( &$this, 'manual_update_ajax' ) );
    }

    function manual_update_notice() {
        global $pagenow;

        if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'gre-migrate-page' == $_GET['page'] )
            return;

        print '<div class="error"><p>';
        print '<strong>' . __( 'Great Real Estate Plugin - Manual Migration Required', 'greatrealestate' ) . '</strong>';
        print '<br />';
        _e( 'Great Real Estate features are currently disabled because the plugin needs to perform a manual migration before continuing.', 'greatrealestate' );
        print '<br /><br />';
        printf( '<a class="button button-primary" href="%s">%s</a>', admin_url( 'admin.php?page=gre-migrate-page' ), __( 'Perform Migration', 'greatrealestate' ) );
        print '</p></div>';
    }

    function manual_update_scripts() {
        wp_enqueue_script( 'gre-manual-update' , GRE_URL . 'admin/js/manual-update.js' );
    }


    function manual_update_add_page() {
        add_submenu_page( 'options.php',
                          __( 'Great Real Estate - Database Migration', 'greatrealestate' ),
                          __( 'Great Real Estate - Database Migration', 'greatrealestate' ),
                          'administrator',
                          'gre-migrate-page',
                          array( &$this, 'manual_update_page' ) );
    }

    function manual_update_page() {
        echo '<div class="wrap gre-admin-migrate-page">';
        echo '<div id="icon-edit-pages" class="icon32"></div>';
        echo '<h2>' . __( 'Great Real Estate - Database Migration', 'greatrealestate' ) . '</h2>';

        echo '<div class="step-upgrade">';
        echo '<p>';
        _e( 'Great Real Estate features are currently disabled because the plugin needs to perform a manual upgrade before it can be used.', 'greatrealestate' );
        echo '<br />';
        _e( 'Click "Start Migration" and wait until the process finishes.', 'greatrealestate' );
        echo '</p>';
        echo '<p>';
        echo '<a href="#" class="start-upgrade button button-primary">' . _x( 'Start Migration', 'manual-upgrade', 'greatrealestate' ) . '</a>';
        echo ' ';
        echo '<a href="#" class="pause-upgrade button">' . _x( 'Pause Migration', 'manual-upgrade', 'greatrealestate' ) . '</a>';
        echo '</p>';
        echo '<textarea id="manual-upgrade-progress" rows="20" style="width: 90%; font-family: courier, monospaced; font-size: 12px;" readonly="readonly"></textarea>';
        echo '</div>';

        echo '<div class="step-done" style="display: none;">';
        echo '<p>' . _x( 'The migration was sucessfully performed. Great Real Estate can be used now.', 'manual-upgrade', 'greatrealestate' ) . '</p>';
        printf ( '<a href="%s" class="button button-primary">%s</a>',
                 admin_url( 'admin.php?page=great-real-estate' ),
                 _x( 'Go to "Great Real Estate admin"', 'manual-upgrade', 'greatrealestate' ) );
        echo '</div>';


        echo '<br class="clear" />';
        echo '</div>';
    }

    public function manual_update_ajax() {
        if ( ! current_user_can( 'administrator' ) )
            return;

        $callback_version = get_option( 'gre-manual-update-pending', false );

        if ( ! $callback_version )
            delete_option( 'gre-manual-update-pending' );

        $response = call_user_func( array( &$this, 'update_to_' . $callback_version ), false );

        if ( $response['done'] )
            delete_option( 'gre-manual-update-pending' );

        echo json_encode( $response );
        exit();
    } 

    function update() {
        if ( $this->installed_version == $this->current_version )
            return;

        if ( $this->in_manual_update() )
            return;

        for ( $n = $this->installed_version + 1; $n <= $this->current_version; $n++ ) {
            $routine = array( &$this, 'update_to_' . $n );

            if ( is_callable( $routine ) ) {
                $is_silent = call_user_func( $routine, true );

                // This update has to be performed manually.
                if ( ! $is_silent ) {
                    $this->request_manual_update( $n );
                }
            }

            update_option( 'greatrealestate_db_version', $n );

            if ( $this->in_manual_update() )
                break;
        }
    }

    function update_to_2( $silent ) {
        if ( $silent )
            return false;

        $progress = get_option( 'gre-2-migration', false );
        if ( ! $progress )
            $progress = array( 'step' => 'downloads' );

        $ok = true;
        $done = false;
        $message = '';

        switch ( $progress['step'] ) {
            case 'downloads':
                list( $done_, $message ) = $this->update_to_2__downloads();

                if ( $done_ )
                    $progress['step'] = 'misc';

                break;
            case 'misc':
                $done = true;
                $message = _x( 'DONE', 'migration', 'greatrealestate' );
                break;
        }

        update_option( 'gre-2-migration', $progress );

        if ( $done )
            delete_option( 'gre-2-migration' );

        return compact( 'ok', 'done', 'message' );
    }

    function update_to_2__downloads() {
        global $wpdb;

        if ( ! $wpdb->get_col( $wpdb->prepare( "SHOW COLUMNS FROM {$wpdb->prefix}greatrealestate_listings LIKE %s", 'dd_migrated' ) ) )
            $wpdb->query( "ALTER TABLE {$wpdb->prefix}greatrealestate_listings ADD dd_migrated tinyint(1) DEFAULT 0" );

        $listings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}greatrealestate_listings WHERE dd_migrated = %d LIMIT 20",
                                                        0 ) );

        if ( ! $listings ) {
            $wpdb->query( "ALTER TABLE {$wpdb->prefix}greatrealestate_listings DROP COLUMN dd_migrated" );
            return array( true, '' );
        }

        $remaining = intval( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}greatrealestate_listings WHERE dd_migrated = %d", 0 ) ) );
        $download_path = stripslashes( get_option( 'download_path' ) );

        foreach ( $listings as &$l ) {
            $downloads = array();
            $downloads_ids = array_map( 'trim', explode( ',', $l->downloadid ) );

            foreach ( $downloads_ids as $d_id ) {
                $file_info = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->downloads} WHERE file_id = %d", $d_id ) );

                if ( ! $file_info )
                    continue;

                $filename = stripslashes( $file_info->file );
                $path = $download_path . stripslashes( $file_info->file );

                if ( gre_is_remote_file( $path ) ) {
                    $downloads[] = array(
                        'file' => $filename,
                        'type' => '',
                        'date' => $file_info->file_updated_date,
                        'description' => $file_info->file_des,
                        'hits' => $file_info->file_hits
                    );
                    continue;
                }

                $path = $download_path . stripslashes( $file_info->file );

                if ( ! file_exists( $path ) )
                    continue;

                // Copy file to temp dir to avoid removing the original.
                $newpath = tempnam( get_temp_dir(), 'greal-real-estate-' );

                if ( file_exists( $newpath ) )
                    unlink( $newpath );

                if ( ! copy( $path, $newpath ) )
                    throw new Exception();

                $file = array( 'name' => basename( $path ),
                               'type' => '',
                               'tmp_name' => $newpath,
                               'error' => 0,
                               'size' => filesize( $newpath ) );
                $res = wp_handle_sideload( $file, array( 'test_form' => false ) );

                if ( ! empty( $res['error'] ) )
                    throw new Exception();

                $downloads[] = array(
                    'file' => _wp_relative_upload_path( $res['file'] ),
                    'type' => $res['type'],
                    'date' => $file_info->file_updated_date,
                    'description' => $file_info->file_des,
                    'hits' => $file_info->file_hits
                );
            }

            if ( $l->pageid && 'page' == get_post_type( $l->pageid ) )
                update_post_meta( $l->pageid, '_gre[downloads]', $downloads );

            $wpdb->update( $wpdb->prefix . 'greatrealestate_listings', array( 'dd_migrated' => 1 ), array( 'id' => $l->id ) );
            $remaining--;
        }

        return array( false, sprintf( 'Migrating downloads from WP Download Manager [%d remaining]...', $remaining ) );
    }

}


function gre_install() {
    $installer = new GRE_Installer();
    $installer->install();
}

add_action( 'init', 'gre_install', 0 );
