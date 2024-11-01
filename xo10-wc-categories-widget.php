<?php

/**
 * Plugin Name: XO10 - WooCommerce Categories widget
 * Plugin URI: http://cartible.com
 * Description: Adds a widget that is able to display WooCommerce product category image thumbnails.
 * Version: 2.0
 * Author: Walter Lee
 * Author URI: http://cartible.com
 * Requires at least: 3.9
 * Tested up to: 4.4
 *
 * Text Domain: xo10-woocommerce-categories-widget
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// check that woocommerce is active
$plugin = plugin_basename(__FILE__);
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    if (is_plugin_active($plugin)) {
        //deactivate_plugins( $plugin );
        return;
    }
}

if (!class_exists('XO10_WC_Cats_Plugin')) :

    /**
     * Main XO10_WC_Cats_Plugin class
     * 
     * @package xo10\woocommerce\categories
     */
    final class XO10_WC_Cats_Plugin {

        /**
         * @var XO10_WC_Cats_Plugin The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main XO10_WC_Cats_Plugin Instance
         * Ensures only one instance of XO10_WC_Cats_Plugin is loaded or can be loaded.
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor.
         * @access private
         */
        private function __construct() {
            // Setup
            $this->define_constants();
            $this->includes();
            add_action('init', array($this, 'init'), 0);

            // Hooks
            add_action('widgets_init', array($this, 'include_widgets'));

            // Housekeeping Hooks
            if (is_admin()) {
                add_action('admin_notices', array($this, 'inactive_wc_alert'));
                register_activation_hook(__FILE__, array($this, 'version_checks'));
                register_uninstall_hook(__FILE__, array('XO10_WC_Cats_Plugin', 'purge_data'));
            }
        }

        /**
         * Define constants we need
         */
        private function define_constants() {

            $plugin_data = get_plugin_data(__FILE__, false, false);
            define('XO10_WC_CATS_PLUGIN_OFFICIAL_NAME', $plugin_data['Name']);

            define('XO10_WC_CATS_PLUGIN_PLUGIN_FILE', __FILE__);
            define('XO10_WC_CATS_PLUGIN_PLUGIN_BASENAME', plugin_basename(__FILE__));

            define('XO10_WC_CATS_PLUGIN_MIN_VERSION_WP', '3.9');
            define('XO10_WC_CATS_PLUGIN_MIN_VERSION_PHP', '5.3');
            define('XO10_WC_CATS_PLUGIN_MIN_VERSION_WC', '2.3');

            define('XO10_WC_CATS_PLUGIN_VERSION', $plugin_data['Version']);
            define('XO10_WC_CATS_PLUGIN_DB_VERSION', '2.0');
            define('XO10_WC_CATS_PLUGIN_WP_VERSION', get_bloginfo('version'));
            define('XO10_WC_CATS_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
            define('XO10_WC_CATS_PLUGIN_URL', trailingslashit(plugin_dir_url(__FILE__)));
            define('XO10_WC_CATS_PLUGIN_INCLUDES', XO10_WC_CATS_PLUGIN_DIR . trailingslashit('includes'));
        }

        /**
         * Include required core files used in admin and on the frontend.
         */
        private function includes() {
            require_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'widgets/class-categories.php' );
            include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'walkers/class-cat-list-walker.php' );
            include_once( XO10_WC_CATS_PLUGIN_DIR . '../woocommerce/includes/abstracts/abstract-wc-widget.php' );
            include_once( XO10_WC_CATS_PLUGIN_DIR . '../woocommerce/includes/walkers/class-product-cat-list-walker.php' );
            include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'decorators/abstract-decorator.php' );
            include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'decorators/factory-decorator.php' );
            include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'decorators/class-default-decorator.php' );
            include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'decorators/class-basic-decorator.php' );
            include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'decorators/class-accordion-decorator.php' );
            include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'decorators/class-plain-accordion-decorator.php' );
            include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'decorators/class-sexy-accordion-decorator.php' );
        }

        /**
         * Init this plugin.
         */
        public function init() {
            // Set up localisation
            $this->load_plugin_textdomain();
        }

        /**
         * Include core widgets
         */
        public function include_widgets() {
              register_widget( 'XO10_WC_Categories_Widget' );
        }

        /**
         * Loads the plugin language files
         *
         * @since 1.3
         */
        public function load_plugin_textdomain() {

            // Set filter for plugin's languages directory
            $plugin_lang_dir = dirname(XO10_WC_CATS_PLUGIN_PLUGIN_BASENAME) . '/languages/';

            // Traditional WordPress plugin locale filter
            $locale = apply_filters('plugin_locale', get_locale(), 'xo10-woocommerce-categories-widget');
            $mofile = sprintf('%1$s-%2$s.mo', 'xo10-woocommerce-categories-widget', $locale);

            // Setup paths to current locale file
            $mofile_local = $plugin_lang_dir . $mofile;
            $mofile_global = WP_LANG_DIR . '/xo10-woocommerce-categories-widget/' . $mofile;

            if (file_exists($mofile_global)) {
                // Look in global /wp-content/languages/xo10-woocommerce-categories-widget folder
                load_textdomain('xo10-woocommerce-categories-widget', $mofile_global);
            } elseif (file_exists($mofile_local)) {
                // Look in local /wp-content/plugins/xo10-woocommerce-categories-widget/languages/ folder
                load_textdomain('xo10-woocommerce-categories-widget', $mofile_local);
            } else {
                // Load the default language files
                load_plugin_textdomain('xo10-woocommerce-categories-widget', false, $plugin_lang_dir);
            }
        }

        // -----------------------------------------
        // Housekeeping
        // -----------------------------------------

        /**
         * Displays error message if WooCommerce is inactive.
         */
        function inactive_wc_alert() {
            if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'views/alert-wc-inactive.php' );
            }
        }

        /**
         * Checks environment when plugin is activated.
         */
        function version_checks() {
            if (version_compare(XO10_WC_CATS_PLUGIN_WP_VERSION, XO10_WC_CATS_PLUGIN_MIN_VERSION_WP, '<')) {
                exit('"' . XO10_WC_CATS_PLUGIN_OFFICIAL_NAME . '" requires at least WordPress version [' . XO10_WC_CATS_PLUGIN_MIN_VERSION_WP . '].');
            }

            if (version_compare(PHP_VERSION, XO10_WC_CATS_PLUGIN_MIN_VERSION_PHP, '<')) {
                exit('"' . XO10_WC_CATS_PLUGIN_OFFICIAL_NAME . '" requires at least PHP version [' . XO10_WC_CATS_PLUGIN_MIN_VERSION_PHP . '].');
            }
        }

        /**
         * Deletes all plugin data on uninstall.
         */
        static function purge_data() {
            delete_option('widget_' . XO10_WC_Categories_Widget::WIDGET_SLUG);
        }

    }

    // end class

endif;


function xo10_wc_cats_go() {
    return XO10_WC_Cats_Plugin::instance();
}

// run the code
xo10_wc_cats_go();
