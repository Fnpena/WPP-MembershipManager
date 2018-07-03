<?php

class GI_InitAdmin 
{
    
    private $version;
    private $plugin_dir_path;
    private $plugin_dir_url;
    private $plugin_dir_url_dir;
    
    public function __construct( $version ) {
        
        $this->version = $version;
        $this->plugin_dir_path = plugin_dir_path( __FILE__ );
        $this->plugin_dir_url = plugin_dir_url( __FILE__ );
        $this->plugin_dir_url_dir = plugin_dir_url( __DIR__ );        
        
    }
    
    public function enqueue_styles( $hook ) {
        
        if( $hook != 'wp-membership-manager_page_gi2m-members' ) {
            return;
        }
        
		wp_enqueue_style( 'bootstrap_min_css', $this->plugin_dir_url . 'css/bootstrap.min.css', [], $this->version, 'all' );
		wp_enqueue_style( 'jquery_ui_css', $this->plugin_dir_url . 'css/jquery-ui.css', [], $this->version, 'all' );
        wp_enqueue_style( 'gims_admin_style', $this->plugin_dir_url . 'css/style.css', [], $this->version, 'all' );
        
    }
    
    public function enqueue_scripts( $hook ) {
                
        if( $hook != 'wp-membership-manager_page_gi2m-members' ) {
            return;
        }
        
		wp_enqueue_script( 'bootstrap_min_js', $this->plugin_dir_url . 'js/bootstrap.min.js', ['jquery'], $this->version, true );
		wp_enqueue_script( 'jquery_ui_js', $this->plugin_dir_url . 'js/jquery-ui.js', ['jquery'], $this->version, true );
		
		wp_enqueue_script( 'polyfill', $this->plugin_dir_url . 'js/polyfill.min.js', ['jquery'], $this->version, true );
		wp_enqueue_script( 'html2canvas', $this->plugin_dir_url . 'js/html2canvas.min.js', ['jquery','polyfill'], $this->version, true );
		
        wp_enqueue_script( 'gims_admin_script', $this->plugin_dir_url . 'js/script.js', ['jquery'], $this->version, true );
		
		wp_localize_script('gims_admin_script',
						   'gims_testing',
						   [ 
						     'url' => admin_url('admin-ajax.php'), 
							 'myvalidator' => wp_create_nonce('gims_myvalidator')
						   ]);
        
    }
    
}