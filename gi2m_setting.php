<?php 
    /*
    Plugin Name: GI2M MyMembershipStatus
    Plugin URI: http://www.b4aconsulting.com/ghetto-interactive
    Description: undercontruction
    Version: 1.0
    Author: Ghetto Interative Lab
    Author URI: http://www.b4aconsulting.com/ghetto-interactive
    Text Domain: GI_MyMembershipStatus
    Domain Path: /lang/
    */
    
	require_once( ABSPATH . 'wp-content/plugins/GI_MyMembershipStatus/helpers/gi2m_functions.php' );

    /*---------------------------------------------------------------
                            Call-up Hook
    -----------------------------------------------------------------*/

    register_activation_hook(__FILE__,'gi2m_install');
    register_deactivation_hook(__FILE__,'gi2m_deactivation');

    /*---------------------------------------------------------------
                                ShortCode
    -----------------------------------------------------------------*/

    //add_shortcode('gi2m-members-view','gi2m_displayMemberDirectory');
	
	/*---------------------------------------------------------------
                          Script & Style Hook
    -----------------------------------------------------------------*/

    add_action('admin_enqueue_scripts','gims_loadReferences');
	
    /*---------------------------------------------------------------
                        Call-up Hook Functions
    -----------------------------------------------------------------*/

    function gi2m_install()
    {
        //Define Current Version for plugin and database
        $default_options=array('plugin_version'=>'1.0','db_version'=>'1.0');

        //Verify If Exist Prior Version of the component
        $current_options = get_option('wpp_gims_options');
        
        //If not exist previous version create save de current options instead
        if($current_options == '' || $current_options == null)
        {
            add_option('wpp_gims_options',$default_options);
            //Call Update Function 
            updatePluginDataBase();
        }
        else
        {
            //Check if the current db version and the installed db version are different
            if(checkPluginDataBase($default_options['db_version'],$current_options['db_version']))
            {
                //If that is true call update function and update the installed db version number
                updatePluginDataBase();
                update_option( 'wpp_gims_options', $default_options );
            }
        }
    }

    function gi2m_deactivation()
    {
        flush_rewrite_rules();
    }

    /*---------------------------------------------------------------
                            Action Functions
    -----------------------------------------------------------------*/

	if(! function_exists('gims_loadReferences'))
    {
		function gims_loadReferences($page_name)
		{
			if($page_name != 'toplevel_page_gi2m-membersx')
			{ return;}
		}
		
		wp_enqueue_style('my-admin-style',
						  plugins_url('\admin\css\style.css',__FILE__),
						  [],
						  false,
						  'all');
						  
		wp_enqueue_script('my-admin-script',
						  plugins_url('\admin\js\script.js',__FILE__),
						  [],
						  false,
						  true);						 
	}
	
    if(! function_exists('gi2m_build_menu'))
    {
        add_action('admin_menu', 'gi2m_build_menu');

        function gi2m_build_menu()
        {
            add_menu_page('WP Membership Manager'
                        , 'Membership Manager'
                        ,0
                        , 'gi2m-release-note'
                        ,'gi2m_display_releasenotes');
            
            ///Validate the acitive user privilege to display the menu
            if(current_user_can('administrator'))
            {
                //Add Sub-Menu Page
                add_submenu_page('gi2m-release-note', 
                                 'Membership List', 
                                 'Membership List',
                                 1, 
                                 'gi2m-members', 
                                 'gi2m_displayMembers');	
   
            }
        }
        
    }

    /*---------------------------------------------------------------
                             Functions
    -----------------------------------------------------------------*/
    if(! function_exists('gi2m_display_releasenotes'))
    {    
        function gi2m_display_releasenotes()
        {
            include('release-note/gi2m_release_note.php');
        }
    }

    if(! function_exists('gi2m_displayMembers'))
    {    
        /*
            Funtion: gi2m_displayMembers
            Description: display all members profile information in admin view
          */
        function gi2m_displayMembers()
        {
            include('admin/gi2m_membersView.php');
        }
    }

    if(! function_exists('gi2m_displayMemberDirectory'))
    {    /*
            Funtion: gi2m_displayMemberDirectory
            Description: display all members profile information in one shortcode
          */
        function gi2m_displayMemberDirectory()
        {
            include('public/gi2m_membersView.php');
        }
    }
?>