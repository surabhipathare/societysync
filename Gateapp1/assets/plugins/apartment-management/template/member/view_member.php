<?php
	 //ACCESS RIGHT
	$user_access=amgt_get_userrole_wise_access_right_array();

	$role = amgt_get_user_role(get_current_user_id());

$curr_user_id=get_current_user_id();
$obj_apartment=new Apartment_management($curr_user_id);
$obj_doc = new Amgt_Document;?>
<?php $edit=0;	
    $member_id=0;
	if(isset($_REQUEST['member_id']))
		$member_id=$_REQUEST['member_id'];
		$edit=1;
	$user_info = get_userdata($member_id);
	
	//VIEW DETAILS VARIABLES
	
	$mobile = get_user_meta($member_id,'mobile',true);
	$first_name = get_user_meta($member_id,'first_name',true);
	$middle_name = get_user_meta($member_id,'middle_name',true);
	$last_name = get_user_meta($member_id,'last_name',true);
	$qualification = get_user_meta($member_id,'qualification',true);
	$username = get_user_meta($member_id,'user_login',true);
	$unit_name = get_user_meta($member_id,'unit_name',true);
    $gender = get_user_meta($member_id,'gender',true);
	$member_type = get_user_meta($member_id,'member_type',true);
	$address = get_user_meta($member_id,'address',true);
	$city_name = get_user_meta($member_id,'city_name',true);
	$state_name = get_user_meta($member_id,'state_name',true);
	$country_name = get_user_meta($member_id,'country_name',true);
	$zipcode = get_user_meta($member_id,'zipcode',true);
	$email = get_user_meta($member_id,'email',true);
	$building = get_the_title(get_user_meta($member_id,'building_id',true));
	$building_id =get_user_meta($member_id,'building_id',true);
	//$staff_category = get_the_title(get_user_meta($member_id,'staff_category',true));
	$unit_category = get_the_title(get_user_meta($member_id,'unit_category',true));
	//$skills = get_the_title(get_user_meta($member_id,'skills',true));
	$skills = get_user_meta($member_id,'skills',true);
	//$gate_name = get_user_meta($member_id,'gate',true);
	//$gate_name = get_the_title(get_user_meta($member_id,'gate_name',true));
	$gate_name ="";
	$staff_category = "";
	
?>
<div class="panel-body"><!--PANEL BODY-->

