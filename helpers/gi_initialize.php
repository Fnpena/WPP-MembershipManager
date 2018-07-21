<?php
class GI_Initialize 
{ 
    protected $plugin_dir_path;
    protected $plugin_dir_path_dir;
    protected $version;
    protected $loader;
    protected $admin;
    protected $gims_public;
	
	protected $ajax_umembership;  
    
    public function __construct() 
	{
        $this->version = '1.2.1';       
        $this->plugin_dir_path = plugin_dir_path( __FILE__ );
        $this->plugin_dir_path_dir = plugin_dir_path( __DIR__ );
        $this->start();
		$this->set_language();
        $this->declare_admin_hooks();
        $this->declare_public_hooks();
        
    }
    
    public function start() 
	{    
        require_once $this->plugin_dir_path . 'gi_loader.php';
        require_once $this->plugin_dir_path_dir . 'admin/gi_init_admin.php';
        require_once $this->plugin_dir_path_dir . 'public/gi_init_public.php';
        
		require_once $this->plugin_dir_path . 'gi_i18n.php';  
		require_once $this->plugin_dir_path . 'gi_buildmenu.php';  
		require_once $this->plugin_dir_path . 'gi_umembership.php';
		
        $this->loader = new GI_Loader;
        
        $this->admin = new GI_InitAdmin($this->version);
        $this->gims_public = new GI_InitPublic($this->version);
        
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
    public function set_language()
	{    
        $gi_i18n = new GI_i18n;
        $this->loader->add_action( 'plugins_loaded', $gi_i18n, 'load_plugin_textdomain' );           
    }
    
    public function declare_admin_hooks() 
	{    
        // Cargando los estilos y scripts del admin
        $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'admin_menu', $this->admin, 'add_menu' );
        
		
		$this->loader->add_action( 'wp_ajax_gims_generateCard', $this->ajax_umembership, 'generate' );
        // Agregando la página mp_pruebas
        //$this->cargador->add_action( 'admin_menu', $this->menu_pruebas, 'options_page' );
    }
    
    public function declare_public_hooks() 
	{    
        // Cargando los estilos y scripts del public side
        $this->loader->add_action( 'public_enqueue_scripts', $this->gims_public, 'enqueue_styles' );
        $this->loader->add_action( 'public_enqueue_scripts', $this->gims_public, 'enqueue_scripts' );
        $this->loader->add_shortcode( 'gims-membership-validator', $this->gims_public, 'custom_shortcode' );
    }
    
    public function run() 
	{
        $this->loader->run();
    }
    
}