<!-- COMPLAIN VIEW POPUP CODE -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

<div class="complaint-popup-bg">
     <div class="overlay-content">
       <div class="complaint_content"></div>    
     </div> 
</div>	
 <!--END POP-UP CODE -->
 <!-- CLASS BOOK IN CALANDER POPUP HTML CODE -->
<div id="eventContent" class="modal-body" style="display:none;"><!--MODAL BODY DIV START-->
	<style>
	   .ui-dialog .ui-dialog-titlebar-close
	   {
		  margin: -15px -4px 0px 0px !important;
	   }
	</style>
			<p><b><?php esc_html_e('Event Title:','apartment_mgt');?></b> <span id="event_title"></span></p><br>
			<p><b><?php esc_html_e('Start Date:','apartment_mgt');?> </b> <span id="startdate"></span></p><br>
			<p><b><?php esc_html_e('End Date:','apartment_mgt');?></b> <span id="enddate"></span></p><br>
			<p><b><?php esc_html_e('Start Time:','apartment_mgt');?></b> <span id="starttime"></span></p><br>
			<p><b><?php esc_html_e('End Time:','apartment_mgt');?></b> <span id="endtime"></span></p><br>
			<p><b><?php esc_html_e('Description:','apartment_mgt');?></b> <span id="description"></span></p><br>
			<p><b><?php esc_html_e('Documents:','apartment_mgt');?></b> <span id="document"></span></p><br>
			 
</div><!--MODAL BODY DIV END-->
<!-- END CLASS BOOK IN CALANDER POPUP HTML CODE -->
<?php 
$obj_units=new Amgt_ResidentialUnit;
$obj_member=new Amgt_Member;
$obj_notice=new Amgt_NoticeEvents;
$eventdata=$obj_notice->amgt_get_all_events();
$noticedata=$obj_notice->amgt_get_notice_list_ondashboard();
$obj_service =new Amgt_Service;
$obj_complaint=new Amgt_Complaint;
$obj_gate=new Amgt_gatekeeper;
$gatedata=$obj_gate->Amgt_get_all_gates();
$obj_account =new Amgt_Accounts;

$cal_array=array();
if(!empty($eventdata))
{
	foreach ( $eventdata as $retrieved_data ) 
	{		
		$start=date('Y-m-d',strtotime($retrieved_data->start_date ))." ".date("H:i", strtotime($retrieved_data->start_time));
		$end=date('Y-m-d',strtotime($retrieved_data->end_date ))." ".date("H:i", strtotime($retrieved_data->end_time));
		if(!empty($retrieved_data->event_doc))
		{
			$document='<a target="blank" href="'.content_url().'/uploads/apartment_assets/'.$retrieved_data->event_doc.'" class="btn btn-default"><i class="fa fa-eye"></i> View Document</a>';
		}
		else
		{
			$document='No Document';
		}
		$cal_array [] = array (
				'type' =>  'eventdata',
				'title' => $retrieved_data->event_title,
				'description' => $retrieved_data->description,
				'document' =>$document,
				'start' =>$start,
				'end' =>$end,
				'starttime' =>$retrieved_data->start_time,
				'endtime' =>$retrieved_data->end_time,
				'backgroundColor' => '#008000'
		);
	}
}
if(!empty($noticedata))
{
	foreach ( $noticedata as $retrieved_data ) 
	{		
		$cal_array [] = array (
				'title' => $retrieved_data->notice_title,
				'start' =>$retrieved_data->created_date,
				'end' =>$retrieved_data->valid_date,
				'backgroundColor' => '#22BAA0'
		);
	}
}	
?>
<script>
	$(document).ready(function()
	//FULLCALENDAR
	{
		$('#calendar').fullCalendar(
		{
			 header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				editable: false,
				timeFormat: 'hh:mm a',
			eventLimit: true,
			events: <?php echo json_encode($cal_array);  ?>,
			//Event pop up//
	     eventClick:  function(event, jsEvent, view) {
			 
	 	<?php $dformate=get_option('amgt_date_formate'); ?>
	 
				var dateformate_value='<?php echo esc_attr($dformate);?>';
				if(dateformate_value == 'Y-m-d')
				{	
					var dateformate='YYYY-MM-DD';
				}
				if(dateformate_value == 'd/m/Y')
				{	
					var dateformate='DD-MM-YYYY';
				}
				if(dateformate_value == 'm/d/Y')
				{	
					var dateformate='MM-DD-YYYY';
				}	
				if(dateformate_value == 'F j, Y')
				{	
					var dateformate='MM-DD-YYYY';
				}
				
				$("#eventContent #event_title").html(event.title);
				$("#eventContent #startdate").html(moment(event.start).format(dateformate));
				$("#eventContent #enddate").html(moment(event.end).format(dateformate)); 
				$("#eventContent #starttime ").html(event.starttime);
				$("#eventContent #endtime ").html(event.endtime);
				$("#eventContent #description ").html(event.description);
				$("#eventContent #document ").html(event.document);

				var type = event.type;
				if(type == 'eventdata')
				{
					$("#eventLink").attr('href', event.url);
					$("#eventContent").dialog({ modal: true, title: 'Event Details',width:350, height:450 });
					$(".ui-dialog-titlebar-close").text('x');
					$(".ui-dialog-titlebar-close").css('height',30);
				}
		    }  
		 
		});
	
	});
