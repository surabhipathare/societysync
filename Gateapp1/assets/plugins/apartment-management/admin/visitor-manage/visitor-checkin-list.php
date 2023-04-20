<script type="text/javascript">
$(document).ready(function() {
	//VISITOR CHECKIN LIST
	"use strict";
	jQuery('#reports').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
	jQuery('#visitor_checkin_list').DataTable({
		"responsive":true,
		"order": [[ 2, "desc" ]],
		"aoColumns":[
	        {"bSortable": false},
	        {"bSortable": true},
	        {"bSortable": true},
	        {"bSortable": true},
	        {"bSortable": true},	      
	        //{"bSortable": true},
	        {"bSortable": true},
	        {"bSortable": false},
	        {"bSortable": false}],
			language:<?php echo amgt_datatable_multi_language();?>
		});
		$(".sdate").datepicker({
       dateFormat: "yy-mm-dd",
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".edate").datepicker("option", "minDate", dt);
        }
    });
    $(".edate").datepicker({
       dateFormat: "yy-mm-dd",
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 0);
            $(".sdate").datepicker("option", "maxDate", dt);
        }
    });	
} );
	$(document).ready(function()
	{		
		jQuery('#select_all').on('click', function(e)
		{
			 if($(this).is(':checked',true))  
			 {
				$(".sub_chk").prop('checked', true);  
			 }  
			 else  
			 {  
				$(".sub_chk").prop('checked',false);  
			 } 
		});
		$('.sub_chk').change(function()
		{ 
			if(false == $(this).prop("checked"))
			{ 
				$("#select_all").prop('checked', false); 
			}
			if ($('.sub_chk:checked').length == $('.sub_chk').length )
			{
				$("#select_all").prop('checked', true);
			}
		});
	});
