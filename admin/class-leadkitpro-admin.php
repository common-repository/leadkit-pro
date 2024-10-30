<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       jeeglo.com
 * @since      1.0.0
 *
 * @package    LeadKitPro
 * @subpackage LeadKitPro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    LeadKitPro
 * @subpackage LeadKitPro/admin
 * @author     Jeeglo <shikeb.ali@jeeglo.com>
 */
class LeadKitPro_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * 
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function lkpr_admin_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LeadKitPro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LeadKitPro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/leadkitpro-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function lkpr_admin_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LeadKitPro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LeadKitPro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/leadkitpro-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the menu for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function lkpr_admin_plugin_menu() {

		// Add Plugin Menu
		add_menu_page( 'LeadKitPro' . ' - Dashboard', 'LeadKit Pro', 'manage_options', 'leadkitpro', array($this, 'lkpr_page_dashboard'), 'dashicons-welcome-widgets-menus', '64.419' );
	}

	/**
	 * Plugin dashboard page
	 *
	 * @since    1.0.0
	 */
	public function lkpr_page_dashboard() {
		$api_key = get_option('_leadkitpro_api_key');
		require_once plugin_dir_path( __FILE__ ) . 'partials/leadkitpro-admin-display.php';
	}




	/**
	 * Add meta box for LeadKitPro Campaigns
	 * @return null
	 */
	public function lkpr_add_meta_box()
	{
		 add_meta_box(
        'leadkitpro-campaigns',
        __( 'LeadKit PRO', 'leadkitpro' ),
        array($this, 'lkpr_meta_box_callback'),
        'page', 'side','high'
    );
	}



	/**
	 * Call back function for meta box
	 * 
	 * @param  $post
	 * @return mix
	 */
	public function lkpr_meta_box_callback($post)
	{
		$lc_campaign_id = get_post_meta(get_the_ID(), '_leadkitpro_campaign_id', true );

		$api_key = get_option('_leadkitpro_api_key');

		if($api_key) {

		    $url = LKPR_API_URL.'campaigns';

		    // Get from Transient Cache
		    $response = get_transient('_lcd_campaigns_data');

		    if ($response === false) {
				$response = $this->_wpRemoteRequestAPI($api_key, $url, 'GET');
		        // echo "Stroing in Cache";
		        set_transient('_lcd_campaigns_data', $response, 300);
		    }
			

			// Append Dropdown
			if(!empty($response) && (isset($response->data) && count($response->data) > 0)) {

				$dd_html = '';

				$dd_html .= '<div class="lcd_campaigns_dd">';
				$dd_html .= '<select name="_leadkitpro_campaign_id" class="form-control lcd_campaigns_dd" style="width: 89%;">';

				$selected_campaign = '';

				$dd_html .= '<option value="">Select</option>';
				
				foreach ($response->data as $key => $value) {
					
					$dd_html .= '<option value="'.$value->lc_id.'" '.($lc_campaign_id == $value->lc_id ? 'selected' : '').' >'.$value->name.'</option>';
				}

				$dd_html .= '</select>';
				$dd_html .= ' <a href="javascipt:void();" class="lcd_refresh_campaigns lcd_refresh_icon" style="vertical-align: middle !important; top: 4px !important; position: relative !important; text-decoration: none !important; color: #555d65 !important;" title="Refresh"><span class="dashicons dashicons-update"></span></a>';
				$dd_html .= '</div>';
				echo $dd_html;
			} else {
				echo '<p>No campaign was found!</p>';
			}
		} else {
			echo '<p>Your LeadKit PRO account has not been connected with your website. Click <a href="'.admin_url('admin.php?page=leadkitpro&act=1').'">here</a> to connect.</p>';
		}

	}


	/**
	 * Save LeadKitPro Campaign id as meta key on the post save
	 * 
	 * @param  $post_id
	 * @return null
	 */
	public function lkpr_save_campaign($post_id)
	{

		if ( isset($_POST['_leadkitpro_campaign_id']) ) {        
			update_post_meta($post_id, '_leadkitpro_campaign_id', $_POST['_leadkitpro_campaign_id']);      
		}
		
	}

	/**
	 * Verify API Key LeadKitPro and save it to DB
	 * 
	 * @param 
	 * @return null
	 */
	public function lcd_verfiy_api_key() {
	    
	    $api_key = $_POST['_leadkitpro_api_key'];

	    $url = LKPR_API_URL.'verify-api-key';

	    $data = array(
	    	'_leadkitpro_api_key' => $api_key
	    );

		$response = $this->_wpRemoteRequestAPI($api_key, $url, 'POST', $data);
		if($response->message && $response->message == 'Success') {

			$option_name = '_leadkitpro_api_key' ;
			$new_value = $api_key;
			 
			if(get_option( $option_name ) !== false ) {
			    // The option already exists, so update it.
			    update_option( $option_name, $new_value );
			 
			} else {
			    // The option hasn't been created yet'.
			    add_option( $option_name, $new_value);
			}

			// Delete cached campaign data
			delete_transient('_lcd_campaigns_data');

			wp_safe_redirect( add_query_arg( array( 'page' => 'leadkitpro'), admin_url( 'plugins.php' ) ) );
		} else {
			wp_safe_redirect( add_query_arg( array( 'page' => 'leadkitpro&act=1', 'res' => 'inv' ), admin_url( 'plugins.php' ) ) );
		}
		
        exit;
	}

	/**
	 * centraize wp_remote curl request for API
	 * @param  [type] $url    [description]
	 * @param  [type] $method [description]
	 * @param  array  $data   [description]
	 * @return [type]         [description]
	 */
	private function _wpRemoteRequestAPI($api_key, $url, $method, $data = array())
	{
		$api_key = $api_key; //get_option('_leadkitpro_api_key');
		

		$response = wp_remote_post( $url, array(
			'method' => $method,
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array('Lcd-Api-Key' => $api_key),
			'body' => $data,
			'cookies' => array()
		    )
		);

		$response = json_decode($response['body']);

		return $response;
	}

	/**
	 * Delete Campaigns from cache and append new campaigns dropdowm
	 * @return [type] [description]
	 */
	public function refresh_campaigns()
	{
		$response = delete_transient('_lcd_campaigns_data');

		echo $this->lkpr_meta_box_callback();
		exit();
	}

	/**
	 * Delete All Cache Data
	 * @return [type] [description]
	 */
	public function lkpr_clear_all_cache_data()
	{
		sleep(1);
		global $wpdb;

        // delete all "lcd namespace" transients
        $sql = "
            DELETE 
            FROM {$wpdb->options}
            WHERE option_name like '\_transient_lcd_campaigns_data%'
            OR option_name like '\_transient__lcd_campaigns_data%'
            OR option_name like '\_transient__lcd_campaign_data_%'
            OR option_name like '\_transient_lcd_campaign_data_%'
            OR option_name like '\_transient_timeout_lcd_campaigns_data%'
            OR 	option_name like '\_transient_timeout__lcd_campaign_data_%'
            OR option_name like '\_transient_timeout__lcd_campaigns_data%'
            OR option_name like '\_transient_lcd%'
            OR option_name like '\_transient__lcd%'
        ";

        $wpdb->query($sql);
		
		exit('success');
	}
	

}
