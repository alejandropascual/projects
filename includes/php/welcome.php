<?php
/**
 * Weclome Page Class
 *
 * @package     PROJECTS
 * @subpackage  php/Welcome
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * PRO_Welcome Class
 *
 * A general class for About and Credits page.
 *
 * @since 1.0
 */
class PRO_Welcome {

    /**
     * @var string The capability users should have to view the page
     */
    public $minimum_capability = 'manage_options';
    //public $minimum_capability = 'edit_posts';

    /**
     * Get things started
     *
     * @since 1.0
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus') );
        add_action( 'admin_head', array( $this, 'admin_head' ) );
        add_action( 'admin_init', array( $this, 'welcome'    ) );
    }

    /**
     * Register the Dashboard Pages which are later hidden but these pages
     * are used to render the Welcome and Credits pages.
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function admin_menus() {
        // About Page
        add_dashboard_page(
            __( 'Welcome to Projects Theme', LANGUAGE_THEME ),
            __( 'Welcome to Projects Theme', LANGUAGE_THEME ),
            $this->minimum_capability,
            'projects-about',
            array( $this, 'about_screen' )
        );

        // Getting Started Page
        add_dashboard_page(
            __( 'Getting started with Projects Theme', LANGUAGE_THEME ),
            __( 'Getting started with Projects Theme', LANGUAGE_THEME ),
            $this->minimum_capability,
            'projects-getting-started',
            array( $this, 'getting_started_screen' )
        );
    }

    /**
     * Hide Individual Dashboard Pages
     *
     * @access public
     * @since 1.0
     * @return void
     */
     public function admin_head() {
         remove_submenu_page( 'index.php', 'projects-about' );
         remove_submenu_page( 'index.php', 'projects-getting-started' );

         // Icon for welcome page
         ?>
         <style type="text/css" media="screen">

         </style>
         <?php
     }

