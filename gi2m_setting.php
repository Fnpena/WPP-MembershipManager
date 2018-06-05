<?php 
    /*
    Plugin Name: Membership Manager
    Plugin URI: http://www.b4aconsulting.com/ghetto-interactive
    Description: undercontruction
    Version: 1.0
    Author: Ghetto Interative Lab
    Author URI: http://www.b4aconsulting.com/ghetto-interactive
    Text Domain: GI_MembershipManager
    Domain Path: /lang/
    */
    
    //require_once plugin_dir_path(__FILE__). '/resources/helpers.php';

    /*---------------------------------------------------------------
                            Call-up Hook
    -----------------------------------------------------------------*/

    register_activation_hook(__FILE__,'gi2m_install');

    register_deactivation_hook(__FILE__,'gi2m_deactivation');

    /*---------------------------------------------------------------
                                ShortCode
    -----------------------------------------------------------------*/

    add_shortcode('gi2m-members-view','gi2m_displayMembers');
    add_shortcode('gi2m-profile-view','gi2m_displayMemberProfile');
    
    /*---------------------------------------------------------------
                        Call-up Hook Functions
    -----------------------------------------------------------------*/

    function gi2m_install()
    {
        //Install Database Schema y Plugins Variables and Components    
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
            add_menu_page('Membership Manager'
                        , 'Membership Manager'
                        ,0
                        , 'gi2m-release-note'
                        ,'gi2m_display_releasenotes');
            
//            //Add Sub-Menu Page
//            add_submenu_page('gi2m-release-note', 
//                             'Members', 
//                             'Members',
//                             1, 
//                             'gi2m-members', 
//                             'gi2m_getMemberList');	
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

//    if(! function_exists('gi2m_getMemberList'))
//    {    
//        function gi2m_GetMemberList()
//        {
//            include('admin/gi2m_release_note.php');
//        }
//    }

    if(! function_exists('gi2m_displayMembers'))
    {    
        function gi2m_displayMembers()
        {
            include('public/gi2m_membersView.php');
            //return my first shortcode hello world;
        }
    }

    if(! function_exists('gi2m_displayMemberProfile'))
    {    
        function gi2m_displayMemberProfile()
        {
            include('public/gi2m_profileView.php');
            //return my first shortcode hello world;
        }
    }
?>