 <script type="text/javascript">
$(document).ready(function() {
"use strict";
//MEMBER FORM
 $('#member_form').on('submit', function(e) {
		e.preventDefault();
		
		var form = $(this).serialize();
		// alert(form);
		var valid = $('#member_form').validationEngine('validate');
		if (valid == true)
		{
			$.ajax({
				type:"POST",
				url: $(this).attr('action'),
				data:form,
				success: function(data){			
					if(data!="")
					{ 

					  $('#member_form').trigger("reset");
					  $('#member_id').append(data);
					  $('.upload_user_avatar_preview').html('<img alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">');
					  $('.amgt_user_avatar_url').val('');
					  $('.unnit_measurement').val('');	
			           $('.unnit_chanrges').val('');
					  $('.modal').modal('hide');
					  window.location = "?page=amgt-residential_unit&tab=unitlist";
					}
					
				},
				error: function(data){

				}
			})
	}
	});

   //add_bulding_form Ajax
 	$('#unit_form').on('submit', function(e)
	{
		e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#unit_form').validationEngine('validate');
		if (valid == true)
		{
			$.ajax({
				type:"POST",
				url: $(this).attr('action'),
				data:form,
				success: function(data)
				{
					 if(data!="")
					 { 
						$('#unit_form').trigger("reset");
						$('#myModal_add_building').modal('hide');
					 } 
				},
				error: function(data){
				}
			
		    })
		}
	});  
	} );
</script>
<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'unitlist';
$obj_units=new Amgt_ResidentialUnit;
?>
<!-- POP UP CODE -->
<div class="popup-bg z_index_100000">
    <div class="overlay-content">
      <div class="modal-content">
        <div class="category_list"></div>
	  </div>
    </div> 
