<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       jeeglo.com
 * @since      1.0.0
 *
 * @package    LeadKitPro
 * @subpackage LeadKitPro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    LeadKitPro
 * @subpackage LeadKitPro/public
 * @author     Jeeglo <shikeb.ali@jeeglo.com>
 */
class LeadKitPro_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function lkpr_public_enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/leadkitpro-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function lkpr_public_enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/leadkitpro-public.js', array( 'jquery' ), $this->version, false );

	}



	/**
	 *	Switch to leadkitpro template 
	 * 
	 * @return mix
	 */
	public function show_leadkitpro_template($page_template_path)
	{
		global $post;
		global $campaign_id;
		global $lcd_campaign;
		
		// $post_meta = $this->getAllPostMeta($post->ID);

		$lc_campaign_id = get_post_meta(get_the_ID(), '_leadkitpro_campaign_id', true );
		
		$campaign_id = $lc_campaign_id;

		$url = LKPR_API_URL.'campaigns/'.$campaign_id;
		
		if($lc_campaign_id && !empty($lc_campaign_id)) {

			// Get from Transient Cache
		    $lcd_campaign = get_transient('_lcd_campaign_data_'.$lc_campaign_id);

		    if ($lcd_campaign === false) {
				$lcd_campaign = $this->_wpRemoteRequestAPI($url, 'GET');
		        set_transient('_lcd_campaign_data_'.$lc_campaign_id, $lcd_campaign, 300);
		    }

			// Get Og Tags
			$lcd_campaign = $this->_wpRemoteRequestAPI($url, 'GET');

			return $page_template = LKPR_PLUGIN_DIR . '/public/partials/leadkitpro-template.php';
		}
		
		return $page_template_path;
	}

	/**
	 * centraize wp_remote curl request for API
	 * @param  [type] $url    [description]
	 * @param  [type] $method [description]
	 * @param  array  $data   [description]
	 * @return [type]         [description]
	 */
	private function _wpRemoteRequestAPI($url, $method, $data = array())
	{
		$api_key = get_option('_leadkitpro_api_key');
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
	 * Get all post's meta
	 * 
	 * @param  $post_id
	 * @return array
	 */
	public function lkprGetAllPostMeta($post_id)
	{
		$post = get_post($post_id);

		$post_meta_array = get_post_meta($post->ID);
		$post_meta = [];

		if($post_meta_array) {
			foreach ($post_meta_array as $key => $value) {
				$post_meta[$key] = $value[0];
			}

			return $post_meta;
		} 
	}

}
