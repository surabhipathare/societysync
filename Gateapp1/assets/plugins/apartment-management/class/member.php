<?php
class Amgt_Member
{
	//ADD MEMBER FUNCTION
	public function amgt_add_member($data)
	{
	 
		global $wpdb;
		$table_amgt_unit_occupied_history = $wpdb->prefix. 'amgt_unit_occupied_history';
		if(isset($data['building_id']))
			$usermetadata['building_id']=$data['building_id'];
		if(isset($data['unit_cat_id']))
			$usermetadata['unit_cat_id']=$data['unit_cat_id'];
		if(isset($data['unit_name']))
			$usermetadata['unit_name']=MJamgt_strip_tags_and_stripslashes($data['unit_name']);
		if(isset($data['unnit_measurement']))
			$usermetadata['unnit_measurement']=MJamgt_strip_tags_and_stripslashes($data['unnit_measurement']);
		if(isset($data['unnit_chanrges']))
			$usermetadata['unnit_chanrges']=MJamgt_strip_tags_and_stripslashes($data['unnit_chanrges']);
		if(isset($data['member_type']))
			$usermetadata['member_type']=MJamgt_strip_tags_and_stripslashes($data['member_type']);
		if(isset($data['occupied_by']))
			$usermetadata['occupied_by']=MJamgt_strip_tags_and_stripslashes($data['occupied_by']);
		if(isset($data['occupied_date']))
			$usermetadata['occupied_date']=amgt_get_format_for_db($data['occupied_date']);
		if(isset($data['middle_name']))
			$usermetadata['middle_name']=MJamgt_strip_tags_and_stripslashes($data['middle_name']);
		if(isset($data['gender']))
		$usermetadata['gender']=$data['gender'];
		if(isset($data['birth_date']))
			$usermetadata['birth_date']=amgt_get_format_for_db($data['birth_date']);
		if(isset($data['mobile']))
		$usermetadata['mobile']=MJamgt_strip_tags_and_stripslashes($data['mobile']);
		if(isset($data['gst_no']))
		$usermetadata['gst_no']=MJamgt_strip_tags_and_stripslashes($data['gst_no']);
		if(isset($data['phone']))
		$usermetadata['phone']=MJamgt_strip_tags_and_stripslashes($data['phone']);
		if(isset($data['address']))
		$usermetadata['address']=MJamgt_strip_tags_and_stripslashes($data['address']);
		if(isset($data['address']))
			$usermetadata['address']=MJamgt_strip_tags_and_stripslashes($data['address']);
		if(isset($data['city_name']))
			$usermetadata['city_name']=MJamgt_strip_tags_and_stripslashes($data['city_name']);
		if(isset($data['state_name']))
			$usermetadata['state_name']=MJamgt_strip_tags_and_stripslashes($data['state_name']);
		if(isset($data['country_name']))
			$usermetadata['country_name']=MJamgt_strip_tags_and_stripslashes($data['country_name']);
		if(isset($data['zipcode']))
			$usermetadata['zipcode']=$data['zipcode'];
		if(isset($data['amgt_user_avatar']))
			$usermetadata['amgt_user_avatar']=$data['amgt_user_avatar'];
		 $usermetadata['created_by']=get_current_user_id();
		if(isset($data['username']))
			$userdata['user_login']=MJamgt_strip_tags_and_stripslashes($data['username']);
		if(isset($data['email']))
			$userdata['user_email']=$data['email'];
		if(isset($data['first_name']))
			$userdata['display_name']=$data['first_name']." ".$data['last_name'];
	 
		if($data['password'] != "")
			$userdata['user_pass']=$data['password']; 
		 
			if(isset($data['committee_member']))
			{
				$usermetadata['committee_member']='yes';
				if(isset($data['designation_id']))
					$usermetadata['designation_id']=$data['designation_id'];
			}
			else
			{
				$usermetadata['committee_member']='no';
			}	
			if($data['role']=='staff_member' || $data['role']=='accountant')
			{
				if(isset($data['birth_date']))
				$usermetadata['birth_date']=amgt_get_format_for_db($data['birth_date']);
				if(isset($data['staff_category']))
				$usermetadata['staff_category']=MJamgt_strip_tags_and_stripslashes($data['staff_category']);
				if(isset($data['badge_id']))
				$usermetadata['badge_id']=$data['badge_id'];
				if(isset($data['qualification']))
				$usermetadata['qualification']=MJamgt_strip_tags_and_stripslashes($data['qualification']);
				if(isset($data['skills']))
				$usermetadata['skills']=MJamgt_strip_tags_and_stripslashes($data['skills']);			
			}			
			if($data['role']=='gatekeeper')	
			{
				
				if(isset($data['birth_date']))
				$usermetadata['birth_date']=amgt_get_format_for_db($data['birth_date']);
				if(isset($data['address']))
					$usermetadata['address']=MJamgt_strip_tags_and_stripslashes($data['address']);	
				if(isset($data['gate']))
					$usermetadata['aasigned_gate']=MJamgt_strip_tags_and_stripslashes($data['gate']);						
			}
			if($data['action']=='edit')
			{
				$userdata['ID']=$data['user_id'];
				$user_id = wp_update_user($userdata);
				$user_data = get_userdata($user_id);
				$membername=$user_data->display_name;		
				if(!empty($data['occupied_by']))
				{
					$historydata['building_id']=$data['building_id'];
					$historydata['unit_cat_id']=$data['unit_cat_id'];
					$historydata['unit_name']=MJamgt_strip_tags_and_stripslashes($data['unit_name']);
					$historydata['member_name']=$user_data->display_name;
					$result_history_id = $wpdb->get_row("SELECT id FROM $table_amgt_unit_occupied_history where member_name='$membername'");
					
					$whereid['id']=$result_history_id->id;
					$entry_data=array();
					
					$entry_data[]= array('email'=>$data['email'],'mobile'=>$data['mobile'],'address'=>$data['address']);
					$member_contact_details=json_encode($entry_data);				
					$historydata['member_contact_details']=$member_contact_details;
					$historydata['occupied_from_date']=amgt_get_format_for_db($data['occupied_date']);	
					if(!empty($result_history_id->id))
					{					
						$result=$wpdb->update($table_amgt_unit_occupied_history,$historydata,$whereid);
					}
					else
					{
						$result=$wpdb->insert($table_amgt_unit_occupied_history,$historydata);
					}		
				}		
				$committee_member_status=get_user_meta($user_id,'committee_member',true);
				if($committee_member_status=='yes' && $usermetadata['committee_member']=='no')
				{
					//-- committee member REMOVE  mail template-- //
					$user_info = get_userdata($data['user_id']);
					$to = $user_info->user_email; 
					$apartmentname=get_option('amgt_system_name');
					$subject =get_option('wp_amgt_Member_removed_committee_subject');
					$message_content=get_option('wp_amgt_Member_removed_committee_email_template');
					$subject_search=array('{{apartment_name}}');
					$subject_replace=array($apartmentname);
					$search=array('{{member_name}}','{{apartment_name}}');
					$replace = array($user_info->display_name,$apartmentname,$loginlink);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$message_content = str_replace($search, $replace, $message_content);
					amgtSendEmailNotification($to,$subject,$message_content);
				}
				else{
					
					//adds committee member mail template
					$user_info = get_userdata($user_id);
					$to = $user_info->user_email; 
					$subject =get_option('wp_amgt_Member_Become_committee_subject');
					$loginlink=home_url().'/apartment-management/';
					$apartmentname=get_option('amgt_system_name');
					$subject_search=array('{{apartment_name}}');
					$subject_replace=array($apartmentname);
					$message_content=get_option('wp_amgt_Member_Become_committee_email_template');
					$search=array('{{member_name}}','{{apartment_name}}','{{loginlink}}');
					$replace = array($user_info->display_name,$apartmentname,$loginlink);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$message_content = str_replace($search, $replace, $message_content);
					amgtSendEmailNotification($to,$subject,$message_content);
					//end adds committee member mail template
				}
				$returnans=update_user_meta( $user_id, 'first_name', $data['first_name'] );
				$returnans=update_user_meta( $user_id, 'last_name', $data['last_name'] );
					foreach($usermetadata as $key=>$val)
					{
						$returnans=update_user_meta( $user_id, $key,$val );
					}
					
					return $user_id;
			}
			else
			{
				$user_id = wp_insert_user( $userdata );		
				$user_data = get_userdata($user_id);
				if(!empty($data['occupied_by']))
				{
					$historydata['building_id']=$data['building_id'];
					$historydata['unit_cat_id']=$data['unit_cat_id'];
					$historydata['unit_name']=MJamgt_strip_tags_and_stripslashes($data['unit_name']);
					$historydata['member_name']=$user_data->display_name;
					$entry_data=array();
					
					$entry_data[]= array('email'=>$data['email'],'mobile'=>$data['mobile'],'address'=>$data['address']);
					$member_contact_details=json_encode($entry_data);				
					$historydata['member_contact_details']=$member_contact_details;
					$historydata['occupied_from_date']=amgt_get_format_for_db($data['occupied_date']);				
					$result=$wpdb->insert($table_amgt_unit_occupied_history,$historydata);
				}	
				$user = new WP_User($user_id);
				$user->set_role($data['role']);
				foreach($usermetadata as $key=>$val){
					$returnans=add_user_meta( $user_id, $key,$val, true );
				}
				if(isset($data['first_name']))
				$returnans=update_user_meta( $user_id, 'first_name', $data['first_name'] );
				if(isset($data['last_name']))
				$returnans=update_user_meta( $user_id, 'last_name', $data['last_name'] );
				  //adds committee member mail template
			   if($usermetadata['committee_member']=='yes')
				{
					
					$user_info = get_userdata($user_id);
					$to = $user_info->user_email; 
					$subject =get_option('wp_amgt_Member_Become_committee_subject');
					$loginlink=home_url().'/apartment-management/';
					$apartmentname=get_option('amgt_system_name');
					$subject_search=array('{{apartment_name}}');
					$subject_replace=array($apartmentname);
					$message_content=get_option('wp_amgt_Member_Become_committee_email_template');
					$search=array('{{member_name}}','{{apartment_name}}','{{loginlink}}');
					$replace = array($user_info->display_name,$apartmentname,$loginlink);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$message_content = str_replace($search, $replace, $message_content);
					amgtSendEmailNotification($to,$subject,$message_content);
				}
					//ADD USER TIME SEND MAIL 
					$user_info = get_userdata($user_id);
					$to = $user_info->user_email; 
					$subject =get_option('wp_amgt_add_user_subject');
					$apartmentname=get_option('amgt_system_name');
					$message_content=get_option('wp_amgt_add_user_email_template');
					$loginlink=home_url().'/apartment-management/';
					$arrayvar=explode("_",$data['role']);
					$role="";
					foreach($arrayvar as $name)
					{
						$role.=$name." ";
					} 
					$rolename=ucwords($role);
					
					$subject_search=array('{{rolename}}','{{apartment_name}}');
					$subject_replace=array($rolename,$apartmentname);
					$search=array('{{member_name}}','{{apartment_name}}','{{rolename}}','{{loginlink}}','{{username}}','{{password}}');
					$replace = array($user_info->display_name,$apartmentname,$rolename,$loginlink,$data['username'],$data['password']);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$message_content = str_replace($search, $replace, $message_content);
					amgtSendEmailNotification($to,$subject,$message_content);
				return $user_id;
			}
	}
	//DELETE USERDATA
	public function amgt_delete_usedata($record_id)
	{
		global $wpdb;
		$user_data = get_userdata($record_id);
		$member_name=$user_data->display_name;
		$table_name = $wpdb->prefix . 'usermeta';
		$table_amgt_unit_occupied_history = $wpdb->prefix . 'amgt_unit_occupied_history';
		$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE user_id= %d",$record_id));
		$retuenval=wp_delete_user( $record_id );
		$history_result = $wpdb->get_row("SELECT id FROM $table_amgt_unit_occupied_history where member_name='$member_name'");
	
		$whereid['id']=$history_result->id;
		$historydata['occupied_to_date']=date('Y-m-d');
		$result_update_history=$wpdb->update( $table_amgt_unit_occupied_history, $historydata ,$whereid);
		
		return $retuenval;
	}
	