</script>
<?php if($active_tab == 'visitor-checkinlist')
{    	?>
	<div class="panel-body"><!--PANBEL BODY-->
        <div class="table-responsive"><!---TABLE-RESPONSIVE--->
		<form method="post" id="reports">
				<div class="form-group ">
					<label class="col-sm-2 date_label label_100 control-label margin_top_6" for="facility_start_date">
					<?php esc_html_e('Start Date','apartment_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-2">
						<input id="start_date" class="form-control sdate  validate[required]" type="text"  
						value="<?php if(isset($_POST['sdate'])) echo $_POST['sdate'];?>" name="sdate" autocomplete="off" readonly>
					</div>
					<label class="col-sm-2 date_label label_100 control-label margin_top_6" for="facility_start_date">
					<?php esc_html_e('End Date','apartment_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-2">
						<input id="edate" class="form-control edate validate[required]" type="text"  
						value="<?php if(isset($_POST['edate'])) echo $_POST['edate'];?>" name="edate" autocomplete="off" readonly>
					</div>
					<label class="col-sm-1 date_label label_100 control-label margin_top_6" for="facility_start_date">
					<?php esc_html_e('Status','apartment_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-2">
						<select class="form-control status validate[required]" name="status">
							<option value=""><?php esc_html_e('Select status','apartment_mgt');?></option>
							<option value="0"><?php esc_html_e('Processing','apartment_mgt');?></option>
							<option value="1"><?php esc_html_e('Approved','apartment_mgt');?></option>
						</select>
					</div>
				</div>
				<div class="form-group col-md-1">
					<label for="subject_id">&nbsp;</label>
					<input type="submit" name="filter_visitor"  Value="<?php esc_html_e('Go','apartment_mgt');?>"  class="btn btn-success"/>
				</div>
				
				<?php  
				if(isset($_POST['filter_visitor']))
				{
					$starting_date = date('Y-m-d',strtotime($_POST['sdate']));
					$ending_date  = date('Y-m-d',strtotime($_POST['edate']));
					$status  = $_POST['status'];
					
					$visitor_checkindata=$obj_gate->amgt_get_all_visitor_checkinentries_filter($starting_date,$ending_date,$status);
					 
				}
				else
				{ 	
			
					$visitor_checkindata=$obj_gate->Amgt_get_all_visitor_checkinentries();
				}
				?>
			<table id="visitor_checkin_list" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><input type="checkbox" id="select_all"></th>
						<th><?php esc_html_e('Visitor Name-ID Number-Vehicle Number', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Gate Name', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Checked In Date', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Checked In On Time', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Checked Out Time', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
						<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
					</tr>
		     	</thead>	
				<tfoot>
					<tr>
						<th><input type="checkbox" id="select_all"></th>
						<th><?php esc_html_e('Visitor Name-ID Number-Vehicle Number', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Gate Name', 'apartment_mgt' ) ;?></th>	
						<th><?php esc_html_e('Checked In Date', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Checked In On Time', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Checked Out Time', 'apartment_mgt' ) ;?></th>
						<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
						<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
					if(!empty($visitor_checkindata))
					{			 
					  foreach ($visitor_checkindata as $retrieved_data)
					  {
						  
						  //var_dump($retrieved_data);
						 
						global $wpdb;	
						if($retrieved_data->status == '0')
						{
							$status=esc_html__('Processing', 'apartment_mgt' );
						}
						else
						{
							$status=esc_html__('Approved', 'apartment_mgt' );
						}	
						$visitor_name_array=array();
						
						$all_visiter_entry=json_decode($retrieved_data->visiters_value);
			            if(!empty($all_visiter_entry))
						{
							foreach($all_visiter_entry as $entry1)
							{
								$visitor_name_array[]=$entry1->visitor_name.'-'.$entry1->mobile.'-'.$entry1->vehicle_number;
							}	
						}
						else
						{
							$visitor_name_array=array($retrieved_data->visitor_name .'-'.$retrieved_data->mobile.'-'.$retrieved_data->vehicle_number);
						}
						
					  $table_name = $wpdb->prefix. 'amgt_gates';
					  $result = $wpdb->get_row("SELECT * FROM $table_name where id=".$retrieved_data->gate_id);
					?>
						<tr>
						    <td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo esc_attr($retrieved_data->id); ?>"></td>
							<td class="name"><?php echo implode(',<br>',$visitor_name_array);?></td>
							
							<td class="gate_name"><?php echo  esc_html($result->gate_name);?></td>
							<td class="vehicle"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->checkin_date));?></td>
							<td class="vehicle"><?php echo esc_html($retrieved_data->checkin_time);?></td>
							<td class="checkout"><?php echo esc_html($retrieved_data->checkout_time); ?></td>
                            <td class="vehicle"><?php echo esc_html($status);?></td>									
							<td class="action">
							      <?php 
							    if($retrieved_data->status == '0')
								{ ?>
								<a  href="?page=amgt-visiter-manage&ab=visiter_request_list&action=aproved_visiter_request&visitor_request_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-default" > <?php esc_html_e('Approve', 'apartment_mgt');?></a>
								<?php } 
								?>
								<a href="?page=amgt-visiter-manage&tab=visitor-checkin&action=edit&visitor_checkin_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
								<a href="?page=amgt-visiter-manage&ab=manage-gates&action=delete&visitor_checkin_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
								onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
								<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
								<?php if($retrieved_data->status == '1' && empty($retrieved_data->checkout_time))
								{ ?>
									<a checkin_id="<?php echo esc_attr($retrieved_data->id); ?>" checkout-type="visitor" class="btn btn-success check-out"><?php esc_html_e('Check Out','apartment_mgt') ;?></a>
								<?php 
								}
								if($retrieved_data->status == '1')
								{
									?>
									<a href="?page=amgt-visiter-manage&print=print&visitor_checkin_id=<?php echo esc_attr($retrieved_data->id);?>" target="_blank" class="btn btn-primary"> <?php esc_html_e('Print Details', 'apartment_mgt' ) ;?>
									</a> 
									<?php 
								} ?>
							</td>               
						</tr>
						<?php } 
					} ?>
				</tbody>
			</table>
			
			<!-- <input type="submit" class="btn delete_margin_bottom btn-primary" name="print_selected" value="<?php esc_html_e('Print', 'lawyer_mgt' ) ;?> " /> -->
			</form>
        </div><!---TABLE-RESPONSIVE END--->
    </div><!--END PANBEL BODY-->
     <?php 
} ?>