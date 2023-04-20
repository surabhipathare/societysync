<?php
class Amgt_Facility
{	
    //ADD FACILITY DOCUMENT FUNCTION
	public function amgt_add_facility($data)
	{
		global $wpdb;
		$table_amgt_facility = $wpdb->prefix. 'amgt_facility';
		$facilitydata['facility_name']=MJamgt_strip_tags_and_stripslashes($data['facility_name']);
		$facilitydata['facility_charge']=MJamgt_strip_tags_and_stripslashes($data['facility_charge']);
		$facilitydata['charge_per']=$data['charge_per'];
		$facilitydata['allow_booking_multiple_base'] = 0;
		if(isset($data['allow_booking_multiple_base']))
		$facilitydata['allow_booking_multiple_base']=$data['allow_booking_multiple_base'];		
		$facilitydata['created_date']=date('Y-m-d');
		
		if($data['action']=='edit')	
		{
			$whereid['facility_id']=$data['facility_id'];
			$result=$wpdb->update( $table_amgt_facility, $facilitydata ,$whereid);
			return $result;
		}
		else
		{
			$facilitydata['created_by']=get_current_user_id();
			$result=$wpdb->insert( $table_amgt_facility, $facilitydata );
			return $result;
		}
	}
	//ADD BOOK FACILITY FUNCTION
	public function amgt_book_facility($data)
	{
		global $wpdb;
		$table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
		$bookdata['facility_id']=$data['facility_id'];
		$bookdata['activity_id']=$data['activity_id'];
		$bookdata['status']=$data['status'];
		$bookdata['period_type']=MJamgt_strip_tags_and_stripslashes($data['period_type']);
		if(isset($data['start_date']))
		{
			//$bookdata['start_date']=date('Y-m-d',strtotime($data['start_date']));
			$bookdata['start_date']=amgt_get_format_for_db($data['start_date']);
		}
		if(isset($data['end_date']))
		{
			$bookdata['end_date'] = amgt_get_format_for_db($data['end_date']);
		}
		if(isset($data['start_time']))
		{
			$bookdata['start_time'] = $data['start_time'];
		}
		if(isset($data['end_time']))
		{
			$bookdata['end_time'] = $data['end_time'];
		}
		$bookdata['booking_cost'] = $data['facility_charge'];
		$bookdata['book_on_behalf_of'] = $data['on_behalf_of'];
		$bookdata['created_date']=date('Y-m-d');
		
		if($data['action']=='edit')
		{
			$whereid['id']=$data['facility_booking_id'];
			$result=$wpdb->update( $table_amgt_facility, $bookdata ,$whereid);
			return $result;
		}
		else
		{
			$bookdata['created_by']=get_current_user_id();
			$result=$wpdb->insert( $table_amgt_facility, $bookdata );
			$retrieved_data=get_userdata($bookdata['book_on_behalf_of']);
			$to = $retrieved_data->user_email; 
			
			$subject =get_option('wp_amgt_book_facility_subject');
			$apartmentname=get_option('amgt_system_name');
			$message_content=get_option('wp_amgt_book_facility_email_template');
			$facility=$this->amgt_get_single_facility($bookdata['facility_id']);
			$facility_name=$facility->facility_name;
			$facility_charge=$facility->facility_charge;
			
			$currentuser=get_userdata(get_current_user_id());
			$booked_user_name=$currentuser->display_name;
			
			$activitpost=get_post($bookdata['activity_id']);
			$activity_name=$activitpost->post_title;
			$start_date="";
			$end_date="";
			$start_time="";
			$end_time="";
			$booking_cost=0;
			if(isset($bookdata['start_date']))
			{
				$start_date=$bookdata['start_date'];
			}
			if(isset($bookdata['end_date']))
			{
				$end_date = $bookdata['end_date'];
			}
			
			if(isset($bookdata['start_time']))
			{
				$start_time = $bookdata['start_time'];
			}
			if(isset($bookdata['end_time']))
			{
				$end_time = $bookdata['end_time'];
			}
			if(isset($bookdata['booking_cost']))
			{
				$booking_cost = $bookdata['booking_cost'];	
			}
			if($result)
			{
				//---------------- SEND  SMS ------------------//
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
				if(is_plugin_active('sms-pack/sms-pack.php'))
				{
					if(!empty(get_user_meta($bookdata['book_on_behalf_of'], 'phonecode',true))){ $phone_code=get_user_meta($bookdata['book_on_behalf_of'], 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
									
					$user_number[] = $phone_code.get_user_meta($bookdata['book_on_behalf_of'], 'mobile',true);
					$apartmentname=get_option('amgt_system_name');
					$message_content ="$facility_name has been successfully booked for you from $apartmentname .";
					$current_sms_service 	= get_option( 'smgt_sms_service');
					$args = array();
					$args['mobile']=$user_number;
					$args['message_from']="FACILITY";
					$args['message']=$message_content;					
					if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
					{				
						$send = send_sms($args);							
					}
				}
				
				if($data['user_type'] == 'admin')
				{
					
					$page_link=home_url().'/??apartment-dashboard=user&page=facility&tab=facility-booking-list';
					$subject_search=array('{{booked_user_name}}','{{activity_name}}','{{from_date}}','{{from_time}}');
					$subject_replace=array($booked_user_name,$activity_name,$start_date,$start_time);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					
					$search=array('{{member_name}}','{{apartment_name}}','{{facility_name}}','{{booked_user_name}}','{{activity_name}}','{{from_date}}','{{to_date}}','{{from_time}}','{{to_time}}','{{facility_charge}}','{{facility_link}}');
					if(empty($end_date))
					{
						$new_end_date=$start_date;
					}
					else
					{
						$new_end_date=$end_date;
					}
					
					$replace = array($retrieved_data->display_name,$apartmentname,$facility_name,$booked_user_name,$activity_name,$start_date,$new_end_date,$start_time,$end_time,$booking_cost,$page_link);
					$message_content = str_replace($search, $replace, $message_content);
					amgtSendEmailNotification($to,$subject,$message_content);
				}
				else
				{
					$user_info = get_userdata(1);
                    $user_name = $user_info->display_name;
					$admin_subject =get_option('wp_amgt_book_facility_subject_admin');
					$admin_message_content=get_option('wp_amgt_book_facility_email_template_admin');
					$page_link=home_url().'/??apartment-dashboard=user&page=facility&tab=facility-booking-list';
					$subject_search=array('{{member_name}}','{{apartment_name}}');
					$subject_replace=array($retrieved_data->display_name,$apartmentname);
					$subject=str_replace($subject_search,$subject_replace,$admin_subject);
					
					$search=array('{{admin_name}}','{{facility_name}}','{{member_name}}','{{apartment_name}}','{{activity_name}}','{{facility_charge}}','{{from_date}}','{{to_date}}','{{from_time}}','{{to_time}}','{{facility_link}}');
					if(empty($end_date))
					{
						$new_end_date=$start_date;
					}
					else
					{
						$new_end_date=$end_date;
					}
					$replace = array($user_name,$facility_name,$retrieved_data->display_name,$apartmentname,$activity_name,$booking_cost,$start_date,$new_end_date,$start_time,$end_time,$page_link);
					$message_content = str_replace($search, $replace, $admin_message_content);
					amgtSendEmailNotification($to,$subject,$message_content);
				}
			}
			return $result;
		}
	}
	//GET ALL FACILITY FUNCTION
	public function amgt_get_all_facility()
	{
		global $wpdb;
		$table_amgt_facility = $wpdb->prefix. 'amgt_facility';
	
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_facility");
		return $result;
	
	}
	//GET ALL BOOKED FACILITY FUNCTION
	public function amgt_get_all_booked_facility()
	{
		global $wpdb;
		$table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
	
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_facility");
		return $result;
	
	}
	// GET OWN BOOK FACILITY
	public function amgt_get_own_booked_facility($user_id)
	{
		global $wpdb;
		$table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
	
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_facility where created_by='$user_id' OR book_on_behalf_of=".$user_id);
		return $result;
	
	}
	// GET SINGLE FACILITY
	public function amgt_get_single_facility($facility_id)
	{
		global $wpdb;
			$table_amgt_facility = $wpdb->prefix. 'amgt_facility';
		$result = $wpdb->get_row("SELECT * FROM $table_amgt_facility where facility_id=".$facility_id);
		return $result;
	}
	//GET SINGLE BOOKED FACILITY
	public function amgt_get_single_boooked_facility($id)
	{
		global $wpdb;
		$table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
		$result = $wpdb->get_row("SELECT * FROM $table_amgt_facility where id=".$id);
		return $result;
	}
	// DELETE FACILITY
	public function amgt_delete_facility($facility_id)
	{
		global $wpdb;
		$table_amgt_facility = $wpdb->prefix. 'amgt_facility';
		$result = $wpdb->query("DELETE FROM $table_amgt_facility where facility_id= ".$facility_id);
		return $result;
	}
	//DELETE BOOKED FACILITY FUNCTION
	public function amgt_delete_booked_facility($id)
	{
		global $wpdb;
		$table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
		$result = $wpdb->query("DELETE FROM $table_amgt_facility where id= ".$id);
		return $result;
	}

}
?>