	//UPLOAD USER DOCUMENT FUNCTION

	public function amgt_upload_documents($id_proof_1,$id_proof_2,$document_title,$upload_docs_array,$user_id)

	{
		
		$usermetadata['id_proof_1']=$id_proof_1;

		$usermetadata['id_proof_2']=$id_proof_2;	

		
		foreach($usermetadata as $key=>$val)

		{

			$returnans=add_user_meta( $user_id,$key,$val,true);					

		}	
		if(!empty($upload_docs_array))
		{
			$returnans1=array();
			foreach($document_title as $key=>$value)
			{
				$returnans1[]=array("title" =>$value,"value" =>$upload_docs_array[$key]);				  
			}
			
			$doc_data=json_encode($returnans1); 
			
			$doc_result=add_user_meta( $user_id,"document",$doc_data,true);	
			
		}
		

	}

	//UPLOAD USER DOCUMENT FUNCTION

	public function amgt_update_upload_documents($id_proof_1,$id_proof_2,$document_title,$upload_docs_array,$user_id)

	{
		
		$usermetadata['id_proof_1']=$id_proof_1;

		$usermetadata['id_proof_2']=$id_proof_2;


		foreach($usermetadata as $key=>$val)

		{

			$returnans=update_user_meta($user_id,$key,$val);				

		}

		if(!empty($upload_docs_array))
		{
			$returnans1=array();
			foreach($document_title as $key=>$value)
			{
				$returnans1[]=array("title" =>$value,"value" =>$upload_docs_array[$key]);				  
			}
			
			$doc_data=json_encode($returnans1); 
			
			$doc_result=update_user_meta( $user_id,"document",$doc_data);	
			
			
		}
		
	}
}
?>