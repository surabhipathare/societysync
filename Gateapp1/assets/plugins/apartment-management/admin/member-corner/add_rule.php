<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	 //COMPLAIN FORM VALIDATIONENGINE
	 "use strict";
	$('#complaint_form').validationEngine();
} );
</script>
     <?php 	
		$complaint_id=0;
		if(isset($_REQUEST['complaint_id']))
			$complaint_id=$_REQUEST['complaint_id'];
		$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
				
				$edit=1;
				$result = $obj_complaint->amgt_get_single_complaint($complaint_id);
				
			} ?>		
		<div class="panel-body"><!--PANEL BODY--> 
         <!----COMPLAIN FORM-----> 		
        <form name="complaint_form" action="" method="post" class="form-horizontal" id="complaint_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
		<input type="hidden" name="complaint_id" value="<?php echo esc_attr($complaint_id);?>"  />
		<div class="form-group">  <!----TITLE----> 
			<label class="col-sm-2 control-label " for="title"><?php esc_html_e('Title','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="title" class="form-control validate[required] text-input" type="text"  name="title" 
				value="<?php if($edit){ echo esc_attr($result->title);}elseif(isset($_POST['title'])) echo esc_attr($_POST['title']);?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				 <textarea name="description" id="description" class="form-control validate[required] text-input"><?php if($edit) echo esc_textarea($result->complaint_description);?></textarea>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ esc_html_e('Submit','apartment_mgt'); }else{ esc_html_e('Add Rule','apartment_mgt');}?>" name="add_rule" class="btn btn-success"/>
        </div>
		
        </form>	<!----END COMPLAIN FORM-----> 	
        </div><!--END PANEL BODY-->
        
<?php  ?>