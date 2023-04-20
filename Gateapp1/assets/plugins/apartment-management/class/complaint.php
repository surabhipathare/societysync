<?php
class Amgt_Complaint
{	//ADD COMPLAIN FUNCTION
	public function amgt_add_complaint($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
		if($data['complaint_nature'] == 'Maintenance Request')
		{
			$complaintdata['complain_date']=amgt_get_format_for_db($data['date']);
			$complaintdata['time']=$data['time'];
		}
		else
		{
			$complaintdata['complain_date']=amgt_get_format_for_db($data['complain_date']);
			$complaintdata['time']='';
		}
		$complaintdata['complaint_cat']=$data['category'];
		$complaintdata['complaint_nature']=$data['complaint_nature'];
		$complaintdata['complaint_member_id']=$data['member_id'];
		$complaintdata['complaint_type']=MJamgt_strip_tags_and_stripslashes($data['type']);
		$complaintdata['complaint_description']=MJamgt_strip_tags_and_stripslashes($data['description']);
		$complaintdata['complain_title']=MJamgt_strip_tags_and_stripslashes($data['complain_title']);
		$complaintdata['created_date']=date('Y-m-d');
		
		if($data['action']=='edit')
		{
			$complaintdata['complaint_status']=MJamgt_strip_tags_and_stripslashes($data['status']);
			$complaintdata['resolution']=MJamgt_strip_tags_and_stripslashes($data['resolution']);
			$whereid['id']=$data['complaint_id'];
			$result=$wpdb->update( $table_name, $complaintdata ,$whereid);
			return $result;
		}
		else
		{
			$complaintdata['complaint_status']='open';
			$complaintdata['resolution']='';
			//--------NOTICE NOTIFICATION EMAIL CODE-------
			if($complaintdata['complaint_type']=='individual')
			{	
				//---------------- SEND  SMS ------------------//
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
				if(is_plugin_active('sms-pack/sms-pack.php'))
				{
					if(!empty(get_user_meta($data['member_id'], 'phonecode',true))){ $phone_code=get_user_meta($data['member_id'], 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
									
					$user_number[] = $phone_code.get_user_meta($data['member_id'], 'mobile',true);
					$apartmentname=get_option('amgt_system_name');
					$message_content ="New Complaint added in $apartmentname .";
					$current_sms_service 	= get_option( 'smgt_sms_service');
					$args = array();
					$args['mobile']=$user_number;
					$args['message_from']="Complaint";
					$args['message']=$message_content;					
					if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
					{				
						$send = send_sms($args);							
					}
				}
				
		        //SEND NOTICE NOTIFICATION MEMBER //
				$retrieved_data=get_userdata($data['member_id']);
				$to = $retrieved_data->user_email; 
				$member_name=$retrieved_data->display_name;
				$subject =get_option('wp_amgt_add_complaint_subject');
				$apartmentname=get_option('amgt_system_name');
				$page_link=home_url().'/?apartment-dashboard=user&page=complaint&tab=complaintlist';
				$message_content=get_option('wp_amgt_add_complaint_email_template');
				$catdata=get_post($complaintdata['complaint_cat']);
				if(!empty($complaintdata['complaint_cat']))
				{
					$category=$catdata->post_title;
				}
				else
				{
					$category="-";
				}
				$apartmentnumber=get_user_meta($retrieved_data->ID,'unit_name',true);
				$fromdata=get_userdata(get_current_user_id());
				$complaintfrom=$fromdata->display_name;
				$useremail=$fromdata->user_email;
				$subject_search=array('{{member_name}}','{{apartment_name}}');
		        $subject_replace=array($member_name,$apartmentname);
				$subject=str_replace($subject_search,$subject_replace,$subject);
				$search=array('{{member_name}}','{{apartment_name}}','{{nature}}','{{noticetype}}','{{noticecategory}}','{{complaintstatus}}','{{description}}','{{apartmentnumber}}','{{complainfrom}}','{{Complain_Link}}');
				$replace = array($member_name,$apartmentname,$complaintdata['complaint_nature'],$complaintdata['complaint_type'],$category,$complaintdata['complaint_status'],$complaintdata['complaint_description'],$apartmentnumber,$complaintfrom,$page_link);
				$message_content = str_replace($search, $replace, $message_content);
				amgtSendEmailNotification($to,$subject,$message_content);
				
				//send mail notification for admin//
				$admin_subject =get_option('wp_amgt_Admin_Complain');
				$page_link=admin_url().'admin.php?page=amgt-complaint&tab=complaintlist';
				$admin_subject_search=array('{{member_name}}','{{apartment_name}}');
		        $admin_subject_replace=array($member_name,$apartmentname);
				$admin_subject=str_replace($admin_subject_search,$admin_subject_replace,$admin_subject);
				$admin_message_content=get_option('wp_amgt_admin_complain_email_template');
			    $blogusers = get_users( [ 'role__in' => [ 'administrator'] ] );
				foreach ( $blogusers as $user ) 
				{
					$email =$user->user_email ;
					$display_name=$user->display_name;
					
					$admin_search=array('{{admin_name}}','{{apartment_name}}','{{nature}}','{{noticetype}}','{{noticecategory}}','{{complaintstatus}}','{{description}}','{{apartmentnumber}}','{{complainfrom}}','{{complainto}}','{{Admin_Complain_Link}}');
					$admin_replace = array($display_name,$apartmentname,$complaintdata['complaint_nature'],$complaintdata['complaint_type'],$category,$complaintdata['complaint_status'],$complaintdata['complaint_description'],$apartmentnumber,$complainfrom,'All Member of Society.',$page_link);
					$admin_message_content = str_replace($admin_search, $admin_replace, $admin_message_content);
					amgtSendEmailNotification($email,$admin_subject,$admin_message_content);
				}
				
			}
			else
			{
				
				$fromdata=get_userdata(get_current_user_id());
				$complainfrom=$fromdata->display_name;
				$apartmentname=get_option('amgt_system_name');
				$apartmentnumber=get_user_meta(get_current_user_id(),'unit_name',true);
				$catdata=get_post($complaintdata['complaint_cat']);
				if(!empty($complaintdata['complaint_cat']))
				{
					$category=$catdata->post_title;
				}
				else
				{
					$category="-";
				}
				
				//send mail notification for admin//
				$admin_subject =get_option('wp_amgt_Admin_Complain');
				$page_link=admin_url().'admin.php?page=amgt-complaint&tab=complaintlist';
				$admin_subject_search=array('{{member_name}}','{{apartment_name}}');
		        $admin_subject_replace=array($complainfrom,$apartmentname);
				$admin_subject=str_replace($admin_subject_search,$admin_subject_replace,$admin_subject);
				$admin_message_content=get_option('wp_amgt_admin_complain_email_template');
			    $blogusers = get_users( [ 'role__in' => [ 'administrator'] ] );
				
				//---------------- SEND  SMS ------------------//
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
				if(is_plugin_active('sms-pack/sms-pack.php'))
				{
					$mail_id = array();
					foreach ( $blogusers as $user_id ) 
					{
						$mail_id[]=$user_id->ID;
					}
					$user_number=array();
					foreach($mail_id as $user_id1)
					{
						if(!empty(get_user_meta($user_id1, 'phonecode',true))){ $phone_code=get_user_meta($user_id1, 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
									
						$user_number[] = $phone_code.get_user_meta($user_id1, 'mobile',true);
					}
					
					$apartmentname=get_option('amgt_system_name');
					$message_content ="New Complaint added in $apartmentname .";
					$current_sms_service 	= get_option( 'smgt_sms_service');
					$args = array();
					$args['mobile']=$user_number;
					$args['message_from']="Complaint";
					$args['message']=$message_content;					
					if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
					{				
						$send = send_sms($args);							
					}
				}
				
				foreach ( $blogusers as $user ) 
				{
					
					$email =$user->user_email ;
					$display_name=$user->display_name;
					
					$admin_search=array('{{admin_name}}','{{apartment_name}}','{{nature}}','{{noticetype}}','{{noticecategory}}','{{complaintstatus}}','{{description}}','{{apartmentnumber}}','{{complainfrom}}','{{complainto}}','{{Admin_Complain_Link}}');
					$admin_replace = array($display_name,$apartmentname,$complaintdata['complaint_nature'],$complaintdata['complaint_type'],$category,$complaintdata['complaint_status'],$complaintdata['complaint_description'],$apartmentnumber,$complainfrom,'All Member of Society.',$page_link);
					$admin_message_content = str_replace($admin_search, $admin_replace, $admin_message_content);
					amgtSendEmailNotification($email,$admin_subject,$admin_message_content);
				}
			}
			$complaintdata['created_by']=get_current_user_id();
			
			$result=$wpdb->insert($table_name,$complaintdata);
			return $result;
		}
	}
	//GET ALL COMPLAIN
	public function amgt_get_all_complaints()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	
	}
	// GET ALL DASHBOARD COMPLAINTS
	public function amgt_get_all_dashboard_complaints()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name order by 'created_date' DESC limit 3");
		return $result;
	
	}
	
	//GET OWN CRETATED COMPLAIN FUNCTION
	public function amgt_get_own_created_complaints($userid)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where created_by=".$userid);
		return $result;
	
	}
	//GET SINGLE COMPLIANTS
	public function amgt_get_single_complaint($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	// DELATE COMPLAIMTS
	public function amgt_delete_comlaint($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	//GET ALL COMPLAIN COUNT BY STATUS
	
	public function amgt_complaint_countby_status($status)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
		$result = $wpdb->get_var("SELECT count(*) FROM $table_name where complaint_status= '".$status."'");
		return $result;
	}
	//GET OWN CRETATED COMPLAIN FUNCTION
	public function amgt_get_own_created_complaints_dashboard($userid)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where created_by='$userid' order by 'id' DESC limit 3");
		return $result;
	
	}
	//GET ALL COMPLAIN
	public function amgt_get_all_complaints_dashboard()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_complaints';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name order by 'id' DESC limit 3");
		return $result;
	
	}
}
?>