</div>
<!-- END POP-UP CODE -->
<div class="page-inner min_height_1088"><!-- PAGE INNER DIV -->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
	if(isset($_POST['save_residential_unit']))//SAVE_RESIDENTIAL_UNIT	
	{
		
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_residential_unit_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_units->amgt_add_residential_unit($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-residential_unit&tab=unitlist&message=2');
				}
			}
			else
			{
				
				$result=$obj_units->amgt_add_residential_unit($_POST);
				
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-residential_unit&tab=unitlist&message=1');
				}
			}
	    }
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE RESIDENTIAL_UNIT
	{
		 
		$result=$obj_units->amgt_delete_unit($_REQUEST['unit_id'],$_REQUEST['index']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-residential_unit&tab=unitlist&message=3');
		}
	}
	if(isset($_REQUEST['message']))//MESSAGES 
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Residential Unit inserted successfully','apartment_mgt');
				?></p></div>
				<?php
			
		}
		elseif($message == 2)
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Residential Unit Updated successfully','apartment_mgt');
		?></div></p><?php
				
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Residential Unit deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
	}?>
	
	<div id="main-wrapper"><!-----Main wrapper----->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL-WHITE-->
					<div class="panel-body"><!--PANEL BODY-->
					    <!--NAV-TAB-WRAPPER-->
						<h2 class="nav-tab-wrapper">
						   <?php $unit_type=get_option( 'amgt_apartment_type' );?>
							<a href="?page=amgt-residential_unit&tab=unitlist" class="nav-tab <?php echo $active_tab == 'unitlist' ? 'nav-tab-active' : ''; ?>">
							
							<?php 
							if($unit_type == 'Residential')
							{	
								echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Residential Unit List', 'apartment_mgt');
							}
							else
							{
								echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Commercial Unit List', 'apartment_mgt');
							}
							?>
							</a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{ ?>
							<a href="?page=amgt-residential_unit&tab=addunit&action=edit&unit_name=<?php echo $_REQUEST['unit_name'];?>&unit_id=<?php echo $_REQUEST['unit_id'];?>&index=<?php echo $_REQUEST['index']; ?>" class="nav-tab <?php echo $active_tab == 'addunit' ? 'nav-tab-active' : ''; ?>">
							<?php 
							if($unit_type == 'Residential')
							{	
								echo esc_html__('Edit Residential Unit', 'apartment_mgt');
							}
							else
							{
								echo esc_html__('Edit Commercial Unit', 'apartment_mgt');
							}
							?></a>
  
							<?php 
							}
							else 
							{ ?>
								<a href="?page=amgt-residential_unit&tab=addunit" class="nav-tab <?php echo $active_tab == 'addunit' ? 'nav-tab-active' : ''; ?>">
								
							<?php 
							if($unit_type == 'Residential')
							{	
								echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Residential Unit', 'apartment_mgt');
							}
							else
							{
								echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Commercial Unit', 'apartment_mgt');
							}
							?>
							
							</a>
							<?php  }?>
						</h2> <!--END NAV-TAB-WRAPPER-->
						<?php 
                        //USERLIST TAB					
						if($active_tab == 'unitlist')
						{ ?>
							<script type="text/javascript">
							  $(document).ready(function() {
								 "use strict";
								jQuery('#unit_list').DataTable({
									"responsive":true,
									"order": [[ 1, "asc" ]],
									"aoColumns":[
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": false}],
												  language:<?php echo amgt_datatable_multi_language();?>
									});
							} );
						   </script>
							<form name="activity_form" action="" method="post">
								<div class="panel-body"> <!--PANEL BODY-->
									<div class="table-responsive"> <!--TABLE RESPONSIVE-->
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
												$get_members = array('role' => 'member');
												$membersdata=get_users($get_members);
												
												$residentialdata=$obj_units->amgt_get_all_residentials();
												if(!empty($residentialdata))
												{
													foreach ($residentialdata as $retrieved_data)
													{
															$units_data=array();
															$units_data=json_decode($retrieved_data->units);
															$i = 0;
															foreach($units_data as $unit)
															{
																$user_query = new WP_User_Query(
																	array(
																		'meta_key'	  =>	'unit_name',
																		'meta_value'	=>	$unit->entry,
																	)
																);
																$allmembers = $user_query->get_results();
																
																?>
																<tr>
																	<td class="unitname"><?php echo $unit->entry;?></td>
																	<td class="building">
																	<?php $building = get_post($retrieved_data->building_id); echo $building->post_title;?></td>
																	<td class="unit"><?php $unit_cat=get_post($retrieved_data->unit_cat_id); echo $unit_cat->post_title;?></td>
																	<td class="action">
																	
																	<?php if(!empty($allmembers))
												                     {?>
												
																	<a href="#" class="btn btn-default view-member" building_id="<?php echo esc_attr($retrieved_data->building_id);?>" unit_name="<?php echo esc_attr($unit->entry);?>" >
																	<i class="fa fa-eye"></i> <?php esc_html_e('View Member', 'apartment_mgt' ) ;?> </a>
																	
																	<?php } else {?>
																	 <a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal_add_member" unit_name="<?php echo esc_attr($unit->entry);?>" >
																	<?php esc_html_e('Add Member', 'apartment_mgt' ) ;?> </a>
																	 <?php } ?>
																	
																			
																	<a href="#" class="btn btn-default view-member-history" building_id="<?php echo esc_attr($retrieved_data->building_id);?>"  unit_name="<?php echo esc_attr($unit->entry);?>" >
																	<i class="fa fa-eye"></i> <?php esc_html_e('View Member History', 'apartment_mgt' ) ;?> </a>
																	
																   <a href="?page=amgt-residential_unit&tab=addunit&action=edit&unit_name=<?php echo esc_attr($unit->entry);?>&unit_id=<?php echo esc_attr($retrieved_data->id);?>&index=<?php echo esc_attr($i); ?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
																	<a href="?page=amgt-residential_unit&tab=Activitylist&action=delete&unit_id=<?php echo esc_attr($retrieved_data->id);?>&index=<?php echo esc_attr($i); ?>" class="btn btn-danger" 
																	onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
																	<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
																	
																	</td>
																   
																</tr>
													<?php $i++; 
															}
													} 
												}?>
											</tbody>
										</table>
								    </div><!--END TABLE RESPONSIVE-->
							    </div>   
						    </form>
						<?php 
						}
						//ADD UNIT TAB
						if($active_tab == 'addunit')
						{
							require_once AMS_PLUGIN_DIR.'/admin/residential-unit/add_unit.php';
						}
						?>
                    </div>
               </div>
          </div>
      </div>
    </div>
</div><!--END  PAGE INNER DIV -->

<!--ADD MEMBER FORM POPUP -->
<script type="text/javascript">
$(document).ready(function() {
	"use strict";
	$('#member_form').validationEngine();
	$.fn.datepicker.defaults.format =" <?php  echo amgt_dateformat_PHP_to_jQueryUI(amgt_date_formate()); ?>";
    $('.birth_date').datepicker({
    endDate: '+0d',
    autoclose: true
    });
	$('#occupied_date').datepicker({	  
	  autoclose: true
	}); 
	}); 
</script>
<!---POP-UP Add Member---->
<div class="modal fade overflow_scroll" id="myModal_add_member" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"><!--MODAL CONTENT -->
        <div class="modal-header"><!--MODAL HEADER-->
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title"><?php esc_html_e('Add Member','apartment_mgt');?></h3>
        </div>
        <div class="modal-body"><!--MODAL BODY -->
         <?php $role='member';?>
		 <!--MEMBER FORM-->
		<form name="member_form" action="<?php echo admin_url('admin-ajax.php'); ?>"  method="post" class="form-horizontal" id="member_form" enctype="multipart/form-data">
	    <input type="hidden" name="action" value="amgt_add_member_popup">
		<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />	
		<div class="form-group">
			<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required] popup_member_building_category" name="building_id" >
				<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
				<?php 
				
				$edit=0;
				if($edit)
					$category =$result->building_id;
				elseif(isset($_REQUEST['building_id']))
					$category =$_REQUEST['building_id'];  
				else 
					$category = "";
				
				$activity_category=amgt_get_all_category('building_category');
				if(!empty($activity_category))
				{
					foreach ($activity_category as $retrive_data)
					{
						echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}
				} ?>
				</select>
			</div>
			<div class="col-sm-2">
			
			<a href="#" class="btn btn-default" data-toggle="modal" id="add_bulding" data-target="#myModal_add_building"> <?php esc_html_e('Add Building','apartment_mgt');?></a>			
			</div>
		</div>		
		<div id="hello"></div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">			
				<select class="form-control validate[required] popup_member_unit_category" name="unit_cat_id" >
				<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
				<?php 
					if($edit)
						$category =$result->unit_cat_id;
					elseif(isset($_REQUEST['unit_cat_id']))
						$category =$_REQUEST['unit_cat_id'];  
					else 
						$category = "";
					
					$activity_category=amgt_get_all_category('unit_category');
					if(!empty($activity_category))
					{
						foreach ($activity_category as $retrive_data)
						{
							echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
						}
					} 
					?>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Unit','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required] popup_member_unit_name" name="unit_name" >
				<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
				<?php 
				if($edit)
				{
					$unitname =$result->unit_name;
					$building_id=$result->building_id;
					$unit_category=$result->unit_cat_id;
					$unitsarray=$obj_units->amgt_get_single_cat_units($building_id,$unit_category);
					$all_entry=json_decode($unitsarray);
					
					$i=0;
					
					foreach ($all_entry as $key => $value) 
					{
						$unit_value[] = $value;
						
					}
					
					if(!empty($unit_value))
					{
						foreach ($all_entry as $key1 => $value1) 
						{?>
							<option value="<?php echo esc_attr($value1->value); ?>" <?php selected($unitname,$value1->value);?>><?php echo esc_html($value1->value);?> </option>
						<?php }
					}					
				} ?>
				</select>
			</div>			
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name"><?php esc_html_e('First Name','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="first_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php esc_html_e('Middle Name','apartment_mgt');?></label>
			<div class="col-sm-8">
				<input id="middle_name" class="form-control validate[custom[onlyLetterSp]]" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="last_name"><?php esc_html_e('Last Name','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>" name="last_name">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="gender"><?php esc_html_e('Gender','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $genderval = "male"; if($edit){ $genderval=$result->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
				<label class="radio-inline">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_html_e('Male','apartment_mgt');?>
			    </label>
			    <label class="radio-inline">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_html_e('Female','apartment_mgt');?> 
			    </label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="birth_date"><?php esc_html_e('Date of birth','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="birth_date" class="form-control validate[required] birth_date" type="text" autocomplete="off"  name="birth_date" 
				value="<?php if($edit){ echo date(amgt_date_formate(),strtotime($result->birth_date));}elseif(isset($_POST['birth_date'])) echo esc_attr($_POST['birth_date']);?>">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Member Type','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required]" name="member_type" id="member_type">
				<option value=""><?php esc_html_e('Select Member Type','apartment_mgt');?></option>
				<?php 
				if($edit)
					$category =$result->member_type;
				elseif(isset($_POST['member_type']))
					$category =$_POST['member_type'];
				else
					$category ="";?>
				<option value="Owner" <?php selected($category,'Owner');?>><?php esc_html_e('Owner','apartment_mgt');?></option>
				<option value="tenant" <?php selected($category,'tenant');?>><?php esc_html_e('Tenant','apartment_mgt');?></option>
				<option value="owner_family" <?php selected($category,'owner_family');?>><?php esc_html_e('Owner Family','apartment_mgt');?></option>
				<option value="tenant_family" <?php selected($category,'tenant_family');?>><?php esc_html_e('Tenant Family','apartment_mgt');?></option>
				<option value="care_taker" <?php selected($category,'care_taker');?>><?php esc_html_e('Care Taker','apartment_mgt');?></option>
				</select>
			</div>
		</div>
		<?php
		if($edit)		 
		{
			if(!empty($result->occupied_by))
			{
			?>
				<div class="occupied_div_edit">
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php esc_html_e('Occupied By','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] allready_occupied" name="occupied_by">
						<option value=""><?php esc_html_e('Select Occupied By','apartment_mgt');?></option>
						<?php 
						if($edit)
							$occupied_by =$result->occupied_by;
						elseif(isset($_POST['occupied_by']))
							$occupied_by =$_POST['occupied_by'];
						else
							$occupied_by ="";?>
						<option value="Owner" <?php selected($occupied_by,'Owner');?>><?php esc_html_e('Owner','apartment_mgt');?></option>
						<option value="tenant" <?php selected($occupied_by,'tenant');?>><?php esc_html_e('Tenant','apartment_mgt');?></option>			
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" ><?php esc_html_e('Occupied Date','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="occupied_date" class="form-control validate[required]" type="text" autocomplete="off" name="occupied_date" 
						value="<?php if($edit){ echo date(amgt_date_formate(),strtotime($result->occupied_date));}elseif(isset($_POST['occupied_date'])) echo esc_attr($_POST['occupied_date']);?>">
					</div>
				</div>
				</div>
			<?php
			}
			else
			{	
			?>
			<div class="occupied_div">
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php esc_html_e('Occupied By','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control validate[required] allready_occupied" name="occupied_by">
					<option value=""><?php esc_html_e('Select Occupied By','apartment_mgt');?></option>
					
					<option value="Owner"><?php esc_html_e('Owner','apartment_mgt');?></option>
					<option value="tenant"><?php esc_html_e('Tenant','apartment_mgt');?></option>			
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" ><?php esc_html_e('Occupied Date','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="occupied_date" class="form-control validate[required]" type="text"  name="occupied_date" 
					value="">
				</div>
			</div>
			</div>
			<?php
			}						
		}
		else
		{	
		?>
		<div class="occupied_div">
		<div class="form-group">
			<label class="col-sm-2 control-label"><?php esc_html_e('Occupied By','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required] allready_occupied" name="occupied_by">
				<option value=""><?php esc_html_e('Select Occupied By','apartment_mgt');?></option>
				<?php 
				if($edit)
					$occupied_by =$result->occupied_by;
				elseif(isset($_POST['occupied_by']))
					$occupied_by =$_POST['occupied_by'];
				else
					$occupied_by ="";?>
				<option value="Owner" <?php selected($occupied_by,'Owner');?>><?php esc_html_e('Owner','apartment_mgt');?></option>
				<option value="tenant" <?php selected($occupied_by,'tenant');?>><?php esc_html_e('Tenant','apartment_mgt');?></option>			
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" ><?php esc_html_e('Occupied Date','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="occupied_date" class="form-control validate[required]" type="text"  autocomplete="off" name="occupied_date" 
				value="<?php if($edit){ echo date(amgt_date_formate(),strtotime($result->occupied_date));}elseif(isset($_POST['occupied_date'])) echo esc_attr($_POST['occupied_date']);?>">
			</div>
		</div>
		</div>
		<?php
		}
		?>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="committee_member"><?php esc_html_e('Commitee Member','apartment_mgt');?></label>
			<div class="col-sm-1">
				<div class="col-sm-1">
				<input id="committee_member" class="form-control text-input" type="checkbox" <?php if($edit==1 && $result->committee_member=='yes'){ echo "checked";}?> name="committee_member" 
				value="yes"></div>	
			</div>	
			<?php if($edit==1 && $result->committee_member=='yes'){ ?>
			<div class="col-sm-9" id="designaion_area">
				<div class="col-sm-6">
				<select class="form-control validate[required] designation_cat" name="designation_id">
				<option value=""><?php esc_html_e('Select Designation','apartment_mgt');?></option>
				<?php 
				if($edit)
					$category =$result->designation_id;
				else 
					$category = "";
				
				$activity_category=amgt_get_all_category('designation_cat');
				if(!empty($activity_category))
				{
					foreach ($activity_category as $retrive_data)
					{
						echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}
				} ?>
				</select>
			</div>
			<div class="col-sm-3"><button id="addremove" model="designation_cat"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
			</div>
			<?php }
				else
				{?>
					<div class="col-sm-9" id="designaion_area">
					</div>
				<?php }	?>
		
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="address"><?php esc_html_e('Correspondence Address','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="address" class="form-control validate[required]" maxlength="150" type="text"  name="address" 
				value="<?php if($edit){ echo esc_attr($result->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
			</div>
		</div>
		             <!--ADDRESS INFORMATION---->
		            <div class="form-group">
						<label class="col-sm-2 control-label" for="city_name"><?php esc_html_e('City','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input id="city_name" class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="city_name" 
							value="<?php if($edit){ echo esc_attr($result->city_name);}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
						</div>
						<label class="col-sm-2 control-label"><?php esc_html_e('State','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterSp]]" type="text" maxlength="50" name="state_name" 
							value="<?php if($edit){ echo esc_attr($result->state_name);}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php esc_html_e('Country','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterSp]]" type="text" maxlength="50" name="country_name" 
							value="<?php if($edit){ echo esc_attr($result->country_name);}elseif(isset($_POST['country_name'])) echo $_POST['country_name'];?>">
						</div>
						<label class="col-sm-2 control-label"><?php esc_html_e('Zip Code','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="10" type="text"  name="zipcode" 
							value="<?php if($edit){ echo esc_attr($result->zipcode);}elseif(isset($_POST['zipcode'])) echo $_POST['zipcode'];?>">
						</div>
					</div>
					<!--END ADDRESS INFORMATION---->
					<div class="form-group">
						<label class="col-sm-2 control-label " for="email"><?php esc_html_e('Email','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="50" type="text"  name="email" 
							value="<?php if($edit){ echo esc_attr($result->user_email);}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('Mobile Number','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-1">
						
						<input type="text" readonly value="+<?php echo amgt_get_countery_phonecode(get_option( 'amgt_contry' ));?>"  class="form-control" name="phonecode">
						</div>
						<div class="col-sm-7">
							<input id="mobile" class="form-control validate[required,custom[phone]] text-input" type="number" min="0" onKeyPress="if(this.value.length==15) return false;"  name="mobile" value="<?php if($edit){ echo esc_attr($result->mobile);}elseif(isset($_POST['mobile'])) echo esc_attr($_POST['mobile']);?>">
						</div>
					</div>
					<!--LOGIN INFORMATION---->
					<div class="form-group">
						<label class="col-sm-2 control-label" for="username"><?php esc_html_e('User Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="username" class="form-control validate[required]" type="text" maxlength="30"  name="username" 
							value="<?php if($edit){ echo esc_attr($result->user_login);}elseif(isset($_POST['username'])) echo esc_attr($_POST['username']);?>" <?php if($edit) echo "readonly";?>>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="password"><?php esc_html_e('Password','apartment_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
						<div class="col-sm-8">
							<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password"  name="password" minlength="8" maxlength="12" value="">
						</div>
					</div>	
                    <!--MEMBER IMAGE---->					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member Image','apartment_mgt');?></label>
						<div class="col-sm-2">
							<input type="text" id="amgt_user_avatar_url" class="form-control" name="amgt_user_avatar"  
							value="<?php if($edit)echo esc_url( $result->amgt_user_avatar );elseif(isset($_POST['amgt_user_avatar'])) echo $_POST['amgt_user_avatar']; ?>" readonly />
						</div>	
						<div class="col-sm-3">
								 <input id="upload_user_avatar_button" type="button" class="button" value="<?php esc_html_e('Upload image', 'apartment_mgt' ); ?>" />
								 <span class="description"><?php esc_html_e('Upload image', 'apartment_mgt' ); ?></span>
						
						</div>
						<div class="clearfix"></div>
						
						<div class="col-sm-offset-2 col-sm-8">
								 <div id="upload_user_avatar_preview" >
									 <?php 
									 if($edit) 
										{
										if($result->amgt_user_avatar == "")
										{?>
										<img class="user_image" alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
										<?php }
										else {
											?>
										<img class="user_image" src="<?php if($edit)echo esc_url( $result->amgt_user_avatar ); ?>" />
										<?php 
										}
										}
										else {
											?>
											<img class="user_image" alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
											<?php 
										}?>
								</div>
					 </div>
					</div>
					<?php 
					if($edit) 
					{
					?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member ID Proof-1','apartment_mgt');?></label>
						<div class="col-sm-4">
							<input type="hidden" name="hidden_id_proof_1" value="<?php if($edit){ echo $result->id_proof_1;}elseif(isset($_POST['id_proof_1'])) echo $_POST['id_proof_1'];?>">
							<input onchange="fileCheck(this);" name="id_proof_1"  value="" type="file"/>
						</div>
						<div class="col-sm-2">
							<?php if(isset($result->id_proof_1) && $result->id_proof_1 != ""){?>
							<a href="<?php echo content_url().'/uploads/apartment_assets/'.$result->id_proof_1;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_html_e('Member ID Proof-1','apartment_mgt');?></a>
							<?php } ?>			
						</div>
					</div>	
					<?php	
					}
					else
					{		
					?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member ID Proof-1','apartment_mgt');?></label>
						<div class="col-sm-4">
							<input  onchange="fileCheck(this);" name="id_proof_1"  type="file"/>
						</div>
					</div>
					<?php
					}
					if($edit) 
					{
					?>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Lease Agreement','apartment_mgt');?></label>
						<div class="col-sm-4">
							<input type="hidden" name="hidden_id_proof_2" value="<?php if($edit){ echo $result->id_proof_2;}elseif(isset($_POST['id_proof_2'])) echo $_POST['id_proof_2'];?>">
							<input onchange="fileCheck(this);" name="id_proof_2"  value="" type="file"/>
						</div>
						<div class="col-sm-2">				
							<?php if(isset($result->id_proof_2) && $result->id_proof_2 != ""){?>
							<a href="<?php echo content_url().'/uploads/apartment_assets/'.$result->id_proof_2;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_html_e('Lease Agreement','apartment_mgt');?></a>
							<?php } ?>
						</div>
					</div>
					<?php
					}
					else
					{
					?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Lease Agreement','apartment_mgt');?></label>
						<div class="col-sm-4">
							<input  onchange="fileCheck(this);" name="id_proof_2"  type="file"/>
						</div>
					</div>
					<?php
					}	
					?>		
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($edit){ esc_html_e('Save','apartment_mgt'); }else{ esc_html_e('Add Member','apartment_mgt');}?>" name="save_member" class="btn btn-success"/>
					</div>		
					</form>		
					</div><!--END MODAL BODY -->
					<div class="modal-footer"><!--MODAL FOOTER -->
					  <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close','apartment_mgt');?></button>
					</div>
				  </div>
				</div>
			  </div> 
 
  

  <div class="modal fade overflow_scroll" id="myModal_add_building" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title"><?php esc_html_e('Add Building','apartment_mgt');?></h3>
        </div>
        <div class="modal-body">
          <script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			$('#unit_form').validationEngine();
			$('.onlyletter_number_space_validation').keypress(function( e ) 
			{     
				var regex = new RegExp("^[0-9a-zA-Z \b]+$");
				var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
				if (!regex.test(key)) 
				{
					event.preventDefault();
					return false;
				} 
		   });  

		});

		</script>
		<form name="unit_form"  method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" class="form-horizontal" id="unit_form">
		<input id="" type="hidden" name="action" value="amgt_add_unit_popup">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required] building_category" name="building_id" id="">
					<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
					<?php 
					$activity_category=amgt_get_all_category('building_category');
					if(!empty($activity_category))
					{
						foreach ($activity_category as $retrive_data)
						{
							echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
						}
					} ?>
					</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="building_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required] unit_category"  name="unit_cat_id" id="">
					<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
					<?php 

					$activity_category=amgt_get_all_category('unit_category');
					if(!empty($activity_category))
					{
						foreach ($activity_category as $retrive_data)
						{
							echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
						}
					} ?>
				</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="unit_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
		</div>
		<?php 
			   if(isset($_POST['unit_names'])){
					$all_data=$obj_units->amgt_get_entry_records($_POST);
					$all_entry=json_decode($all_data);
				}
			   ?>
				<div id="unit_name_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="unit_entry"><?php esc_html_e('Unit Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input class="form-control validate[required] text-input onlyletter_number_space_validation unit_name" type="text" value="" name="unit_names[]" placeholder="<?php esc_html_e('Unit Name','apartment_mgt');?>">
						</div>
						<?php $unit_measerment_type=get_option( 'amgt_unit_measerment_type' );?>
						<label class="col-sm-3 control-label" for="unit_entry"><?php esc_html_e('Unit Size','apartment_mgt');?>(<?php echo $unit_measerment_type;?>)<span class="require-field">*</span></label> 
						<div class="col-sm-2">
							<input  class="form-control validate[required] text-input" type="number" onKeyPress="if(this.value.length==6) return false;"  min="0" value="" name="unit_size[]" placeholder="<?php esc_html_e('Unit Size','apartment_mgt');?>">
						</div>
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
						</button>
						</div>
						</div>	
		        </div>		    
				<div class="form-group">
					<label class="col-sm-2 control-label" for="unit_entry"></label>
					<div class="col-sm-3">
						
						<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php esc_html_e('Add More Unit','apartment_mgt'); ?>
						</button>
					</div>
				</div>
		<hr>			
		
		<div class="col-sm-offset-2 col-sm-8">
		<?php $unit_type=get_option( 'amgt_apartment_type' ); ?>
        	<input type="submit" value="<?php  esc_html_e('Add '.$unit_type.' Unit','apartment_mgt'); ?>" name="save_residential_unit" class="btn btn-success"/>
        </div>
		
        </form>
		
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close','apartment_mgt');?></button>
		</div>
      </div>
    </div>
  </div>
 <script>
	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_expense_entry = $('#unit_name_entry').html();
   	}); 

   	function add_entry()
   	{
   		$("#unit_name_entry").append(blank_expense_entry);
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n)
	{
		//if(confirm("Are you sure want to delete this record?"))
			if(confirm(language_translate.add_remove))
		{
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}	
   	}
</script>
