<?php 
$obj_member=new Amgt_Member;
//-------- CHECK BROWSER JAVA SCRIPT ----------//
MJamgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=amgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJamgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
//---------------------------- SAVE GATEKEEPER ----------------------------//
	if(isset($_POST['save_gatekeeper']))		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_gatekeeper_nonce' ) )
		{
			if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
			{
				
				if($_FILES['upload_user_avatar_image']['size'] > 0)
				$member_image=amgt_amgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
				$member_image_url=content_url().'/uploads/apartment_assets/'.$member_image;
			}
			else
			{
				if(isset($_REQUEST['hidden_upload_user_avatar_image']))
				$member_image=$_REQUEST['hidden_upload_user_avatar_image'];
				$member_image_url=$member_image;
			}
			
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$ext=amgt_check_valid_extension($member_image_url);
				if(!$ext == 0)
				{
					$result=$obj_member->amgt_add_member($_POST);
					$returnans=update_user_meta($result,'amgt_user_avatar',$member_image_url);
				
					if($result)
					{
						wp_redirect ( home_url().'?apartment-dashboard=user&page=gatekeeper&tab=gatekeeper_list&message=2');
					}
				}
				else
					{ ?>
						<div id="message" class="updated below-h2">
							<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
						</div>
			   <?php }
			}
			else
			{
				if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
					$ext=amgt_check_valid_extension($member_image_url);
					if(!$ext == 0)
					{
					 $result=$obj_member->amgt_add_member($_POST);
					 $returnans=update_user_meta($result,'amgt_user_avatar',$member_image_url);
						if($result)
						{
							wp_redirect ( home_url().'?apartment-dashboard=user&page=gatekeeper&tab=gatekeeper_list&message=1');
						}
					}
					else
						{ ?>
							<div id="message" class="updated below-h2">
								<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
							</div>
				   <?php }
				}
				else
				{ ?>
							<div id="message" class="updated below-h2">
							<p><p><?php esc_html_e('Username Or Emailid Already Exist.','apartment_mgt');?></p></p>
							</div>
							
		  <?php }		
			}
		}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=$obj_member->amgt_delete_usedata($_REQUEST['member_id']);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=gatekeeper&tab=gatekeeper_list&message=3');
			}
		}
		
		if(isset($_REQUEST['message']))
	      {
		   $message =$_REQUEST['message'];
			if($message == 1)
			{?>
					<div id="message" class="updated below-h2 ">
					<p>
					<?php 
						esc_html_e('Gatekeeper inserted successfully','apartment_mgt');
					?></p></div>
					<?php 
				
			}
			elseif($message == 2)
			{?><div id="message" class="updated below-h2 "><p><?php
						_e("Gatekeeper updated successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
			elseif($message == 3) 
			{?>
			<div id="message" class="updated below-h2"><p>
			<?php 
				esc_html_e('Gatekeeper deleted successfully','apartment_mgt');
			?></div></p><?php
					
			}
	    }
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'gatekeeper_list';
?>
<div class="panel-body panel-white"><!-- PANEL WHITE DIV -->
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="<?php if($active_tab=='gatekeeper_list'){?>active<?php }?>">
			<a href="?apartment-dashboard=user&page=gatekeeper&tab=gatekeeper_list" class="tab <?php echo $active_tab == 'gatekeeper_list' ? 'active' : ''; ?>">
			 <i class="fa fa-align-justify"></i> <?php esc_html_e('Gatekeeper List', 'apartment_mgt'); ?></a>
		  </a>
		</li>
		<li class="<?php if($active_tab=='add_gatekeeper'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['member_id']))
				{ ?>
				<a href="?apartment-dashboard=user&page=gatekeeper&tab=add_gatekeeper&action=edit&member_id=<?php echo $_REQUEST['member_id'];?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'add_gatekeeper' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php esc_html_e('Edit Gatekeeper', 'apartment_mgt'); ?></a>
				 <?php 
				}
				else
				{
					if($user_access['add']=='1')
					{ ?>
						<a href="?apartment-dashboard=user&page=gatekeeper&tab=add_gatekeeper" class="tab margin_top_10_res <?php echo $active_tab == 'add_gatekeeper' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Gatekeeper', 'apartment_mgt'); ?></a>
		  <?php 	} 	
				}?>
		  
		</li>
	</ul>
	<div class="tab-content">
	<?php if($active_tab == 'gatekeeper_list')
	{ ?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			"use strict";
			jQuery('#member_list').DataTable({
				"responsive":true,
				"order": [[ 1, "asc" ]],
				"aoColumns":[
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true}
							  <?php  
							if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant' AND $obj_apartment->role !=='gatekeeper')
							{
								?>
							  ,{"bSortable": false}
							 <?php  
							 } 
							 ?> 												  
						   ],		
							  language:<?php echo amgt_datatable_multi_language();?>
				});
			});
		</script>
    	<div class="panel-body"><!-- PANEL BODY DIV -->
           <div class="table-responsive">
				<table id="member_list" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Assigned Gate', 'apartment_mgt' ) ;?></th>
							<th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
							<th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
							<?php 
						if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant' AND $obj_apartment->role !=='gatekeeper')
									{ ?>
							 <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						<?php
						}
						?>
						</tr>
				    </thead>
					<tfoot>
						<tr>
						  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
						  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
						  <th><?php esc_html_e('Assigned Gate', 'apartment_mgt' ) ;?></th>
						  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
						  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
						 <?php 
						if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant' AND $obj_apartment->role !=='gatekeeper')
									{ ?>
						   <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						<?php
						}
						?>
						</tr>
					</tfoot>
					<tbody>
						<?php 
						$user_id=get_current_user_id();
						//--- MEMBER DATA FOR MEMBER  ------//
						if($obj_apartment->role=='member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$get_members = array('role' => 'gatekeeper','meta_key'  => 'created_by','meta_value' =>$user_id);
								$membersdata=get_users($get_members);
							}
							else
							{
								$get_members = array('role' => 'gatekeeper');
								$membersdata=get_users($get_members);
							}
						} 
						//--- MEMBER DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$get_members = array('role' => 'gatekeeper','meta_key'  => 'created_by','meta_value' =>$user_id);
								$membersdata=get_users($get_members);
							}
							else
							{
								$get_members = array('role' => 'gatekeeper');
								$membersdata=get_users($get_members);
							}
						}
						//--- MEMBER DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$get_members = array('role' => 'gatekeeper','meta_key'  => 'created_by','meta_value' =>$user_id);
								$membersdata=get_users($get_members);
							}
							else
							{
								$get_members = array('role' => 'gatekeeper');
								$membersdata=get_users($get_members);
							}
						}
						//--- MEMBER DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$membersdata[]=get_userdata($user_id);
							}
							else
							{
								$get_members = array('role' => 'gatekeeper');
								$membersdata=get_users($get_members);
							}
						}
						
						if(!empty($membersdata))
						{
							foreach ($membersdata as $retrieved_data)
							{
								?>
							<tr>
								<td class="user_image"><?php $uid=$retrieved_data->ID;
											$userimage=get_user_meta($uid, 'amgt_user_avatar', true);
										if(empty($userimage))
										{
											echo '<img src='.get_option( 'amgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
										}
										else
										{
											echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
										}
								?>
								</td>
							   
								<td class="name"><?php echo esc_html($retrieved_data->display_name);?></td>
								 <td class="gate"><?php echo amgt_get_gate_name($retrieved_data->aasigned_gate);?></td>
									
								<td class="email"><?php echo esc_html($retrieved_data->user_email);?></td>
								<td class="mobile"><?php echo esc_html($retrieved_data->mobile);?></td>
								 <?php 
								if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant' AND $obj_apartment->role !=='gatekeeper')
									{ ?>
								<td class="action">
								<?php
									if($user_access['edit']=='1')
									{  ?>
										<a href="?apartment-dashboard=user&page=gatekeeper&tab=add_gatekeeper&action=edit&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
									<?php
									}
									if($user_access['delete']=='1')
									{
									?>
										<a href="?apartment-dashboard=user&page=gatekeeper&tab=gatekeeper_list&action=delete&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
										<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
										<?php
									}
									?>
									</td>
								<?php
								}
								?>
							</tr>
							<?php LOOPEND: } 
							
						}?>
					</tbody>
			    </table>
            </div>
        </div>
	<?php 
	}
	if($active_tab == 'add_gatekeeper')
	{
		$role='gatekeeper';?>
		<script type="text/javascript">
		$(document).ready(function() 
		{
			"use strict";
			$('#gatekeeper_form').validationEngine();
			jQuery('#birth_date').datepicker({
			dateFormat: "yy-mm-dd",
			maxDate : 0,
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
			//USERNAME NOT  ALLOW SPACE VALIDATION
			$('#username').keypress(function( e ) 
			{
			   if(e.which === 32) 
				 return false;
			});
		} );
		</script>
		<script type="text/javascript">
				function member_imgefileCheck(obj)
				{
					"use strict";
					var fileExtension = ['jpg','jpeg','png'];
					if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
					{
						alert("Only '.jpg','.jpeg','.png'  formats are allowed.");
						$(obj).val('');
					}	
				}
				function fileCheck(obj)
				{
					var fileExtension = ['pdf','doc','jpg','jpeg','png'];
					if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
					{
						alert("Only '.pdf','.docx','.jpg','.jpeg','.png'  formats are allowed.");
						$(obj).val('');
					}	
				}
				</script>
     <?php
	 $obj_gate=new Amgt_gatekeeper;
	 $gatedata=$obj_gate->Amgt_get_all_gates();
      // ADD GATEKEEPER	 
		$member_id=0;
		if(isset($_REQUEST['member_id']))
			$member_id=$_REQUEST['member_id'];
		$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = get_userdata($member_id);
			}
				?>
		<div class="panel-body"><!--PANEL BODY-->
		        <!--GATEKEEPER FORM-->
                <form name="gatekeeper_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="gatekeeper_form">
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				 <input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				 <input type="hidden" name="user_id" value="<?php echo esc_attr($member_id);?>"  />
				 <input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />

				<div class="form-group">
					<label class="col-sm-2 control-label" for="first_name"><?php esc_html_e('First Name','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="first_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="middle_name"><?php esc_html_e('Middle Name','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input id="middle_name" class="form-control validate[custom[onlyLetterSp]]" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="last_name"><?php esc_html_e('Last Name','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>" name="last_name">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="gender"><?php esc_html_e('Gender','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
					<?php $genderval = "male"; if($edit){ $genderval=$result->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
						<label class="radio-inline">
						 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_html_e('Male','apartment_mgt');?>
						</label>
						<label class="radio-inline">
						  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_html_e('Female','apartment_mgt');?> 
						</label>
					</div>
				</div>
				 <style>
					.dropdown-menu {
						min-width: 240px;
					}
                </style>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="birth_date"><?php esc_html_e('Date of birth','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="birth_date" class="form-control validate[required]" autocomplete="off" type="text"  name="birth_date" 
						value="<?php if($edit){ echo date("Y-m-d",strtotime($result->birth_date));}elseif(isset($_POST['birth_date'])) echo esc_attr($_POST['birth_date']);?>">
					</div>
				</div>
				 <div class="form-group">
					<label class="col-sm-2 control-label" for="gate"><?php esc_html_e('Assign Gate','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
					<?php $gateval = "1"; if($edit){ $gateval=$result->aasigned_gate; }elseif(isset($_POST['gate'])) {$gateval=$_POST['gate'];}
						if(!empty($gatedata))
						{
							$i=1;
							foreach($gatedata as $gate)
							{
									if($edit)
									{
									?>
									<label class="radio-inline">
									<input type="radio" value="<?php echo esc_attr($gate->id);?>" class="tog validate[required]" name="gate"  <?php  echo checked( $gate->id, $gateval);  ?>/><?php echo esc_attr($gate->gate_name);?>
									</label>							
									<?php 
									}
									else
									{?>
										<label class="radio-inline">
										<input type="radio" value="<?php echo esc_attr($gate->id);?>" class="tog validate[required]" name="gate"  <?php  if($i==1) echo "checked"; ?>/><?php echo esc_attr($gate->gate_name);?>
										</label>
									<?php 
									}
								$i+=1;
									
									
							}
						}
						else
						{ ?>
							<label class="radio-inline">
							<?php esc_html_e('No Any Gates.','apartment_mgt');
							echo "</label>";
						}
					?>
						
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label " for="email"><?php esc_html_e('Email','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="50" type="text"  name="email" 
						value="<?php if($edit){ echo $result->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('Mobile Number','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-1">
					
					<input type="text" readonly value="+<?php echo amgt_get_countery_phonecode(get_option( 'amgt_contry' ));?>"  class="form-control" name="phonecode">
					</div>
					<div class="col-sm-7">
						<input id="mobile" class="form-control validate[required,custom[phone]] text-input" type="number" min="0" onKeyPress="if(this.value.length==15) return false;"  name="mobile" value="<?php if($edit){ echo esc_attr($result->mobile);}elseif(isset($_POST['mobile'])) echo esc_attr($_POST['mobile']);?>">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="address"><?php esc_html_e('Address','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="address" class="form-control validate[required]" type="text" maxlength="150" name="address" 
						value="<?php if($edit){ echo esc_attr($result->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
					</div>
				</div>
				<div class="form-group">
						<label class="col-sm-2 control-label" for="city_name"><?php esc_html_e('City','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input id="city_name" class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="city_name" 
							value="<?php if($edit){ echo esc_attr($result->city_name);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
						</div>
						<label class="col-sm-2 control-label"><?php esc_html_e('State','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="state_name" 
							value="<?php if($edit){ echo esc_attr($result->state_name);}elseif(isset($_POST['state_name'])) echo esc_attr($_POST['state_name']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php esc_html_e('Country','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="country_name" 
							value="<?php if($edit){ echo esc_attr($result->country_name);}elseif(isset($_POST['country_name'])) echo esc_attr($_POST['country_name']);?>">
						</div>
						<label class="col-sm-2 control-label"><?php esc_html_e('Zip Code','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="10" type="text"  name="zipcode" 
							value="<?php if($edit){ echo esc_attr($result->zipcode);}elseif(isset($_POST['zipcode'])) echo esc_attr($_POST['zipcode']);?>">
						</div>
					</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="username"><?php esc_html_e('User Name','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="username" class="form-control validate[required,custom[username_validation]]"  maxlength="30" type="text"  name="username" 
						value="<?php if($edit){ echo esc_attr($result->user_login);}elseif(isset($_POST['username'])) echo esc_attr($_POST['username']);?>" <?php if($edit) echo "readonly";?>>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="password"><?php esc_html_e('Password','apartment_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
					<div class="col-sm-8">
						<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password"  name="password" minlength="8" maxlength="12" value="">
					</div>
				</div>
				
				<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Image','apartment_mgt');?></label>
						<div class="col-sm-2">
							<input type="text" id="amgt_user_avatar_url" class="form-control" name="amgt_user_avatar"  
							value="<?php if($edit)echo esc_url( $result->amgt_user_avatar );elseif(isset($_POST['amgt_user_avatar'])) echo $_POST['amgt_user_avatar']; ?>" readonly />
							<input type="hidden" class="form-control" name="hidden_upload_user_avatar_image"  onchange="member_imgefileCheck(this);" 
							value="<?php if($edit)echo esc_url( $result->amgt_user_avatar );elseif(isset($_POST['hidden_upload_user_avatar_image'])) echo $_POST['hidden_upload_user_avatar_image']; ?>" />
						</div>	
							<div class="col-sm-3 margin_top_10_res">
								 <input id="upload_user_avatar" name="upload_user_avatar_image"  onchange="member_imgefileCheck(this);" type="file" />
							</div>
						<div class="clearfix"></div>
						
						<div class="col-sm-offset-2 col-sm-8">
								<div id="upload_user_avatar_preview" >
									 <?php if($edit) 
										{
										if($result->amgt_user_avatar == "")
										{?>
										<img alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
										<?php }
										else {
											?>
										<img style="max-width:100%;" src="<?php if($edit)echo esc_url( $result->amgt_user_avatar ); ?>" />
										<?php 
										}
										}
										else {
											?>
											<img alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
											<?php 
										} ?>
								</div>
						</div>
					</div>
					   <?php wp_nonce_field( 'save_gatekeeper_nonce' ); ?>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($edit){ esc_html_e('Save','apartment_mgt'); }else{ esc_html_e('Add Gatekeeper','apartment_mgt');}?>" name="save_gatekeeper" class="btn btn-success"/>
					</div>
		
            </form><!--END GATEKEEPER FORM-->
        </div><!--END PANEL BODY-->
	<?php
		}
	?>
	</div>
</div><!--END PANEL WHITE DIV -->
<?php ?>