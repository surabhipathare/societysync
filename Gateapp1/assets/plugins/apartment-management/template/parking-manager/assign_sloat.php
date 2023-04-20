<?php 
//ASSIGN SLOT
if($active_tab == 'assign_sloat') { ?>
		<script type="text/javascript">
		$(document).ready(function() {
			//SLOT FORM VALIDATIONENGINE
			"use strict";
			$('#sloat_form').validationEngine();
			 var start = new Date();
				var end = new Date(new Date().setYear(start.getFullYear()+1));
				$(".datepicker1").datepicker({
		       dateFormat: "yy-mm-dd",
				minDate:0,
		        onSelect: function (selected) {
		            var dt = new Date(selected);
		            dt.setDate(dt.getDate() + 0);
		            $(".datepicker2").datepicker("option", "minDate", dt);
		        }
			    });
			    $(".datepicker2").datepicker({
			      dateFormat: "yy-mm-dd",
			        onSelect: function (selected) {
			            var dt = new Date(selected);
			            dt.setDate(dt.getDate() - 0);
			            $(".datepicker1").datepicker("option", "maxDate", dt);
			        }
			    });	
				 
			
		//------ADD MEMBER AJAX----------
			 $('#member_form').on('submit', function(e) {
				e.preventDefault();
				
				var form = $(this).serialize();
				
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
						}
						
					},
					error: function(data){

					}
				})
			}
			});

		   //ADD_BULDING_FORM AJAX
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
								$('.modal').modal('hide');
							 } 
						},
						error: function(data){
						}
					
					})
				}
			});
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
		} );
		</script>
		<style>
		 .dropdown-menu {
			min-width: 240px;
		  }
		</style>
		<!-- POP UP CODE -->
		<div class="popup-bg z_index_100000">
			<div class="overlay-content">
				<div class="modal-content">
					<div class="category_list"> </div>
				</div>
			</div>    
		</div>
		<!-- END POP-UP CODE -->
	    <?php  
			$sloat_assign_id=0;
			if(isset($_REQUEST['sloat_assign_id']))
				$sloat_assign_id=$_REQUEST['sloat_assign_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = $obj_parking->amgt_get_single_assigned_sloat($sloat_assign_id);
				
				} ?>
		<div class="panel-body"><!-- PANEL BODY DIV -->
		    <!-- SLOT FORM  -->
			<form name="sloat_form" action="" method="post" class="form-horizontal" id="sloat_form">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="sloat_assign_id" value="<?php echo esc_attr($sloat_assign_id);?>"  />
				
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="sloat"><?php esc_html_e('Slot','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required]" name="sloat_id" id="slaot_name">
						<option value=""><?php esc_html_e('Select Slot','apartment_mgt');?></option>
						<?php 
						if($edit)
							 $sloatid =$result->sloat_id;
						elseif(isset($_POST['sloat_id']))
							$sloatid =$_POST['sloat_id'];
						else
							$sloatid ="";
						
						$sloatdata=$obj_parking->amgt_get_all_sloats();
						 if(!empty($sloatdata))
						 {
							foreach ($sloatdata as $sloat)
								{ ?>
									<option value="<?php echo esc_attr($sloat->id); ?>" <?php selected($sloatid,$sloat->id);?>><?php echo esc_html($sloat->sloat_name);?> </option>
								<?php }
							}
							
						 ?>
						</select>
					</div>
				</div>
				<div class="form-group"><!-- VEHICLE NUMBER -->
					<label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input type="text" maxlength="20" value="<?php  if($edit){ echo esc_attr($result->vehicle_number); } elseif(isset($_POST['vehicle_number'])){ echo esc_attr($_POST['vehicle_number']); }?>" class="form-control validate[required,custom[address_description_validation]] text-input" name="vehicle_number"/>
					 </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="model"><?php esc_html_e('Vehicle Model','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input type="text" value="<?php  if($edit){ echo esc_attr($result->vehicle_model); } elseif(isset($_POST['vehicle_model'])){ echo esc_attr($_POST['vehicle_model']); }?>" class="form-control text-input onlyletter_number_space_validation" maxlength="30" name="vehicle_model"/>
					 </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="RFID"><?php esc_html_e('RFID','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input type="text" value="<?php  if($edit){ echo esc_attr($result->RFID); } elseif(isset($_POST['RFID'])){ echo esc_attr($_POST['RFID']); }?>" class="form-control text-input onlyletter_number_space_validation" maxlength="50" name="RFID"/>
					 </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="vehicle_type"><?php esc_html_e('Vehicle Type','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
					<?php $vehicletype = "2"; if($edit){ $vehicletype=$result->vehicle_type; }elseif(isset($_POST['sloat_type'])) {$vehicletype=$_POST['vehicle_type'];}?>
						<label class="radio-inline front_radio">
						 <input type="radio" value="2" class="tog validate[required] radio_border_radius" name="vehicle_type"  <?php  checked( '2', $vehicletype);  ?>/><?php esc_html_e('Two Wheeler','apartment_mgt');?>
						</label>
						<label class="radio-inline front_radio">
						  <input type="radio" value="4" class="tog validate[required] radio_border_radius" name="vehicle_type"  <?php  checked( '4', $vehicletype);  ?>/><?php esc_html_e('Four Wheeler','apartment_mgt');?> 
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] building_category" name="building_id">
						<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
						<?php 
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
							   <a href="#" class="btn btn-default margin_top_10_res" data-toggle="modal" id="add_bulding" data-target="#myModal_add_building"> <?php esc_html_e('Add Building','apartment_mgt');?></a>
							</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] unit_categorys" name="unit_cat_id">
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
						<select class="form-control validate[required] unit_name" name="unit_name">
						<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
						<?php 
						if($edit)
						{
							$unitname =$result->unit_name;
							$building_id=$result->building_id;
									 
							 $unitsarray=$obj_units->amgt_get_single_cat_units($building_id,$result->unit_cat_id);
							 $all_entry=json_decode($unitsarray);
							
							if(!empty($all_entry))
							{
								foreach($all_entry as $unit)
								{ ?>
									<option value="<?php echo esc_attr($unit->value); ?>" <?php selected($unitname,$unit->value);?>><?php echo esc_html($unit->value);?> </option>
								<?php 
								}							
							} 					
						} 
						?>
						</select>
					</div>
				</div>
				<div class="form-group"><!---MEMBER-->
							<label class="col-sm-2 control-label"  for="member"><?php esc_html_e('Member','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] member_id" id="member_id" name="member_id">
								<option value=""><?php esc_html_e('Select Member','apartment_mgt');?></option>
									<?php if($edit)
									{
										$memberid =$result->member_id;
										$unitname =$result->unit_name;
										$category =$result->unit_cat_id;
										$building =$result->building_id;
										
									  $user_query = new WP_User_Query(
										 array(
										'meta_key'	  =>	'unit_name',
										'meta_value'	=>	$unitname
										 ),
										array( 'meta_key'	  =>	'building_id',
										'meta_value'	=>	$building ),
										array( 'meta_key'	  =>	'unit_cat_id',
										'meta_value'	=>	$category )
											 ); 
										  $allmembers = $user_query->get_results();
										   foreach($allmembers as $allmembers_data)
										  {
											 echo '<option value="'.$allmembers_data->ID.'" '.selected($memberid,$allmembers_data->ID).'>'.$allmembers_data->display_name.'</option>';
										  }
									}
									 ?>
								</select>
							</div>
							
							<div class="col-sm-2">
							  <a href="#" class="btn btn-default margin_top_10_res" data-toggle="modal" data-target="#myModal_add_member"> <?php esc_html_e('Add Member','apartment_mgt');?></a>
						   </div>
							
						</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="from_date"><?php esc_html_e('From Date','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input id="from_date" class="form-control validate[required] datepicker1" autocomplete="off" type="text"  name="from_date" 
						value="<?php if($edit){ echo date("Y-m-d",strtotime($result->from_date));}elseif(isset($_POST['from_date'])) echo esc_attr($_POST['from_date']); else echo date("Y-m-d");?>">
					 </div>
					
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="to_date"><?php esc_html_e('To Date','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input id="to_date" class="form-control validate[required] datepicker2" type="text" autocomplete="off" name="to_date" 
						value="<?php if($edit){ echo date("Y-m-d",strtotime($result->to_date));}elseif(isset($_POST['to_date'])) echo esc_attr($_POST['to_date']); else echo date("Y-m-d");?>">
					 </div>
					
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?></label>
					<div class="col-sm-8">
						 <textarea name="description" maxlength="150" class="form-control validate[custom[address_description_validation]] text-input text-input"><?php if($edit){ echo esc_textarea($result->description); }elseif(isset($_POST['description'])) echo esc_textarea($_POST['description']);?></textarea>
					</div>
				</div>
				<?php wp_nonce_field( 'assign_sloat_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ esc_html_e('save','apartment_mgt'); }else{ esc_html_e('Assign Slot','apartment_mgt');}?>" name="assign_sloat" class="btn btn-success"/>
				</div>
				
			</form><!-- SLOT FORM DIV-->
        </div><!-- END PANEL BODY DIV -->
	<?php }  ?>