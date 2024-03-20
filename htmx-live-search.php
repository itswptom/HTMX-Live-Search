<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://itswptom.com
 * @since             1.0.0
 * @package           Htmx_Live_Search
 *
 * @wordpress-plugin
 * Plugin Name:       HTMX Live Search
 * Plugin URI:        https://itswptom.com
 * Description:       This uses HTMX to create a plugin that will add live search and filtering to WordPress.
 * Version:           1.0.0
 * Author:            Tom Rankin
 * Author URI:        https://itswptom.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       htmx-live-search
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HTMX_LIVE_SEARCH_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-htmx-live-search-activator.php
 */

include_once(plugin_dir_path(__FILE__) . 'ajax-functions.php');

function activate_htmx_live_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-htmx-live-search-activator.php';
	Htmx_Live_Search_Activator::activate();
}

/**
 * Plugin deactivation code.
 * This action is documented in includes/class-htmx-live-search-deactivator.php
 */
function deactivate_htmx_live_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-htmx-live-search-deactivator.php';
	Htmx_Live_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_htmx_live_search' );
register_deactivation_hook( __FILE__, 'deactivate_htmx_live_search' );

// Register scripts for Block Editor
function htmx_live_search_block_register() {

    // Enqueue HTMX script
    wp_enqueue_script('htmx-script', 'https://unpkg.com/htmx.org@1.9.11/dist/htmx.min.js', array(), '1.9.11', true);
    
    // Enqueue Block JS
    wp_enqueue_script(
        'htmx-live-search-block-editor',
        plugins_url('block.js', __FILE__), 
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'htmx-script'), null, true); 

    // Register block.
    register_block_type('tdr/htmx-live-search', array(
        'editor_script' => 'htmx-live-search-block-editor',
	    'render_callback' => 'htmx_live_search_render',
    ));
}

add_action('init', 'htmx_live_search_block_register');

// Search form output
function htmx_live_search_render() {
    // Starts recording to the output buffer
    ob_start(); ?>

    <!-- Search form HTML -->
    <h2>Search Form</h2>

	<form>
    <!--
    1. The indicator hides the "Searching…" dialog until it's needed.
    2. We send a post request to the admin-ajax.php file, then the live_search function within the ajax-functions.php file.
    3. The target is where the output from the request will go to – in this case, a specific div.
    4. A trigger tells HTMX how to process a 'non-natural' AJAX request. You specify what event should trigger and whether it has a delay. You can process multiple triggers, as we're doing here. -->

    <p>Use the search form below to find pages on site based on the title.</p>

    <input type="search"
        name="search" placeholder="Search..."
        hx-post="<?php echo admin_url('admin-ajax.php'); ?>?action=live_search"
        hx-target="#search-results"
        hx-trigger="input changed delay:500ms, search"
        hx-indicator=".htmx-indicator">

        <span class="htmx-indicator" style='display:inline;'>Searching…</span>
	</form>
	<div id="search-results"></div>

	<?php
	// Return as a string, deletes contents of output buffer
    return ob_get_clean();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-htmx-live-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_htmx_live_search() {

	$plugin = new Htmx_Live_Search();
	$plugin->run();

}

run_htmx_live_search();
