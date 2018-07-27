<?php

class GIMCL_InitAdmin 
{
    
    private $version;
	private $build_menupage;
	
    private $plugin_dir_path;
    private $plugin_dir_url;
    private $plugin_dir_url_dir;
    
    public function __construct( $version ) {
        
        $this->version = $version;
        $this->plugin_dir_path = plugin_dir_path( __FILE__ );
        $this->plugin_dir_url = plugin_dir_url( __FILE__ );
        $this->plugin_dir_url_dir = plugin_dir_url( __DIR__ );
		$this->build_menupage = new GIMCL_BuildMenu();	
        
    }
    
    public function enqueue_styles( $hook ) {
        
        /*if( $hook != 'toplevel_page_gi2m-members' ) {
            return;
        }*/
        
		wp_enqueue_style( 'bootstrap_min_css', $this->plugin_dir_url . 'css/bootstrap.min.css', [], $this->version, 'all' );
		wp_enqueue_style( 'jquery_ui_css', $this->plugin_dir_url . 'css/jquery-ui.css', [], $this->version, 'all' );
        wp_enqueue_style( 'gimcl_admin_style', $this->plugin_dir_url . 'css/style.css', [], $this->version, 'all' );   
    }
    
    public function enqueue_scripts( $hook ) {
                
        /*if( $hook != 'toplevel_page_gi2m-members' ) {
            return;
        }*/
        
		wp_enqueue_script( 'bootstrap_min_js', $this->plugin_dir_url . 'js/bootstrap.min.js', ['jquery'], $this->version, true );
		wp_enqueue_script( 'jquery_ui_js', $this->plugin_dir_url . 'js/jquery-ui.js', ['jquery'], $this->version, true );
		
        wp_enqueue_script( 'gimcl_admin_script', $this->plugin_dir_url . 'js/script.js', ['jquery'], $this->version, true );
		
		wp_localize_script('gimcl_admin_script',
						   'gimcl_ajaxform',
						   [ 
						     'url' => admin_url('admin-ajax.php'), 
							 'myvalidator' => wp_create_nonce('gimcl_validator')
						   ]);
        
    }
    
	public function add_menu()
	{
		/* add_menu_page(__('WP Membership Manager','GI_MyMembershipStatus')
				,__('WP Membership Manager','GI_MyMembershipStatus')
				,0
				,'gi2m-release-note'
				,'gi2m_display_releasenotes'); */
	
		//Add Sub-Menu Page
		$this->build_menupage->add_menu_page(__('My Course Log','GI_MyCourseLog'),
					  __('My Course Log','GI_MyCourseLog'),
						0,
						'gimcl-membersCourseView',
						[$this,'displayMenu']);
						
		$this->build_menupage->run();
	}
	
	public function displayMenu()
	{
		require_once GIMCL_PLUGIN_DIR_PATH.'admin/partials/gimcl_membersCourseView.php';
	}
}