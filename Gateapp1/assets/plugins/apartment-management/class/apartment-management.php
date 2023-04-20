<?php 
//GET CURENT USER ROLE CLASS
class Apartment_management
{
	public $role;
	public $building_id;
	function __construct($user_id = NULL)
	{		
		if($user_id)
		{			
			$this->role=$this->get_current_user_role();						
			$meta_query = get_user_meta( $user_id );
			if($this->role=='member')
			$this->building_id = $meta_query["building_id"][0];			
		}
	}
	//GET CURRENT USER ROLE
	private function get_current_user_role ()
	{
		global $current_user;
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	}
	
}
?>