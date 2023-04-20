<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	//TAX VALIDATIONENGINE
	"use strict";
	$('#tax_form').validationEngine();
	
} );
</script>
     <?php 	
		$tax_id=0;
		if(isset($_REQUEST['tax_id']))
			$tax_id=$_REQUEST['tax_id'];
		$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_tax->amgt_get_single_tax($tax_id);
			} ?>
		<div class="panel-body"><!--PANEL BODY -->
			<form name="tax_form" action="" method="post" class="form-horizontal" id="tax_form"><!--TAX FORM-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="tax_id" value="<?php echo esc_attr($tax_id);?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="tax_title">
					<?php esc_html_e('Tax Name','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="tax_title" maxlength="30" class="form-control validate[required,custom[onlyLetterSp]] text-input" 
						type="text" value="<?php if($edit){ echo esc_attr($result->tax_title);}
						elseif(isset($_POST['tax_title'])) echo esc_attr($_POST['tax_title']);?>" name="tax_title">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="tax">
					<?php esc_html_e('Tax','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="tax" placeholder="<?php esc_html_e('Must be enter percentage value like 5','apartment_mgt');?>" class="form-control validate[required,custom[number]] text-input" 
						type="number" value="<?php if($edit){ echo esc_attr($result->tax);}
						elseif(isset($_POST['tax'])) echo esc_attr($_POST['tax']);?>" name="tax" min="0" max="100" onKeyPress="if(this.value.length==15) return false;">
					</div>
				</div>
				<?php wp_nonce_field( 'save_tax_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" 
					value="<?php if($edit){ esc_html_e('Save Tax','apartment_mgt'); }else{ esc_html_e('Add Tax','apartment_mgt');}?>" 
					name="save_tax" 
					class="btn btn-success"/>
				</div>
			</form><!--END TAX FORM-->
		</div><!-- END PANEL BODY DIV -->