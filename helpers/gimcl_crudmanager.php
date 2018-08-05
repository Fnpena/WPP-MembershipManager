<?php

class GIMCL_CRUDManager
{
     /**
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
    private $db;
    
    public function __construct() 
	{
        global $wpdb;
        $this->db = $wpdb;
    }
    
    static function Add_NewStudent($user_id,$firstname,$lastname)
    {
        try 
		{
			//global $wpdb;
			$table_name = GIMCL_TBL_MEMBER;
            $data = array('personal_id' => $user_id,
                          'firstname' => $firstname,
                          'lastname'=>$lastname,
                          'register_date'=> date('d').'-'.date('M').'-'.date('y'),
                          'status'=>'01');
            //$format = array('%s','%s');
            $this->db->insert($table_name,$data);
            $my_id = $this->db->insert_id;
			
			echo $my_id;
		} 
		catch (Exception $e) 
		{
			echo $e->getMessage();
		}
    }
    
    function Add_NewCourse($member_id,$aux_code,$description,$duration)
    {
        try 
		{
			//global $wpdb;
			$table_name = GIMCL_TBL_MEMBER_EDU;
			
			$data = array('member_id' => $user_id,
                          'education_lvl' => 'CU',
                          'aux_code'=>$aux_code,
                          'description' => $description,
                          'duration'=> $duration,
                          'status'=>'A');
            //$format = array('%s','%s');
            $this->db->insert($table_name,$data);
            $my_id = $this->db->insert_id;
			
			return $my_id;
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
    }
    
   /* public static function Exist_Student($personal_id)
    {
        try 
		{
			//global $wpdb;
			
			$table_name = GIMCL_TBL_MEMBER;
            $result = $this->db->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE personal_id = '%s'", $user_id ) );
 
			return $result;
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
    }*/
    
    public function GetStudentProfile()
    {
        check_ajax_referer( 'gimcl_validator', 'nonce' );
        
        $prm_userID = $_POST['userid'];
        
        $tbl_member = GIMCL_TBL_MEMBER;
        $tbl_member_edu = GIMCL_TBL_MEMBER_EDU;
        
        $SEL_Member = "SELECT firstname,lastname,personal_id FROM $tbl_member WHERE id = %s";
        $SEL_MemberEDU = "SELECT aux_code, description, duration FROM $tbl_member_edu WHERE member_id = %s";
        
        try
        {
            $result = $this->db->get_row( $this->db->prepare( $SEL_Member,array($prm_userID) ), 'ARRAY_A' );

            extract($result,EXTR_OVERWRITE);

            //Get MemberEDU Info
            $EduData = $this->db->get_results( $this->db->prepare( $SEL_MemberEDU,array($prm_userID) ), 'ARRAY_A' );

            $course_list = '';
            $course_listRAW = '';
            
            foreach($EduData as $item)
            {
                extract($item,EXTR_OVERWRITE);
                
                $course_list .= sprintf("<div class='row'><div class='col-sm-3'>%s</div><div class='col-sm-7'>%s</div><div class='col-sm-2'>%s</div></div>",$aux_code,$description,$duration);
                
                $course_listRAW .= sprintf("%s,%s,%s|",$aux_code,$description,$duration);
            }
            
            $json_result = json_encode([
                'response'=>'OK',
                'student_id' => $prm_userID,
                'firstname'  => $firstname,
                'lastname'   => $lastname,
                'personal_id'=> $personal_id,
                'course_list'=> $course_list,
                'course_listRAW'=> $course_listRAW]);
            echo $json_result;
            
        }
        catch (Exception $e) 
        {
            $error  = $e->getMessage();
            $json_result = json_encode([
                'response'=>'OK',
                'insert_id'=>$error]);
            echo $json_result;
        }
        wp_die();
    }
    
    public function SaveRequest()
    {
        //Validate if the function invocation origin is secure
        check_ajax_referer( 'gimcl_validator', 'nonce' );
        
        if(current_user_can('manage_options'))
        {
            try
            {
                //Get data 
                extract($_POST,EXTR_OVERWRITE);
                
                if($mode == 'A')
                {
                    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                                        INSERT MODE LOGIC   
                    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
                    
                        //$inserted_id = self::Add_NewStudent($userid,$firstname,$lastname);

                        //Insert Table Master
                        $table_name = GIMCL_TBL_MEMBER;
                        $data = array('personal_id' => $userid,
                                  'firstname' => $firstname,
                                  'lastname'=>$lastname,
                                  'register_date'=> date('d').'-'.date('M').'-'.date('y'),
                                  'status'=>'01');
                        //$format = array('%s','%s');
                        $this->db->insert($table_name,$data);
                        $inserted_id = $this->db->insert_id;

                        //Get List Course to insert
                        $arr_rows = explode('|',$arr_course);

                        for($rx = 0; $rx < count($arr_rows); $rx++)
                        {    
                            if($arr_rows[$rx]!='')
                            {
                                $inner_list = explode(',',$arr_rows[$rx]);

                                $table_name = GIMCL_TBL_MEMBER_EDU;

                                $data = array('member_id' => $inserted_id,
                                          'education_lvl' => 'CU',
                                          'aux_code'=>$inner_list[0],
                                          'description' => $inner_list[1],
                                          'duration'=> $inner_list[2],
                                          'status'=>'A');
                                //$format = array('%s','%s');
                                $this->db->insert($table_name,$data);
                            }
                        }

                        $json_result = json_encode([
                            'response'=>'OK',
                            'insert_id'=>$inserted_id,
                            'message'=>'Proceso de Registro Satisfactorio']);
                        echo $json_result;
                    
                    
                    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
                }
                else if($mode == 'U')
                {
                    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                                        UPDATE MODE LOGIC   
                    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
                    
                    //$UPD_MemberEDU = 'UPDATE $table_EDU SET description =[value-4], aux_code =[value-5], duration =[value-8] WHERE member_id = %s';
                    
                    $arr_rows = explode('|',$arr_course);

                        for($rx = 0; $rx < count($arr_rows); $rx++)
                        {    
                            if($arr_rows[$rx]!='')
                            {
                                $inner_list = explode(',',$arr_rows[$rx]);
                                
                                //Exclude the previous inserted rows
                                if(inner_list[3] != 'I')
                                {
                                    $table_name = GIMCL_TBL_MEMBER_EDU;

                                    $data = array('member_id' => $current_user,
                                              'education_lvl' => 'CU',
                                              'aux_code'=>$inner_list[0],
                                              'description' => $inner_list[1],
                                              'duration'=> $inner_list[2],
                                              'status'=>'A');
                                    //$format = array('%s','%s');
                                    $this->db->insert($table_name,$data);
                                }
                            }
                        }
                    
                    
                    $json_result = json_encode([
                            'response'=>'OK',
                            'insert_id'=>$inserted_id,
                            'message'=>'Proceso de Actualizacion Completado']);
                    echo $json_result;
                    
                    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
                }
            }
            catch (Exception $e) 
            {
                $error  = $e->getMessage();
                $json_result = json_encode([
                    'response'=>'OK',
                    'insert_id'=>$error]);
                echo $json_result;
            }
        }
        wp_die();
    }
}