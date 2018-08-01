<?php

class GIMCL_CRUDManager
{
    private function Add_NewStudent($user_id,$firstname,$lastname)
    {
        try 
		{
			global $wpdb;
			$table_name = GIMCL_TBL_MEMBER;
            $data = array('personal_id' => $user_id,
                          'firstname' => $firstname,
                          'lastname'=>$lastname,
                          'register_date'=> date('d').'-'.date('M').'-'.date('y'),
                          'status'=>'01');
            //$format = array('%s','%s');
            $wpdb->insert($table_name,$data);
            $my_id = $wpdb->insert_id;
			
			return $my_id;
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
    }
    
    private function Add_NewCourse($member_id,$aux_code,$description,$duration)
    {
        try 
		{
			global $wpdb;
			$table_name = GIMCL_TBL_MEMBER_EDU;
			
			$data = array('member_id' => $user_id,
                          'education_lvl' => 'CU',
                          'aux_code'=>$aux_code,
                          'description' => $description,
                          'duration'=> $duration,
                          'status'=>'A');
            //$format = array('%s','%s');
            $wpdb->insert($table_name,$data);
            $my_id = $wpdb->insert_id;
			
			return $my_id;
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
    }
    
    public function Exist_Student($personal_id)
    {
        try 
		{
			global $wpdb;
			
			$table_name = GIMCL_TBL_MEMBER;
            $result = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE personal_id = '%s'", $user_id ) );
 
			return $result;
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
    }
    
    public function SaveRequest()
    {
        //Validate if the function invocation origin is secure
        check_ajax_referer( 'gimcl_validator', 'nonce' );
        
        if(isset($_POST['action'])) 
        {
            //Get data 
            $list_item = $_POST['requested_ids'];
        }
    }
}