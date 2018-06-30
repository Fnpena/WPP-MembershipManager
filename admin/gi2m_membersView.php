<?php 
/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Class: gi2m_membersView
Function: Display and Search member status information also 
Generate Membership Card

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Change Request Log
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
CR29062018
Reason: Only display and sort by firstname and personal_id because the 
excel file that load this data at production will have only this fields
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

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
		
		/* CR29062018
		$sql = "SELECT personal_id,
 					   membership,
					   firstname,		  
					   lastname,
					   status
				  FROM {$wpdb->prefix}gi2m_membership_status"; */
		
		$sql = "SELECT personal_id,
					   firstname,		  
					   status
				  FROM {$wpdb->prefix}gi2m_membership_status";

		if ( isset( $_POST['s'] ) ) 
		{
			$sql .= " WHERE firstname LIKE '%" . esc_sql( $_POST['s'] ). "%' OR personal_id LIKE '%" . esc_sql( $_POST['s'] ). "%' ";
		}
		
		/*CR29062018
		if ( isset( $_POST['s'] ) ) 
		{
			$sql .= " WHERE firstname LIKE '%" . esc_sql( $_POST['s'] ). "%' OR lastname LIKE '%" . esc_sql( $_POST['s'] ). "%' OR personal_id LIKE '%" . esc_sql( $_POST['s'] ). "%' ";
		} */
		
		//Aditional validation search parameter from QR
		if(isset($_REQUEST['sx']))
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

	/** Bulk action list */
	function get_bulk_actions() 
	{	
		$actions = array(
		'PrintCard'    => 'Print ID'
		);
		return $actions;
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
			// 'lastname' => array( 'lastname', false ), CR29062018
			'status' => array( 'status', false )
		);

		return $sortable_columns;
	}

	function column_cb($item) 
	{
        return sprintf('<input type="checkbox" class="cbk_id" name="carnet[]" value="%s" />', $item['personal_id']);    
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
			// case 'lastname': CR29062018
			// case 'membership':
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
		/*CR29062018 
		$columns = array('cb' => '<input type="checkbox" />',
						 'personal_id' => 'Cedula',
						 'firstname'    => 'Nombre',
						 'lastname'    => 'Apellido',
						 'membership'    => 'Idoneidad',
						 'status'      => 'Estado'); */
		$columns = array('cb' => '<input type="checkbox" />',
						 'personal_id' => 'Cedula',
						 'firstname'    => 'Nombre',
						 'status'      => 'Estado');
		return $columns;
	}
	
	function process_bulk_action()
	{
		if('PrintCard' === $this->current_action())
		{
			require_once  ABSPATH . 'wp-content/plugins/GI_MyMembershipStatus/helpers/gi_umembership.php';
			/* if(isset($_POST['action']) && $_POST['action'] == 'PrintCard')*/
			$this->cargador = new GI_UMembership;
			$list_item = $_POST['carnet'];
			$respuesta = '';
			for($i = 0; $i < count($list_item) ; $i++)
			{
				printf('Aqui %d',$i);
				$respuesta = $this->request_card($list_item[$i]);
			}
			
			echo $respuesta;
		}
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
		//$this->process_bulk_action();
		
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
	
	<!--Begin Modal MemberCard-->
	<div id="dialog-viewer" title="Modal Presenter">
		<div id="capture" class="dialog-viewer-content"></div>
		<div id="tab2" class="tab-content">
			<img id="my_logo" alt="test"/>
		</div>
	</div>
	<!--End Modal MemberCard-->
</div>