<!-- TOP PROFILE VIEW-->
  <div class="member_view_row1"><!--MEMBER_VIEW_ROW-->
	<div class="col-md-12 membr_left profile_view member_border padding_none">
	  <div class="col-md-2 left_side padding_none">
			<?php 
				if($user_info->amgt_user_avatar == "")
				{ ?>
					<img class="" alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
				 <?php 
				}
				else 
				{ ?>
						<img class=" max_width_100" src="<?php if($edit)echo esc_url( $user_info->amgt_user_avatar ); ?>" />
					<?php 
				} ?>
			
	  </div>
		
	  <div class="col-md-10 left_side padding_none">
		 <div class="col-md-12 col-sm-12 left_side view_data padding_none view_margin">
		   <div class="col-md-12 col-sm-12 padding_none">
			<div class="col-md-12 col-sm-12 left_side view_data">
			  <div class="col-md-1 user">
			    <i class="fa fa-user"></i>
			  </div>
			  <div class="col-md-11">
			    <span class="newicon"><?php print esc_html($first_name); ?></span>
			  </div>
			</div>
			
			<div class="col-md-12 left_side view_data">
			   <div class="col-md-1 user">
			     <i class="fa fa-envelope"></i>
			   </div>
			   <div class="col-md-11">
			     <span class="email_color"><?php echo chunk_split($user_info->user_email,50,"<BR>");?></span>
			   </div>
			</div>
			<div class="col-md-12 left_side view_data">
			  <div class="col-md-1 user">
			    <i class="fa fa-phone"></i>
			  </div>
			  <div class="col-md-11">
			    <span class="newicon"><?php print esc_html($mobile); ?></span>
			  </div>
			</div>
		 </div>
	  </div>
	  </div>
	</div>
  </div>
  <!-- TOP PROFILE VIEW END-->
	
	
	<div class="col-md-12 padding_none">
    <!-- GENERAL INFORMATION VIEW START-->
	  <div class="member_view_row1">
	    <div class="col-md-12 main_info_view">
		  
		  	<div class="member_view_row1_template">
	           <div class="col-md-12 member_border padding_none">
			     <div class="col-md-2 profile_view_first">
			       <span class="emp_info view_title_font view_title_font"><?php esc_html_e('General Information','apartment_mgt');?></span>
				 </div>  
				 <div class="col-md-12 col-sm-12 padding_none bank_margin">
				 <div class="col-md-6  col-sm-12 padding_none"> 
				    <?php if($first_name){?>
					<div class="col-md-12 col-sm-12 bank_padding">
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-user newicon"></i><?php esc_html_e('Name','apartment_mgt');?>
					  </div>
					   <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						 <span class="span_left span_padding">:</span>
						<span class="txt_color"><?php if(!empty($first_name)) { print esc_html($first_name); }else { print '' ;}?></span>
					  </div>
					  
				    </div>
					<?php } ?>
					
				    <?php if($middle_name){?>
					<div class="col-md-12 col-sm-12 bank_padding">
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-user newicon"></i><?php esc_html_e('Middle Name','apartment_mgt');?>
						</div>
						<div class="col-md-6 col-sm-6 bank_padding employee_weight">
					   <span class="span_left span_padding">:</span>
					   <span class="txt_color"><?php if(!empty($middle_name)) { print esc_html($middle_name); }else { print '' ;}?></span>
				    </div>
				    </div>
					<?php } ?>
					
				    <?php if($last_name){?>
				    <div class="col-md-12 col-sm-12 bank_padding">
					   <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-user last_name_padding"></i> <?php esc_html_e('Last Name','apartment_mgt') ?>
					   </div>
					   <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				       <span class="span_left span_padding">:</span>
					   <span class="txt_color"><?php if(!empty($last_name)) { print esc_html($last_name); }else { print '' ;}?></span>
				    </div>
				    </div>
					<?php } ?>
					
				    
				    <div class="col-md-12  col-sm-12 bank_padding">
					 <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-birthday-cake last_name_padding"></i><?php esc_html_e('Date Of Birth','apartment_mgt');?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php echo date(amgt_date_formate(),strtotime($user_info->birth_date));?></span>
				    </div>
				    </div>
					
				    <?php if($gender){?>
				    <div class="col-md-12 bank_padding">
					 <div class="col-md-6 col-sm-6 bank_padding employee_weight">
                          <i class="fa fa-mars"></i> <?php esc_html_e('Gender','apartment_mgt');?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
					  <span class="span_left span_padding">:</span>
					  <span class="txt_color">
					  	<?php  
					  	if($gender == "male")
					  	{
					  	esc_html_e('Male','apartment_mgt');
					  	}
					  	else
					 	{
					  	esc_html_e('Female','apartment_mgt');
					 	} ?>
					  		
					  	</span>
				    </div>
				    </div>
					<?php } ?>
				   
				    <div class="col-md-12 bank_padding">
					 <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-user"></i> <?php esc_html_e('User Name','apartment_mgt');?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
					  <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php echo chunk_split($user_info->user_login,25,"<BR>");?></span>
				    </div>
				    </div>
				</div>
				
			    <div class="col-md-6 padding_none">
				     <?php if($member_type){ ?>
					<div class="col-md-12 bank_padding">
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-user"></i> <?php esc_html_e('Member Type','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
					  <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($member_type)) { print esc_html($member_type); }else { print '' ;}?></span>
				    </div>
				    </div>
					 <?php } ?>
					
				   <?php if($mobile){?>
				   <div class="col-md-12 bank_padding">
				     <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						 <i class="fa fa-phone"></i> <?php esc_html_e('Mobile','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($mobile)) { print esc_html($mobile); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				   <?php if($building){?>
				   <div class="col-md-12 bank_padding">
				      <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-university last_name_padding"></i> <?php esc_html_e('Building Name','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($building)) { print esc_html($building); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				   <?php if($unit_name){?>
				   <div class="col-md-12 bank_padding">
				     <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-list-ol"></i> <?php esc_html_e('Unit Name','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($unit_name)) { print esc_html($unit_name); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				  
				   
				     <?php if($qualification){?>
				   <div class="col-md-12 bank_padding">
				      <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-graduation-cap"></i> <?php esc_html_e('Qualification','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($qualification)) { print esc_html($qualification); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				    <?php if($staff_category){?>
				   <div class="col-md-12 bank_padding">
				     <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-calendar"></i> <?php esc_html_e('Staff Category','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($staff_category)) { print esc_html($staff_category); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				   <?php if($skills){?>
				   <div class="col-md-12 bank_padding">
				      <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-calendar"></i> <?php esc_html_e('Skills','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($skills)) { print esc_html($skills); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				   <?php if($gate_name){?>
				   <div class="col-md-12 bank_padding">
				     <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-calendar"></i> <?php esc_html_e('Gate Name','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($gate_name)) { print esc_html($gate_name); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				   
				  
				</div>
		        </div>

		      </div>
	        </div>
	      </div>
        </div>
		
	<!-- GENERAL INFORMATION VIEW END-->
	<!-- CONTACT INFORMATION VIEW-->
	
	    <div class="member_view_row1">
	      <div class="col-md-12 main_info_view">
		   <div class="member_view_row1_template">
	           <div class="col-md-12 member_border padding_none">
			     <div class="col-md-12 padding_none Permanent_border bank_padding">
				   <div class="col-md-6 padding_none">
			        <span class="emp_info view_title_font padding_none"><?php esc_html_e('Permanent Contact Information','apartment_mgt');?></span>
				   </div>
				   
				 </div>
				 
				 <div class="col-md-12 padding_none bank_margin">
				  <div class="col-md-6 padding_none">
				  
				    <?php if($address){?>
					<div class="col-md-12 bank_padding">
					 <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-map-marker newicon"></i><?php esc_html_e('Address','apartment_mgt');?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
					  <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($address)) { print esc_html($address); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				   <?php if($city_name){?>
				   <div class="col-md-12 bank_padding">
				      <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-map-marker newicon"></i><?php esc_html_e('City','apartment_mgt');?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($city_name)) { print esc_html($city_name); }else { print '' ;}?></span>
				   </div>
				   </div>
				    <?php } ?>
					
					<?php if($state_name){?>
				   <div class="col-md-12 bank_padding">
				      <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-map-marker last_name_padding"></i> <?php esc_html_e('State','apartment_mgt') ?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($state_name)) { print esc_html($state_name); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				 </div>
				
		          <div class="col-md-6 padding_none">
				   
				   <?php if($country_name){?>
				   <div class="col-md-12 bank_padding">
				      <div class="col-md-6 col-sm-6 bank_padding employee_weight">
						  <i class="fa fa-globe last_name_padding"></i><?php esc_html_e('Country','apartment_mgt');?>
					  </div>
					  <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($country_name)) { print esc_html($country_name); }else { print '' ;}?></span>
				   </div>
				   </div>
				   <?php } ?>
				   
				   <?php if($zipcode){?>
				   <div class="col-md-12 bank_padding">
				     <div class="col-md-6 col-sm-6 bank_padding employee_weight">
                          <i class="fa fa-list-ol"></i> <?php esc_html_e('Zip Code','apartment_mgt');?>
					 </div>
					 <div class="col-md-6 col-sm-6 bank_padding employee_weight">
				      <span class="span_left span_padding">:</span>
					  <span class="txt_color"><?php if(!empty($zipcode)) { print esc_html($zipcode); }else { print '' ;}?>
				   </div>
				   </div>
				   <?php } ?>
				   
				</div>
		</div>
		</div>
	  </div>
	 </div>
  </div>
  <!-- CONTACT INFORMATION VIEW END-->
  </div>
  
   </div>
    <?php if(!empty($member_type)){ ?>
<div class="panel-body">
	<div class="clear"></div>
		<div class="col-sm-6 border groups-list unit_member_list_div">
			<span class="report_title">
				<span class="fa-stack cutomcircle">
					<i class="fa fa-users fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php esc_html_e('Unit Member List','apartment_mgt');?></span>
			</span>
			<div class="my-group-list">
				<table class="table">
				<?php 
					$unit_name=get_user_meta($member_id,'unit_name',true);

					$unit_groupdata = amgt_get_unit_members($unit_name);

					 if(!empty($unit_groupdata))

						{
							$i= 1;
							foreach ($unit_groupdata as $retrieved_data)
							{
								$user=get_userdata($retrieved_data->ID);
								?>
								<tr>
									<td><?php $memberimage=$user->amgt_user_avatar;
											if(empty($memberimage))
											{?>
												<a href="?page=amgt-member&tab=viewmember&action=view&member_id=<?php echo esc_attr($user->ID);?>">
												<?php echo '<img src='.get_option( 'amgt_system_logo' ).' height="25px" width="25px" class="img-circle" />';
												   
												 ?>
												 
												</a>											
												<span class="txt_color_member padding_7"><?php echo esc_html($user->display_name); ?></span> 
											<?php
											}
											else
											 { ?>
												<a href="?page=amgt-member&tab=viewmember&action=view&member_id=<?php echo esc_attr($user->ID);?>">
												<?php echo '<img src='.$memberimage.' height="25px" width="25px" class="img-circle"/>';
												  
												 ?>
												</a>
                                                <span class="txt_color_member padding_7"><?php echo esc_html($user->display_name); ?></span> 												
											<?php } ?>
											
									</td>
									<td>
										  <span class="txt_color padding_7"><?php echo amgt_get_member_status_label($user->member_type);?></span>
									</td>
								</tr>
								<?php 
								$i++;
							}
						} else
						{?>
							<tr><td> <p><?php _e("No any Unit Members yet.","apartment_mgt");?></p></td></tr>
						<?php 
						}
				    ?>
				</table>
			</div>
		</div>
		<?php if(get_current_user_id() == $_REQUEST['member_id'] || $obj_apartment->role=='staff_member')
		{
			?>
		<div class="col-sm-6 border groups-list unit_member_list_div view_user_document_list_div   margin_top_10_res">
			<span class="report_title"><!-- REPORT TITLE -->
				<span class="fa-stack cutomcircle">
					<i class="fa fa-file-text fa-stack-1x"></i>
				</span> 
				<span class="shiptitle"><?php esc_html_e('Document List','apartment_mgt');?></span>
			</span><!-- END REPORT TITLE -->
			<div class="my-group-list padding_0_and_21">
			<?php $alldocuments = $obj_doc->amgt_get_units_all_documents_new($unit_name,$building_id);
			
				if(!empty($alldocuments))
				{
					$i= 1; ?>
					<table class="table">
						<?php 
						foreach ($alldocuments as $retrieved_data)
						{ ?>
							<tr>
								<td>
								<span class="document_title"><i class="fa fa-file-text" aria-hidden="true"></i> 
								<?php echo esc_html($retrieved_data->doc_title);?>
								</span>
								</td>
								<td>
								<?php if($retrieved_data->document_content != ''){?>
								<a target="blank" href="<?php echo $retrieved_data->document_content; ?>"><button class="btn btn-default margin_top_5" type="button">
								<i class="fa fa-eye"></i> <?php esc_html_e('View Document','apartment_mgt');?></button></a>
								<?php }?>
								</td>
							</tr>
							<?php 
						} ?>
					<?php 
					}
					else
					{ ?>
						<tr>
							<td><?php _e("No any Documents yet.","apartment_mgt");?></td>
						</tr>
					<?php  
					} ?>
		    	</table>
			</div>
			
			<span class="report_title"> <!-- REPORT TITLE-->
				<span class="fa-stack cutomcircle" style="margin-bottom: 15px;">
					<i class="fa fa-file-text fa-stack-1x" ></i>
				</span> 
				<span class="shiptitle padding_bottom_10"><?php esc_html_e('Proof Document List','apartment_mgt');?></span>
			</span>
			<div class="my-group-list">							 
				<table class="table">	
				<?php
					$id_proof_1 = get_user_meta( $member_id, 'id_proof_1' , true );			
					$id_proof_2 = get_user_meta( $member_id, 'id_proof_2' , true );			
								
					   if($id_proof_1 != '')
					   {
						?>
						<tr>
							<td>
							<span class="document_title"><i class="fa fa-file-text" aria-hidden="true"></i> 
							<!-- <?php echo 'Id Proof 1';?> -->
							
							<?php esc_html_e('Member ID Proof-1','apartment_mgt');?>
							</span>
							</td>
							<td>
							
							<a target="blank" href="<?php echo content_url().'/uploads/apartment_assets/'.$id_proof_1; ?>"><button class="btn btn-default margin_top_5" type="button">
							<i class="fa fa-eye"></i> <?php esc_html_e('View Document','apartment_mgt');?></button></a>
							
							</td>
						</tr>
						<?php 
						}
						if($id_proof_2 != '')
						{
						?>
						<tr>
							<td>
							<span class="document_title"><i class="fa fa-file-text" aria-hidden="true"></i> 
							<?php esc_html_e('Lease Agreement','apartment_mgt');?>
							</span>
							</td>
							<td>
							
							<a target="blank" href="<?php echo content_url().'/uploads/apartment_assets/'.$id_proof_2; ?>"><button class="btn btn-default margin_top_5" type="button">
							<i class="fa fa-eye"></i> <?php esc_html_e('View Document','apartment_mgt');?></button></a>
							
							</td>
						</tr>
						<?php }

					$doc_data=get_user_meta( $member_id, 'document' , true );
						$data_new=json_decode($doc_data);
						if(!empty($data_new))
						{
							foreach($data_new as $data)
							{
						?>

							<tr>

								<td>

								<span class="document_title"><i class="fa fa-file-text" aria-hidden="true"></i> 

								<?php echo $data->title;?>

								</span>

								</td>

								<td>
									<a target="blank" href="<?php echo content_url().'/uploads/apartment_assets/'.$data->value; ?>"><button  class="btn btn-default margin_top_5" type="button">

									<i class="fa fa-eye"></i> <?php esc_html_e('View Document','apartment_mgt');?></button></a>
								</td>

							</tr>

						<?php 
							}

						}

					if($id_proof_1 == '' && $id_proof_2 == '' && empty($doc_data))

					{ 

					?>

						<tr>

							<td><?php _e("No any Proof Documents yet.","apartment_mgt");?></td>

						</tr>

					<?php  

					} 

					?>
			    </table>
			</div>
			
		</div>
		<?php
		}
		?>
</div>
	<?php } ?><!-- END PANEL BODY DIV -->