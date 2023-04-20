<?php
class Amgt_NoticeEvents
{	
   //ADD NOTICE FUNCTION
	public function amgt_add_notice($data,$file_name)
	{
	    $obj_apartment=new Apartment_management(get_current_user_id());
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_notice';
		$noticedata['notice_title']=MJamgt_strip_tags_and_stripslashes($data['notice_title']);
		$noticedata['notice_type']=MJamgt_strip_tags_and_stripslashes($data['notice_type']);
		$noticedata['notice_doc']=$file_name;
		$noticedata['description']=MJamgt_strip_tags_and_stripslashes($data['description']);
		$noticedata['valid_date']=amgt_get_format_for_db($data['notice_valid']);
		if($obj_apartment->role=='administrator')
		{
			$noticedata['status']='Open';
		}
		else
		{
			$noticedata['status']='Not Approved';
		}
		$noticedata['created_date']=date('Y-m-d');
		
		if($data['action']=='edit')
		{
			$whereid['id']=$data['notice_id'];
			$result=$wpdb->update( $table_name, $noticedata ,$whereid);
			return $result;
		}
		else
		{
			$noticedata['created_by']=get_current_user_id();
			$result=$wpdb->insert( $table_name, $noticedata );
			$get_members = array('role' => 'member');
			$membersdata=get_users($get_members);
			if(!empty($membersdata))
			{
				$user_id = array();
				foreach($membersdata as $retrieved_data1)
				{
					$user_id[]=$retrieved_data1->ID;
				}
			}
			$amgt_sms_service_enable=0;
			if(isset($_POST['amgt_sms_service_enable']))
			$amgt_sms_service_enable = $_POST['amgt_sms_service_enable'];
			if($amgt_sms_service_enable)
			{
				$user_number=array();
				foreach($user_id as $user)
				{
					if(!empty(get_user_meta($user, 'phonecode',true))){ $phone_code=get_user_meta($user, 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
								
					$user_number[] = $phone_code.get_user_meta($user, 'mobile',true);
				
				}
				//---------------- SEND  SMS ------------------//
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
				if(is_plugin_active('sms-pack/sms-pack.php'))
				{
					
					$apartmentname=get_option('amgt_system_name');
					$message_content = MJamgt_strip_tags_and_stripslashes($_POST['sms_template']);
					$current_sms_service 	= get_option( 'smgt_sms_service');
					$args = array();
					$args['mobile']=$user_number;
					$args['message_from']="NOTICE";
					$args['message']=$message_content;					
					if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
					{				
						$send = send_sms($args);							
					}
				}
			}
			return $result;
		}
	}
	
	//ADD EVENT FUNCTION
	public function amgt_add_event($data,$file_name)
	{
		$obj_apartment=new Apartment_management(get_current_user_id());
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_events';
		$eventdata['event_title']=MJamgt_strip_tags_and_stripslashes($data['notice_title']);
		$eventdata['description']=MJamgt_strip_tags_and_stripslashes($data['description']);
		$eventdata['start_date']=amgt_get_format_for_db($data['start_date']);
		$eventdata['start_time']=$data['start_time'];
		$eventdata['end_date']=amgt_get_format_for_db($data['end_date']);
		$eventdata['end_time']=$data['end_time'];
		$eventdata['event_doc']=$file_name;
	
		if(isset($data['publish']))
		{	
			$eventdata['publish_status']=$data['publish'];
		}
		else
		{
			$eventdata['publish_status']='no';
		}
		$eventdata['created_date']=date('Y-m-d');
		$eventdata['created_by']=get_current_user_id();
		if($data['action']=='edit')
		{
			
			$whereid['id']=$data['event_id'];
			$result=$wpdb->update( $table_name, $eventdata ,$whereid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_name, $eventdata );
			//--------Event NOTIFICATION EMAIL CODE-------
			$get_members = array('role' => 'member');
			$membersdata=get_users($get_members);
			
			if(!empty($membersdata))
			{
				$user_id = array();
				foreach($membersdata as $retrieved_data1)
				{
					$user_id[]=$retrieved_data1->ID;
				}
			}
			$amgt_sms_service_enable=0;
			if(isset($_POST['amgt_sms_service_enable']))
			$amgt_sms_service_enable = $_POST['amgt_sms_service_enable'];
			if($amgt_sms_service_enable)
			{
				$user_number=array();
				foreach($user_id as $user)
				{
					if(!empty(get_user_meta($user, 'phonecode',true))){ $phone_code=get_user_meta($user, 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
								
					$user_number[] = $phone_code.get_user_meta($user, 'mobile',true);
				
				}
				//---------------- SEND  SMS ------------------//
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
				if(is_plugin_active('sms-pack/sms-pack.php'))
				{
					
					$apartmentname=get_option('amgt_system_name');
					$message_content = MJamgt_strip_tags_and_stripslashes($_POST['sms_template']);
					$current_sms_service 	= get_option( 'smgt_sms_service');
					$args = array();
					$args['mobile']=$user_number;
					$args['message_from']="Event";
					$args['message']=$message_content;					
					if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
					{				
						$send = send_sms($args);							
					}
				}
			}	
			if(!empty($membersdata))
			{
				foreach($membersdata as $retrieved_data)
				{
					$to = $retrieved_data->user_email; 
					$subject =get_option('wp_amgt_add_event_subject');
					$apartmentname=get_option('amgt_system_name');
					$page_link=home_url().'/?apartment-dashboard=user&page=notice-event&tab=event_list';
					$subject_search=array('{{apartment_name}}');
		            $subject_replace=array($apartmentname);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$message_content=get_option('wp_amgt_add_event_email_template');
					$search=array('{{member_name}}','{{apartment_name}}','{{event_title}}','{{event_start_date}}','{{event_end_date}}','{{event_start_time}}','{{event_end_time}}','{{event_description}}','{{Event_Link}}');
					$replace = array($retrieved_data->display_name,$apartmentname,$eventdata['event_title'],$eventdata['start_date'],$eventdata['end_date'],$eventdata['start_time'],$eventdata['end_time'],$eventdata['description'],$page_link);
					$message_content = str_replace($search, $replace, $message_content);
					amgtSendEmailNotification($to,$subject,$message_content);
					
				}
			}
			
			return $result;
		}
	
	}
	// GET ALL EVENTS
	public function amgt_get_all_events()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_events';
		$obj_apartment=new Apartment_management(get_current_user_id());
		if($obj_apartment->role=='administrator'){
		   $result = $wpdb->get_results("SELECT * FROM $table_name");
		}
		else
		{
			$result = $wpdb->get_results("SELECT * FROM $table_name where publish_status='yes'");
		}
		return $result;
	
	}
	// GET OWN EVENTS
	public function amgt_get_own_events($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_events';
		$obj_apartment=new Apartment_management(get_current_user_id());
		if($obj_apartment->role=='administrator'){
		   $result = $wpdb->get_results("SELECT * FROM $table_name");
		}
		else
		{
			$result = $wpdb->get_results("SELECT * FROM $table_name where publish_status='yes' and created_by=".$user_id);
		}
		return $result;
	
	}
	// GET ALL NOTICE
	public function amgt_get_all_notice()
	{
		global $wpdb;
		
		$table_name = $wpdb->prefix. 'amgt_notice';
		$obj_apartment=new Apartment_management(get_current_user_id());
		if($obj_apartment->role=='administrator'){
			$result = $wpdb->get_results("SELECT * FROM $table_name");
		}
		else
		{
			$result = $wpdb->get_results("SELECT * FROM $table_name where status='open'");
		}
		return $result;
	
	}
	// GET OWN NOTICES
	public function amgt_get_own_notice($user_id)
	{
		global $wpdb;
		
		$table_name = $wpdb->prefix. 'amgt_notice';
		$obj_apartment=new Apartment_management(get_current_user_id());
		if($obj_apartment->role=='administrator'){
			$result = $wpdb->get_results("SELECT * FROM $table_name");
		}
		else
		{
			$result = $wpdb->get_results("SELECT * FROM $table_name where status='open' and created_by=".$user_id);
		}
		return $result;
	
	}
	// GET SINGLE NOTICE
	public function amgt_get_single_notice($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_notice';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	// GET SINGLE EVENT
	public function amgt_get_single_event($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_events';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	// DELETE NOTICE
	public function amgt_delete_notice($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_notice';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	// DELETE EVENTS
	public function amgt_delete_event($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_events';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	// GET NOTICE FOR DASHBOARD
	public function amgt_get_notice_list_ondashboard()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_notice';
		$currentdate=date('Y-m-d');
		$result = $wpdb->get_results("SELECT * FROM $table_name where status='open' AND valid_date >= '$currentdate' order by 'created_date' DESC limit 2");
		return $result;
	
	}
	//Approved NOTICE FUNCTION
	public function amgt_approve_notice($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_notice';
		$whereid['id']=$id;
		$noticedata['status']='Open';
		$result=$wpdb->update( $table_name, $noticedata ,$whereid);
		if($result)
		{
			$noticeData=$this->amgt_get_single_notice($id);
			//---------NOTICE NOTIFICATION EMAIL CODE-------
			$get_members = array('role' => 'member');
			$membersdata=get_users($get_members);
			if(!empty($membersdata))
			{
				foreach($membersdata as $retrieved_data)
				{
					$to = $retrieved_data->user_email; 
					$subject =get_option('wp_amgt_add_notice_subject');
					$page_link=home_url().'/?apartment-dashboard=user&page=notice-event&tab=notice_list';
					$apartmentname=get_option('amgt_system_name');
					$message_content=get_option('wp_amgt_add_notice_email_template');
					$subject_search=array('{{member_name}}','{{apartment_name}}');
		            $subject_replace=array($retrieved_data->display_name,$apartmentname);
					$search=array('{{member_name}}','{{apartment_name}}','{{notice_title}}','{{notice_type}}','{{notice_valid_date}}','{{notice_content}}','{{Notice_Link}}');
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$replace = array($retrieved_data->display_name,$apartmentname,$noticeData->notice_title,$noticeData->notice_type,$noticeData->valid_date,$noticeData->description,$page_link);
					$message_content = str_replace($search, $replace, $message_content);
					amgtSendEmailNotification($to,$subject,$message_content);
					
				}
			}
			
		}
		
		return $result;
	}
	//Approved EVENT FUNCTUION
	public function amgt_approve_event($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_events';
		$whereid['id']=$id;
		$eventdata['publish_status']='yes';
		$result=$wpdb->update( $table_name, $eventdata ,$whereid);
		return $result;
	}
	// GET OWN NOTICE DASHBOARD
	public function amgt_get_own_notice_dashboard($user_id)
	{
		global $wpdb;
		
		$table_name = $wpdb->prefix. 'amgt_notice';
		$currentdate=date('Y-m-d');
		
		$result = $wpdb->get_results("SELECT * FROM $table_name where status='open' and created_by='$user_id' AND valid_date >= '$currentdate' order by 'created_date' DESC limit 2");
		return $result;
	}
	// GET ALL NOTICE DASHBOARD
	public function amgt_get_all_notice_dashboard()
	{
		global $wpdb;
		
		$table_name = $wpdb->prefix. 'amgt_notice';
		$currentdate=date('Y-m-d');
		$result = $wpdb->get_results("SELECT * FROM $table_name where status='open' AND valid_date >= '$currentdate' order by 'created_date' DESC limit 2");
		
		return $result;
	
	}
}
?>