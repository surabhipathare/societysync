<script type="text/javascript">
$(document).ready(function()
{   //FACILITY BOOKING VALIDATIONENGINE
"use strict";
	$('#facility_booking_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
	$(".display-members").select2();
	$('.timepicker').timepicki();	
		var date = new Date();
		 date.setDate(date.getDate()-0);
		 $("#start_date").datepicker({
	       dateFormat: "yy-mm-dd",
			minDate:0,
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() + 0);
	            $("#end_date").datepicker("option", "minDate", dt);
	        }
		    });
		    $("#end_date").datepicker({
		      dateFormat: "yy-mm-dd",
		        onSelect: function (selected) {
		            var dt = new Date(selected);
		            dt.setDate(dt.getDate() - 0);
		            $("#start_date").datepicker("option", "maxDate", dt);
		        }
		    });	
} );
</script>
    <?php 	
		$facility_booking_id=0;
		$status=1;
		if(isset($_REQUEST['facility_booking_id']))
			$facility_booking_id=$_REQUEST['facility_booking_id'];
		$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_facility->amgt_get_single_boooked_facility($facility_booking_id);
			
			} ?>
	<style>
	.dropdown-menu
	{
		min-width: 240px;
	}
	</style>
	<div class="panel-body"><!--PANEL BODY-->
	     <!--FACILITY_BOOKING_FORM-->
        <form name="facility_booking_form" action="" method="post" class="form-horizontal" id="facility_booking_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
		<input type="hidden" name="facility_booking_id" value="<?php echo esc_attr($facility_booking_id);?>"  />
		<input type="hidden" name="status" value="<?php echo esc_attr($status);?>"  />
		<input type="hidden" name="user_type" value="admin"  />
		<div class="form-group">
			<label class="col-sm-2 control-label" for="facility_name">
			<?php esc_html_e('Select A Facility To Book','apartment_mgt');?><span class="require-field">*</span></label>
			<?php
			if($edit)
			{
			?>
				<div class="col-sm-8">					
					<input class="form-control text-input" id="facility_id" type="hidden" value="<?php echo esc_attr($result->facility_id); ?>" name="facility_id">
					<input class="form-control text-input" type="text" value="<?php echo amgt_get_facility_name($result->facility_id); ?>" name="" readonly>
				</div>	
			<?php
			}
			else
			{	
			?>	
				<div class="col-sm-8"><!---SELECT FACILITY FORM--->
					<select name="facility_id" id="facility_id" class="form-control validate[required]">
						<option value=''><?php esc_html_e('Select Facility','apartment_mgt');?></option>
						<?php
						if($edit)
							$facility_id =$result->facility_id;
						elseif(isset($_REQUEST['facility_id']))
							$facility_id =$_REQUEST['facility_id'];  
						else 
							$facility_id = "";
						$all_facility = $obj_facility->amgt_get_all_facility();
						if(!empty($all_facility))
						{
							foreach($all_facility as $retrive_data)
							{
								echo '<option value="'.$retrive_data->facility_id.'" '.selected($facility_id,$retrive_data->facility_id).'>'.$retrive_data->facility_name.'</option>';
							}
						}
						?>
					</select>
				</div>
			<?php
			}
			?>
		</div>
		<div class="form-group"><!--FORM GROUP BOOK FOR ACTIVITY-->
			<label class="col-sm-2 control-label" for="facility_activity">
			<?php esc_html_e('Book For Activity','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required] facility_booking_for" name="activity_id">
				<option value=""><?php esc_html_e('Select Activity','apartment_mgt');?></option>
				<?php 
				if($edit)
					$category =$result->activity_id;
				elseif(isset($_REQUEST['activity_id']))
					$category =$_REQUEST['activity_id'];  
				else 
					$category = "";
				
				$activity_category=amgt_get_all_category('facility_booking_for');
				if(!empty($activity_category))
				{
					foreach ($activity_category as $retrive_data)
					{
						echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}
				} ?>
				</select>
			</div>
			<!--ADD OR REMOVE FACILITY-->
			<div class="col-sm-2 margin_top_10_res"><button id="addremove" model="facility_booking_for"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
		</div>
		<!---period_type--->
		<?php if($edit==1 && $result->period_type=='date_type'){ ?>
		<input id="period_type"  type="hidden" value="date_type" name="period_type">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="facility_start_date">
			<?php esc_html_e('Start Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="start_date" class="form-control validate[required] start" autocomplete="off" type="text"  
				value="<?php if($edit){ echo date("Y-m-d",strtotime($result->start_date));}
				elseif(isset($_POST['start_date'])) echo esc_attr($_POST['start_date']);?>" name="start_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="end_date">
			<?php esc_html_e('End Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="end_date" class="form-control validate[required] end" autocomplete="off" type="text"  
				value="<?php if($edit){ echo date("Y-m-d",strtotime($result->end_date));}
				elseif(isset($_POST['end_date'])) echo esc_attr($_POST['end_date']);?>" name="end_date">
			</div>
		</div>
		<?php } ?>
		<?php if($edit==1 && $result->period_type=='hour_type'){?>
		<input id="period_type"  type="hidden" value="hour_type" name="period_type">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="facility_start_date">
			<?php esc_html_e('Booking Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="" class="form-control validate[required]" autocomplete="off" type="text"  
				value="<?php if($edit){ echo date("Y-m-d",strtotime($result->start_date));}
				elseif(isset($_POST['start_date'])) echo esc_attr($_POST['start_date']);?>" name="start_date" readonly>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="facility_start_date">
			<?php esc_html_e('Start Time','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input type="text" autocomplete="off" value="<?php if($edit){ echo esc_attr($result->start_time);}elseif(isset($_POST['start_time'])) echo esc_attr($_POST['start_time']);?>" class="form-control start" name="start_time" readonly />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="end_date">
			<?php esc_html_e('End Time','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="end_time" type="text" value="<?php if($edit){ echo esc_attr($result->end_time);}elseif(isset($_POST['end_time'])) echo esc_attr($_POST['end_time']);?>" class="form-control end" name="end_time" readonly />
			</div>
		</div>
		<?php } ?>
		<div id="select_facility_block">
				
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="facility_charge">
			<?php esc_html_e('Total Cost','apartment_mgt'); echo ' '. '('. amgt_get_currency_symbol(get_option( 'apartment_currency_code')).')';?> </label>
			<div class="col-sm-8">
				<input id="facility_charge" class="form-control facility_charge" readonly type="number" onKeyPress="if(this.value.length==8) return false;"  
				value="<?php if($edit){ echo esc_attr($result->booking_cost);}
				elseif(isset($_POST['facility_charge'])) echo esc_attr($_POST['facility_charge']);?>" name="facility_charge">
			</div>
		</div>
		
		<div class="form-group"><!--BOOKING FACILITY ON BEHALF OF-->
				<label class="col-sm-2 control-label" for="day"><?php esc_html_e('Booking Facility On Behalf Of','apartment_mgt');?><span  class="require-field required">*</span></label>	
				<div class="col-sm-8">
					<select id="member_list" class="display-members" name="on_behalf_of" required="true">
					<option value=""><?php esc_html_e('Select Member','apartment_mgt');?></option>
						<?php 
						if($edit)
						{
							$memberid =$result->book_on_behalf_of;
						}
						elseif(isset($_REQUEST['on_behalf_of']))
						{
							$memberid =$_REQUEST['on_behalf_of']; 
						}							
						else 
						{
							$memberid = 0;
						}
						
						$get_members = array('role' => 'member');
						$membersdata=get_users($get_members);
						
						if(!empty($membersdata))
						{
							foreach ($membersdata as $retrive_data)
							{
								 echo '<option value="'.$retrive_data->ID.'" '.selected($memberid,$retrive_data->ID).'>'.amgt_get_display_name($retrive_data->ID) .'  -  ' .$retrive_data->unit_name .' </option>';
							}
						} ?>
				</select>
				</div>				
			</div>
			<?php wp_nonce_field( 'save_book_facility_nonce' ); ?>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ esc_html_e('Save','apartment_mgt'); }else{ esc_html_e('Save','apartment_mgt');}?>" name="save_book_facility" class="btn btn-success time_validation"/>
			</div>
		</form><!--END FACILITY_BOOKING_FORM-->
    </div><!--END PANEL BODY-->