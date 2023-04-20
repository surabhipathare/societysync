<?php 
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
$curr_user_id=get_current_user_id();
$obj_apartment=new Apartment_management($curr_user_id);
$obj_member=new Amgt_Member;
$obj_units=new Amgt_ResidentialUnit;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'memberlist';
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
			
			$imagurl=$_POST['amgt_user_avatar'];
			  $ext=amgt_check_valid_extension($imagurl);
			    if(!$ext == 0)
			    {
					
			         $result=$obj_member->amgt_add_member($_POST);
					 $returnans=update_user_meta($result,'amgt_user_avatar',$member_image_url);
					 $obj_member->amgt_update_upload_documents($id_proof_1,$id_proof_2,$document_title,$upload_docs_array,$result);
						if($result)
						{
							wp_redirect ( home_url().'?apartment-dashboard=user&page=member&tab=memberlist&message=2');
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
				  $returnans=update_user_meta($result,'amgt_user_avatar',$member_image_url);
				  $obj_member->amgt_upload_documents($id_proof_1,$id_proof_2,$document_title,$upload_docs_array,$result);	
				  if($result)
				   {
					 wp_redirect ( home_url().'?apartment-dashboard=user&page=member&tab=memberlist&message=1');
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

	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE USER
	{
		$result=$obj_member->amgt_delete_usedata($_REQUEST['member_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=member&tab=memberlist&message=3');
		}
	}

	if(isset($_REQUEST['message']))//MESSAGE
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				esc_html_e('Member inserted successfully','apartment_mgt');
			?></p></div>
			<?php
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Member updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			esc_html_e('Member deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
	}
	?>
    <div class="panel-body panel-white"><!--PANEL-WHITE-->
	     <!----PANEL_TABS---->
		<ul class="nav nav-tabs panel_tabs" role="tablist">
			
				<li class="<?php if($active_tab=='memberlist'){?>active<?php }?>">
					<a href="?apartment-dashboard=user&page=member&tab=memberlist" class="tab <?php echo $active_tab == 'memberlist' ? 'active' : ''; ?>">
					  <i class="fa fa-align-justify"></i> <?php esc_html_e('Member List', 'apartment_mgt'); ?></a>
					</a>
				</li>
			    <li class="<?php if($active_tab=='addmember'){?>active<?php }?>">
				  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['member_id']))
					{ ?>
					<a href="?apartment-dashboard=user&page=member&tab=addmember&action=edit&member_id=<?php echo $_REQUEST['member_id'];?>" class="nav-tab <?php echo $active_tab == 'addmember' ? 'nav-tab-active' : ''; ?>">
					 <i class="fa fa"></i> <?php esc_html_e('Edit Member', 'apartment_mgt'); ?></a>
					 <?php 
					}
					else
					{
						if($user_access['add']=='1')
						{ ?>
							<a href="?apartment-dashboard=user&page=member&tab=addmember" class="tab <?php echo $active_tab == 'addmember' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Member', 'apartment_mgt'); ?></a>
			  <?php 	} 	
					}?>
			  
			    </li>
			    <li class="<?php if($active_tab=='viewmember'){?>active<?php }?>">
				  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
					{?>
						
					<a href="?apartment-dashboard=user&page=member&tab=viewmember&action=view&member_id=<?php echo $_REQUEST['member_id'];?>" class="nav-tab <?php echo $active_tab == 'viewmember' ? 'nav-tab-active' : ''; ?>">
					 <i class="fa fa"></i><?php esc_html_e('View Member', 'apartment_mgt'); ?></a>  
						<?php 
					}?>
				</li>
		</ul><!----END PANEL_TABS---->
		    <div class="tab-content">
			<?php if($active_tab == 'memberlist')//MEMBER LIST
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
									  {"bSortable": true},
									  {"bSortable": true},
									  {"bSortable": true},
									  {"bSortable": false}
									],
									language:<?php echo amgt_datatable_multi_language();?>
						});
				});
				</script>
    	        <div class="panel-body"><!--PANEL BODY-->
        	        <div class="table-responsive"><!---TABLE-RESPONSIVE--->
						<table id="member_list" class="display" cellspacing="0" width="100%"><!---MEMBER_LIST--->
							<thead>
								<tr>
								  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
								  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
								  <th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
								  <th><?php esc_html_e('Building Name', 'apartment_mgt') ;?></th>
								  <th><?php esc_html_e('Unit Name', 'apartment_mgt' ) ;?></th>
								  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
								  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
								  <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
								 <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
								  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
								  <th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
								  <th><?php esc_html_e('Building Name', 'apartment_mgt') ;?></th>
								  <th><?php esc_html_e('Unit Name', 'apartment_mgt' ) ;?></th>
								  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
								  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
								   <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
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
										$unit_name=get_user_meta($user_id,'unit_name',true);
										$building_id=get_user_meta($user_id,'building_id',true);
										$user_query = new WP_User_Query( 
															array(
																'meta_query'    => array(
																	'relation'  => 'AND',
																	array( 
																		'key'     => 'unit_name',
																		'value'   => $unit_name,
																	),
																	array(
																		'key'     => 'building_id',
																		'value'   => $building_id,
																		'compare' => '='
																	)
																)
															));
										$membersdata = $user_query->get_results();
										//$membersdata[]=get_userdata($user_id);
									}
									else
									{
										
										
										$building_id=get_user_meta($user_id,'building_id',true);
										
										$membersdata =  get_users(	
											array(
												'meta_key' => 'building_id',
												'meta_value' =>$building_id,	   
											)
										);
										
									}
								} 
								//--- MEMBER DATA FOR STAFF MEMBER  ------//
								elseif($obj_apartment->role=='staff_member')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{  
										$get_members = array('role' => 'member','meta_key'  => 'created_by','meta_value' =>$user_id);
										$membersdata=get_users($get_members);
									}
									else
									{
										$get_members = array('role' => 'member');
										$membersdata=get_users($get_members);
									}
								}
								//--- MEMBER DATA FOR ACCOUNTANT  ------//
								elseif($obj_apartment->role=='accountant')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$get_members = array('role' => 'member','meta_key'  => 'created_by','meta_value' =>$user_id);
										$membersdata=get_users($get_members);
									}
									else
									{
										$get_members = array('role' => 'member');
										$membersdata=get_users($get_members);
									}
								}
								//--- MEMBER DATA FOR GATEKEEPER  ------//
								else
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$get_members = array('role' => 'member','meta_key'  => 'created_by','meta_value' =>$user_id);
										$membersdata=get_users($get_members);
									}
									else
									{
										$get_members = array('role' => 'member');
										$membersdata=get_users($get_members);
									}
								}
								
								if(!empty($membersdata))
								{
									foreach ($membersdata as $retrieved_data)
									{
										if(empty($retrieved_data->amgt_hash))
										{
											$building_name=get_the_title($retrieved_data->building_id);
											
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
										  ?></td>
											<td class="name">
											<?php echo esc_html($retrieved_data->display_name);?></a></td>
											<td class="bnumber"><?php echo amgt_get_member_status_label($retrieved_data->member_type);?></td>
											<td class="activitydate"><?php echo esc_html($building_name);?></td>
											<td class="activitydate"><?php echo esc_html($retrieved_data->unit_name);?></td>
											<td class=""><?php echo esc_html($retrieved_data->user_email);?></td>
											<td class=""><?php echo esc_html($retrieved_data->mobile);?></td>
											<td class="action">
												<a href="?apartment-dashboard=user&page=member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-success"> <?php esc_html_e('View', 'apartment_mgt' ) ;?></a>
												<?php
												if($user_access['edit']=='1')
												{  ?>
													<a href="?apartment-dashboard=user&page=member&tab=addmember&action=edit&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
												<?php
												}
												if($user_access['delete']=='1')
												{
												?>
													<a href="?apartment-dashboard=user&page=member&tab=memberlist&action=delete&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
													<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
												<?php
												}
												?>
											</td>
										</tr>
									<?php
										}
									} 
								}?>
							</tbody>
						</table><!---END MEMBER_LIST--->
                    </div><!---END TABLE-RESPONSIVE--->
                </div><!--END PANEL BODY-->
		<?php } ?>
	   
			 <?php 
			if($active_tab == 'addmember')
			{ 
				require_once AMS_PLUGIN_DIR.'/template/member/add_member.php' ;
			} 
			if($active_tab == 'viewmember')
			{ 
				require_once AMS_PLUGIN_DIR.'/template/member/view_member.php' ;
			} 
