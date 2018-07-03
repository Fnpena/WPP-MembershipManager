<?php

/**
 * Define la funcionalidad de internacionalización
 *
 * Carga y define los archivos de internacionalización de este plugin para que esté listo para su traducción.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    GI_MyMembershipStatus
 * @subpackage GI_MyMembershipStatus/helpers
 */

/**
 * this class define this translation of the plugin
 *
 * @since      1.0.0
 * @package    GI_MyMembershipStatus
 * @subpackage GI_MyMembershipStatus/helpers
 * @author     Franklin Peña
 */
class GI_i18n 
{
    /**
	 * Load the domain text (textdomain) of the plugin for traslate.
	 *
     * @since    1.0.0
     * @access public static
	 */    
    public function load_plugin_textdomain() 
	{    
        load_plugin_textdomain('GI_MyMembershipStatus', false, plugin_dir_path( __FILE__ ) . 'lang');   
    } 
}