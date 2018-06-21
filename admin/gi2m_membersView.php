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
	public static function get_members( $per_page = 25, $page_number = 1 ) {

		global $wpdb;
		
		$sql = "SELECT personal_id,
 					   membership,
					   firstname,		  
					   lastname,
					   status
				  FROM {$wpdb->prefix}gi2m_membership_status";

		if ( isset( $_POST['s'] ) ) 
		{
			$sql .= " WHERE firstname LIKE '%" . esc_sql( $_POST['s'] ). "%' OR lastname LIKE '%" . esc_sql( $_POST['s'] ). "%' OR personal_id LIKE '%" . esc_sql( $_POST['s'] ). "%' ";
		}
		
		//Aditional validation search parameter from QR
		if(isset($_REQUEST['ss']))
		{
			$sql .= " WHERE personal_id LIKE '%" . esc_sql( $_REQUEST['ss'] ). "%' ";
		}
		
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
	{
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}gi2m_membership_status";
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
			'lastname' => array( 'lastname', false ),
			'status' => array( 'status', false )
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
			case 'membership':
			case 'status':
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
		$columns = array('personal_id' => 'Cedula',
						 'firstname'    => 'Nombre',
						 'lastname'    => 'Apellido',
						 'membership'    => 'Idoneidad',
						 'status'      => 'Estado');
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
		
		$per_page     = $this->get_items_per_page( 'members_per_page', 25 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [ 'total_items' => $total_items, 'per_page'    => $per_page ] );

		$this->items = self::get_members( $per_page, $current_page );
	}
}

$myMembersListTable = new Members_ListTable();
?>
<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>
	<form method="post">
	<?php
			$myMembersListTable->prepare_items();
			$myMembersListTable->search_box( 'Buscar', 'search-box-id' ); 
			$myMembersListTable->display(); 
	?>
	</form>
</div>