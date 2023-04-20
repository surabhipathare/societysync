<?php 
//ADD DOCUMENT
if($active_tab == 'add_document')
	{?>
		<script type="text/javascript">
		$(document).ready(function()
		{
			"use strict";
			$('#document_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
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
       <?php 	
	     $document_id=0;
			if(isset($_REQUEST['document_id']))
				$document_id=$_REQUEST['document_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					
					$edit=1;
					$result = $obj_document->amgt_get_single_document($document_id);
				
				} ?>
		<div class="panel-body"><!--PANEL BODY-->
		     <!--DOCUMENT_FORM-->
			<form name="document_form" action="" method="post" class="form-horizontal" id="document_form" enctype="multipart/form-data">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="document_id" value="<?php echo esc_attr($document_id);?>"  />
				<?php
				if($obj_apartment->role == 'member')
				{
					$member_id=get_current_user_id();
					$building =get_user_meta($member_id,'building_id',true);
					$unit_cat_id =get_user_meta($member_id,'unit_cat_id',true);
					$unit_name =get_user_meta($member_id,'unit_name',true);
				?>
				<input type="hidden" name="building_id" value="<?php echo esc_attr($building);?>"  />	
				<input type="hidden" name="unit_cat_id" value="<?php echo esc_attr($unit_cat_id);?>"  />	
				<input type="hidden" name="unit_name" value="<?php echo esc_attr($unit_name);?>"  />	
				<?php
				}
				else{
				?>
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
								if($retrive_data->ID == $obj_apartment->building_id && $obj_apartment->role=='member')
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
								elseif($obj_apartment->role=='accountant' || $obj_apartment->role=='staff_member')
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
							}
						} ?>
						</select>
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
						} ?>
						</select>
					</div>
					
				</div>
				<?php
				}
				?>
				<?php if($obj_apartment->role=='member')
				{ 
					$curr_user_id=get_current_user_id();
					$unit_name =get_userdata($curr_user_id);
			
				?>
					<div class="form-group">
						<label class="col-sm-2 control-label"  for="member"><?php esc_html_e('Member','apartment_mgt');?></label>
						<div class="col-sm-8">
							<input name="member_id" type="hidden" value="<?php echo esc_attr($curr_user_id);?>">
							<input name="member_id123" type="text" class="form-control" value="<?php echo esc_attr($unit_name->display_name);?>" Readonly>
						</div>
					</div>
				<?php 
				}
				else
				{ ?>
				<div class="form-group">
					<label class="col-sm-2 control-label"  for="member"><?php esc_html_e('Member','apartment_mgt');?></label>
					<div class="col-sm-8">
						<select class="form-control member_id" id="member_id" name="member_id">
						<option value=""><?php esc_html_e('Select Member','apartment_mgt');?></option>
						<?php 
						if($edit)
							$memberid =$result->member_id;
						elseif(isset($_REQUEST['member_id']))
							$memberid =$_REQUEST['member_id'];  
						else 
							$memberid = "";
						
						$get_members = array('role' => 'member');
						$membersdata=get_users($get_members);
						
						if(!empty($membersdata))
						{
							foreach ($membersdata as $staff_data)
							{
								echo '<option value="'.$staff_data->ID.'" '.selected($memberid,$staff_data->ID).'>'.$staff_data->display_name.'</option>';
							}
						} ?>
						</select>
					</div>
					
				</div>
				<?php } ?>
				<?php if($edit)
				{ ?>
					<div class="form-group">
				
					<label class="col-sm-2 control-label" for="doc_title"><?php esc_html_e('Upload Document','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-2">
						<input id="doc_title" maxlength="50" class="form-control validate[required] text-input onlyletter_number_space_validation" type="text" placeholder="Title"  value="<?php if($edit) echo esc_attr($result->doc_title);?>" name="doc_title">
					</div>
					<input type="hidden" name="hidden_upload_file" value="<?php if($edit){ echo esc_attr($result->document_content);}elseif(isset($_POST['upload_file'])) echo esc_attr($_POST['upload_file']);?>">
					<div class="col-sm-2">
						<a target="blank" href="<?php echo esc_attr($result->document_content); ?>" class="btn btn-default"> <i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
					</div>	
						<div class="col-sm-2">
							 <input id="upload_file" onchange="fileCheck(this);" name="upload_file"  type="file" <?php if($edit){ ?>class="" <?php }else{ ?>class="validate[required]"<?php } ?>  />
					</div>
					<div class="col-sm-2">
						 <textarea name="description"  maxlength="150" placeholder="Description" class="form-control text-input"><?php if($edit) echo esc_textarea($result->description);?></textarea>
					</div>
					
					
				</div>
				 <?php } else{ ?>
					 <div class="form-group">
				
					<label class="col-sm-2 control-label" for="doc_title"><?php esc_html_e('Upload Document','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-2">
						<input id="doc_title" maxlength="50" class="form-control validate[required] text-input onlyletter_number_space_validation" type="text" placeholder="<?php esc_html_e('Title','apartment_mgt');?>"  value="<?php if($edit) echo esc_attr($result->doc_title);?>" name="doc_title[]">
					</div>
					<div class="col-sm-1">
						<input type="text" id="amgt_user_avatar_url" class="form-control margin_top_10_res" name="amgt_user_avatar[]"    
						value="" readonly/>
					</div>	
					<div class="col-sm-3 member_doc">
							 <input id="upload_file" onchange="fileCheck(this);" name="upload_file[]"  type="file" <?php if($edit){ ?>class="margin_top_5_res margin_left_15_res" <?php }else{ ?>class="validate[required] margin_top_5_res margin_left_15_res"<?php } ?>  />
					</div>
					<div class="col-sm-2">
						 <textarea name="description[]"  maxlength="150" placeholder="<?php esc_html_e('Description','apartment_mgt');?>" class="form-control text-input resize margin_top_10_res"><?php if($edit) echo esc_textarea($result->description);?></textarea>
					</div>
					
					
					<div class="col-sm-1">
						<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
						</button>
					</div>
				</div>
				
				<div id="document_entry_frontend"><!--DOCUMENT_ENTRY_FRONTEND-->
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="invoice_entry"></label>
					<div class="col-sm-3">				
						<p  id="add_new_document_entry_frontend" class="btn btn-default btn-sm btn-icon icon-left"   name="add_new_entry" >
						<?php esc_html_e('Add More Document','apartment_mgt'); ?>
						</p>
					</div>
				</div>
				 <?php  } ?>
				
				<?php wp_nonce_field( 'save_document_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ esc_html_e('save','apartment_mgt'); }else{ esc_html_e('Add Document','apartment_mgt');}?>" name="save_document" class="btn btn-success"/>
				</div>
            </form> <!--END DOCUMENT_FORM-->
        </div><!--END PANEL BODY-->
     <?php } ?>