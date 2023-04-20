<?php
class Amgt_Service
{	
    //ADD SERVICES FUNCTION
	public function amgt_add_service($data)
	{
		global $wpdb;
		$table_amgt_serivce = $wpdb->prefix. 'amgt_serivce';
		$servicedata['service_name']=MJamgt_strip_tags_and_stripslashes($data['service_name']);
		$servicedata['service_provider']=MJamgt_strip_tags_and_stripslashes($data['service_provider']);
		$servicedata['contact_number']=MJamgt_strip_tags_and_stripslashes($data['contact_number']);
		$servicedata['mobile_number']=MJamgt_strip_tags_and_stripslashes($data['mobile_number']);
		$servicedata['email']=$data['email'];
		$servicedata['address']=MJamgt_strip_tags_and_stripslashes($data['address']);
		$servicedata['created_date']=date('Y-m-d');
		$servicedata['status']=1;
		if($data['action']=='edit')
		{
			$whereid['service_id']=$data['service_id'];
			$result=$wpdb->update( $table_amgt_serivce, $servicedata ,$whereid);
			return $result;
		}
		else
		{
			$servicedata['created_by']=get_current_user_id();
			$result=$wpdb->insert( $table_amgt_serivce, $servicedata );
			return $result;
		}
	}
	//GET ALL SERVICES FUNCTION
	public function amgt_get_all_service()
	{
		global $wpdb;
		$table_amgt_serivce = $wpdb->prefix. 'amgt_serivce';
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_serivce");
		return $result;
	}
	// GET OWN SERVICES
	public function amgt_get_own_service($user_id)
	{
		global $wpdb;
		$table_amgt_serivce = $wpdb->prefix. 'amgt_serivce';
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_serivce where created_by=".$user_id);
		return $result;
	}
	
	//GET ALL dashboard SERVICES FUNCTION
	public function amgt_get_all_dashboard_service()
	{
		global $wpdb;
		$table_amgt_serivce = $wpdb->prefix. 'amgt_serivce';
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_serivce order by 'created_date' DESC limit 3");
		return $result;
	}
	// GET OWN SERVICES DASHBOARD
	public function amgt_get_own_service_dashboard($user_id)
	{
		global $wpdb;
		$table_amgt_serivce = $wpdb->prefix. 'amgt_serivce';
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_serivce where created_by='$user_id' order by 'created_date' DESC limit 3");
		return $result;
	}
	//GET SINGLE SERVICES FUNCTION
	public function amgt_get_single_service($service_id)
	{
		global $wpdb;
			$table_amgt_serivce = $wpdb->prefix. 'amgt_serivce';
		$result = $wpdb->get_row("SELECT * FROM $table_amgt_serivce where service_id=".$service_id);
		return $result;
	}
	//DELETE SERVICES FUNCTION
	public function amgt_delete_service($service_id)
	{
		global $wpdb;
		$table_amgt_serivce = $wpdb->prefix. 'amgt_serivce';
		$result = $wpdb->query("DELETE FROM $table_amgt_serivce where service_id= ".$service_id);
		return $result;
	}
}
//END CLASS
?>