<?php 
    /*
    Plugin Name: MyMembershipStatus
    Plugin URI: http://www.b4aconsulting.com/ghetto-interactive
    Description: undercontruction
    Version: 1.0
    Author: Ghetto Interative Lab
    Author URI: http://www.b4aconsulting.com/ghetto-interactive
    Text Domain: MyMembershipStatus
    Domain Path: /lang/
    */
    
	require_once( ABSPATH . 'wp-content/plugins/GI_MembershipManager/helpers/gi2m_functions.php' );

    /*---------------------------------------------------------------
                            Call-up Hook
    -----------------------------------------------------------------*/

    register_activation_hook(__FILE__,'gi2m_install');

    register_deactivation_hook(__FILE__,'gi2m_deactivation');

    /*---------------------------------------------------------------
                                ShortCode
    -----------------------------------------------------------------*/

    add_shortcode('gi2m-members-view','gi2m_displayMemberDirectory');
    add_shortcode('gi2m-profile-view','gi2m_displayMemberProfile');
	
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

	if(! function_exists('gi2m_memberPreRegistration'))
	{
		add_action('user_register','gi2m_memberPreRegistration');
		
		function gi2m_memberPreRegistration($user_id)
		{
			memberPreRegistration($user_id);
		}
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
                                 'Members', 
                                 'Members',
                                 1, 
                                 'gi2m-members', 
                                 'gi2m_displayMembers');	
   
            }
            else
            {
                add_submenu_page('gi2m-release-note', 
                                 'Member Profile', 
                                 'Member Profile',
                                 0,
                                 'gi2m-member-info', 
                                 'gi2m_displayMemberInfo');
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

    if(! function_exists('gi2m_displayMemberInfo'))
    {    
        /*
            Funtion: gi2m_displayMemberInfo
            Description: display current member profile information in admin view
          */
        function gi2m_displayMemberInfo()
        {
            include('admin/gi2m_profileView.php');
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

    if(! function_exists('gi2m_displayMemberProfile'))
    {    /*
            Funtion: gi2m_displayMemberProfile
            Description: display one member profile on-demand in one shortcode
          */
        function gi2m_displayMemberProfile()
        {
            include('public/gi2m_profileView.php');
        }
    }
?>