</script>
<div class="page-inner min_height_1088"> <!--- INNER PAGE DIV START  ---->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
	<div id="main-wrapper"> <!--MAIN WRAPPER-->
		<div class="row"><!-- Start Row2 -->
			<div class="row left_section col-md-8 col-sm-8 col-xs-12 row_left_section">
			<div class="col-lg-3 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=amgt-member';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body doctor">
						
						<div class="info-box-stats committee_span member_margin">
								<span class="dash_p_span"><p class="counter member_span"><?php echo count(get_users(array('role'=>'member')));?></p></span>
								
								<span class="info-box-title dash_member_span member_span_member member_color"><?php echo esc_html( esc_html__('Members', 'apartment_mgt' ) );?></span>
							</div>
						<img src="<?php echo AMS_PLUGIN_URL."/assets/images/dashboard/member.png"?>" class="dashboard_background">
                        
					</div>
				</div>
				</a>
			</div>
				
				<div class="col-lg-3 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=amgt-committee-member';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body nurse">
						<div class="info-box-stats">
							<span class="dash_p_span"><p class="counter commit_member_color"><?php echo count(get_users(array('role' => 'member','meta_key' => 'committee_member','meta_value'=> 'yes')));?></p></span>
								<span class="info-box-title dash_member_span padding_top_0 commit_member_color">
								<?php 
								esc_html_e('Committee',"apartment_mgt");
								?>
								<br>
								<?php 
								esc_html_e('Members',"apartment_mgt");
								?>
								</span>
							</div>
						<img src="<?php echo AMS_PLUGIN_URL.'/assets/images/dashboard/Committee-member.png';?>" class="dashboard_background">
                        
					</div>
				</div>
				</a>
			</div><!-- END COMMITTEE MEMBER BOX DIV -->
			
				<div class="col-lg-3 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=amgt-residential_unit';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body receptionist">
						<div class="info-box-stats">
						    
								<span class="dash_p_span"><p class="counter Compounds_color"><?php echo amgt_count_units();?></p></span>
								
								<span class="info-box-title dash_member_span build Compounds_color"><?php echo esc_html( esc_html__('Compounds', 'apartment_mgt' ) );?></span>
							</div>
						<img src="<?php echo AMS_PLUGIN_URL."/assets/images/dashboard/building.png"?>" class="dashboard_background">
                        
					</div>
				</div>
				</a>
			</div>
			
			<!-- END RESIDENTIAL UNIT BOX DIV -->
			<div class="col-lg-3 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo admin_url().'admin.php?page=amgt-message';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body setting">
						<div class="info-box-stats">
						    
								<span class="dash_p_span"><p class="counter Message_color"><?php echo count(amgt_count_inbox_item(get_current_user_id()));?></p></span>
								
								<span class="info-box-title dash_member_span Message_color"><?php echo esc_html( esc_html__('Message', 'apartment_mgt' ) );?></span>
							</div>
						<img src="<?php echo AMS_PLUGIN_URL."/assets/images/dashboard/message.png"?>" class="dashboard_background">
                        
					</div>
				</div>
				</a>
			</div><!--END MESSGAE  BOX DIV -->
			

			</div>
		</div>
		
		
		<div class="row dashboard_top_border">
			<div class="col-md-6 no-paddingR">
				
				
				<div class="panel panel-white event operation dasboard_notice">
					<div class="panel-heading ">
					<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/Notice-And-Event.png"?>">
					<h3 class="panel-title notice_event_flot"><?php esc_html_e('Notice','apartment_mgt');?><span class="float_right" ><a href="<?php echo admin_url().'admin.php?page=amgt-notice-event';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
							<?php		
							if(!empty($noticedata)){
							
							foreach ($noticedata as $retrieved_data){
							?>			
									<div class="calendar-event view-notice" id="<?php echo esc_attr($retrieved_data->id);?>"> 
									
									<p class="remainder_title_pr Bold viewpriscription show_task_event" id="" model="Prescription Details" >  <?php esc_html_e('Notice Title','apartment_mgt');?> : 
									<?php echo esc_html($retrieved_data->notice_title); ?>
										</p>
									<p class="remainder_date_pr"><?php  echo date(amgt_date_formate(),strtotime($retrieved_data->valid_date)); ?></p>
									
									<p class="remainder_title_pr  viewpriscription" id="22" data-toggle="modal" data-target="#myModal1">
									<?php esc_html_e('Description','apartment_mgt');?> : 
									<?php echo esc_html($retrieved_data->description); ?>
									</p>
									
									</div>	
							<?php }
							} 
							else 
							{ ?>
							<div class="calendar-event"> 
									
									<p class="remainder_title_pr Bold viewpriscription show_task_event" id="" model="Prescription Details" >  <?php esc_html_e('No Notice Found','apartment_mgt');?>
										</p>
									
									
									</div>	
							<?php } ?>												
							</div>                        
					</div>
				</div>
				
				 <div class="panel panel-white Appoinment dasboard_complain">
					 <div class="panel-heading">
							<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/Complaint.png"?>">
							<h3 class="panel-title notice_event_flot"><?php esc_html_e('Complain','apartment_mgt');?><span class="float_right" ><a href="<?php echo admin_url().'admin.php?page=amgt-complaint';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
							<?php		
							$complaintsdata=$obj_complaint->amgt_get_all_dashboard_complaints();
						    if(!empty($complaintsdata))
						    {
							foreach ($complaintsdata as $retrieved_data){
							$user=get_userdata($retrieved_data->created_by);
							?>				
									<div class="calendar-event view-complaint" id="<?php echo esc_attr($retrieved_data->id);?>"> 
									
									<p class="remainder_title_pr Bold viewpriscription show_task_event" model="Prescription Details" >  <?php esc_html_e('Complain Title','apartment_mgt');?> : 
									<?php echo esc_html($retrieved_data->complain_title); ?>
									
									<p class="remainder_date_pr"><?php if($retrieved_data->complain_date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(amgt_date_formate(),strtotime($retrieved_data->complain_date)); }else{ echo "-"; } ;?></p>
									
									<p class="remainder_title_pr  viewpriscription" id="22" data-toggle="modal" data-target="#myModal1">
									<?php esc_html_e('Description','apartment_mgt');?> : 
									<?php echo esc_html($retrieved_data->complaint_description); ?>
									</p>
									
									
									</div>	
							<?php } }
							else 
							{ ?>
							<div class="calendar-event"> 
									
									<p class="remainder_title_pr Bold viewpriscription show_task_event" id="" model="Prescription Details" >  <?php esc_html_e('No Complains Found','apartment_mgt');?>
										</p>
									
									
									</div>	
							<?php } ?>							
							</div>    				
					</div>
			   </div>
				<div class="panel panel-white">
				   <div class="panel-heading margin_bottom_15">
						<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/calender.png"?>">
						<h3 class="panel-title notice_event_flot"><?php esc_html_e('Calendar','apartment_mgt');?></h3>			
					</div>
					<div class="panel-body dasboard_calander">
						<div id="calendar" ></div>
					</div>
				</div>
		 </div>
				 
		<div class="col-md-6">
			   <div class="panel panel-white event priscription dashboard_bulding_list_scroll">
					<div class="panel-heading ">
                    					
					<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/Assets--Inventory-Tracker.png"?>">
						<h3 class="panel-title notice_event_flot"><?php esc_html_e('Compounds Units','apartment_mgt');?><span class="float_right" ><a href="<?php echo admin_url().'admin.php?page=amgt-residential_unit';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>				
					</div>
					
					<div class="panel-body">
						<table class="table table-borderless">
									  <thead>
										<tr>
										  <th scope="col compound_unit_dash"><?php esc_html_e('Unit Name','apartment_mgt');?></th>
										  <th scope="col compound_unit_dash"><?php esc_html_e('Unit Category','apartment_mgt');?></th>
										  <th scope="col compound_unit_dash"><?php esc_html_e('Compound Name','apartment_mgt');?></th>
										</tr>
									  </thead>
									  <tbody>
								   <?php 
											$get_members = array('role' => 'member');
											$membersdata=get_users($get_members);
											
											$residentialdata=$obj_units->amgt_get_all_residentials_dashboard();
											if(!empty($residentialdata))
											{
												foreach ($residentialdata as $retrieved_data)
												{
														$units_data=array();
														$units_data=json_decode($retrieved_data->units);
														$i = 0;
														foreach($units_data as $unit)
														{
															$user_query = new WP_User_Query(
																array(
																	'meta_key'	  =>	'unit_name',
																	'meta_value'	=>	$unit->entry,
																)
															);
															$allmembers = $user_query->get_results();
															
										?>
									    <tr>
										  <td class="border_bottom_1_dash"><?php echo esc_html($unit->entry);?></td>
										  <td class="unit border_bottom_1_dash"><?php $unit_cat=get_post($retrieved_data->unit_cat_id); echo esc_html($unit_cat->post_title);?></td>
										  <td class="building_id border_bottom_1_dash"><span class="btn btn-success btn-xs"><?php $building = get_post($retrieved_data->building_id); echo esc_html($building->post_title);?></span></td>
										</tr>
											<?php } } }
											else 
											{ ?>
												<div class="calendar-event"> 	
													<p class="remainder_title_pr Bold" id="" model="Prescription Details" >  <?php esc_html_e('No Compound Units Found','apartment_mgt');?>
													</p>					
												</div>	
									        <?php } ?>
											
									  </tbody>
								</table>               
					</div>
				</div>
			
				<div class="panel panel-white Appoinment dasboard_services">
					<div class="panel-heading">
					<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/services.png"?>">
					<h3 class="panel-title notice_event_flot"><?php esc_html_e('Service','apartment_mgt');?><span class="float_right" ><a href="<?php echo admin_url().'admin.php?page=amgt-service-mgt';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
									<?php		
							           $service_data= $obj_service->amgt_get_all_dashboard_service();
										if(!empty($service_data))
										{
											foreach ($service_data as $retrieved_data)
									{ ?>			
									<div class="calendar-event view-service" id="<?php echo esc_attr($retrieved_data->service_id);?>"> 
									<p class="remainder_title_pr Bold"  model="Prescription Details" >  <?php esc_html_e('Service Name','apartment_mgt');?> : 
									<?php echo esc_html($retrieved_data->service_name); ?>
									
									<p class="remainder_date_pr"><?php if($retrieved_data->created_date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(amgt_date_formate(),strtotime($retrieved_data->created_date)); }else{ echo "-"; } ;?></p>
									
									<p class="remainder_title_pr  viewpriscription" id="22" data-toggle="modal" data-target="#myModal1">
									<?php esc_html_e('Service Provider','apartment_mgt');?> : 
									<?php echo esc_html($retrieved_data->service_provider); ?>
									</p>
									
									</div>	
									<?php } } 
									else 
											{ ?>
												<div class="calendar-event"> 	
													<p class="remainder_title_pr Bold" model="Prescription Details" >  <?php esc_html_e('No Services Found','apartment_mgt');?>
													</p>					
												</div>	
									     <?php } ?>
													
							</div>    				
					</div>
				</div>
				
			   <div class="panel panel-white event assignbed dashboard_gatekeeper_list_scroll">
					<div class="panel-heading">
					<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/Gatekeeper.png"?>">
					<h3 class="panel-title notice_event_flot"><?php esc_html_e('Security','apartment_mgt');?></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
						<?php $get_members = array('role' => 'gatekeeper');
											$membersdata=get_users($get_members);
											 if(!empty($membersdata))
											 {
												foreach ($membersdata as $retrieved_data)
												{		
													global $wpdb;
													$table_amgt_gates = $wpdb->prefix. 'amgt_gates';
													$gatedata = $wpdb->get_row("SELECT gate_name FROM $table_amgt_gates where id=".$retrieved_data->aasigned_gate );	
													
												?>
												
											   <div class="calendar-event"> 
												<p class="remainder_title_pr Bold viewpriscription show_task_event" id="" model="Prescription Details" >  <?php esc_html_e('Security Name','apartment_mgt');?> : 
												<?php echo esc_html($retrieved_data->display_name); ?>
												
												<p class="remainder_date_pr"><?php echo esc_html($gatedata->gate_name); ?>
												
												</div>	
											 <?php 
											 } 
										}else 
											{ ?>
												<div class="calendar-event"> 	
													<p class="remainder_title_pr Bold viewpriscription" model="Prescription Details" >  <?php esc_html_e('No Security Found','apartment_mgt');?>
													</p>					
												</div>	
									     <?php } ?>									
								
						</div>                       
					</div>
			   </div>
				   
			   <div class="panel panel-white event assignbed dasboard_invoice">
					<div class="panel-heading">
					<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/document.png"?>">
					<h3 class="panel-title notice_event_flot"><?php esc_html_e('Invoice','apartment_mgt');?><span class="float_right" ><a href="<?php echo admin_url().'admin.php?page=amgt-accounts';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>						
					</div>
					<div class="panel-body">
						<div class="events overflow_auto_res">
							      <table class="table table-borderless">
									  <thead>
										<tr>
										  <th scope="col compound_unit_dash"><?php esc_html_e('Invoice No','apartment_mgt');?></th>
										  <th scope="col compound_unit_dash"><?php esc_html_e('Member Name','apartment_mgt');?></th>
										  <th scope="col compound_unit_dash"><?php esc_html_e('Total Amount','apartment_mgt');?></th>
										  <th scope="col compound_unit_dash"><?php esc_html_e('Payment Status','apartment_mgt');?></th>
										  
										</tr>
									  </thead>
									  <tbody>
									  <?php 
											 $invoice_data= $obj_account->amgt_get_all_invoice_dashboard();
											 $obj_amgttax=new Amgt_Tax();
											 
											if(!empty($invoice_data))
											{
												foreach ($invoice_data as $retrieved_data)
												{ 
													$member_id=$retrieved_data->member_id;
													$chargedata=amgt_get_invoice_charges_calculate_by($retrieved_data->charges_id);
													if(empty($retrieved_data->invoice_no))
													{
														$invoice_no='-';
														$charge_cal_by='Fix Charges';
														$charge_type=get_the_title($retrieved_data->charges_type_id);
													}
													else
													{
														$invoice_no=$retrieved_data->invoice_no;
														if($chargedata->charges_calculate_by=='fix_charge')
														{
															$charge_cal_by='Fix Charges';
														}
														else
														{
															$charge_cal_by='Measurement Charge';
														}
														if($retrieved_data->charges_type_id=='0')
														{
															$charge_type='Maintenance Charges';
														}
														else
														{
															$charge_type=get_the_title($retrieved_data->charges_type_id);
														}	
													}	
													$userdata=get_userdata($member_id);
													
													?>
													<tr>
													  <td class="border_bottom_1_dash"><?php echo esc_html(get_option('invoice_prefix').''.$invoice_no);?></td>
													  <td class="border_bottom_1_dash"><?php echo esc_html($userdata->display_name);?></td>
													  <?php
														if(empty($retrieved_data->invoice_no))
														{
															$invoice_no='-';
															$charge_cal_by='Fix Charges';
															$entry=json_decode($retrieved_data->charges_payment);
															$entry_amount='0';
															foreach($entry as $entry_data)
															{
																$entry_amount+=$entry_data->amount;
															}
															$discount_amount=$retrieved_data->discount_amount;
															$after_discount_amount=$entry_amount-$discount_amount;
															$total_amount=$after_discount_amount;
															$due_amount='0';
															$paid_amount=$after_discount_amount;
															$payment_status=$retrieved_data->payment_status;
														}
														else
														{													  
															$invoice_length=strlen($retrieved_data->invoice_no);
															if($invoice_length == '9')
															{
																$total_amount=$retrieved_data->invoice_amount;
																$due_amount=$retrieved_data->invoice_amount - $retrieved_data->paid_amount;
																if($retrieved_data->payment_status=='Unpaid')
																{
																	$payment_status= esc_html__('Unpaid','apartment_mgt');
																}
																elseif($retrieved_data->payment_status=='Paid' || $retrieved_data->	payment_status=='Fully Paid')
																{																
																	$payment_status= esc_html__('Fully Paid','apartment_mgt');
																}
																elseif($retrieved_data->payment_status=='Partially Paid')
																{
																	$payment_status= esc_html__('Partially Paid','apartment_mgt');
																}			
															}													    
															else
															{
																$total_amount=$retrieved_data->total_amount;
																$due_amount=$retrieved_data->due_amount;
																if($retrieved_data->payment_status=='Unpaid')
																{
																	$payment_status= esc_html__('Unpaid','apartment_mgt');
																}
																elseif($retrieved_data->payment_status=='Paid' || $retrieved_data->	payment_status=='Fully Paid')
																{																
																	$payment_status= esc_html__('Fully Paid','apartment_mgt');
																}
																elseif($retrieved_data->payment_status=='Partially Paid')
																{
																	$payment_status= esc_html__('Partially Paid','apartment_mgt');
																}
																//$payment_status=$retrieved_data->payment_status;
															}
															$paid_amount=$retrieved_data->paid_amount;
														}
												        ?>
													  <td class="building_id border_bottom_1_dash"><?php   echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($total_amount);?></td>
													  <td class="building_id border_bottom_1_dash"><span class="btn btn-success btn-xs"><?php _e("$payment_status","apartment_mgt");?></span></td>
													  
													</tr>
											<?php } }
											else 
											{ ?>
												<div class="calendar-event"> 	
														
                                                      <tr>
													  <td  colspan="4" class="border_bottom_1_dash text_align_center"><?php esc_html_e('No Invoice Found','apartment_mgt');?></td>
													  
													</tr>													
												</div>	
									     <?php } ?>		
										
									  </tbody>
								</table>
															
						</div>                      
					</div>
			   </div>
		</div>
      </div>
	</div>
</div>
<?php ?>