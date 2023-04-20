<?php
  //ADD_INVENTORY TAB
 if($active_tab == 'add_inventory')
	{ ?>
	<script type="text/javascript">
	$(document).ready(function() {
		"use strict";
		$('#inventory_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
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
				$inventory_id=0;
				if(isset($_REQUEST['inventory_id']))
					$inventory_id=$_REQUEST['inventory_id'];
				$edit=0;
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
						
						$edit=1;
						$result = $obj_assets->amgt_get_single_inventory($inventory_id);
					
					} ?>
		<div class="panel-body"><!--PANEL BODY-->
		    <!--INVENTORY_FORM-->
			<form name="inventory_form" action="" method="post" class="form-horizontal" id="inventory_form">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="inventory_id" value="<?php echo esc_attr($inventory_id);?>"  />
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="inventory_name"><?php esc_html_e('Inventory Name','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="inventory_name" maxlength="50" class="form-control validate[required] text-input onlyletter_number_space_validation" type="text"  value="<?php if($edit){ echo esc_attr($result->inventory_name);}elseif(isset($_POST['inventory_name'])) echo esc_attr($_POST['inventory_name']);?>" name="inventory_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="quentity "><?php esc_html_e('Quantity  ','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="quentity " min="1" class="form-control text-input validate[required]" type="number" onKeyPress="if(this.value.length==4) return false;"  value="<?php if($edit){ echo esc_attr($result->quentity);}elseif(isset($_POST['quentity'])) echo esc_attr($_POST['quentity']);?>" name="quentity">
					</div>	
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="inventory_unit_cat"><?php esc_html_e('Unit','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] inventory_unit_cat" name="inventory_unit_cat" id="">
							<option value=""><?php esc_html_e('Select Unit','apartment_mgt');?></option>
							<?php 
							if($edit)
								$category =$result->inventory_unit_cat;
							elseif(isset($_REQUEST['inventory_unit_cat']))
								$category =$_REQUEST['inventory_unit_cat'];  
							else 
								$category = "";
							
							$activity_category=amgt_get_all_category('inventory_unit_cat');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
							} ?>
						</select>
					</div>
					<div class="col-sm-2 margin_top_10_res"><button id="addremove" model="inventory_unit_cat"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				</div>
				<?php wp_nonce_field( 'save_inventory_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ esc_html_e('save','apartment_mgt'); }else{ esc_html_e('Add Inventory','apartment_mgt');}?>" name="save_inventory" class="btn btn-success"/>
				</div>
		    </form><!--END INVENTORY_FORM-->
        </div><!--END PANEL BODY-->
	<?php }  ?>