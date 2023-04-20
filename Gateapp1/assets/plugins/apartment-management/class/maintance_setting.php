<?php
class Amgt_maintenance
{	
	// ADD MAINTANCE SETTING
	public function amgt_add_maintance_settings($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'maintenance_settings';
		$amgt_maintence_tax=$wpdb->prefix.'amgt_maintence_tax';
		$maintenancedata['building_id']=$data['building_id'];
		$maintenancedata['maintenance_charges']=MJamgt_strip_tags_and_stripslashes($data['maintenance_charges']);
		$maintenancedata['maintenance_charge_period']=MJamgt_strip_tags_and_stripslashes($data['amgt_charge_period']);
		$maintenancedata['created_date']=date('Y-m-d');
		$maintenancedata['created_by']=get_current_user_id();
		
		if($data['action']=='edit')
		{
			$whereid['id']=$data['maintenance_setings_id'];
			$result=$wpdb->update( $table_name, $maintenancedata ,$whereid);
		     $delete_tax = $wpdb->query("DELETE FROM $amgt_maintence_tax where maintence_setings_id= ".$data['maintenance_setings_id']);
			foreach($data['tax_entry'] as $key=>$tax)
				{
					$invoicetax['maintence_setings_id']=$data['maintenance_setings_id'];
					$invoicetax['building_id']=$data['building_id'];
					$invoicetax['tax_id']=$data['tax_title'][$key];
					$invoicetax['tax']=$tax;
					$invoicetax['created_at']=date('Y-m-d');
					$invoicetax['created_by']=get_current_user_id();
					//$result1=$wpdb->update( $amgt_maintence_tax, $invoicetax ,$whereid);
					$insert_tax=$wpdb->insert( $amgt_maintence_tax, $invoicetax );
					
				}
			return $result; 
		}
		else
		{
			$result=$wpdb->insert( $table_name, $maintenancedata );
			$last_id=$wpdb->insert_id;
			foreach($data['tax_entry'] as $key=>$tax)
				{
				    $amgt_maintence_tax=$wpdb->prefix.'amgt_maintence_tax';
					$invoicetax['maintence_setings_id']=$last_id;
					$invoicetax['building_id']=$data['building_id'];
					$invoicetax['tax_id']=$data['tax_title'][$key];
					$invoicetax['tax']=$tax;
					//$invoicetax['tax_amount']=$data['tax_amount_entry'][$key];
					$invoicetax['created_at']=date('Y-m-d');
					$invoicetax['created_by']=get_current_user_id();
					$result1=$wpdb->insert( $amgt_maintence_tax, $invoicetax );
				}
			return $result;
		}
	
	}
	// GET ALL MAINTANCE SETTING
	public function amgt_get_all_maintenance_setings()
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix. 'maintenance_settings';
		$result = $wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	
	}
	// GET SINGLE MAINTANCE SETTING
	public function amgt_get_single_maintenance_setings($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'maintenance_settings';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	// GET SINGLE MAINTANCE SETTING FOR BUILDING
	public function amgt_get_single_maintenance_setings_bulding($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'maintenance_settings';
		$result = $wpdb->get_row("SELECT * FROM $table_name where building_id=".$id);
		return $result;
	}
	// MAINTANCE TAX
	public function amgt_maintence_tax($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_maintence_tax';
		$result = $wpdb->get_results("SELECT * FROM $table_name where building_id=$id");
		return $result;
	}
	// DELETE MAINTANCE SETTING
	public function amgt_delete_maintenance_setings($id)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix. 'maintenance_settings';
		$amgt_maintence_tax=$wpdb->prefix.'amgt_maintence_tax';
	    $result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		$result1 = $wpdb->query("DELETE FROM $amgt_maintence_tax where maintence_setings_id= ".$id);
	   return $result;
	}
}
?>