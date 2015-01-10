<?php
/**
 * The plugin bootstrap file
 *
 * This file is responsible for starting the plugin using the main plugin
 * class file.
 *
 * @link              http://.tutsplus.com/tutorials/creating-maintainable-wordpress-meta-boxes--cms-22189
 * @since             0.1.0
 * @package           Author_Commentary
 *
 * @wordpress-plugin
 * Plugin Name:       Capabilities Improvements Table
 * Plugin URI:        http://.tutsplus.com/tutorials/creating-maintainable-wordpress-meta-boxes--cms-22189
 * Description:       Allows authors to keep notes and track information and resources when drafting posts.
 * Version:           0.1.0
 * Author:            Tom McFarlin
 * Author URI:        http://tommcfarlin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       author-commentary
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once ('admin/class-capital-improvements-meta-box.php');

/**
 * The core plugin class that is used to define the meta boxes, their tabs,
 * the views, and the partial content for each of the tabs.
 */
require_once ('admin/class-capital-improvements.php');

function run_author_commentary() {
	$author_commentary = new Cap_Improve_Admin( 'capital-improvement', '1.0.0' );
	$author_commentary->initialize_hooks();
}
run_author_commentary();
?>