    /**
     * Navigation tabs
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function tabs() {
        $selected = isset( $_GET['page'] ) ? $_GET['page'] : 'projects-about';
        ?>
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab <?php echo $selected == 'projects-about' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'projects-about' ), 'index.php' ) ) ); ?>">
                <?php _e( "What's New", LANGUAGE_THEME ); ?>
            </a>
            <a class="nav-tab <?php echo $selected == 'projects-getting-started' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'projects-getting-started' ), 'index.php' ) ) ); ?>">
                <?php _e( 'Getting Started', LANGUAGE_THEME ); ?>
            </a>
        </h2>
    <?php
    }

    /**
     * Render About Screen
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function about_screen() {
        list( $display_version ) = explode( '-', APS_PROJECTS_VERSION );
        ?>
        <div class="wrap about-wrap">
            <h1><?php printf( __( 'Welcome to Projects Theme %s', LANGUAGE_THEME ), $display_version ); ?></h1>
            <div class="about-text"><?php printf( __( 'Thank you for updating to the latest version!', LANGUAGE_THEME ), $display_version ); ?></div>
            <div class="projects-badge"><?php printf( __( 'Version %s', LANGUAGE_THEME ), $display_version ); ?></div>

            <?php $this->tabs(); ?>

            <p class="about-description"><?php _e( 'Use the tips below to get started using Projects. You will be up and running in no time!', LANGUAGE_THEME ); ?></p>


            <div class="changelog">
                <h3><?php _e( 'Some title text', LANGUAGE_THEME );?></h3>
                
                <div class="feature-section">
                
                    <img src="<?php echo APS_THEME_URI . '/includes/stylesheets/images/blog/blog_grid_0.png'; ?>" class="projects-welcome-screenshots"/>

                    <h4><?php _e( 'Some subtitle text',LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                    <h4><?php _e( 'Easily Access Reports', LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>
                
                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Some title text', LANGUAGE_THEME );?></h3>

                <div class="feature-section">

                    <img src="<?php echo APS_THEME_URI . '/includes/stylesheets/images/blog/blog_grid_0.png'; ?>" class="projects-welcome-screenshots"/>

                    <h4><?php _e( 'Some subtitle text',LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                    <h4><?php _e( 'Easily Access Reports', LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Some title text', LANGUAGE_THEME );?></h3>

                <div class="feature-section">

                    <img src="<?php echo APS_THEME_URI . '/includes/stylesheets/images/blog/blog_grid_0.png'; ?>" class="projects-welcome-screenshots"/>

                    <h4><?php _e( 'Some subtitle text',LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                    <h4><?php _e( 'Easily Access Reports', LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                </div>
            </div>
            
        </div>
        <?php
    }
    
    /**
     * Render Getting Started Screen
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function getting_started_screen() {
        list( $display_version ) = explode( '-', APS_PROJECTS_VERSION );
        ?>
        <div class="wrap about-wrap">
            <h1><?php printf( __( 'Welcome to Projects Theme %s', LANGUAGE_THEME ), $display_version ); ?></h1>
            <div class="about-text"><?php printf( __( 'Thank you for updating to the latest version!', LANGUAGE_THEME ), $display_version ); ?></div>
            <div class="projects-badge"><?php printf( __( 'Version %s', LANGUAGE_THEME ), $display_version ); ?></div>

            <?php $this->tabs(); ?>

            <p class="about-description"><?php _e( 'Use the tips below to get started using Projects. You will be up and running in no time!', LANGUAGE_THEME ); ?></p>

            <div class="changelog">
                <h3><?php _e( 'Some title text', LANGUAGE_THEME );?></h3>

                <div class="feature-section">

                    <img src="<?php echo APS_THEME_URI . '/includes/stylesheets/images/blog/blog_grid_0.png'; ?>" class="projects-welcome-screenshots"/>

                    <h4><?php _e( 'Basic Installation',LANGUAGE_THEME );?></h4>
                    <p><iframe src="//player.vimeo.com/video/97112066" width="500" height="313" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href="http://vimeo.com/97112066">PROJECTS WP-Theme - Installation</a> from <a href="http://vimeo.com/user28714723">ELAPSL</a> on <a href="https://vimeo.com">Vimeo</a>.</p></p>

                    <h4><?php _e( 'Install on click example', LANGUAGE_THEME );?></h4>
                    <p><iframe src="//player.vimeo.com/video/97157561" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href="http://vimeo.com/97157561">PROJECTS WP-Theme | Install example</a> from <a href="http://vimeo.com/user28714723">ELAPSL</a> on <a href="https://vimeo.com">Vimeo</a>.</p></p>

                    <h4><?php _e( 'Styling', LANGUAGE_THEME );?></h4>
                    <p><iframe src="//player.vimeo.com/video/97160453" width="500" height="306" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href="http://vimeo.com/97160453">PROJECTS Wp-Theme | Styling</a> from <a href="http://vimeo.com/user28714723">ELAPSL</a> on <a href="https://vimeo.com">Vimeo</a>.</p></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Some title text', LANGUAGE_THEME );?></h3>

                <div class="feature-section">

                    <img src="<?php echo APS_THEME_URI . '/includes/stylesheets/images/blog/blog_grid_0.png'; ?>" class="projects-welcome-screenshots"/>

                    <h4><?php _e( 'Some subtitle text',LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                    <h4><?php _e( 'Easily Access Reports', LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Some title text', LANGUAGE_THEME );?></h3>

                <div class="feature-section">

                    <img src="<?php echo APS_THEME_URI . '/includes/stylesheets/images/blog/blog_grid_0.png'; ?>" class="projects-welcome-screenshots"/>

                    <h4><?php _e( 'Some subtitle text',LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                    <h4><?php _e( 'Easily Access Reports', LANGUAGE_THEME );?></h4>
                    <p><?php _e( 'Some description text', LANGUAGE_THEME );?></p>

                </div>
            </div>

        </div>
        <?php
    }

    /**
     * Sends user to the Welcome page on first activation of PROJECTS as well as each
     * time PROJECTS is upgraded to a new version
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function welcome() {

        // Bail if activating from network, or bulk
        if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
            return;

        global $pagenow;

        if (isset($_GET['activated'] ) && $pagenow == "themes.php" ) {

            $upgrade = get_option( 'projects_version_upgraded_from' );

            if( ! $upgrade ) { // First time install
                wp_safe_redirect( admin_url( 'index.php?page=projects-getting-started' ) ); exit;
            } else { // Update
                wp_safe_redirect( admin_url( 'index.php?page=projects-about' ) ); exit;
            }
        }

    }

}
new PRO_Welcome();