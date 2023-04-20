<?php
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'complaintlist';
	$obj_complaint=new Amgt_Complaint;
?>
<!-- POP UP CODE -->
<div class="popup-bg">
    <div class="overlay-content">
      <div class="modal-content">
        <div class="category_list"></div>
	  </div>
    </div> 
</div>
<!-- End POP-UP Code -->
<!-- View Popup Code -->	
<div class="complaint-popup-bg">
     <div class="overlay-content">
       <div class="complaint_content"></div>    
     </div> 
</div>	
<!-- End POP-UP Code -->
<div class="page-inner min_height_1088">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
	if(isset($_POST['save_complaint']))//SAVE_COMPLAINT	
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_complaint_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_complaint->amgt_add_complaint($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-complaint&tab=complaintlist&message=2');
				}
			}
			else
			{
				$result=$obj_complaint->amgt_add_complaint($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-complaint&tab=complaintlist&message=1');
				}
			}
	    }
	}
	  
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE_COMPLAINT
		{
			$result=$obj_complaint->amgt_delete_comlaint($_REQUEST['complaint_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-complaint&tab=complaintlist&message=3');
			}
		}
		if(isset($_REQUEST['message']))//MESSAGES
	     {
			$message =$_REQUEST['message'];
			if($message == 1)
			{?>
					<div id="message" class="updated below-h2 notice is-dismissible">
					<p>
					<?php 
						esc_html_e('Complain inserted successfully','apartment_mgt');
					?></p></div>
					<?php
				
			}
			elseif($message == 2)
			{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
						_e("Complain updated successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
			elseif($message == 3) 
			{?>
			<div id="message" class="updated below-h2 notice is-dismissible"><p>
			<?php 
				esc_html_e('Complain deleted successfully','apartment_mgt');
			?></div></p><?php
					
			}
	    }?>
	<div id="main-wrapper"><!--MAIN WRAPPER-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL-WHITE-->
					<div class="panel-body"><!--PANEL BODY-->
					        <!--NAV-TAB-WRAPPER-->
							<h2 class="nav-tab-wrapper">
								<a href="?page=amgt-complaint&tab=complaintlist" class="nav-tab <?php echo $active_tab == 'complaintlist' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Complain List', 'apartment_mgt'); ?></a>
								
								<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
								{ ?>
								<a href="?page=amgt-complaint&tab=addcomplaint&action=edit&complaint_id=<?php echo $_REQUEST['complaint_id'];?>" class="nav-tab <?php echo $active_tab == 'addcomplaint' ? 'nav-tab-active' : ''; ?>">
								<?php esc_html_e('Edit Complain', 'apartment_mgt'); ?></a>  
								<?php 
								}
								else 
								{ ?>
									<a href="?page=amgt-complaint&tab=addcomplaint" class="nav-tab <?php echo $active_tab == 'addcomplaint' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Complain', 'apartment_mgt'); ?></a>
								<?php  }?>
							</h2><!--END NAV-TAB-WRAPPER-->
							 <?php 
							//COMPLAINLIST TAB
							if($active_tab == 'complaintlist')
							{ ?>
							<script type="text/javascript">
						    $(document).ready(function() {
								"use strict";
							jQuery('#complaint_list').DataTable({
								"responsive": true,
								"order": [[ 0, "asc" ]],
								"aoColumns":[
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": false}],
											  language:<?php echo amgt_datatable_multi_language();?>
								});
						} );
						</script>
							<form name="activity_form" action="" method="post">
								<div class="panel-body"><!--PANEL BODY-->
									<div class="table-responsive"><!---TABLE-RESPONSIVE--->
									   <!------COMPLAINT_LIST TABLE-------->
									   <table id="complaint_list" class="display" cellspacing="0" width="100%">
										  <thead>
												<tr>
													<th><?php esc_html_e('Nature', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Title', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Created By', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Date', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Time', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Resolution', 'apartment_mgt' ) ;?></th>
													<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
												</tr>
										   </thead>
											<tfoot>
												<tr>
													<th><?php esc_html_e('Nature', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Title', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Created By', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Date', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Time', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Resolution', 'apartment_mgt' ) ;?></th>
													<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
												</tr>
											</tfoot>
											 <tbody>
											  <?php 
											  $complaintsdata=$obj_complaint->amgt_get_all_complaints();
											  if(!empty($complaintsdata))
											   {
												 foreach ($complaintsdata as $retrieved_data){
												
													 ?>
													<tr>
														<td class="nature"><a href="?page=amgt-complaint&tab=addcomplaint&action=edit&complaint_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo ucfirst($retrieved_data->complaint_nature);?></a></td>
									                   	<td class="title"><?php echo wp_trim_words( $retrieved_data->complain_title,5);?></td>				
														
														<td class="created_by"><?php $user=get_userdata($retrieved_data->created_by); echo ucfirst($user->display_name);?></td>
														<?php if($retrieved_data->complaint_nature == 'maintenance_request')
														{ 
													?>
															<td class="end_date"><?php if($retrieved_data->date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(amgt_date_formate(),strtotime($retrieved_data->date)); }else{ echo "-"; } ;?></td>
															<?php 	
													    } 
													else
													{
													?>
														<td class="end_date"><?php if($retrieved_data->complain_date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(amgt_date_formate(),strtotime($retrieved_data->complain_date)); }else{ echo "-"; } ;?></td>
													<?php
													}
													?>
													<td class="status"><?php echo ucfirst($retrieved_data->time);?></td>
														<?php
														if($retrieved_data->complaint_status == 'open')
														{
															$status=esc_html__('Open', 'apartment_mgt' );
														}
														else if($retrieved_data->complaint_status == 'close')
														{
															$status=esc_html__('Closed', 'apartment_mgt' );
														}
														else if($retrieved_data->complaint_status == 'on_hold')
														{
															$status=esc_html__('On Hold', 'apartment_mgt' );
														}
														elseif($retrieved_data->complaint_status == 'scheduled')
														{
															$status=esc_html__('Scheduled', 'apartment_mgt' );
														} 
														else
														{
															$status="-";
														}
														?>
														<td class="status"><?php echo $status?></td>
														<td class="description"><?php echo wp_trim_words( $retrieved_data->complaint_description,5);?></td>
														<?php if(empty($retrieved_data->resolution))
														{ ?>
															<td class="resoltion"><?php echo "-";?></td>
														<?php } else{ ?>
														<td class="resoltion"><?php echo wp_trim_words( $retrieved_data->resolution,5);?></td> <?php } ?>
														<td class="action">
															<a href="#" class="btn btn-primary view-complaint" id="<?php echo esc_attr($retrieved_data->id);?>"> <?php esc_html_e('View','apartment_mgt');?></a>
															<a href=	"?page=amgt-complaint&tab=addcomplaint&action=edit&complaint_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
															<a href="?page=amgt-complaint&tab=Activitylist&action=delete&complaint_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
														   onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
														   <?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
														</td>
												   
													</tr>
												 <?php } 
												
												}?>
										 
											</tbody>
										 </table>
									</div><!---END TABLE-RESPONSIVE--->
								</div><!--END PANEL BODY-->
							</form>
                       <?php }
						if($active_tab == 'addcomplaint')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/complaint/add_complaint.php';
						 }
						 ?>
                    </div><!--END PANEL BODY-->
	            </div><!--END PANEL-WHITE-->
	        </div>
        </div>
    </div><!--END MAIN WRAPPER-->
</div>