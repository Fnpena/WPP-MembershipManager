<?php

class GI_Initialize 
{ 
    protected $plugin_dir_path;
    protected $plugin_dir_path_dir;
    protected $version;
    protected $loader;
    protected $admin;
	
	protected $ajax_umembership;
    
    
    
    public function __construct() 
	{
        $this->version = '1.0.0';       
        $this->plugin_dir_path = plugin_dir_path( __FILE__ );
        $this->plugin_dir_path_dir = plugin_dir_path( __DIR__ );
        $this->start();
		$this->set_language();
        $this->declare_hooks();
    }
    
    public function start() 
	{    
        require_once $this->plugin_dir_path . 'gi_loader.php';
        require_once $this->plugin_dir_path_dir . 'admin/gi_init_admin.php';
		require_once $this->plugin_dir_path . 'gi_i18n.php';  
		require_once $this->plugin_dir_path . 'gi_umembership.php';
		
        $this->loader = new GI_Loader;
        $this->admin = new GI_InitAdmin($this->version);
        
		$this->ajax_umembership = new GI_UMembership;	
    }
	
	 /**
	 * Defina la configuración regional de este plugin para la internacionalización.
     *
     * Utiliza la clase BC_i18n para establecer el dominio y registrar el gancho
     * con WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function set_language()
	{    
        $bc_i18n = new GI_i18n();
        $this->loader->add_action( 'plugins_loaded', $bc_i18n, 'load_plugin_textdomain' );           
    }
    
    public function declare_hooks() 
	{    
        // Cargando los estilos y scripts del admin
        $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );
        
		
		$this->loader->add_action( 'wp_ajax_gims_generateCard', $this->ajax_umembership, 'generate' );
        // Agregando la página mp_pruebas
        //$this->cargador->add_action( 'admin_menu', $this->menu_pruebas, 'options_page' );
    }
    
    public function run() 
	{
        $this->loader->run();
    }
    
}