<?php

class GIMCL_InitPublic 
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
    
    public function enqueue_styles() {
        
		wp_enqueue_style( 'bootstrap_public', GI_PLUGIN_DIR_URL . 'public/css/bootstrap.min.css', [], $this->version, 'all' );
        
        wp_enqueue_style( 'gimcl_materialize_admin_css', GIMCL_PLUGIN_DIR_URL . 'public/css/materialize.min.css', [], $this->version, 'all' );
        
        wp_enqueue_style( 'gimcl_material_icon_admin_css', 'https://fonts.googleapis.com/icon?family=Material+Icons' , [], '', 'all' );
        
        wp_enqueue_style( 'gimcl_public_style', GIMCL_PLUGIN_DIR_URL . 'public/css/style.css', [], $this->version, 'all' );
    }
    
    public function enqueue_scripts() {
		wp_enqueue_script( 'bootstrap_public_js', GI_PLUGIN_DIR_URL . 'public/js/bootstrap.min.js', ['jquery'], $this->version, true );
        
        wp_enqueue_script( 'gimcl_materialize_admin_js', GIMCL_PLUGIN_DIR_URL . 'public/js/materialize.min.js', ['jquery'], $this->version, true );
        
        wp_enqueue_script( 'gimcl_public_script', GI_PLUGIN_DIR_URL . 'public/js/script.js', ['jquery'], $this->version, true );
    }
    
    /*public function custom_shortcode( $atts, $content = '' ) {
        
        $args = shortcode_atts([
            'id'    => ''
        ], $atts);
        
        extract( $args, EXTR_OVERWRITE );
        
    }*/
    
    public function custom_shortcode( $atts, $content = '' ) {
        //echo $this->plugin_dir_url;
        include GIMCL_PLUGIN_DIR_PATH . 'public/partials/gimcl_searchView.php';
    }
}