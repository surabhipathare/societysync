<script type="text/javascript">
function fileCheck(obj)
{
	"use strict";
	var fileExtension = ['pdf','doc','jpg','jpeg','png'];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("Only '.pdf','.docx','.jpg','.jpeg','.png'  formats are allowed.");
		$(obj).val('');
	}	
}
</script>
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
$obj_document=new Amgt_Document;
$obj_units=new Amgt_ResidentialUnit;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'documentlist';

if(isset($_POST['save_document']))//SAVE DOCUMENT
{	
    $nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_document_nonce' ) )
	{
    $upload_docs_array=array(); 
	 	
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			if($_FILES['upload_file']['name'] != "" && $_FILES['upload_file']['size'] > 0)
			{ 
			   $filename=amgt_load_documets($_FILES['upload_file'],$_FILES['upload_file'],'upload_file');
			   $upload_docs_array=content_url().'/uploads/apartment_assets/'.$filename;
			}
			else
			{
				$upload_docs_array=$_REQUEST['hidden_upload_file'];
			} 

			$result=$obj_document->amgt_add_document($_POST,$upload_docs_array);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=documents&tab=documentlist&message=2');
			}
		}
		else
		{
			if(!empty($_FILES['upload_file']['name']))
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

					$filename=amgt_load_documets($value,$value,$get_file_name);	
					$upload_docs_array[]=content_url().'/uploads/apartment_assets/'.$filename;
				} 
			}
			//$_POST['amgt_user_avatar']=$file_url;
			$result=$obj_document->amgt_add_document($_POST,$upload_docs_array);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=documents&tab=documentlist&message=1');
			}
		}
}
}	
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	
	$result=$obj_document->amgt_delete_document($_REQUEST['document_id']);
	if($result)
	{
		wp_redirect ( home_url().'?apartment-dashboard=user&page=documents&tab=documentlist&message=3');
	}
}
if(isset($_REQUEST['message']))//MESSAGES
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				esc_html_e('Document inserted successfully','apartment_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Document updated successfully.",'apartment_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		esc_html_e('Document deleted successfully','apartment_mgt');
	?></div></p><?php
			
	}
}
?>
<!-- POP UP CODE -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
		    <div class="category_list"></div>
		</div>
    </div> 
</div>
<!-- END POP-UP CODE -->
<div class="panel-body panel-white"><!-- PANEL WHITE DIV -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--NAV-TABS-->
	  	<li class="<?php if($active_tab=='documentlist'){?>active<?php }?>">
			<a href="?apartment-dashboard=user&page=documents&tab=documentlist" class="tab <?php echo $active_tab == 'documentlist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php esc_html_e('Document List', 'apartment_mgt'); ?></a>
          </a>
        </li>
        <li class="<?php if($active_tab=='add_document'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['document_id']))
			{ ?>
			<a href="?apartment-dashboard=user&page=documents&tab=add_document&action=edit&document_id=<?php echo $_REQUEST['document_id'];?>" class="nav-tab <?php echo $active_tab == 'add_document' ? 'nav-tab-active' : ''; ?>">
             <i class="fa fa"></i> <?php esc_html_e('Edit Document', 'apartment_mgt'); ?></a>
			 <?php }
			else
			{ 
				if($user_access['add']=='1')
				{
				?>
					<a href="?apartment-dashboard=user&page=documents&tab=add_document" class="tab <?php echo $active_tab == 'add_document' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Document', 'apartment_mgt'); ?></a>
  	 <?php 		}
  			} ?>
	  
	    </li>
    </ul>
	<div class="tab-content">
	<?php if($active_tab == 'documentlist')//DOCUMENTLIST LIST
	{ ?>
		<script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			jQuery('#document_list').DataTable({
				"responsive": true,
				"order": [[ 0, "asc" ]],
				"aoColumns":[
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
    	<div class="panel-body"><!-- PANEL BODY DIV -->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
			    <table id="document_list" class="display" cellspacing="0" width="100%"><!-- DOCUMENT_LIST TABLE -->
					<thead>
						<tr>
							<th><?php esc_html_e('Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Unit name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Submitted By', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Submitted Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Unit name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Submitted By', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Submitted Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
						<?php 
						$user_id=get_current_user_id();
						//--- Document DATA FOR MEMBER  ------//
						if($obj_apartment->role=='member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$unit_name=get_user_meta($user_id,'unit_name',true);
								$building_id=get_user_meta($user_id,'building_id',true);
								$documentdata=$obj_document->amgt_get_units_all_documents_new($unit_name,$building_id);
							}
							else
							{
								$documentdata=$obj_document->amgt_get_all_documents();
							}
						} 
						//--- Document DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$documentdata=$obj_document->amgt_get_own_documents($user_id);
							}
							else
							{
								$documentdata=$obj_document->amgt_get_all_documents();
							}
						}
						//--- Document DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$documentdata=$obj_document->amgt_get_own_documents($user_id);
							}
							else
							{
								$documentdata=$obj_document->amgt_get_all_documents();
							}
						}
						//--- Document DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$documentdata=$obj_document->amgt_get_own_documents($user_id);
							}
							else
							{
								$documentdata=$obj_document->amgt_get_all_documents();
							}
						}
						
						if(!empty($documentdata))
						{
							foreach ($documentdata as $retrieved_data)
							{ ?>
								<tr>
									<td class="title"><!--TITLE--->
										<?php  echo $retrieved_data->doc_title;?></td>
									<td class="unit"><?php echo $retrieved_data->unit_name;?></td>
									<td class="member"><?php echo amgt_get_display_name($retrieved_data->member_id);?></td>
									<td class="date"><?php echo amgt_change_dateformat($retrieved_data->created_date);?></td>
									<td class="description"><?php echo wp_trim_words( $retrieved_data->description,5);?></td>
									<td class="action">
									<?php
									if($user_access['edit']=='1')
									{  ?>
										<a href="?apartment-dashboard=user&page=documents&tab=add_document&action=edit&document_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
									<?php
									}
									if($user_access['delete']=='1')
									{
									?>
										<a href="?apartment-dashboard=user&page=documents&tab=Activitylist&action=delete&document_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
										<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
									<?php
									}
									?>
									<?php if($retrieved_data->document_content!='') { ?>
									<a target="blank" href="<?php echo $retrieved_data->document_content; ?>" class="btn btn-default"> <i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
									<?php } ?>
									 </td>
								 </tr>
							<?php 
							}
						} ?>
					</tbody>
			    </table>
            </div><!---END TABLE-RESPONSIVE--->
        </div><!-- END PANEL BODY DIV -->
		<?php
	}
	if($active_tab == 'add_document')
	{ 
		require_once AMS_PLUGIN_DIR.'/template/documents/add_documents.php' ;
	}
?>
	</div>
</div><!-- END PANEL WHITE DIV -->
<?php ?>
<script>
	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n)
	{
		if(confirm("Are you sure want to delete this record?"))
		{
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}	
   	}
</script>