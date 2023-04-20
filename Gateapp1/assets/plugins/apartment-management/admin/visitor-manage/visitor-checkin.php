<?php error_reporting(0); ?>
<script type="text/javascript">
$(document).ready(function()
{    //VISITOR CHECKIN FORM VALIDATIONENGINE
	"use strict";
	$('#visitor_checkin_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
	var date = new Date();
	jQuery('#checkin_date').datepicker({
			dateFormat: "yy-mm-dd",
			minDate:'today',
			changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},    
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "/" + year);
	        }                    
		}); 
	$('.timepicker').timepicki();
	    //ADD_UNIT_FORM POP UP AJAX
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
	//USER NAME NOT  ALLOW SPACE VALIDATION
	$('#username').keypress(function( e ) 
	{
	   if(e.which === 32) 
		 return false;
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
	$("body").on("click","#add_new_visitor",function()
	{
		$("#add_visiter_entry_div").append('<div class="row padding_left_10" ><div class="form-group"><label class="col-sm-2 control-label" for="name"><?php esc_html_e('Visitor Name','apartment_mgt');?><span class="require-field">*</span></label><div class="col-sm-8"><input type="text" value="" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input onlyletter_number_space_validation" maxlength="50" name="visitor_name[]"/></div></div><div class="form-group"><label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('ID Number','apartment_mgt');?><span class="require-field">*</span></label><div class="col-sm-3"><input id="mobile" class="form-control validate[required,custom[onlyLetterNumber]] text-input" type="text" maxlength="15" name="mobile[]" value=""></div><label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?></label><div class="col-sm-3"><input type="text" value="" maxlength="20" class="form-control text-input" name="vehicle_number[]"/></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-2"><button type="button" class="btn btn-default" onclick="deletevisiterentry(this)"><i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i></button></div></div></div>');
	});		
} );
</script>
<script>
	/* function add_visiter_entry()
   	{
   		
   	} */
	function deletevisiterentry(n)
	{
		n.parentNode.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode.parentNode);				
	}
