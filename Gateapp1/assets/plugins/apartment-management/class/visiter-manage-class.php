<?php
class Amgt_gatekeeper
{	
    //ADD GATE FUNCTION//
	public function amgt_add_gate($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_gates';
		$gatedata['created_date']=date('Y-m-d');
		$gatedata['created_by']=get_current_user_id();
			if(isset($data['counter']))
			{
				for($i=0;$i<=$data['counter'];$i++)
				{
					$gatedata['for_entry']='';
					$gatedata['for_exit']='';
					$gatedata['gate_name']=$data['gate_name_'.$i];
					if(isset($data['for_entry_'.$i]))
					$gatedata['for_entry']=$data['for_entry_'.$i];
					if(isset($data['for_exit_'.$i]))
					$gatedata['for_exit']=$data['for_exit_'.$i];
					
					if($data['action']=='edit')
					{
						$res_id=$this->amgt_check_gate_id($data['gate_id_'.$i]);
						if(isset($data['gate_id_'.$i]))
							$whereid['id']=$data['gate_id_'.$i];	
						if($res_id)
							$result=$wpdb->update( $table_name, $gatedata,$whereid );
						else
							$result=$wpdb->insert( $table_name, $gatedata );
					}
					else
					{						
						$result=$wpdb->insert( $table_name, $gatedata );
					}
					
				}
			}
			return $result;
	}
	// get visiter record entry
	public function amgt_get_visiter_entry_records($data)
	{
			$visitor_name=$data['visitor_name'];
			$mobile=$data['mobile'];
			$vehicle_number=$data['vehicle_number'];
			
			$entry_data=array();
			$i=0;
			foreach($visitor_name as $one_entry)
			{
				$entry_data[]= array('visitor_name'=>$one_entry,'mobile'=>$mobile[$i],'vehicle_number'=>$vehicle_number[$i]);
				$i++;
			}
			
			return json_encode($entry_data);
	}
	//ADD VISITOR ENTRY FUNCTION
	public function amgt_add_visitor_entry($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
		$entrydata['gate_id']=$data['gate'];
		$entrydata['checkin_date']=amgt_get_format_for_db($data['checkin_date']);
		$entrydata['checkin_time']=$data['checkintime'];
		$entrydata['description']=$data['description'];
		
		if(isset($data['checkin_type']))
		{
			$entrydata['checkin_type']=$data['checkin_type'];
			if($data['checkin_type']=='visitor_checkin')
			{
				$entry_value=$this->amgt_get_visiter_entry_records($data);
				$entrydata['status']=$data['status'];
				$entrydata['visitor_name']=$data['visitor_name'];$entrydata['mobile']=$data['mobile'];
				$entrydata['vehicle_number']=$data['vehicle_number'];
				$entrydata['reason_id']=$data['reason_id'];
				$entrydata['building_id']=$data['building_id'];
				$entrydata['unit_cat']=$data['unit_cat_id'];
				$entrydata['unit_name']=$data['unit_name'];
				$entrydata['visiters_value']=$entry_value;
			}
			if($data['checkin_type']=='staff_checkin')
			{
				$entrydata['member_id']=$data['member_id'];
			}
		}
		$entrydata['created_date']=date('Y-m-d');
		
		if($data['action']=='edit')
		{
			$whereid['id']=$data['vcheckin_id'];
			$result=$wpdb->update( $table_name, $entrydata,$whereid );
		}
		else
		{
			$entrydata['created_by']=get_current_user_id();
			$result=$wpdb->insert( $table_name, $entrydata );
			if($data['checkin_type']=='visitor_checkin')
			{
				if($result)
				{
					$apartmentname=get_option('amgt_system_name');
					$reson=get_the_title($data['reason_id']);
					$user_query = new WP_User_Query(
					array(
						'meta_key'	  =>	'unit_name',
						'meta_value'	=>	$data['unit_name']
					)
					);
					$allmembers = $user_query->get_results();
					$member_name=$allmembers[0]->display_name;
					$subject =get_option('wp_amgt_visitor_request_subject');
					$subject_search=array('{{member_name}}','{{apartment_name}}');
					$subject_replace=array($member_name,$apartmentname);
					$subject_replacement=str_replace($subject_search,$subject_replace,$subject);
					$message_content=get_option('wp_amgt_visitor_request_content');
					$search=array('{{admin_name}}','{{member_name}}','{{apartment_name}}','{{visit_reson}}','{{visit_time}}','{{visit_date}}','{{apartment_name}}');
					$replace = array($user_info->display_name,$member_name,$apartmentname,$reson,$data['checkintime'],$data['checkin_date'],$apartmentname);
					$message_content_replacement = str_replace($search, $replace, $message_content);
					$blogusers = get_users( [ 'role__in' => [ 'administrator'] ] );
					foreach ( $blogusers as $user )
					{
						$to = $user->user_email; 
						amgtSendEmailNotification($to,$subject_replacement,$message_content_replacement);
					}
				}
			}
		}
		return $result;
	}
	// GET ALL VISITOR CHECKIN ENTRIES
	public function Amgt_get_all_visitor_checkinentries()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where checkin_type='visitor_checkin'");
		return $result;
	
	}
	// GET ALL CHECKIN ENTRY OWNDATA
	public function amgt_get_all_visitor_checkinentries_owndata($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where checkin_type='visitor_checkin' and created_by=".$user_id);
		return $result;
	
	}
	// GET ALL CHECKIN ENTRY OWN DATA
	public function amgt_get_all_visitor_checkinentries_own($building_id,$unit_cat_id,$unit_name)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
		$result = $wpdb->get_results("SELECT * FROM $table_name where checkin_type='visitor_checkin' AND building_id=$building_id AND unit_cat=$unit_cat_id AND unit_name='$unit_name'");
		return $result;
	}
	//GET ALL STAFF CHECKIN ENTRIS FUNCTION
	public function amgt_get_all_staff_checkinentries()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
		
		$result = $wpdb->get_results("SELECT * FROM $table_name where checkin_type='staff_checkin'");
		return $result;
	
	}
	//GET ALL GATES FUNCTION
	public function Amgt_get_all_gates()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_gates';
		$result = $wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	}
	// CHECK GATE ID
	public function amgt_check_gate_id($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_gates';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		if(!empty($result))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	// GET SINGLE CHECKIN
	public function amgt_get_single_checkin($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		if(!empty($result))
		{
			return $result;
		}
		
	}
	//DELETE  GATES FUNCTION
	public function amgt_delete_gate($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_gates';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	// DELETE VISITOR CHECKIN ENTRY
	public function amgt_delete_visitor_checkin_entry($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	//VISITOR CHEKIN OUT ENTERYS FUNCTION
	public function amgt_visitor_check_out_entry($data)
	{
		date_default_timezone_set('Asia/Kolkata');
		
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
		$entrydata['checkout_time']=date( 'h:i A');
		$entrydata['exit_gate_id']=$data['gate_name'];
		$whereid['id']=$data['checkin_id'];
		$result=$wpdb->update( $table_name, $entrydata,$whereid );
		return $result;
	}
	// GET MEMBER BY BADGEID
	public function amgt_get_memberby_basedid($badgeid)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'usermeta';
		$result = $wpdb->get_row("SELECT * FROM $table_name where meta_key='badge_id' AND meta_value='$badgeid'");
		if(!empty($result))
			return $result;
		
	}
	//GET ALL EXIT  GATES FUNCTION
	public function amgt_get_all_exit_gates()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_gates';
		$result = $wpdb->get_results("SELECT * FROM $table_name where for_exit='yes'");
		return $result;
	}
	//GET ALL ENTRY GATES FUNCTION
	public function amgt_get_all_entry_gates()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_gates';
		$result = $wpdb->get_results("SELECT * FROM $table_name where for_entry='yes' OR for_exit='yes'");
		return $result;
	}
	
