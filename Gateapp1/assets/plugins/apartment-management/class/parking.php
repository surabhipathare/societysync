<?php
class Amgt_Parking
{	
     //ADD PARKING SLOAT FUNCTION
	public function amgt_add_sloat($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_sloats';
		$sloatdata['sloat_name']=MJamgt_strip_tags_and_stripslashes($data['sloat_name']);
		$sloatdata['sloat_type']=MJamgt_strip_tags_and_stripslashes($data['sloat_type']);
		$sloatdata['comment']=MJamgt_strip_tags_and_stripslashes($data['comment']);
		$sloatdata['created_date']=date('Y-m-d');
		
		if($data['action']=='edit')
		{
			$whereid['id']=$data['sloat_id'];
			$result=$wpdb->update( $table_name, $sloatdata ,$whereid);
			return $result;
		}
		else
		{
			$sloatdata['created_by']=get_current_user_id();
			$result=$wpdb->insert( $table_name, $sloatdata );
			return $result;
		}
	
	}
	//ADD ASIGN PARKING SLOAT FUNCTION
	public function amgt_assign_sloat($data)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_parking';
		$assignsloatdata['sloat_id']=$data['sloat_id'];
		$assignsloatdata['vehicle_number']=MJamgt_strip_tags_and_stripslashes($data['vehicle_number']);
		$assignsloatdata['vehicle_model']=MJamgt_strip_tags_and_stripslashes($data['vehicle_model']);
		$assignsloatdata['RFID']=MJamgt_strip_tags_and_stripslashes($data['RFID']);
		$assignsloatdata['vehicle_type']=MJamgt_strip_tags_and_stripslashes($data['vehicle_type']);
		$assignsloatdata['building_id']=$data['building_id'];
		$assignsloatdata['unit_cat_id']=$data['unit_cat_id'];
		$assignsloatdata['unit_name']=MJamgt_strip_tags_and_stripslashes($data['unit_name']);
		$assignsloatdata['member_id']=$data['member_id'];
		$assignsloatdata['from_date']=amgt_get_format_for_db($data['from_date']);
		$assignsloatdata['to_date']=amgt_get_format_for_db($data['to_date']);
		$todate=date('Y-m-d',strtotime($assignsloatdata['to_date']));
		$currentdate=date('Y-m-d');
		if($todate>=$currentdate)
		{
		  $assignsloatdata['status']='alloted';
		}
		else{
			 $assignsloatdata['status']='unallocated';
		   }
		$assignsloatdata['description']=MJamgt_strip_tags_and_stripslashes($data['description']);
		$assignsloatdata['created_date']=date('Y-m-d');
		
		
		if($data['action']=='edit')
		{
			$whereid['id']=$data['sloat_assign_id'];
			$result=$wpdb->update( $table_name, $assignsloatdata ,$whereid);
			return $result;
		}
		else
		{   //ASIGN PARKING SLOAT MAIL SEND 
			$assignsloatdata['created_by']=get_current_user_id();
			$retrieved_data=get_userdata($assignsloatdata['member_id']);
			$to = $retrieved_data->user_email; 
			$subject =get_option('wp_amgt_add_assign_sloat_subject');
			$apartmentname=get_option('amgt_system_name');
			$subject_search=array('{{apartment_name}}');
			$page_link=home_url().'/?apartment-dashboard=user&page=parking-manager&tab=assigned-sloat-list';
		    $subject_replace=array($apartmentname);
			$message_content=get_option('wp_amgt_add_assign_sloat_email_template');
			$slotdata=$this->amgt_get_single_sloat($assignsloatdata['sloat_id']);
			$sloat_name=$slotdata->sloat_name;
			$search=array('{{member_name}}','{{apartment_name}}','{{slotname}}','{{startdate}}','{{enddate}}','{{vehiclenumber}}','{{vehiclemodel}}','{{vehicletype}}','{{RFID}}','{{Sloat_Link}}');
			$replace = array($retrieved_data->display_name,$apartmentname,$sloat_name,$assignsloatdata['from_date'],$assignsloatdata['to_date'],$assignsloatdata['vehicle_number'],$assignsloatdata['vehicle_model'],$assignsloatdata['vehicle_type'],$assignsloatdata['RFID'],$page_link);
			$subject=str_replace($subject_search,$subject_replace,$subject);
			$message_content = str_replace($search, $replace, $message_content);
			amgtSendEmailNotification($to,$subject,$message_content);
			$result=$wpdb->insert($table_name, $assignsloatdata );
			return $result;
		}
	
	}
	//GET ALL SLAOT
	public function amgt_get_all_sloats()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_sloats';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	
	}
	// GET OWN SLOATS
	public function amgt_get_own_sloats($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_sloats';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where created_by=".$user_id);
		return $result;
	
	}
	// GET ASSIGNED SLOATS
	public function amgt_get_all_assigned_sloats()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_parking';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	
	}
	// GET OWN ASSIGNED SLOATS
	public function amgt_get_own_assigned_sloats($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_parking';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where member_id=$user_id OR created_by=".$user_id);
		return $result;
	
	}
	// GET SINGLE SLOAT
	public function amgt_get_single_sloat($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_sloats';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	// GET SINGLE ASSIGNED SLOAT
	public function amgt_get_single_assigned_sloat($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_parking';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	// DELETE SLOAT
	public function amgt_delete_sloat($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_sloats';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	//DELETE ASSIGN PARKINF SLOAT
	public function amgt_delete_assigned_sloat($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_parking';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	// GET ALL ASSIGNED SLOAT BY DATE AND ID
	public function amgt_get_all_assigned_sloats_by_date_slot_id($id,$from_date,$to_date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_parking';
	    $result = $wpdb->get_results("SELECT * FROM $table_name WHERE sloat_id = $id AND (to_date <= '$to_date' AND from_date >= '$from_date')");
		return $result;
	
	}
}
//END CLASS
?>