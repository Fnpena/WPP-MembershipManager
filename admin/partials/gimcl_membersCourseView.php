<?php 

//Check if class WP_List_Table exist 
if( ! class_exists( 'WP_List_Table' ) ) 
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Members_ListTable extends WP_List_Table 
{
	/**
	 * Retrieve members data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_members( $per_page = 50, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT  tb1.id
                       ,tb1.firstname
	                   ,tb1.lastname
	                   ,tb1.personal_id
					   ,GROUP_CONCAT(tb2.aux_code) AS course_list
				  FROM {$wpdb->prefix}gimcl_members tb1 JOIN {$wpdb->prefix}gimcl_member_education tb2 ON tb1.id = tb2.member_id ";

		if ( isset( $_POST['s'] ) ) {
			$sql .= " WHERE tb1.firstname LIKE '%" . esc_sql( $_POST['s'] ). "%' OR tb1.lastname LIKE '%" . esc_sql( $_POST['s'] ). "%' OR tb1.personal_id LIKE '%" . esc_sql( $_POST['s'] ). "%' OR tb2.aux_code LIKE '%" . esc_sql( $_POST['s'] ). "%' ";
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
	Funtion: column_personal_id
	Description: display value for every column without a special function defined
	*/
	function column_personal_id( $item ) 
    {
        $actions = array('editar' => sprintf("<a href='#' class='btnEditStudent' item-code='%s'>Editar</a>",$item['id']));
        return sprintf('%1$s %2$s', $item['personal_id'], $this->row_actions($actions) );
    }

	
	/*
	Funtion: column_default
	Description: display value for every column without a special function defined
	*/
	function column_default( $item, $column_name ) 
	{
		switch( $column_name ) 
		{ 
			//case 'personal_id':
			case 'firstname':
			case 'lastname':
			 return $item[ $column_name ];
			case 'course_list':
                $raw_data = $item[ $column_name ];
                if(strpos($raw_data, ',') !== false)
                {
                    $str_bullet = '';
                    $arr_data = explode(",", $raw_data);
                    
                    for($rw = 0; $rw < count($arr_data); $rw++)
                    {
                            $str_bullet .= '<li>'.$arr_data[$rw].'</li>';
                    }
                    
                    $str_bullet = "<a href='#' class='btnDisplay' item='AX-".$item['id']."'>mostrar</a><div class='div-hidden AX-".$item['id']."'><ul>$str_bullet<ul></div>";
                    return $str_bullet;
                }
                else
                {
			     return $item[ $column_name ];
                }
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
	
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() 
	{
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$per_page     = $this->get_items_per_page( 'members_per_page', 50 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [ 'total_items' => $total_items, 'per_page'    => $per_page ] );

		$this->items = self::get_members( $per_page, $current_page );
	}
}

$myMembersListTable = new Members_ListTable();
?>
<div class="wrap">
	<div class="row">
        <div class="col s12">
            <h5><?php echo esc_html(get_admin_page_title()); ?></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <a class="btnAddRegistry btn btn-primary"><i class="material-icons">add</i>
            <span>Nuevo Registro</span>
            </a>
        </div>
    </div>
	<form method="post">
	<?php
			$myMembersListTable->prepare_items();
			$myMembersListTable->search_box( 'Buscar', 'search-box-id' ); 
			$myMembersListTable->display(); 
	?>
	</form>
</div>
<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                            Modal Structure 
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<div id="NewStudentModal">
<div class="modal-header">
    <h4 class="modal-title">Agregar Estudiante</h4>
</div>
<div class="modal-body">
  <form method="post">
        <input type="hidden" id="hfmodal-mode" value="X">
        <input type="hidden" id="hfmodal-index">
        <input type="hidden" id="hfcurrent_user">
      
      <div class="row">
            <div class="form-group col-sm-6">
            <input type="text" id="firstname-input" class="form-control firstname">
            <label for="firstname-input">Nombre</label>
            </div>
            <div class="form-group col-sm-6">
            <input type="text" id="lastname-input" class="form-control lastname">
            <label for="lastname-input">Apellido</label>
            </div>
      </div>

      <div class="row">
            <div class="form-group  col-sm-6">
            <input type="text" id="personalid-input" class="form-control personalid">
            <label for="personalid-input">Cedula</label>
            </div>
      </div>
       
      <div class="row">
            <div class="form-group col-sm-3">
            <input type="text" id="course-code-input" class="form-control">
            <label for="course-code-input">Codigo</label>
            </div>
            <div class="form-group col-sm-5">
            <input type="text" id="course-description-input" class="form-control">
            <label for="course-description-input">Descripcion</label>
            </div>
            <div class="form-group col-sm-2">
            <input type="text" id="course-duration-input" class="form-control">
            <label for="course-duration-input">No. Horas</label>
            </div>
            <div class="col-sm-2">
            <a class="btnAddNewRow btn btn-primary">
                <i class="material-icons">add</i>
            </a>    
            </div>
      </div>
      
      <div class="row">
          <div class="col-sm-12 divPanel">
          </div>
            <input type="text" id="hf_courses"/>
      </div>
        
  </form>
</div>
</div>