?>

	    </div>
    </div>
<?php ?>
<!-----ADD_UNIT_FORM----->
<div class="modal fade overflow_scroll" id="myModal_add_building" role="dialog">
    <div class="modal-dialog modal-lg"><!-----MODAL-DIALOG------>
        <div class="modal-content"><!-----MODAL-CONTENT------->
            <div class="modal-header"><!-----MODAL HEADER------>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h3 class="modal-title"><?php esc_html_e('Add Building','apartment_mgt');?></h3>
            </div>
            <div class="modal-body">
				<script type="text/javascript">
				$(document).ready(function() {
					"use strict";
					$('#unit_form').validationEngine();
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

				});

				</script>					
				<form name="unit_form"  method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" class="form-horizontal" id="unit_form">
				  <input id="" type="hidden" name="action" value="amgt_add_unit_popup">
				    <div class="form-group">
					    <label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
					    <div class="col-sm-8">
							<select class="form-control validate[required] building_category" name="building_id" id="">
								<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
								<?php 
								$activity_category=amgt_get_all_category('building_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
									}
								} ?>
							</select>
					    </div>
					    <div class="col-sm-2"><button id="addremove" model="building_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				    </div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<select class="form-control validate[required] unit_category"  name="unit_cat_id" id="">
								<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
								<?php 

								$activity_category=amgt_get_all_category('unit_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
									}
								} ?>
							</select>
						</div><!-----ADD REMOVE UNIT_CATEGORY--------->
						<div class="col-sm-2"><button id="addremove" model="unit_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
					</div>
					<?php 
						   if(isset($_POST['unit_names'])){
								$all_data=$obj_units->amgt_get_entry_records($_POST);
								$all_entry=json_decode($all_data);
							}
						   ?>
							<div id="unit_name_entry"><!----UNIT_NAME_ENTRY--->
									<div class="form-group">
									<label class="col-sm-2 control-label" for="unit_entry"><?php esc_html_e('Unit Name','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-2">
										<input class="form-control validate[required] text-input onlyletter_number_space_validation unit_name" type="text" value="" name="unit_names[]" placeholder="<?php esc_html_e('Unit Name','apartment_mgt');?>">
									</div>	
									<?php $unit_measerment_type=get_option( 'amgt_unit_measerment_type' );?>						
									<label class="col-sm-3 control-label" for="unit_entry"><?php esc_html_e('Unit Size','apartment_mgt');?>(<?php if($unit_measerment_type =='square_meter'){
									 echo esc_html_e('square meter','apartment_mgt');
									 }
									 else{
										echo $unit_measerment_type;
									 }
						
						           ?>)<span class="require-field">*</span></label>
									<div class="col-sm-2">
										<input  class="form-control validate[required] text-input" type="number" onKeyPress="if(this.value.length==6) return false;"  min="0" value="" name="unit_size[]" placeholder="<?php esc_html_e('Unit Size','apartment_mgt');?>">
									</div>
									<div class="col-sm-2">
									<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
									<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
									</button>
									</div>
									</div>	
							</div><!----END UNIT_NAME_ENTRY--->
						
							<div class="form-group">
								<label class="col-sm-2 control-label" for="unit_entry"></label>
								<div class="col-sm-3">
									
									<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php esc_html_e('Add More Unit','apartment_mgt'); ?>
									</button>
								</div>
							</div>
					<hr>			
					<div class="col-sm-offset-2 col-sm-8">
					<?php $unit_type=get_option( 'amgt_apartment_type' ); ?>
						<input type="submit" value="<?php  esc_html_e('Add '.$unit_type.' Unit','apartment_mgt'); ?>" name="save_residential_unit" class="btn btn-success"/>
					</div>
				
				</form>
		
            </div>
			<div class="modal-footer"><!---MODAL FOOTER--->
			  <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close','apartment_mgt'); ?></button>
			</div>
        </div>
    </div>
</div>
<script>
	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_expense_entry = $('#unit_name_entry').html();
   		//alert("hello" + blank_invoice_entry);
   	}); 

   	function add_entry()
   	{
   		$("#unit_name_entry").append(blank_expense_entry);
   		//alert("hellooo");
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n)
	{
		if(confirm("Are you sure want to delete this record?"))
		{
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}	
   	}
</script>
