<?php
class GIMCL_Initialize 
{ 
    protected $version;
    protected $loader;
    protected $gimcl_admin;
    protected $gimcl_public;
	
	//protected $ajax_umembership;  
    
    public function __construct() 
	{
        $this->version = '1.0.0';       
        $this->start();
		//$this->set_language();
        $this->declare_admin_hooks();
        //////$this->declare_public_hooks();      
    }
    
    public function start() 
	{    
        require_once GIMCL_PLUGIN_DIR_PATH . 'helpers/gimcl_loader.php';
		require_once GIMCL_PLUGIN_DIR_PATH . 'helpers/gimcl_buildmenu.php';  

        require_once GIMCL_PLUGIN_DIR_PATH . 'admin/gimcl_init_admin.php';
        require_once GIMCL_PLUGIN_DIR_PATH . 'public/gimcl_init_public.php';
        
		//require_once $this->plugin_dir_path . 'gi_i18n.php';  
		//require_once $this->plugin_dir_path . 'gi_umembership.php';		
        $this->loader = new GIMCL_Loader;
        
        $this->gimcl_admin = new GIMCL_InitAdmin($this->version);
        //////$this->gimcl_public = new GIMCL_InitPublic($this->version);
        
		//$this->ajax_umembership = new GI_UMembership;	
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
    /*public function set_language()
	{    
        $gi_i18n = new GI_i18n;
        $this->loader->add_action( 'plugins_loaded', $gi_i18n, 'load_plugin_textdomain' );           
    }*/
    
    public function declare_admin_hooks() 
	{    
        // Cargando los estilos y scripts del admin
        $this->loader->add_action( 'gimcl_admin_enqueue_scripts', $this->gimcl_admin, 'enqueue_styles' );
        $this->loader->add_action( 'gimcl_admin_enqueue_scripts', $this->gimcl_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'admin_menu', $this->gimcl_admin, 'add_menu' );
		//$this->loader->add_action( 'wp_ajax_gims_generateCard', $this->ajax_umembership, 'generate' );
    }
    
    public function declare_public_hooks() 
	{    
        // Cargando los estilos y scripts del public side
        $this->loader->add_action( 'gimcl_public_enqueue_scripts', $this->gimcl_public, 'enqueue_styles' );
        $this->loader->add_action( 'gimcl_public_enqueue_scripts', $this->gimcl_public, 'enqueue_scripts' );
        $this->loader->add_shortcode( 'gimcl-members-view', $this->gimcl_public, 'custom_shortcode' );
    }
    
    public function run() 
	{
        $this->loader->run();
    }
}