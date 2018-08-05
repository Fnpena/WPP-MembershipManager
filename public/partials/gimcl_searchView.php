<?php 

class Members_ListTable //extends WP_List_Table 
{
    protected $items;
    
    protected $_column_headers;
    
    /**
    * domain url value
    *
    * @since    1.0.0
    * @access   protected
    */
    protected $domain_url;
    
    /**
    * URI url value
    *
    * @since    1.0.0
    * @access   protected
    */
    protected $uri_url;
    
    /**
    * request page value
    *
    * @since    1.0.0
    * @access   protected
    */
    protected $current_page;
    
    
    public function __construct()
    {
        $this->domain_url = $_SERVER['HTTP_HOST'];
        $this->uri_url = explode('?',$_SERVER['REQUEST_URI'])[0];               
    }
    
	/**
	 * Retrieve members data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_members( $per_page = 2, $page_number = 1 ) 
    {
		global $wpdb;

		$sql = "SELECT  tb1.firstname
	                   ,tb1.lastname
	                   ,tb1.personal_id
					   ,GROUP_CONCAT(tb2.aux_code) AS course_list
				  FROM {$wpdb->prefix}gimcl_members tb1 JOIN {$wpdb->prefix}gimcl_member_education tb2 ON tb1.id = tb2.member_id ";

		if ( isset( $_POST['search-textbox'] ) ) 
        {
			$sql .= " WHERE tb1.firstname LIKE '%" . esc_sql( $_POST['search-textbox'] ). "%' OR tb1.lastname LIKE '%" . esc_sql( $_POST['search-textbox'] ). "%' OR tb1.personal_id LIKE '%" . esc_sql( $_POST['search-textbox'] ). "%' OR tb2.aux_code LIKE '%" . esc_sql( $_POST['search-textbox'] ). "%' ";
		}
        
        $sql .= " GROUP BY tb1.firstname,tb1.lastname,tb1.personal_id";
		
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}
	
	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	*/
	public static function record_count() 
	{//TODO: Agrega where clause
		global $wpdb;
		$sql = "SELECT COUNT(tb1.personal_id) FROM {$wpdb->prefix}gimcl_members tb1 JOIN {$wpdb->prefix}gimcl_member_education tb2 ON tb1.id = tb2.member_id ";
        $sql .= " GROUP BY tb1.firstname,tb1.lastname,tb1.personal_id";
		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No existen registro que mostrar.');
	}
	
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'personal_id' => array( 'personal_id', true ),
			'firstname' => array( 'firstname', true ),
			'lastname' => array( 'lastname', false )
		);

		return $sortable_columns;
	}

	
	/*
	Funtion: column_default
	Description: display value for every column without a special function defined
	*/
	function column_default( $item, $column_name ) 
	{
		switch( $column_name ) 
		{ 
			case 'personal_id':
			case 'firstname':
			case 'lastname':
			 return $item[ $column_name ];
			case 'course_list':
			  return $item[ $column_name ];
			default:
			  return print_r( $item, true ) ; //Troubleshooting Info
		}
	}

	/*
	Funtion: get_columns
	Description: Return array with every column to display in the table view
	*/
	function get_columns()
	{
		$columns = array(
		'personal_id' => 'Cedula',
		'firstname'    => 'Nombre',
		'lastname'    => 'Apellido',
		'course_list'    => 'Cursos'
		);
		return $columns;
	}
    
    /*
      Funtion: get_columns
      Description: Return current page number in the table view
    */
    function get_pagenum()
    {
        if(isset($_GET['gimcl-search-view']))
        {
            $sel_page = esc_sql($_GET['gimcl-search-view']);
            if(is_numeric($sel_page))
                return $sel_page;
            return 1;
        }
        return 1;
    }
	
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() 
	{
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$per_page     = 2;//$this->get_items_per_page( 'members_per_page', 2 );
		$this->current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		//$this->set_pagination_args( [ 'total_items' => $total_items, 'per_page'    => $per_page ] );
        //echo 'current_page:'.$this->current_page;
		$this->items = self::get_members( $per_page, $this->current_page );
	}
    
    public function search_box($text_button)
    {
        $full_domain = sprintf("%s/%s",$this->domain_url,$this->uri_url);
        echo "<div class='row col-sm-12'><input type='text' class='searchbox-input col-sm-6' id='search-textbox' name='search-textbox'><input type='submit' id='btnsearch' class='btnSearchBox waves-effect waves-light btn' name='$text_button' /></div>";
    }
    
    public function display()
    {
        $total_items  = self::record_count();
        $page_num = round($total_items/2);
        //$current_page = 1;
        
        //$full_domain = sprintf("%s/%s",$this->domain_url,$this->uri_url);
        //echo $this->$req_page;
        
        $pagination_section = '';
        
        $pagination_section .= "<div class='row col-sm-12 tablenav-pages'>";
        //$pagination_section .= "<span class='displaying-num'>$total_items items</span>";
        //$pagination_section .= "<span class='pagination-links'>";
        
        //this logic control if backward button is enable or disable
        if($this->current_page == 1)
        {
            $pagination_section .= "<span class='tablenav-pages-navspan tablenav-first' aria-hidden='true'><i class='material-icons'>first_page</i></span>";
	        $pagination_section .= "<span class='tablenav-pages-navspan' aria-hidden='true'><i class='material-icons'>chevron_left</i></span>";
        }
        else
        {
            //echo $this->current_page;
            $fullbackward_url = sprintf('?gimcl-search-view=%s','1');
            $backward_url = sprintf('?gimcl-search-view=%s',$this->current_page-1);
            
            $pagination_section .= sprintf("<a class='tablenav-first first-page' href='%s'>
            <span aria-hidden='true'><i class='material-icons'>first_page</i></span>
            </a>",$fullbackward_url);
            $pagination_section .= sprintf("<a class='prev-page' href='%s'>
            <span aria-hidden='true'><i class='material-icons'>chevron_left</i></span>
            </a>",$backward_url);
        }
        
        
        $pagination_section .= "<span class='paging-index'>";
		$pagination_section .= "<span class='current-page' id='current-page-selector' name='paged'>$this->current_page</span>";
		$pagination_section .= "<span class='tablenav-paging-text'> of <span class='total-pages'>$page_num</span></span>
	    </span>";
        
        //this logic control if forward button is enable or disable
        if($this->current_page < $page_num)
        {
            $fullforward_url = sprintf('?gimcl-search-view=%s',$page_num);
            $forward_url = sprintf('?gimcl-search-view=%d',$this->current_page+1);
            
            $pagination_section .= sprintf("<a class='next-page' href='%s'>
            <span aria-hidden='true'><i class='material-icons'>chevron_right</i></span>
            </a>",$forward_url);
            $pagination_section .= sprintf("<a class='last-page' href='%s'>
            <span aria-hidden='true'><i class='material-icons'>last_page</i></span>
            </a>",$fullforward_url);
        }
        else
        {
            $pagination_section .= "<span class='tablenav-pages-navspan' aria-hidden='true'><i class='material-icons'>chevron_right</i></span>";
	        $pagination_section .= "<span class='tablenav-pages-navspan' aria-hidden='true'><i class='material-icons'>last_page</i></span>";   
        }
        
        
        $pagination_section .= "</div>";
        
        echo $pagination_section;
        
        echo "<table class='table table-centered table-striped table-bordered'>";

        echo "<thead class='thead-dark'>
        <tr>
        <th scope='col'>Cedula</th>
        <th scope='col'>Nombre</th>
        <th scope='col'>Apellido</th>
        <th scope='col'>Cursos</th>
        </tr>
        </thead>";
        
        foreach ( $this->items as $item )
        {
            extract($item,EXTR_OVERWRITE);
            echo '<tr><td>'.$personal_id.'</td>';
            echo '<td>'.$firstname.'</td>';
            echo '<td>'.$lastname.'</td>';
            echo '<td>'.$course_list.'</td></tr>';
        }
        echo '</table>';
        echo $pagination_section;
    }
}

$myExternalView = new Members_ListTable();
?>
<div class="wrap">
	<h2>Directorio COICI</h2>
	<form id="form_search" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
	<?php
			$myExternalView->prepare_items();
			$myExternalView->search_box( 'Buscar'); 
			$myExternalView->display(); 
	?>
	</form>
</div>