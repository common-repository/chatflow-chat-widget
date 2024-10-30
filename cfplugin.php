<?php
/**
 * Plugin Name: ChatFlow - Chat Widget for Website
 * Description: Adds ability for your visitors to start chat with you on Facebook Messenger and WhatsApp.
 * Version: 1.0.2
 * Author: ChatFlow.io
 * Author URI: https://chatflow.io
 */
if (!defined('ABSPATH')) die ('No direct access allowed');

if(!class_exists('ChatFlowIO'))
{
    class ChatFlowIO
    {
		private $options;

		public function __construct() {
    		add_action( 'admin_init', array($this, 'init_settings') );
			add_action('admin_menu', array($this, 'chatflow_config') );
			add_action('wp_footer', array($this, 'add_chatflow_code') );
		}
		
		public static function init_settings() {
			register_setting('chatflow', 'chatflow-jscode');
		}
		
		public static function uninstall() {
			self::delete_options();
		}
		
		
		public static function delete_options() {
			unregister_setting('chatflow', 'chatflow-jscode');
			delete_option('chatflow-jscode');
		}

		public function chatflow_config()
		{
			add_menu_page(__('ChatFlow', 'chatflow'), __('ChatFlow', 'chatflow'), 'manage_options', basename(__FILE__), array($this, 'chatflow_create_settings'), plugin_dir_url( __FILE__ ) . 'images/icon.png');
		}

		public function chatflow_create_settings()
		{
			$logo = plugin_dir_url(__FILE__) . 'images/chatflow-io-logo.png';

			echo '<div id="chatflowpost" style="padding:20px;">';
			echo '<a href="https://chatflow.io" target="_blank"><img src="'.htmlspecialchars($logo).'" style="height:auto;width:100%;max-width:300px;"></a>';

			if (get_option('chatflow-jscode')) {
				echo '<p>You can alway regenerate the chat widget code at <a href="https://chatflow.io?install=wp">https://chatflow.io</a></p>';
			} else {
				echo '<p>Get your free chat widget code at <a href="https://chatflow.io?install=wp">https://chatflow.io</a></p>';
			}

			echo '<form action="options.php" method="POST">';
			settings_fields('chatflow');
			do_settings_sections('chatflow');
			echo '<textarea rows="15" name="chatflow-jscode" style="width:100%;" placeholder="Copy and Paste JavaScript Codes Here">' . esc_attr(get_option('chatflow-jscode')) . '</textarea>';
			submit_button('Save');
			echo '</form>';
			echo '</div>';
		}

		function add_chatflow_code()
		{
			echo get_option('chatflow-jscode');
		}
	}
}

if(class_exists('ChatFlowIO')) {
	register_uninstall_hook(__FILE__, array('ChatFlowIO', 'uninstall'));
	$cfchat = new ChatFlowIO();
}

?>