<?php 
    $user_type=isset($_REQUEST['user_type'])?$_REQUEST['user_type']:'member';
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'memberlist';
	$obj_member=new Amgt_Member;
	$obj_units=new Amgt_ResidentialUnit;
    $obj_gate=new Amgt_gatekeeper;
    $gatedata=$obj_gate->Amgt_get_all_gates();
	
?>
<!-- POP UP CODE -->
<div class="popup-bg z_index_100000">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"> </div>
		</div>
    </div>    
</div>
<!-- END POP-UP CODE -->
<div class="page-inner min_height_1088"><!-- PAGE INNER DIV -->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<!-- ADD MEMBER-->
<?php 
//---------------------- SAVE MEMBER -------------------------//
	if(isset($_POST['save_member']))		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_member_nonce' ) )
		{
			$upload_docs_array=array(); 
			$document_title=array(); 
			
			if($_FILES['id_proof_1']['name'] != "" && $_FILES['id_proof_1']['size'] > 0)
			{
				$id_proof_1=amgt_load_documets($_FILES['id_proof_1'],$_FILES['id_proof_1'],'id_proof_1');
			}
			else
			{
				$id_proof_1=$_REQUEST['hidden_id_proof_1'];
			} 
			
			if($_FILES['id_proof_2']['name'] != "" && $_FILES['id_proof_2']['size'] > 0)
			{
				$id_proof_2=amgt_load_documets($_FILES['id_proof_2'],$_FILES['id_proof_2'],'id_proof_2');
			}
			else
			{
				$id_proof_2=$_REQUEST['hidden_id_proof_2'];
			} 
			
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$document_title=$_POST['doc_title'];
			if(!empty($_FILES['upload_file']['name']))//UPLOAD FILE

			{

				$count_array=count($_FILES['upload_file']['name']);

				for($a=0;$a<$count_array;$a++)

				{	

					foreach($_FILES['upload_file'] as $image_key=>$image_val)

					{	

						$document_array[$a]=array(

						'name'=>$_FILES['upload_file']['name'][$a],

						'type'=>$_FILES['upload_file']['type'][$a],

						'tmp_name'=>$_FILES['upload_file']['tmp_name'][$a],

						'error'=>$_FILES['upload_file']['error'][$a],

						'size'=>$_FILES['upload_file']['size'][$a]

						);	

					}

				}	

				foreach($document_array as $key=>$value)
				{	

					$get_file_name=$document_array[$key]['name'];	

					$upload_docs_array[]=amgt_load_documets($value,$value,$get_file_name);

				} 

			}
			else
			{
				
				$upload_docs_array=$_REQUEST['hidden_upload_file'];

			} 
			
			 $imagurl=$_POST['upload_user_avatar_image'];
			  $ext=amgt_check_valid_extension($imagurl);
			    if(!$ext == 0)
			    {
			         $result=$obj_member->amgt_add_member($_POST);
					 $obj_member->amgt_update_upload_documents($id_proof_1,$id_proof_2,$document_title,$upload_docs_array,$result);
						if($result)
						{
							wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=memberlist&message=14');
						}
			   }
				else
					{ ?>
					<div id="message" class="updated below-h2 notice is-dismissible">
						<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
					</div>
			   <?php }
		}
		else
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] ))
		    {
				$document_title=$_POST['doc_title'];
				if(!empty($_FILES['upload_file']['name']))//UPLOAD FILE
				{

					$count_array=count($_FILES['upload_file']['name']);

					for($a=0;$a<$count_array;$a++)
					{	
						foreach($_FILES['upload_file'] as $image_key=>$image_val)
						{	

							$document_array[$a]=array(

							'name'=>$_FILES['upload_file']['name'][$a],

							'type'=>$_FILES['upload_file']['type'][$a],

							'tmp_name'=>$_FILES['upload_file']['tmp_name'][$a],

							'error'=>$_FILES['upload_file']['error'][$a],

							'size'=>$_FILES['upload_file']['size'][$a]

							);	

						}

					}	

					foreach($document_array as $key=>$value)
					{	

						$get_file_name=$document_array[$key]['name'];	

						$upload_docs_array[]=amgt_load_documets($value,$value,$get_file_name);	

					} 

				}

				$imagurl=$_POST['amgt_user_avatar'];
			    $ext=amgt_check_valid_extension($imagurl);
			    if(!$ext == 0)
			     {
				  $result=$obj_member->amgt_add_member($_POST);
				  $obj_member->amgt_upload_documents($id_proof_1,$id_proof_2,$document_title,$upload_docs_array,$result);	
				  if($result)
				   {
					 wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=memberlist&message=15');
				    }
				 }
				 
				 else{ ?>
				        <div id="message" class="updated below-h2 notice is-dismissible">
						    <p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
						</div>
		        <?php }
			}
		    else{ ?>
						<div id="message" class="updated below-h2 notice is-dismissible">
						   <p><p><?php esc_html_e('Username Or Emailid Already Exist.','apartment_mgt');?></p></p>
						</div>
	      <?php }
		}
	}
	}
	
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'active_member')
	{
		//---------------- SEND  SMS ------------------//
		include_once(ABSPATH.'wp-admin/includes/plugin.php');
		if(is_plugin_active('sms-pack/sms-pack.php'))
		{
			if(!empty(get_user_meta($_REQUEST['member_id'], 'phonecode',true))){ $phone_code=get_user_meta($_REQUEST['member_id'], 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
							
			$user_number[] = $phone_code.get_user_meta($_REQUEST['member_id'], 'mobile',true);
			
			$apartmentname=get_option('amgt_system_name');
			$message_content ="You are successfully registered at $apartmentname .";
			$current_sms_service 	= get_option( 'smgt_sms_service');
			$args = array();
			$args['mobile']=$user_number;
			$args['message_from']="Registration";
			$args['message']=$message_content;					
			if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
			{				
				$send = send_sms($args);							
			}
		}
		$member_id = $_REQUEST['member_id'];
		delete_user_meta($member_id, 'amgt_hash');
		$user_info = get_userdata($member_id);
		$to = $user_info->user_email; 
		$subject =get_option('wp_amgt_Member_approve_subject');
		
		$apartmentname=get_option('amgt_system_name');
		$message_content=get_option('wp_amgt_Member_approve_email_template');
		
		$loginlink=home_url().'/apartment-management/';
		$subject_search=array('{{apartment_name}}');
		$subject_replace=array($apartmentname);
		$search=array('{{member_name}}','{{apartment_name}}','{{loginlink}}');
		$replace = array($user_info->display_name,$apartmentname,$loginlink);
		$message_content = str_replace($search, $replace, $message_content);
		$subject=str_replace($subject_search,$subject_replace,$subject);
		amgtSendEmailNotification($to,$subject,$message_content);
		wp_redirect ( admin_url() . 'admin.php?page=amgt-member&tab=memberlist&message=4');
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete_staff')
	{
		
		$result=$obj_member->amgt_delete_usedata($_REQUEST['member_id']);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=stafflist&message=5');
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
					esc_html_e('Record inserted successfully','apartment_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Record updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Record deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Member Active Successfully','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 5) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Staff Member deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 6) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Accountant deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 7) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Gatekeeper deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 8) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Gatekeeper updated successfully.','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 9) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Gatekeeper inserted successfully.','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 10) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Staff Member updated successfully.','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 11) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Staff Member inserted successfully.','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 12) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Accountant updated successfully.','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 13) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Accountant inserted successfully.','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 14) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Member updated successfully.','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 15) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Member inserted successfully.','apartment_mgt');
		?></div></p><?php
				
		}
	}
	?>
	
	<?php 
	if(isset($_POST['save_accountant']))//<!-- SAVE ACCOUNTANT-->		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_accountant_nonce' ) )
		{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
		$imagurl=$_POST['amgt_user_avatar'];
			  $ext=amgt_check_valid_extension($imagurl);
			    if(!$ext == 0)
			    {	
					$result=$obj_member->amgt_add_member($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=accountantlist&message=12');
					}
				}
				else
				{ ?>
					<div id="message" class="updated below-h2 notice is-dismissible">
						<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
					</div>
			   <?php 
			   }
		}
		else
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] ))
			{
				$imagurl=$_POST['amgt_user_avatar'];
			    $ext=amgt_check_valid_extension($imagurl);
			    if(!$ext == 0)
				{
					$result=$obj_member->amgt_add_member($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=accountantlist&message=13');
					}
				}
				 else
					{ ?>
					<div id="message" class="updated below-h2 notice is-dismissible">
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
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete_account')
	{
		
		$result=$obj_member->amgt_delete_usedata($_REQUEST['member_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=accountantlist&message=6');
		}
	}
	
?>
<?php 
	if(isset($_POST['save_gatekeeper']))//<!--ADD GATEKEEPER-->		
	{
		 $nonce = $_POST['_wpnonce'];
			if (wp_verify_nonce( $nonce, 'save_gatekeeper_nonce' ) )
			{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$imagurl=$_POST['amgt_user_avatar'];
			$ext=amgt_check_valid_extension($imagurl);
			if(!$ext == 0)
			{
				$result=$obj_member->amgt_add_member($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=gatekeeperlist&message=8');
				}
			}
			else
			    { ?>
					<div id="message" class="updated below-h2 notice is-dismissible">
						<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
					</div>
		   <?php }
		}
		else
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
				$imagurl=$_POST['amgt_user_avatar'];
				$ext=amgt_check_valid_extension($imagurl);
				if(!$ext == 0)
				{
				 $result=$obj_member->amgt_add_member($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=gatekeeperlist&message=9');
					}
				}
				else
			        { ?>
						<div id="message" class="updated below-h2 notice is-dismissible">
							<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
						</div>
		       <?php }
			}
			else
			{ ?>
						<div id="message" class="updated below-h2 notice is-dismissible">
						<p><p><?php esc_html_e('Username Or Emailid Already Exist.','apartment_mgt');?></p></p>
						</div>
						
	  <?php }		
		}
	}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete_gatekeeper')//<!--DELETE-->
		{
			
			$result=$obj_member->amgt_delete_usedata($_REQUEST['member_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=gatekeeperlist&message=7');
			}
		}
		
	?>
	<?php 
	if(isset($_POST['save_staff_member']))//<!--SAVE STAFF-MEMBER-->	
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_staff_member_nonce' ) )
		{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
		    $imagurl=$_POST['amgt_user_avatar'];
			$ext=amgt_check_valid_extension($imagurl);
			    if(!$ext == 0)
				{
					$result=$obj_member->amgt_add_member($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=stafflist&message=10');
					}
				}
				else
					{ ?>
					<div id="message" class="updated below-h2 notice is-dismissible">
						<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
					</div>
		        <?php }
		}
			else
			{
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
				$imagurl=$_POST['amgt_user_avatar'];
			    $ext=amgt_check_valid_extension($imagurl);
			     if(!$ext == 0)
				 {
					$result=$obj_member->amgt_add_member($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=stafflist&message=11');
					}
				 }
				 else
			            { ?>
				        <div id="message" class="updated below-h2 notice is-dismissible">
						    <p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
						</div>
		           <?php }
			}	
			else
			{ ?>
					<div id="message" class="updated below-h2 notice is-dismissible">
					<p><p><?php esc_html_e('Username Or Emailid Already Exist.','apartment_mgt');?></p></p>
					</div>
						
	  <?php }	
		  }
	 }
	}
	?>
	<div id="main-wrapper"><!--MAIN-WRAPPER-->	
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL-WHITE-->
					<div class="panel-body"><!--PANEL BODY-->
						<h2 class="nav-tab-wrapper">
							<a href="?page=amgt-member&tab=memberlist" class="nav-tab <?php echo $active_tab == 'memberlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Member List', 'apartment_mgt'); ?></a>
							<a href="?page=amgt-member&tab=accountantlist" class="nav-tab <?php echo $active_tab == 'accountantlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Accountant List', 'apartment_mgt'); ?></a>
							<a href="?page=amgt-member&tab=stafflist" class="nav-tab <?php echo $active_tab == 'stafflist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Staff Member List', 'apartment_mgt'); ?></a>
							<a href="?page=amgt-member&tab=gatekeeperlist" class="nav-tab <?php echo $active_tab == 'gatekeeperlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Gatekeeper List', 'apartment_mgt'); ?></a>
                            
							
							<?php if($active_tab=='viewmember'){?>
		                    <a href="#" class="nav-tab <?php echo $active_tab == 'viewmember' ? 'nav-tab-active' : ''; ?>">
		                    <?php echo '<span class="fa fa-eye"></span> '.esc_html__('View User','apartment_mgt'); ?></a>
		                    <?php } ?>
							
														
							<div class="dropdown">
                               <button class="dropbtn add_user_button"><span class="dashicons dashicons-plus-alt color_white" ></span><span><?php esc_html_e('Add User', 'apartment_mgt');?></span></button>
                                <div class="dropdown-content margin_left_56_min">
                                  <a href="?page=amgt-member&tab=adduser&user_type=member" class="<?php if($user_type=="member") print "info"; else print "primary" ; ?>" ><span class="dashicons dashicons-plus-alt color_black_m_t_9"></span><span class="span_drop"><?php esc_html_e('Add Member','apartment_mgt'); ?></span></a>
                                 <a href="?page=amgt-member&tab=adduser&user_type=accountant"class="<?php if($user_type=="accountant") print "info"; else print "primary"; ?>" ><span class="dashicons dashicons-plus-alt color_black_m_t_9 "></span><span class="span_drop"><?php esc_html_e('Add Accountant','apartment_mgt'); ?></span></a>
                                  <a href="?page=amgt-member&tab=adduser&user_type=staff-Member"<?php if($user_type=="staff-Member")
			                           print "info"; else print "primary"; ?> ><span class="dashicons dashicons-plus-alt color_black_m_t_9" ></span><span class="span_drop"><?php esc_html_e('Add Staff Member','apartment_mgt'); ?></span></a>
								  <a href="?page=amgt-member&tab=adduser&user_type=gatekeeper"<?php if($user_type=="gatekeeper") print "info"; else print "primary"; ?>><span class="dashicons dashicons-plus-alt color_black_m_t_9"></span><span class="span_drop"><?php esc_html_e('Add Gatekeeper','apartment_mgt'); ?></span></a>
                                </div>
                            </div>	
						</h2>

						
                         <?php
						 if($active_tab == 'adduser')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/member/add_user.php';
						 }
		
						 if($active_tab == 'memberlist')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/member/member_list.php';
						 }
						 if($active_tab == 'accountantlist')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/member/accountant_list.php';
						 }
						 if($active_tab == 'stafflist')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/member/staff_list.php';
						 }
						 if($active_tab == 'gatekeeperlist')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/member/gatekeeper_list.php';
						 }

						 if($active_tab == 'viewmember')
	                     {		  	  
		                    require_once AMS_PLUGIN_DIR.'/admin/member/view_user.php';
	                     }
						 
						?>
                    </div><!--END PANEL BODY-->
                </div><!--END PANEL-WHITE-->
            </div>
        </div>
    </div><!--END MAIN WRAPPER-->
</div>
<!-- END PAGE INNER DIV -->