//ADD VISITOR Ruqest FUNCTION//
	public function amgt_add_visitor_request($data)
	{
		 
		global $wpdb;
		$amgt_visitor_request = $wpdb->prefix. 'amgt_visitor_request';
		$request_data['visitor_name']=$data['visitor_name'];
		$request_data['mobile']=$data['id'];
		$request_data['vehicle_number']=$data['vehicle_number'];
		$request_data['member_id']=$data['member'];
		$request_data['visit_date']=amgt_get_format_for_db($data['visit_date']);
		$request_data['description']=$data['description'];
		$request_data['created_date']=date('Y-m-d');
		$request_data['status']=$data['status'];
		$request_data['created_by']=get_current_user_id();
		
		
		if($data['action']=='edit')
		{
			$whereid['id']=$data['visitor_request_id'];
			$result=$wpdb->update( $amgt_visitor_request, $request_data,$whereid );
		}
		else
		{
			$result=$wpdb->insert( $amgt_visitor_request, $request_data );
			if($result)
			{
				$user_info = get_userdata(1);
				$to = $user_info->user_email; 
				$apartmentname=get_option('amgt_system_name');
				
				$member_info = get_userdata($data['member']);
                $member_name = $member_info->display_name;
				
				$subject =get_option('wp_amgt_visitor_request_subject');
				$subject_search=array('{{member_name}}','{{apartment_name}}');
		        $subject_replace=array($member_name,$apartmentname);
				$subject_replacement=str_replace($subject_search,$subject_replace,$subject);
				
				$message_content=get_option('wp_amgt_visitor_request_content');
				$search=array('{{admin_name}}','{{visitor_name}}','{{visit_date}}','{{apartment_name}}');
				$replace = array($user_info->display_name,$data['visitor_name'],$data['visit_date'],$apartmentname);
				$message_content_replacement = str_replace($search, $replace, $message_content);
				amgtSendEmailNotification($to,$subject_replacement,$message_content_replacement);
			}
		}
		return $result;
	}
	
	//GET ALL Ruqest Function
	public function amgt_get_all_request()
	{
		global $wpdb;
		$amgt_visitor_request = $wpdb->prefix. 'amgt_visitor_request';
		$result = $wpdb->get_results("SELECT * FROM $amgt_visitor_request");
		return $result;
	}
	
	//GET ALL Ruqest Function
	public function amgt_get_all_request_curent_user_id()
	{
		global $wpdb;
		
		$get_current_user_id=get_current_user_id();
		$amgt_visitor_request = $wpdb->prefix. 'amgt_visitor_request';
		$result = $wpdb->get_results("SELECT * FROM $amgt_visitor_request where member_id=$get_current_user_id");
		return $result;
	}
	// GET SINGLE VISITOR REQUEST	
	public function amgt_get_single_visitor_request($id)
	{
		global $wpdb;
		$amgt_visitor_request = $wpdb->prefix. 'amgt_visitor_request';
		$result = $wpdb->get_row("SELECT * FROM $amgt_visitor_request where id=".$id);
		if(!empty($result))
		{
			return $result;
		}
	}
	// DELETE VISITOR REQUEST
	public function amgt_delete_visitor_request($id)
	{
		global $wpdb;
		$amgt_visitor_request = $wpdb->prefix. 'amgt_visitor_request';
		$result = $wpdb->query("DELETE FROM $amgt_visitor_request where id= ".$id);
		return $result;
	}
	/*<---GET ALL TASK BY DUEDATE WISE FUNCTION--->*/
	public function amgt_get_visitor_request_filter_data($starting_date,$ending_date,$status)
	{
		global $wpdb;
		$amgt_visitor_request = $wpdb->prefix. 'amgt_visitor_request';
		$result = $wpdb->get_results("SELECT * FROM $amgt_visitor_request where visit_date >= '$starting_date' AND visit_date <= '$ending_date' AND status=$status");
		 
		return $result;	
		
	}
	// EXPORT SELECTED VISITOR
	public function amgt_export_selected_visitor($all)
	{		
		global $wpdb;
		$amgt_visitor_request = $wpdb->prefix. 'amgt_visitor_request';
		$result = $wpdb->get_results("select * FROM $amgt_visitor_request where id IN($all)");
		return $result;
	}
	// FILTER CHECKIN ENTRY
	public function amgt_get_all_visitor_checkinentries_filter($starting_date,$ending_date,$status)
	{
		global $wpdb;
		$amgt_checkin_entry = $wpdb->prefix. 'amgt_checkin_entry';
		$result = $wpdb->get_results("SELECT * FROM $amgt_checkin_entry where checkin_type='visitor_checkin' and checkin_date >= '$starting_date' AND checkin_date <= '$ending_date' AND status=$status");
		 
		return $result;	
	}

}
//END CLASS
?>