<?php 
//ADD UNIT TAB
if($active_tab == 'addunit') { ?>
   <script type="text/javascript">
		jQuery(document).ready(function() 
		{    //UNIT FORM VALIDATIONENGINE
			"use strict";
			$('#unit_form_frontend').validationEngine();
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
		<?php	$unit_id=0;
		if(isset($_REQUEST['unit_id']))
			$unit_id=$_REQUEST['unit_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
				$edit=1;
				$result = $obj_units->amgt_get_single_unit($unit_id);
				//var_dump($result);
			} ?>
		<div class="panel-body"><!---PANEL BODY--->
		    <!---UNIT FORM---->
			<form name="unit_form" action="" method="post" class="form-horizontal" id="unit_form_frontend">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
			<input type="hidden" name="unit_id" value="<?php echo esc_attr($unit_id);?>"  />
			<input type="hidden" name="unit_index" value="<?php echo esc_attr($_REQUEST['index']); ?>"  />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
				<?php
				if($edit)
				{
					$building = get_post($result->building_id);
				?>
					<div class="col-sm-8">					
						<input class="form-control text-input" type="hidden" value="<?php echo esc_attr($result->building_id
						); ?>" name="building_id">
						<input class="form-control text-input" type="text" value="<?php echo esc_attr($building->post_title); ?>" name="" readonly>
					</div>	
				<?php
				}
				else
				{		
				?>	
					<div class="col-sm-8">
						<select class="form-control validate[required] building_category" name="building_id" <?php if($edit){ echo'readonly'; } ?>>
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
					<div class="col-sm-2 margin_top_10_res"><button id="addremove" model="building_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				<?php
				}
				?>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
				<?php
				if($edit)
				{
					$unit_cat=get_post($result->unit_cat_id);
					?>
					<div class="col-sm-8">					
						<input class="form-control text-input" type="hidden" value="<?php echo esc_attr($result->unit_cat_id); ?>" name="unit_cat_id">
						<input class="form-control text-input" type="text" value="<?php echo esc_attr($unit_cat->post_title); ?>" name="" readonly>
					</div>	
				<?php
				}
				else
				{		
				?>	
					<div class="col-sm-8">
						<select class="form-control validate[required] unit_category" name="unit_cat_id" <?php if($edit){ echo'readonly'; } ?>>
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
							} ?>
						</select>
					</div>
					<div class="col-sm-2 margin_top_10_res"><button id="addremove" model="unit_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				<?php
				}
				?>
			</div>
			<?php  if($edit){
					$all_entry=json_decode($result->units);
				}
				else
				{
					if(isset($_POST['unit_names'])){
						$all_data=$obj_units->amgt_get_entry_records($_POST);
						$all_entry=json_decode($all_data);
					}
				}
				if(!empty($all_entry))
				{
					foreach($all_entry as $entry)
					{
						$entry_obj=array($entry);
						$entry_array=$entry_obj[0];
						
						$unit_name=$_REQUEST['unit_name'];
						$uname=$entry->entry;
						if($unit_name==$uname)
						{	
						?>
						<div id="unit_name_entry"><!--UNIT_NAME_ENTRY--->
							<div class="form-group">
								<label class="col-sm-2 control-label" for="unit_entry"><?php esc_html_e('Unit Name','apartment_mgt');?><span class="require-field">*</span></label>
							
								<div class="col-sm-2">
									<input class="form-control validate[required] text-input onlyletter_number_space_validation unit_name" type="text" value="<?php echo esc_attr($entry->entry);?>" maxlength="30" name="unit_names[]" placeholder="<?php esc_html_e('Unit Name','apartment_mgt');?>" readonly>
								</div>
								<?php $unit_measerment_type=get_option( 'amgt_unit_measerment_type' );?>
								<label class="col-sm-2 margin_top_10_res control-label" for="unit_entry"><?php esc_html_e('Unit Size','apartment_mgt');?>(<?php if($unit_measerment_type =='square_meter'){
										echo esc_html_e('square meter','apartment_mgt');
										}
										else{
											echo $unit_measerment_type;
										}
									?>)<span class="require-field">*</span></label> 
								<div class="col-sm-2">
									<input  class="form-control validate[required] margin_top_10_res text-input" type="number" onKeyPress="if(this.value.length==6) return false;"  min="0" value="<?php if(array_key_exists("measurement",$entry_array)){  echo esc_attr($entry->measurement); }?>" name="unit_size[]" placeholder="<?php esc_html_e('Unit Size','apartment_mgt');?>">
								</div>
							</div>	
						</div>
					<?php
						}
					}
				}
				else
				{ ?>
					<div id="unit_name_entry"><!---UNIT_NAME_ENTRY--->
							<div class="form-group">
							<label class="col-sm-2 control-label" for="unit_entry"><?php esc_html_e('Unit Name','apartment_mgt');?><span class="require-field">*</span></label>
						
							<div class="col-sm-2">
								<input class="form-control validate[required] text-input onlyletter_number_space_validation unit_name" type="text" value="" maxlength="30" name="unit_names[]" placeholder="<?php esc_html_e('Unit Name','apartment_mgt');?>">
							</div>	
							<?php $unit_measerment_type=get_option( 'amgt_unit_measerment_type' );?>						
							<label class="col-sm-2 control-label margin_top_10_res" for="unit_entry"><?php esc_html_e('Unit Size','apartment_mgt');?>(<?php if($unit_measerment_type =='square_meter'){
								echo esc_html_e('square meter','apartment_mgt');
								}
								else{
									echo $unit_measerment_type;
								}
								?>)<span class="require-field">*</span></label>
							<div class="col-sm-2">
								<input  class="form-control validate[required] margin_top_10_res text-input" type="number" onKeyPress="if(this.value.length==6) return false;"  min="0" value="" name="unit_size[]" placeholder="<?php esc_html_e('Unit Size','apartment_mgt');?>">
							</div>
							<div class="col-sm-2">
							<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
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
			<?php } ?>
			<hr>			
			<?php wp_nonce_field( 'save_residential_unit_nonce' ); ?>
			<div class="col-sm-offset-2 col-sm-8">
			<?php $unit_type=get_option( 'amgt_apartment_type' ); ?>
				<input type="submit" value="<?php if($edit){ esc_html_e('Save','apartment_mgt'); }else{ esc_html_e('Add '.$unit_type.' Unit','apartment_mgt');}?>" name="save_residential_unit" class="btn btn-success"/>
			</div>
			</form>
        </div><!---END PANEL BODY--->
		 <script>
		// CREATING BLANK INVOICE ENTRY
		var blank_income_entry ='';
		jQuery(document).ready(function() { 
			blank_expense_entry = jQuery('#unit_name_entry').html();
			//alert("hello" + blank_invoice_entry);
		}); 

		function add_entry()
		{
			jQuery("#unit_name_entry").append(blank_expense_entry);
			//alert("hellooo");
		}
		
		// REMOVING INVOICE ENTRY
		function deleteParentElement(n)
		{
			if(confirm("Are you sure want to delete this record?"))
			{
				n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
			}	
		}
	</script>
    <?php 
	} ?>