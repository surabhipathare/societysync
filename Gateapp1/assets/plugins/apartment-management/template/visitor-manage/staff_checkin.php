<?php if($active_tab == 'staff-checkin') { ?>
		<script type="text/javascript">
			$(document).ready(function() {
				   //STAFF CHECKING FORM VALIDATIONENGINE
				   "use strict";
				$('#staff_checkin_form').validationEngine();
					$('.timepicker').timepicki();
					var date = new Date();
					date.setDate(date.getDate()-0);
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
			});
        </script>
	     <?php  
			$vcheckin_id=0;
			if(isset($_REQUEST['staff_checkin_id']))
				$vcheckin_id=$_REQUEST['staff_checkin_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					
					$edit=1;
					$result = $obj_gate->amgt_get_single_checkin($vcheckin_id);
				
				} ?>
		<div class="panel-body"><!---PANEL BODY-->
		     <!---STAFF CHECKING FORM TABLE-->
			<form name="staff_checkin_form" action="" method="post" class="form-horizontal" id="staff_checkin_form">
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="vcheckin_id" value="<?php echo esc_attr($vcheckin_id);?>"  />
				
				<input type="hidden" name="checkin_type" value="staff_checkin"  />
				<div class="form-group margin_top_3o clear_both">
					<label class="col-sm-2 control-label" for="gate"><?php esc_html_e('Choose Gate','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
					<?php $gateval = "0"; if($edit){ $gateval=$result->gate_id; }elseif(isset($_POST['gate'])) {$gateval=$_POST['gate'];}
					if(!empty($gatedata))
						{
							$i=1;
							foreach($gatedata as $gate){
								if($edit){
							?>
							<label class="radio-inline front_radio">
							<input type="radio" value="<?php echo esc_attr($gate->id);?>" class="tog validate[required] radio_border_radius" name="gate"  <?php  echo checked( $gate->id, $gateval);  ?>/><?php echo esc_attr($gate->gate_name);?>
							</label>
					
								<?php }
								else
								{?>
									<label class="radio-inline front_radio">
							<input type="radio" value="<?php echo esc_attr($gate->id);?>" class="tog validate[required] radio_border_radius" name="gate"  <?php  if($i==1) echo "checked"; ?>/><?php echo esc_attr($gate->gate_name);?>
							</label>
								<?php }
						$i+=1;
								
								
						}
						}
						else
						{ ?>
							<label class="radio-inline front_radio">
							<?php esc_html_e('No Any Gates.','apartment_mgt');
							echo "</label>";
						}
					?>
						
					</div>
				</div>
				<div class="form-group"><!--STAFF MEMBER--->
					<label class="col-sm-2 control-label"  for="member"><?php esc_html_e('Staff Member','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required]" id="member_id" name="member_id">
						<option value=""><?php esc_html_e('Select Staff Member','apartment_mgt');?></option>
						<?php 
						if($edit)
							$memberid =$result->member_id;
						elseif(isset($_REQUEST['member_id']))
							$memberid =$_REQUEST['member_id'];  
						else 
							$memberid = "";
						
						$get_members = array('role' => 'staff_member');
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
				
				<div id="staff-data">
					<!---Here Display staff member details---->
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Check In Date','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input id="checkin_date" class="form-control validate[required]" type="text" autocomplete="off" name="checkin_date" 
						value="<?php if($edit){ echo date("Y-m-d",strtotime($result->checkin_date));}elseif(isset($_POST['checkin_date'])) echo esc_attr($_POST['checkin_date']); else echo date("Y-m-d");?>">
					 </div>
					 <label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Check In Time','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input type="text" value="<?php if($edit){ echo esc_attr($result->checkin_time);}elseif(isset($_POST['checkintime'])) echo esc_attr($_POST['checkintime']);?>" class="form-control timepicker validate[required]" name="checkintime"/>
					 </div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?></label>
					<div class="col-sm-8">
						 <textarea name="description" id="description" maxlength="150" class="form-control validate[custom[address_description_validation]] text-input"><?php if($edit) echo esc_textarea($result->description);?></textarea>
					</div>
				</div>
				<?php wp_nonce_field( 'save_staff_checkin_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ esc_html_e('Checkin','apartment_mgt'); }else{ esc_html_e('Checkin','apartment_mgt');}?>" name="save_staff_checkin" class="btn btn-success"/>
				</div>
			
			</form>
        </div><!---END PANEL BODY-->
	<?php } ?>