<?php
class Amgt_ResidentialUnit
{	
    //GET ENTRY RECORD FUNCTION
	public function amgt_get_entry_records($data)
	{			
			$all_units_entry=$data['unit_names'];
			$all_measurement_entry=$data['unit_size'];
			$all_unit_measerment_type=$data['unit_measerment_type'];
			
			$entry_data=array();
			foreach($all_units_entry as $key=>$one_entry)
			{
				$entry_data[]= array('entry'=>$one_entry,'measurement'=>$all_measurement_entry[$key]);
			}
			return json_encode($entry_data);
	}
	 //GET Edit ENTRY RECORD FUNCTION
	public function amgt_get_edit_entry_records($data)
	{	
		$unit_id=$data['unit_id'];
		$unit_index=$data['unit_index'];
		$all_units_entry=$data['unit_names'];
		$all_measurement_entry=$data['unit_size'];
		//$all_unit_measerment_type=$data['unit_measerment_type'];
		//$entry_data=array();
		foreach($all_units_entry as $key=>$one_entry)
		{
			$entry_data= array('entry'=>$one_entry,'measurement'=>$all_measurement_entry[$key]);
		}
		
		$entary = array();
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id= ".$unit_id,ARRAY_A);
		$units=$result['units'];
		
		$entary=json_decode($units,true);
		$edit_record=array();
		foreach($entary as $key=>$en)
		{
			if($key == $unit_index)
			{				
				$edit_record[]=array_replace($entary[$key],$entry_data);
			}
			else
			{
				$edit_record[]=$entary[$key];
			}			
		}
			
		return json_encode($edit_record);
		  
	}
	//ADD RESIDENTIAL UNIT FUNCTION
	
	public function amgt_add_residential_unit($data)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$amgt_building_cat = $wpdb->prefix. 'amgt_building_cat';
		$unitdata['building_id']=$data['building_id'];
		$unitdata['unit_cat_id']=$data['unit_cat_id'];		
		$unitdata['created_date']=date('Y-m-d');
		
		
		if($data['action']=='edit')
		{
			$edit_entry_value=$this->amgt_get_edit_entry_records($data);
			$unitdata['units']=$edit_entry_value;
			
			$building_cat['building_id']=$data['building_id'];
			$building_cat['building_cat_id']=$data['unit_cat_id'];
			$where_building_cat_id['building_id']=$data['building_id'];
			$result1=$wpdb->update( $amgt_building_cat, $building_cat ,$where_building_cat_id);
			$whereid['id']=$data['unit_id'];
			$result=$wpdb->update( $table_name, $unitdata ,$whereid);
			return $result;
		}
		else
		{
			$unitdata['created_by']=get_current_user_id();
			$entry_value=$this->amgt_get_entry_records($data);
			$unitdata['units']=$entry_value;	
			
			$building_cat['building_id']=$data['building_id'];
			$building_cat['building_cat_id']=$data['unit_cat_id'];
			$building_cat['created_by']=get_current_user_id();
			$result1=$wpdb->insert( $amgt_building_cat, $building_cat );
			$result=$wpdb->insert( $table_name, $unitdata );
			return $result;
		}
	
	}
	//GET OWN RESIDENTIAL UNIT.
	public function amgt_get_all_residentials_own($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$building_id=get_user_meta($user_id,'building_id',true);
		$result = $wpdb->get_results("SELECT * FROM $table_name where building_id=".$building_id);
		return $result;
	}	
	//GET ALL RESIDENTIAL UNIT.
	public function amgt_get_all_residentials()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$result = $wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	}	
	//GET SINGAL RESIDENTIAL FUNCTION
	public function amgt_get_single_unit($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	//DELETE UNIT FUNCTION
	public function amgt_delete_unit($id,$index)
	{
		$entary = array();
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id= ".$id,ARRAY_A);
		$units=$result['units'];
		
		$entary=json_decode($units,true);
		$i=0;
		foreach($entary as $key=>$en)
		{
			if($key == $index)
			{
				unset($entary[$index]);
			}
			$i++;
		}	
		  if(sizeof($entary)>0)
		  {
            $unset_record=json_encode(array_values($entary));
		    $unitsdata = array();
			$unitsdata['units']=$unset_record;			
			$result=$wpdb->update($table_name,$unitsdata,array("id"=>$id));
		  }
		  else 
		 {
			$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
	      }
		   return $result;
	}
	//GET SINGLE UNIT CATEGORY
	public function amgt_get_single_cat_units($building_id,$unit_category)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$unit_catdata = $wpdb->get_results("select units from $table_name where building_id=".$building_id." AND unit_cat_id=".$unit_category);
		$all_entry=array();
		if(!empty($unit_catdata))
		{
			foreach($unit_catdata as $unit)
			{
				$all_entry[]=json_decode($unit->units);
			}
		}
		
		$array_var =array();
		
		if(!empty($all_entry))
		{
			foreach($all_entry as $key=>$val)
			{			
		
				foreach($val as $key1=>$val1)
				{					
					$array_var[]= array("value" => "$val1->entry");
				}
			}
		}
		return json_encode($array_var);
	}
	// UPDATE UNIT
	public function amgt_update_unit($building_id,$unit_cat_id,$cat_name)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$unitdata['units'] = $cat_name;
			$whereid['building_id']=$building_id;
				$whereid['unit_cat_id']=$unit_cat_id;
			$result=$wpdb->update( $table_name, $unitdata ,$whereid);
		return $result;
	}
	
	//GET RESIDENTIAL UNIT REPORT FUNCTION
	public function amgt_unit_report()
	{
		
		$all_building = $this->amgt_get_all_residentials();
		foreach($all_building as $retrivedata)
		{
			$units = json_decode($retrivedata->units,true);
		    $building[$retrivedata->building_id][] = count($units);
		}
		$return_array = array();
		foreach($building as $key => $value)
		{
			$total_unit = 0;
			foreach($value as $no_of_unit)
				$total_unit += $no_of_unit;
			$return_array[] = array('building_id'=>$key,'no_of_unit'=>$total_unit);
		}
		return $return_array;
	
	}
	//ADD MEMBER BY BULDING FUNCTION
	public function amgt_member_by_building()
	{
		global $wpdb;
		$table_amgt_residential_units = $wpdb->prefix. 'amgt_residential_units';
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_residential_units group by building_id");
		$return_array = array();
		foreach($result as $retrivedata)
		{
			$member = get_users(array('meta_key' => 'building_id', 'meta_value' => $retrivedata->building_id,'role'=>'member'));
			$return_array[] = array('building_id'=>$retrivedata->building_id,'no_of_member'=>count($member));
		}
		return $return_array;
	}
	// GET ALL RESIDENTAL DASHBOARD
	public function amgt_get_all_residentials_dashboard()
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name order by 'created_date' DESC limit 5");
		return $result;
	
	}
	//GET ALL RESIDENTIAL UNIT CRETED BY.
	public function amgt_get_all_residentials_created_by($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$result = $wpdb->get_results("SELECT * FROM $table_name where created_by=".$user_id);
		return $result;
	}
	//GET ALL RESIDENTIAL UNIT BY MEMBER.
	public function amgt_get_unit_list_member($building_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_residential_units';
		$result = $wpdb->get_results("SELECT * FROM $table_name where building_id=".$building_id);
		return $result;
	}		
}
//END CLASS
?>