</script>
<style>
.dropdown-menu {
    min-width: 240px;
}
</style>
<?php 
//VISITOR-CHECKIN	
if($active_tab == 'visitor-checkin')
{
    $vcheckin_id=0;
	$status=0;
	if(isset($_REQUEST['visitor_checkin_id']))
		$vcheckin_id=$_REQUEST['visitor_checkin_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_gate->amgt_get_single_checkin($vcheckin_id);
			$status=$result->status;
		
		} ?>
		<div class="panel-body"><!----PANEL-BODY--->
		    <!----VISITOR_CHECKIN_FORMY--->
			<form name="visitor_checkin_form" action="" method="post" class="form-horizontal" id="visitor_checkin_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
			<input type="hidden" name="vcheckin_id" value="<?php echo esc_attr($vcheckin_id);?>"  />
			<input type="hidden" name="checkin_type" value="visitor_checkin"  />
			<input type="hidden" name="user_type" value="admin_user"  />
		    <input type="hidden" name="status" value="<?php echo esc_attr($status);?>"  />
			<!---GENERAL INFORMATION--->
			<div class="form-group">
				<label class="col-sm-2 control-label" for="gate"><?php esc_html_e('Choose Gate','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
				<?php $gateval = "0"; if($edit){ $gateval=$result->gate_id; }elseif(isset($_POST['gate'])) {$gateval=$_POST['gate'];}
				if(!empty($gatedata))
				{
					$i=1;
					foreach($gatedata as $gate)
					{
						if($edit)
						{
						?>
						<label class="radio-inline">
						<input type="radio" value="<?php echo esc_attr($gate->id);?>" class="tog validate[required]" name="gate"  <?php  echo checked( $gate->id, $gateval);  ?>/><?php echo esc_attr($gate->gate_name);?>
						</label>
						<?php 
						}
						else
						{?>
						 <label class="radio-inline">
						<input type="radio" value="<?php echo esc_attr($gate->id);?>" class="tog validate[required]" id="gate_id" name="gate"  <?php  if($i==1) echo "checked"; ?>/><?php echo esc_attr($gate->gate_name);?>
						</label>
						<?php 
						}
					   $i+=1;	
					}
				}
				else
				{ ?>
					<label class="radio-inline">
					<?php esc_html_e('No Any Gates.','apartment_mgt');
					echo "</label>";
				}
				?>
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="visitreason_category"><?php esc_html_e('Reason For Visit','apartment_mgt');?></label>
				<div class="col-sm-8">
					<select class="form-control onlyletter_number_space_validation visit_reason_cat visit_reason_append" name="reason_id" maxlength="50" >
					<option value=""><?php esc_html_e('Select Reason','apartment_mgt');?></option>
					<?php 
					if($edit)
						$category =$result->reason_id;
					elseif(isset($_REQUEST['reason_id']))
						$category =$_REQUEST['reason_id'];  
					else 
						$category = "";
					
					$activity_category=amgt_get_all_category('visit_reason_cat');
					if(!empty($activity_category))
					{
						foreach ($activity_category as $retrive_data)
						{
							echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';	
						}
					} ?>
					</select>
				</div>
				<!---GENERAL INFORMATION--->
				<div class="col-sm-2"><button id="addremove" model="visit_reason_cat"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Compound','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control building_category validate[required] visitor_compound_append" name="building_id">
					<option value=""><?php esc_html_e('Select Compound','apartment_mgt');?></option>
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
				  <a href="#" class="btn btn-default" data-toggle="modal" id="add_bulding" data-target="#myModal_add_building"> <?php esc_html_e('Add Compound','apartment_mgt');?></a>
				</div>
				
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control unit_categorys validate[required] visitor_unit_cat_append" name="unit_cat_id">
					<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
						<?php 
							if($edit)
								$category =$result->unit_cat;
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
							} ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Unit','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control unit_name validate[required] visitor_unit_name_append" name="unit_name">
					<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
					<?php 
					if($edit)
					{
						$unitname =$result->unit_name;
						$building_id=$result->building_id;
						$unitsarray=$obj_units->amgt_get_single_cat_units($building_id,$result->unit_cat);
						$all_entry=json_decode($unitsarray);
						if(!empty($all_entry))
						{
							foreach($all_entry as $unit)
							{ ?>
								<option value="<?php echo esc_attr($unit->value); ?>" <?php selected($unitname,$unit->value);?>><?php echo esc_html($unit->value);?> </option>
							<?php 
							}
						}
						
					} ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Visit Date','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input id="checkin_date" class="form-control validate[required]" type="text"  name="checkin_date" 
					value="<?php if($edit){ echo date("Y-m-d",strtotime($result->checkin_date));}elseif(isset($_POST['checkin_date'])) echo esc_attr($_POST['checkin_date']); else echo date("Y-m-d");?>">
				 </div>
				 <label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Visit Time','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input type="text" value="<?php if($edit){ echo esc_attr($result->checkin_time);}elseif(isset($_POST['checkintime'])) echo esc_attr($_POST['checkintime']);?>" class="form-control timepicker validate[required]" name="checkintime"/>
				 </div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?></label>
				<div class="col-sm-8">
					 <textarea name="description" maxlength="150" id="description" class="form-control visitor_des_append validate[custom[address_description_validation]] text-input"><?php if($edit) echo esc_textarea($result->description);?></textarea>
				</div>
			</div>
			<?php 
			if($edit)
			{
				$all_visiter_entry=json_decode($result->visiters_value);
				?>
				<div id="add_visiter_entry_div">
				<?php
				$v=0;
				if(!empty($all_visiter_entry))
				{
					foreach($all_visiter_entry as $entry1)
					{
						?>	
							<div class="row padding_left_10">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="name"><?php esc_html_e('Visitor Name','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input type="text" value="<?php echo esc_attr($entry1->visitor_name);?>" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input onlyletter_number_space_validation" maxlength="50" name="visitor_name[]"/>
									 </div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('ID Number','apartment_mgt');?><span class="require-field">*</span></label>
									
									<div class="col-sm-3">
										<input id="mobile" class="form-control validate[required,custom[onlyLetterNumber]] text-input" type="text" maxlength="15"  name="mobile[]" 
										value="<?php echo esc_attr($entry1->mobile);?>">
									</div>
									<label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?></label>
									<div class="col-sm-3">
										<input type="text" value="<?php echo esc_attr($entry1->vehicle_number);?>" maxlength="20" class="form-control text-input" name="vehicle_number[]"/>
									 </div>
								</div>
								<?php
								if($v > 0)
								{
									?>	
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-2">
											<button type="button" class="btn btn-default" onclick="deletevisiterentry(this)">
											<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
											</button>
										</div>
									</div>
									<?php
								}
								?>
							</div>
						
						<?php
						$v=$v+1;
					}
				}
				else
				{
					?>
					<div class="row padding_left_10">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="name"><?php esc_html_e('Visitor Name','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input type="text" value="" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input onlyletter_number_space_validation" maxlength="50" name="visitor_name[]"/>
							 </div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('ID Number','apartment_mgt');?><span class="require-field">*</span></label>
							
							<div class="col-sm-3">
								<input id="mobile" class="form-control validate[required,custom[onlyLetterNumber]] text-input" type="text" maxlength="15"  name="mobile[]" 
								value="">
							</div>
							<label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?></label>
							<div class="col-sm-3">
								<input type="text" value="" maxlength="20" class="form-control text-input" name="vehicle_number[]"/>
							 </div>
						</div>	
					</div>
					<?php
				}
				?>
				</div>				
				<?php
			}
			else
			{
				?>	
				<div id="add_visiter_entry_div">
					<div class="row padding_left_10">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="name"><?php esc_html_e('Visitor Name','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-6">
								<input type="text" value="" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input onlyletter_number_space_validation visitor_name" maxlength="50" name="visitor_name[]"/>
							 </div>
							 <div class="col-sm-2">
							    <button type="button" style="margin-top: 4px;" class="btn btn-info visitor_details_search"><?php esc_html_e('Search','apartment_mgt');?></button>
							 </div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('ID Number','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-3">
								<input id="mobile" class="form-control validate[required,custom[onlyLetterNumber]] text-input" type="text" maxlength="15"  name="mobile[]" 
								value="">
								
							</div>
							<label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?></label>
							<div class="col-sm-3">
								<input type="text" value="" maxlength="20" class="form-control text-input visitor_vehicle_number" name="vehicle_number[]"/>
							 </div>
						</div>	
					</div>
				</div>
				<?php
			}
			?>	
			<div class="form-group">
				<label class="col-sm-2 control-label" for="unit_entry"></label>
				<div class="col-sm-3">
					<button id="add_new_visitor" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_visiter_entry"><?php esc_html_e('Add More Entry','apartment_mgt'); ?>
					</button>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ esc_html_e('Checkin','apartment_mgt'); }else{ esc_html_e('Checkin','apartment_mgt');}?>" name="save_visitor_checkin" class="btn btn-success"/>
			</div>
			
			</form>
        </div><!--END PANEL-BODY---->
       
     <?php 
	 } ?>
	   <!----------ADD_UNIT_FORM---------------------->
		<div class="modal fade overflow_scroll" id="myModal_add_building" role="dialog">
			<div class="modal-dialog modal-lg"><!---MODAL-DIALOG --->
			  <div class="modal-content"><!---MODAL-CONTENT --->
				<div class="modal-header"><!---MODAL HEADER --->
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h3 class="modal-title"><?php esc_html_e('Add Compound','apartment_mgt');?></h3>
				</div>
				<div class="modal-body"><!---MODAL BODY--->
				   <script type="text/javascript">
					$(document).ready(function() {
						 //UNIT FORM VALIDATIONENGINE
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
				<!---UNIT FORM --->
				<form name="unit_form"  method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" class="form-horizontal" id="unit_form">
				<input id="" type="hidden" name="action" value="amgt_add_unit_popup">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Compound','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] building_category" name="building_id" id="">
							<option value=""><?php esc_html_e('Select Compound','apartment_mgt');?></option>
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
				<!----UNIT CATEGORY--->
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
								<label class="col-sm-3 control-label" for="unit_entry"><?php esc_html_e('Unit Size','apartment_mgt');?>(<?php if($unit_measerment_type =='square_meter'){
								echo esc_html_e('square meter','apartment_mgt');
								}
								else{
									echo $unit_measerment_type;
								}
								
								?>)<span class="require-field">*</span></label>
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
				
				</form><!---END UNIT FORM--->
				
				</div>
				<div class="modal-footer"><!------MODAL FOOTER----->
				  <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close','apartment_mgt');?></button>
				</div>
			  </div><!---END MODAL-CONTENT --->
			</div><!---END MODAL-DIALOG --->
		</div>
<script>
// CREATING BLANK INVOICE ENTRY
var blank_income_entry ='';
$(document).ready(function() { 
	blank_expense_entry = $('#unit_name_entry').html();
	//alert("hello" + blank_invoice_entry);
}); 

function add_entry()
{
	$("#unit_name_entry").append(blank_expense_entry);
	//alert("hellooo");
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