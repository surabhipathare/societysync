<script type="text/javascript">
	$(document).ready(function() 
	{    //EVENT VALIDATIONENGINE
		"use strict";
		$('#event_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
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
		$('.timepicker').timepicki();	
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
<script type="text/javascript">
function fileCheck(obj)
{   //FILE VALIDATIONENGINE
	"use strict";
	var fileExtension = ['pdf','doc','jpg','jpeg','png'];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("Only '.pdf','.docx','.jpg','.jpeg','.png'  formats are allowed.");
		$(obj).val('');
	}	
}
</script>
        <?php 	
        	$event_id=0;
			if(isset($_REQUEST['event_id']))
				$event_id=$_REQUEST['event_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = $obj_notice->amgt_get_single_event($event_id);
				} ?>
		
		<div class="panel-body"><!--PANEL BODY-->	
            <form name="event_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="event_form"><!--ADD EVENT FORM-->
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="event_id" value="<?php echo esc_attr($event_id);?>"  />
				<div class="form-group"><!--EVENT TITLE-->
					<label class="col-sm-2 control-label" for="notice_title"><?php esc_html_e('Event Title','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="notice_title" maxlength="50" class="form-control text-input validate[required,custom[onlyLetter_specialcharacter]]" type="text"  value="<?php if($edit){ echo esc_attr($result->event_title);}elseif(isset($_POST['notice_title'])) echo esc_attr($_POST['notice_title']);?>" name="notice_title">
					</div>
				</div>
			
				<div class="form-group"><!--DESCRIPTION-->	
					<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						 <textarea name="description" id="description" maxlength="150" class="form-control validate[required,custom[address_description_validation]] text-input"><?php if($edit){ echo esc_textarea($result->description); }elseif(isset($_POST['description'])) echo esc_textarea($_POST['description']);?></textarea>
					</div>
				</div>
				<style>
					.dropdown-menu {
						min-width: 240px;
					}
                </style>
				<!--STARTDATE END DATE-->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="start"><?php esc_html_e('Start Date','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input id="start_date" class="form-control validate[required] start_date datepicker1" autocomplete="off" type="text"  name="start_date" 
						value="<?php if($edit){ echo date("Y-m-d",strtotime($result->start_date));}elseif(isset($_POST['start_date'])) echo esc_attr($_POST['start_date']); else echo date("Y-m-d");?>">
					</div>
					<label class="col-sm-2 control-label" for="start"><?php esc_html_e('Start Time','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input  id="start_time" placeholder="<?php esc_html_e('Select Start Time','apartment_mgt');?>" type="text" value="<?php if($edit){ echo $result->start_time;}elseif(isset($_POST['start_time'])) echo esc_attr($_POST['start_time']);?>" class="form-control validate[required] timepicker" name="start_time"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="end"><?php esc_html_e('End Date','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input id="end_date" class="form-control validate[required] start_date datepicker2" type="text"  name="end_date" autocomplete="off"
						value="<?php if($edit){ echo date("Y-m-d",strtotime($result->end_date));}elseif(isset($_POST['end_date'])) echo esc_attr($_POST['end_date']); else echo date("Y-m-d");?>">
					</div>
					<label class="col-sm-2 control-label" for="end"><?php esc_html_e('End Time','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input id="end_time" placeholder="<?php esc_html_e('Select End Time','apartment_mgt');?>" type="text" value="<?php if($edit){ echo $result->end_time;}elseif(isset($_POST['end_time'])) echo esc_attr($_POST['end_time']);?>" class="form-control validate[required] timepicker" name="end_time"/>
					</div>
				</div>
				<!---DOCUMENT--->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="Document"><?php esc_html_e('Document','apartment_mgt');?></label>
					<div class="col-sm-2">
						<input type="text" readonly id="amgt_user_avatar_url" class="form-control" name="amgt_user_avatar"  
						value="<?php if($edit){ echo esc_url( $result->event_doc );} elseif(isset($_POST['amgt_user_avatar'])){ echo $_POST['amgt_user_avatar']; }?>" />
					</div>	
					<div class="col-sm-3">
					<input type="hidden" name="hidden_upload_file" value="<?php if($edit){ echo $result->event_doc;}elseif(isset($_POST['upload_file'])) echo $_POST['upload_file'];?>">
						<input id="upload_file" name="upload_file" type="file" onchange="fileCheck(this);" class=""  />		
							
					  </div>
				</div>
				<div class="form-group margin_bottom_5px">
					<label class="col-sm-2 control-label " for="enable"><?php esc_html_e('Send SMS','apartment_mgt');?></label>
					<div class="col-sm-8 margin_bottom_5px">
						 <div class="checkbox">
							<label>
								<input id="chk_sms_sent" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="amgt_sms_service_enable">
							</label>
						</div>
						 
					</div>
				</div>
		
				<div id="hmsg_message_sent" class="hmsg_message_none margin_bottom_5px">
					<div class="form-group ">
						<label class="col-sm-2 control-label" for="sms_template"><?php esc_html_e('SMS Text','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<textarea name="sms_template" class="form-control validate[required,custom[address_description_validation]]" maxlength="160"></textarea>
							<label><?php esc_html_e('Max. 160 Character','apartment_mgt');?></label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label " for="publish"><?php esc_html_e('Publish To Notice Board','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input id="publish" class="form-control text-input" type="checkbox" <?php if($edit==1 && $result-> 	publish_status=='yes'){ echo "checked";}?> name="publish" value="yes">
					</div>	
				</div>
				<?php wp_nonce_field( 'save_event_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ esc_html_e('Submit','apartment_mgt'); }else{ esc_html_e('Submit','apartment_mgt');}?>" name="save_event" class="btn btn-success event_time_validation"/>
				</div>
            </form><!--END ADD EVENT FORM-->
        </div><!--END PANEL BODY-->
<?php ?>