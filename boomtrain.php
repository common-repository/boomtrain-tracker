<?php
/**
 * Plugin Name: Boomtrain Tracker
 * Plugin URI: https://boomtrain.com/
 * Author: nofearinc
 * Author URI: http://devwp.eu/
 * Version: 1.0
 * Text Domain: bmtr
 * License: GPL2+
 * 
 */

define( 'BMTR_VERSION', '1.0' );
define( 'BMTR_ROOT_DIR', plugin_dir_path(__FILE__) );
define( 'BMTR_ROOT_URL', plugin_dir_url(__FILE__) );


if ( ! class_exists( 'Boomtrain' ) ) {
	require_once BMTR_ROOT_DIR . '/lib/class-tgm-plugin-activation.php';
	
	/**
	 * Main Boomtrain tracker class
	 * 
	 * @author nofearinc
	 *
	 */
	class Boomtrain {
	
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'boomtrain_admin' ) );
			add_action( 'wp_head', array( $this, 'boomtrain_script' ) );
			add_action( 'wp_footer', array( $this, 'signup_form_script' ) );
			add_action( 'tgmpa_register', array( $this, 'include_dependencies' ) );
		}
		
		/**
		 * Include dependencies with the TGM Plugin Activation library
		 */
		public function include_dependencies() {
			$plugins = array(
					array(
							'name'      => 'JSON REST API (WP API)',
							'slug'      => 'json-rest-api',
							'required'  => true,
							'force_activation' => true
					),
			);
			
			$config = array(
					'default_path' => '',                      // Default absolute path to pre-packaged plugins.
					'menu'         => 'tgmpa-install-plugins', // Menu slug.
					'has_notices'  => true,                    // Show admin notices or not.
					'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
					'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
					'is_automatic' => true,                   // Automatically activate plugins after installation or not.
					'message'      => '',                      // Message to output right before the plugins table.
					'strings'      => array(
							'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
							'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
							'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
							'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
							'notice_can_install_required'     => _n_noop( 'The Boomtrain Tracker requires the following plugin: %1$s.', 'The Boomtrain Tracker requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
							'notice_can_install_recommended'  => _n_noop( 'The Boomtrain Tracker recommends the following plugin: %1$s.', 'The Boomtrain Tracker recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
							'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
							'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
							'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
							'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
							'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this plugin: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this plugin: %1$s.' ), // %1$s = plugin name(s).
							'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
							'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
							'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
							'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
							'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
							'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
							'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
					)
			);
			
			// Trigger it
			tgmpa( $plugins, $config );
		}
		
		public function boomtrain_admin() {
			add_menu_page(
				__( "Boomtrain", 'bmtr' ), 
				__( "Boomtrain", 'bmtr' ), 
				'manage_options', 
				'boomtrain', 
				array( $this, 'boomtrain_admin_cb' ) );
		}
		
		public function boomtrain_admin_cb() {
			// Handle database logic
			$defaults = array(
				'script' => '',
				'signup_form' => ''
			);
			
			$boomtrain_options = get_option( 'boomtrain_options', $defaults );
			
			// Store the database data if updated
			if( isset( $_POST['boomtrain_script'] ) ) {
				$boomtrain_options['script'] = $_POST['boomtrain_script'];
				$boomtrain_options['signup_form'] = $_POST['boomtrain_signup_form'];
				
				update_option( 'boomtrain_options', $boomtrain_options );
			}
			
			// Load the template
			include_once  BMTR_ROOT_DIR . 'inc/admin-page.php';
		}
		
		// Add the optin form
		public function signup_form_script() {
			$boomtrain_options = get_option( 'boomtrain_options', array() );
			
			// Load script if set
			if( ! empty( $boomtrain_options ) && ! empty( $boomtrain_options['signup_form'] ) ) {
				$boomtrain_script = $boomtrain_options['signup_form'];
				
				// Echo the user script
				echo stripslashes( $boomtrain_script );
			}
		}
		
		public function boomtrain_script() {
			// Only load for posts
			if( function_exists( 'is_single' ) && is_singular( 'post' ) ) {
				
				$boomtrain_options = get_option( 'boomtrain_options', array() );
				
				// Load script if set and saved in the database
				if( ! empty( $boomtrain_options ) && ! empty( $boomtrain_options['script'] ) ) {
					$boomtrain_script = $boomtrain_options['script'];
					
					// Echo the user script
					echo stripslashes( $boomtrain_script );
					
					$post_id = get_the_ID();
					$post_type = 'post';
					
					// Trigger the specific event
					?>
					<script type="text/javascript">
						_bt.track("viewed", {model: "<?php echo $post_type; ?>", id: "<?php echo $post_id; ?>"} );
					</script>
					<?php 
				}
				
			}
		}
		
	}

	new Boomtrain();
}
