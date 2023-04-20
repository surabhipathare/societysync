<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
MJamgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=amgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJamgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
$curr_user_id=get_current_user_id();
$obj_apartment=new Apartment_management($curr_user_id);
$obj_units=new Amgt_ResidentialUnit;
$obj_doc = new Amgt_Document;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'unitlist';
	//-------------------- SAVE RESIDENTAL UNIT ------------------//
		if(isset($_POST['save_residential_unit']))		
		{
			$nonce = $_POST['_wpnonce'];
			if (wp_verify_nonce( $nonce, 'save_residential_unit_nonce' ) )
			{
				if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
				{
						$result=$obj_units->amgt_add_residential_unit($_POST);
						if($result)
						{
							wp_redirect ( home_url().'?apartment-dashboard=user&page=resident_unit&tab=unitlist&message=2');
						}
				}
				else
				{
					$result=$obj_units->amgt_add_residential_unit($_POST);
					if($result)
					{
						wp_redirect ( home_url().'?apartment-dashboard=user&page=resident_unit&tab=unitlist&message=1');
					}
				}
			}
		}
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			$result=$obj_units->amgt_delete_unit($_REQUEST['unit_id'],$_REQUEST['index']);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=resident_unit&tab=unitlist&message=3');
			}
        }
		if(isset($_REQUEST['message']))
		{
			$message =$_REQUEST['message'];
			if($message == 1)
			{?>
					<div id="message" class="updated below-h2 ">
					<p>
					<?php 
						esc_html_e('Residential Unit inserted successfully','apartment_mgt');
					?></p></div>
					<?php 
			}
			elseif($message == 2)
			{?><div id="message" class="updated below-h2 "><p><?php
						_e("Residential Unit updated successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
			elseif($message == 3) 
			{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				esc_html_e('Residential Unit deleted successfully','apartment_mgt');
			?></div></p><?php
					
			}
	    }?>
		<!-- POP UP CODE -->
		<div class="popup-bg">
			<div class="overlay-content">
				<div class="modal-content">
					<div class="category_list">
					</div>
				</div>
			</div> 
			
		</div>
        <!-- END POP-UP CODE -->
    <div class="panel-body panel-white"><!-- PANEL WHITE DIV -->
		<ul class="nav nav-tabs panel_tabs" role="tablist"> <!-- END POP-UP CODE -->
			<li class="<?php if($active_tab=='unitlist'){?>active<?php }?>">
			       <?php $unit_type=get_option( 'amgt_apartment_type' );?>
					<a href="?apartment-dashboard=user&page=resident_unit&tab=unitlist" class="tab <?php echo $active_tab == 'unitlist' ? 'active' : ''; ?>">
					 <i class="fa fa-align-justify"></i> <?php esc_html_e(''.$unit_type.' Unit List', 'apartment_mgt'); ?></a>
				    </a>
			</li>
		   <li class="<?php if($active_tab=='addunit'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['unit_id']))
				{?>
				<a href="?apartment-dashboard=user&page=resident_unit&tab=addunit&&action=edit&unit_name=<?php echo $_REQUEST['unit_name'];?>&unit_id=<?php echo $_REQUEST['unit_id'];?>&index=<?php echo $_REQUEST['index']; ?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'addunit' ? 'nav-tab-active' : ''; ?>">
					<i class="fa fa"></i> <?php 
					if($unit_type == 'Residential')
					{	
						echo esc_html__('Edit Residential Unit', 'apartment_mgt');
					}
					else
					{
						echo esc_html__('Edit Commercial Unit', 'apartment_mgt');
					}
					?>
				</a>
				 <?php 
				}
				else
				{
					if($user_access['add']=='1')
					{?>
					<a href="?apartment-dashboard=user&page=resident_unit&tab=addunit" class="tab margin_top_10_res <?php echo $active_tab == 'addunit' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add '.$unit_type.' Unit', 'apartment_mgt'); ?></a>
			<?php 	} 
				}
				?>
		  
		   </li>
	  
		</ul>
		<div class="tab-content">
			<?php if($active_tab == 'unitlist')
			{ ?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				"use strict";
				jQuery('#unit_list').DataTable({
					"responsive": true,
					"order": [[ 0, "asc" ]],
					"aoColumns":[
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": false}],
								  language:<?php echo amgt_datatable_multi_language();?>
					});
			} );
			</script>
    	    <div class="panel-body">
				<div class="table-responsive">
					<table id="unit_list" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php esc_html_e('Unit Name', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Building Name', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Unit Category', 'apartment_mgt' ) ;?></th>
								<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
							  <th><?php esc_html_e('Unit Name', 'apartment_mgt' ) ;?></th>
							  <th><?php esc_html_e('Building Name', 'apartment_mgt' ) ;?></th>
							  <th><?php esc_html_e('Unit Category', 'apartment_mgt' ) ;?></th>
							  <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							</tr>
						</tfoot>
						<tbody>
						<?php 
							$user_id=get_current_user_id();
							//--- RESIDENT DATA FOR MEMBER  ------//
							if($obj_apartment->role=='member')
							{
								
								$own_data=$user_access['own_data'];
								
								if($own_data == '1')
								{
									$residentialdata=$obj_units->amgt_get_all_residentials_own($user_id);
									
								}
								else
								{
									$residentialdata=$obj_units->amgt_get_all_residentials_own($user_id);
									
								}
							} 
							//--- RESIDENT DATA FOR STAFF MEMBER  ------//
							elseif($obj_apartment->role=='staff_member')
							{
								$own_data=$user_access['own_data'];
								if($own_data == '1')
								{ 
									$residentialdata=$obj_units->amgt_get_all_residentials_created_by($user_id);
									
								}
								else
								{
									$residentialdata=$obj_units->amgt_get_all_residentials();
								}
							}
							//--- RESIDENT DATA FOR ACCOUNTANT  ------//
							elseif($obj_apartment->role=='accountant')
							{
								$own_data=$user_access['own_data'];
								if($own_data == '1')
								{ 
									$residentialdata=$obj_units->amgt_get_all_residentials_created_by($user_id);
								}
								else
								{
									$residentialdata=$obj_units->amgt_get_all_residentials();
								}
							}
							//--- RESIDENT DATA FOR GATEKEEPER  ------//
							else
							{
								$own_data=$user_access['own_data'];
								if($own_data == '1')
								{ 
									$residentialdata=$obj_units->amgt_get_all_residentials_created_by($user_id);
								}
								else
								{
									$residentialdata=$obj_units->amgt_get_all_residentials();
								}
							}
							if(!empty($residentialdata))
							{
								foreach ($residentialdata as $retrieved_data)
								{ 
									$units_data=array();
									$units_data=json_decode($retrieved_data->units);
									$i = 0;
									foreach($units_data as $unit)
									{
										?>
										<tr>
											<?php
											if($obj_apartment->role=='member')
											{
												$own_data=$user_access['own_data'];
												if($own_data == '1')
												{
													$unit_name=get_user_meta($user_id,'unit_name',true);
													$building_id=get_user_meta($user_id,'building_id',true);
													if($unit->entry == $unit_name && $retrieved_data->building_id == $building_id)
													{ 
												?>
														<td class="unitname"><?php echo esc_html($unit_name); ?></td>
														<td class="building">
														<?php  
														$building = get_post($retrieved_data->building_id); echo esc_html($building->post_title);?></a></td>
														<td class="unit"><?php $unit_cat=get_post($retrieved_data->unit_cat_id); echo esc_html($unit_cat->post_title);?></td>
														<td class="action">
															<a href="#" class="btn btn-default view-member" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>" >
															<i class="fa fa-eye"></i> <?php esc_html_e('View Member', 'apartment_mgt' ) ;?> </a>
															
															<a href="#" class="btn btn-default view-member-history" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>" ><i class="fa fa-eye"></i> <?php esc_html_e('View Member History', 'apartment_mgt' ) ;?> </a>
															<?php
															$document=$obj_doc->amgt_get_member_document($user_id);
															if(!empty($document))
															{
															?>
																 <a href="#" class="btn btn-default view-unit-document" building_id="<?php echo esc_attr($retrieved_data->building_id);?>" unit_name="<?php echo esc_attr($unit->entry);?>">
																<i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
															<?php
															}
															$role = amgt_get_user_role(get_current_user_id());
															if($role=='staff_member')
															{
															?>
															<a href="#" class="btn btn-default view-unit-document" unit_name="<?php echo esc_attr($unit->entry);?>">
															 <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
															<?php
															}
															if($user_access['edit']=='1')
															{
															?>
																<a href="?apartment-dashboard=user&page=resident_unit&tab=addunit&action=edit&unit_name=<?php echo esc_attr($unit->entry);?>&unit_id=<?php echo esc_attr($retrieved_data->id);?>&index=<?php echo $i; ?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
															<?php
															}
															if($user_access['delete']=='1')
															{
															?>
																<a href="?apartment-dashboard=user&page=resident_unit&tab=Activitylist&action=delete&unit_id=<?php echo esc_attr($retrieved_data->id);?>&index=<?php echo esc_attr($i); ?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
																<?php esc_html_e('Delete', 'apartment_mgt' ) ;?>
																</a>
															<?php
															}
															?>
														</td>
													<?php
													}
												}
												else
												{ ?>
													<td class="unitname"><?php echo esc_html($unit->entry); ?></td>
													<td class="building">
													<?php  
													$building = get_post($retrieved_data->building_id); echo esc_html($building->post_title);?></a></td>
													<td class="unit"><?php $unit_cat=get_post($retrieved_data->unit_cat_id); echo esc_html($unit_cat->post_title);?></td>
													<td class="action">
														<a href="#" class="btn btn-default view-member" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>" >
														<i class="fa fa-eye"></i> <?php esc_html_e('View Member', 'apartment_mgt' ) ;?> </a>
														
														<a href="#" class="btn btn-default view-member-history" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>" ><i class="fa fa-eye"></i> <?php esc_html_e('View Member History', 'apartment_mgt' ) ;?> </a>
														<?php
														
														$document=$obj_doc->amgt_get_member_document($user_id);
														if(!empty($document))
														{
														?>
															 <a href="#" class="btn btn-default view-unit-document" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>">
															<i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
														<?php
														}  
														$role = amgt_get_user_role(get_current_user_id());
														if($role=='staff_member')
														{
														?>
														<a href="#" class="btn btn-default view-unit-document" building_id="<?php echo esc_attr($retrieved_data->building_id);?>" unit_name="<?php echo esc_attr($unit->entry);?>">
														 <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
														<?php
														}
														if($user_access['edit']=='1')
														{
														?>
															<a href="?apartment-dashboard=user&page=resident_unit&tab=addunit&action=edit&unit_name=<?php echo esc_attr($unit->entry);?>&unit_id=<?php echo esc_attr($retrieved_data->id);?>&index=<?php echo $i; ?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
														<?php
														}
														if($user_access['delete']=='1')
														{
														?>
															<a href="?apartment-dashboard=user&page=resident_unit&tab=Activitylist&action=delete&unit_id=<?php echo esc_attr($retrieved_data->id);?>&index=<?php echo esc_attr($i); ?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
															<?php esc_html_e('Delete', 'apartment_mgt' ) ;?>
															</a>
														<?php
														}
														?>
													</td>
												<?php
												}
											}
											else
											{ ?>
												<td class="unitname"><?php echo esc_html($unit->entry); ?></td>
												<td class="building">
												<?php  
												$building = get_post($retrieved_data->building_id); echo esc_html($building->post_title);?></a></td>
												<td class="unit"><?php $unit_cat=get_post($retrieved_data->unit_cat_id); echo esc_html($unit_cat->post_title);?></td>
												<td class="action">
													<a href="#" class="btn btn-default view-member" building_id="<?php echo $retrieved_data->building_id;?>" unit_name="<?php echo $unit->entry;?>" >
													<i class="fa fa-eye"></i> <?php esc_html_e('View Member', 'apartment_mgt' ) ;?> </a>
													
													<a href="#" class="btn btn-default view-member-history" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>" ><i class="fa fa-eye"></i> <?php esc_html_e('View Member History', 'apartment_mgt' ) ;?> </a>
													<?php
													
													$document=$obj_doc->amgt_get_member_document($user_id);
													if(!empty($document))
													{
													?>
														<a href="#" class="btn btn-default view-unit-document" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>">
														<i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
													<?php
													}  
													$role = amgt_get_user_role(get_current_user_id());
													if($role=='staff_member')
													{
													?>
													<a href="#" class="btn btn-default view-unit-document" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>">
													 <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
													<?php
													}
													if($user_access['edit']=='1')
													{
													?>
														<a href="?apartment-dashboard=user&page=resident_unit&tab=addunit&action=edit&unit_name=<?php echo esc_attr($unit->entry);?>&unit_id=<?php echo esc_attr($retrieved_data->id);?>&index=<?php echo $i; ?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
													<?php
													}
													if($user_access['delete']=='1')
													{
													?>
														<a href="?apartment-dashboard=user&page=resident_unit&tab=Activitylist&action=delete&unit_id=<?php echo esc_attr($retrieved_data->id);?>&index=<?php echo esc_attr($i); ?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
														<?php esc_html_e('Delete', 'apartment_mgt' ) ;?>
														</a>
													<?php
													}
													?>
												</td>
											<?php
											}?>	
										</tr>
									 <?php 
										 
									}
								} 
							}
							?>
						</tbody>
					</table>
				</div>
            </div>
		     <?php 
			} 
			if($active_tab == 'addunit')
			{ 
			  require_once AMS_PLUGIN_DIR.'/template/resident_unit/add_unit.php' ;
			}
			?>
	</div>
</div><!-- END PANEL BODY DIV -->
<?php ?>