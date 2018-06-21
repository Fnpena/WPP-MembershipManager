<?php 
    /*
    Plugin Name: GI2M MyCourseLog
    Plugin URI: http://www.b4aconsulting.com/ghetto-interactive
    Description: This plugin is a record the all courses taken by one member. Additional let you search all course that one member has taken or all members that has taken some course in particular
    Version: 1.0
    Author: Ghetto Interative Lab
    Author URI: http://www.b4aconsulting.com/ghetto-interactive
    Text Domain: GI_MyCourseLog
    Domain Path: /lang/
    */
    
	require_once( ABSPATH . 'wp-content/plugins/GI_MembershipManager/helpers/gi2m_functions.php' );

    /*---------------------------------------------------------------
                            Call-up Hook
    -----------------------------------------------------------------*/

    register_activation_hook(__FILE__,'gi2m_install');
    register_deactivation_hook(__FILE__,'gi2m_deactivation');
	
    /*---------------------------------------------------------------
                        Call-up Hook Functions
    -----------------------------------------------------------------*/

    function gi2m_install()
    {
        //Define Current Version for plugin and database
        $default_options=array('plugin_version'=>'1.0','db_version'=>'1.0');

        //Verify If Exist Prior Version of the component
        $current_options = get_option('wpp_gi2m_options');
        
        //If not exist previous version create save de current options instead
        if($current_options == '' || $current_options == null)
        {
            add_option('wpp_gi2m_options',$default_options);
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
                update_option( 'wpp_gi2m_options', $default_options );
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
	
    if(! function_exists('gi2m_build_menu'))
    {
        add_action('admin_menu', 'gi2m_build_menu');

        function gi2m_build_menu()
        {
            add_menu_page('My Course Log'
                        , 'My Course Log'
                        ,0
                        , 'gi2m-release-note'
                        ,'gi2m_display_releasenotes');
            
            ///Validate the active user privilege to display the menu
            if(current_user_can('administrator'))
            {
                //Add Sub-Menu Page
                add_submenu_page('gi2m-release-note', 
                                 'Search', 
                                 'Search',
                                 1, 
                                 'gi2m-search-course', 
                                 'gi2m_displayResults');	
                
                 add_submenu_page('gi2m-release-note', 
                                 'New Student', 
                                 'New Student',
                                 1, 
                                 'gi2m-member-view', 
                                 'gi2m_displayMemberProfile');	
   
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
	
	if(! function_exists('gi2m_displayResults'))
    {    
        function gi2m_displayResults()
        {
            include('admin/gi2m_searchView.php');
        }
    }

    if(! function_exists('gi2m_displayMemberProfile'))
    {    
        function gi2m_displayMemberProfile()
        {
            include('admin/gi2m_profileView.php');
        }
    }
?>