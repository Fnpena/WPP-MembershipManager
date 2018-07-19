<?php

class GI_InitPublic 
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
        
		wp_enqueue_style( 'bootstrap_public', GI_PLUGIN_DIR_PATH . 'public/css/bootstrap.min.css', [], $this->version, 'all' );
		//wp_enqueue_style( 'jquery_ui_css', GI_PLUGIN_DIR_PATH . 'css/jquery-ui.css', [], $this->version, 'all' );
        
        wp_enqueue_style( 'gims_public_style', GI_PLUGIN_DIR_PATH . 'public/css/style.css', [], $this->version, 'all' );
    }
    
    public function enqueue_scripts( $hook ) {
		wp_enqueue_script( 'bootstrap_public_js', GI_PLUGIN_DIR_PATH . 'public/js/bootstrap.min.js', ['jquery'], $this->version, true );
		//wp_enqueue_script( 'jquery_ui_js', GI_PLUGIN_DIR_PATH . 'js/jquery-ui.js', ['jquery'], $this->version, true );
		
        wp_enqueue_script( 'gims_public_script', GI_PLUGIN_DIR_PATH . 'public/js/script.js', ['jquery'], $this->version, true );
    }
    
    /*public function custom_shortcode( $atts, $content = '' ) {
        
        $args = shortcode_atts([
            'id'    => ''
        ], $atts);
        
        extract( $args, EXTR_OVERWRITE );
        
    }*/
    
    public function custom_shortcode( $atts, $content = '' ) {
        //echo $this->plugin_dir_url;
        include GI_PLUGIN_DIR_PATH . 'public/partials/gims_membershipValidatorView.php';
    }
}