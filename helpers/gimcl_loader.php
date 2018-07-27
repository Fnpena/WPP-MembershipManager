<?php
/**
 * Register all the actions , filters and shortcode of the plugin
 *
 * @since      1.0.0
 *
 * @package    GI_MyCourseLog
 * @subpackage GI_MyCourseLog/helpers
 */

/**
 * Register all the actions , filters and shortcode of the plugin
 *
 * @since      1.0.0
 * @package    GI_MyCourseLog
 * @subpackage GI_MyCourseLog/helpers
 * @author     Franklin Peña Rojas
 * 
 * @property array $actions
 * @property array $filters
 * @property array $shortcodes
 */

class GIMCL_Loader
{    
    /**
	 * the array of all actions register in WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
    protected $actions;
    
    /**
	 * the array of the filters register in WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
    protected $filters;
    
    /**
	 * the array of the Shortcodes register in WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
    protected $shortcodes;
    
    public function __construct() {
        
        $this->actions = [];
        $this->filters = [];
        $this->shortcodes = [];
        
    }
    
    /**
	 * Add a new action to the array ($this->actions) to be register in WordPress.
	 *
	 * @since    1.0.0
     * @access   public
     * 
	 * @param    string    $hook             El nombre de la acción de WordPress que se está registrando.
	 * @param    object    $component        Una referencia a la instancia del objeto en el que se define la acción.
	 * @param    string    $callback         El nombre de la definición del método/función en el $component.
	 * @param    int       $priority         Opcional. La prioridad en la que se debe ejecutar la función callback. El valor predeterminado es 10.
	 * @param    int       $accepted_args    Opcional. El número de argumentos que se deben pasar en el $callback. El valor predeterminado es 1.
	 */
    public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        
        $this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
        
    }
    
    /**
	 * Add a new filter to the array ($this->filter) to be register in WordPress.
	 *
	 * @since    1.0.0
     * @access   public
     * 
	 * @param    string    $hook             El nombre del filtro de WordPress que se está registrando.
	 * @param    object    $component        Una referencia a la instancia del objeto en el que se define el filtro.
	 * @param    string    $callback         El nombre de la definición del método/función en el $component.
	 * @param    int       $priority         Opcional. La prioridad en la que se debe ejecutar la función callback. El valor predeterminado es 10.
	 * @param    int       $accepted_args    Opcional. El número de argumentos que se deben pasar en el $callback. El valor predeterminado es 1.
	 */
    public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        
        $this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
        
    }
    
    /**
	 * this utility function is used to register the actions and filters hooks.
	 *
	 * @since    1.0.0
	 * @access   private
     * 
	 * @param    array     $hooks            La colección de ganchos que se está registrando (es decir, acciones o filtros).
	 * @param    string    $hook             El nombre del filtro de WordPress que se está registrando.
	 * @param    object    $component        Una referencia a la instancia del objeto en el que se define el filtro.
	 * @param    string    $callback         El nombre de la definición del método/función en el $component.
	 * @param    int       $priority         La prioridad en la que se debe ejecutar la función.
	 * @param    int       $accepted_args    El número de argumentos que se deben pasar en el $callback.
     * 
	 * @return   array                       La colección de acciones y filtros registrados en WordPress para proceder a iterar.
	 */
    private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
        
        $hooks[] = [
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        ];
        
        return $hooks;    
    }
    
     /**
	 * Add a new shortcode nuevo to the array ($this->shortcodes) to be register in WordPress.
	 *
	 * @since    1.0.0
     * @access   public
     * 
	 * @param    string    $tag              El nombre del Shortcode de WordPress que se está registrando.
	 * @param    object    $component        Una referencia a la instancia del objeto en el que se define el el Shortcode.
	 * @param    string    $callback         El nombre de la definición del método/función en el $component.
	 */
    public function add_shortcode( $tag, $component, $callback ) {
        
        $this->shortcodes = $this->add_s( $this->shortcodes, $tag, $component, $callback );   
    }
    
    /**
	 * this utility function is used to register the shortcode.
	 *
	 * @since    1.0.0
	 * @access   private
     * 
	 * @param    array     $shortcodes       La colección de Shortcodes que se está registrando.
	 * @param    string    $tag              El nombre del Shortcode de WordPress que se está registrando.
	 * @param    object    $component        Una referencia a la instancia del objeto en el que se define el el Shortcode.
	 * @param    string    $callback         El nombre de la definición del método/función en el $component.
     * 
	 * @return   array                       La colección de Shortcodes en WordPress para proceder a iterar.
	 */
    private function add_s( $shortcodes, $tag, $component, $callback ) {
        
        $shortcodes[] = [
            'tag'           => $tag,
            'component'     => $component,
            'callback'      => $callback
        ];
        
        return $shortcodes;
        
    }
    
    
    /**
	 * Register all the filters, shortcode and actions with WordPress.
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function run() {
        
        foreach( $this->actions as $hook_u ) {
            
            extract( $hook_u, EXTR_OVERWRITE );
            
            add_action( $hook, [ $component, $callback ], $priority, $accepted_args );
            
        }
        
        foreach( $this->filters as $hook_u ) {
            
            extract( $hook_u, EXTR_OVERWRITE );
            
            add_filter( $hook, [ $component, $callback ], $priority, $accepted_args );
            
        }
        
        foreach( $this->shortcodes as $shortcode ) {
            
            extract( $shortcode, EXTR_OVERWRITE );
            
            add_shortcode( $tag, [ $component, $callback ] );
            
        }
    }
}