<?php

class GI_Initialize 
{ 
    protected $plugin_dir_path;
    protected $plugin_dir_path_dir;
    protected $version;
    protected $loader;
    protected $admin;
	
	protected $ajax_umembership;
    
    //protected $build_menupage;
    //protected $menu_pruebas;
    
    public function __construct() {
        
        $this->version = '1.0.0';       
        $this->plugin_dir_path = plugin_dir_path( __FILE__ );
        $this->plugin_dir_path_dir = plugin_dir_path( __DIR__ );
        $this->start();
        $this->declare_hooks();
    }
    
    public function start() {
        
        require_once $this->plugin_dir_path . 'gi_loader.php';
        require_once $this->plugin_dir_path_dir . 'admin/gi_init_admin.php';
		
		require_once $this->plugin_dir_path . 'gi_umembership.php';
		
        $this->loader = new GI_Loader;
        $this->admin = new GI_InitAdmin($this->version);
        
		$this->ajax_umembership = new GI_UMembership;
		
        //require_once $this->plugin_dir_path . 'mp-build-menupage.php';
        //require_once $this->plugin_dir_path . 'mp-menu-pruebas.php';
        //$this->build_menupage = new MP_Build_Menupage;
        //$this->menu_pruebas = new MP_Menu_Pruebas( $this->build_menupage );
    }
    
    public function declare_hooks() 
	{    
        // Cargando los estilos y scripts del admin
        $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );
        
		
		$this->loader->add_action( 'wp_ajax_gims_generateCard', $this->ajax_umembership, 'generate' );
		//$this->loader->add_action( 'wp_ajax_gims_generate', $this->ajax_umembership, 'request_card' );
        // Agregando la página mp_pruebas
        //$this->cargador->add_action( 'admin_menu', $this->menu_pruebas, 'options_page' );
    }
    
    public function run() {
        $this->loader->run();
    }
    
}