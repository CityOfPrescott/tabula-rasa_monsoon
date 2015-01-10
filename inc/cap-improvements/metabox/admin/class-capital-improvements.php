<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://tutsplus.com/tutorials/creating-maintainable-wordpress-meta-boxes--cms-22189
 * @since      1.0.0
 *
 * @package    Author_Commentary
 * @subpackage Author_Commentary/admin
 */
 
//The dashboard-specific functionality of the plugin
class Cap_Improve_Admin {
	private $name;
	private $version;
	private $meta_box;		 

	// Initialize the class and set its properties
	public function __construct( $name, $version ) {
		$this->name = $name;
		$this->version = $version;
		$this->meta_box = new Capital_Improvements_Meta_Box();
	}

	// Registers the hooks and their associated callback functions with WordPress
	public function initialize_hooks() {
		$this->meta_box->initialize_hooks();
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}
	
	// Enqueues all files specifically for the dashboard
	public function enqueue_admin_styles() {
		wp_enqueue_style(
			$this->name . '-admin',
		get_stylesheet_directory_uri() . '/inc/cap-improvements/metabox/admin/assets/css/admin.css',
			false,
			$this->version
		);
	}
}
?>