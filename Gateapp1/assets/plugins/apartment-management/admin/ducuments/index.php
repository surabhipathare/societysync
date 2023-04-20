<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'documentlist';
$obj_document=new Amgt_Document;
$obj_units=new Amgt_ResidentialUnit;
?>
<!-- POP UP CODE -->
<div class="popup-bg z_index_100000">
    <div class="overlay-content"><!--OVERLAY-CONTENT--->
		<div class="modal-content"><!--MODAL CONTENT--->
			<div class="category_list"> </div>
		</div>
    </div>    
</div>
<!-- END POP-UP CODE -->
<div class="page-inner min_height_1088">
	<div class="page-title"><!--PAGE TITLE--->
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>

<?php 
	if(isset($_POST['save_document']))//SAVE DOCUMENT	
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce($nonce, 'save_document_nonce'))
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
					wp_redirect ( admin_url().'admin.php?page=amgt-legal-documents&tab=documentlist&message=2');
				} 
			}
			else
			{
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

						$filename=amgt_load_documets($value,$value,$get_file_name);	
						$upload_docs_array[]=content_url().'/uploads/apartment_assets/'.$filename;
					} 
				}
				
				
				$result=$obj_document->amgt_add_document($_POST,$upload_docs_array);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-legal-documents&tab=documentlist&message=1');
				} 
			}
		}
	}
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE DOCUMENT
		{
			$result=$obj_document->amgt_delete_document($_REQUEST['document_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-legal-documents&tab=documentlist&message=3');
			}
		}
	if(isset($_REQUEST['message']))//MESSAGES
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{ ?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Document inserted successfully','apartment_mgt');
				?></p></div>
				<?php
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Document updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Document deleted successfully','apartment_mgt');
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
								<a href="?page=amgt-legal-documents&tab=documentlist" class="nav-tab <?php echo $active_tab == 'documentlist' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Document List', 'apartment_mgt'); ?></a>
								
								<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
								{ ?>
								<a href="?page=amgt-legal-documents&tab=add_document&action=edit&document_id=<?php echo $_REQUEST['document_id'];?>" class="nav-tab <?php echo $active_tab == 'add_document' ? 'nav-tab-active' : ''; ?>">
								<?php esc_html_e('Edit Document', 'apartment_mgt'); ?></a>  
								<?php 
								}
								else 
								{ ?>
									<a href="?page=amgt-legal-documents&tab=add_document" class="nav-tab <?php echo $active_tab == 'add_document' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Document', 'apartment_mgt'); ?></a>
								<?php  } ?>
							</h2>
				 <?php 
				//DOCUMENT LIST TAB
				if($active_tab == 'documentlist')
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
				<form name="activity_form" action="" method="post"><!--ACTIVITY_FORM-->
				
					<div class="panel-body"><!--PANEL BODY-->
						<div class="table-responsive"><!---TABLE-RESPONSIVE--->
					   <table id="document_list" class="display" cellspacing="0" width="100%"><!---DOCUMENT_LIST TABLE--->
						 <thead>
						<tr>
							<th><?php esc_html_e('Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Unit name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Submitted By', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Submitted Date', 'apartment_mgt' ) ;?></th>
							<!--<th><?php esc_html_e('Visible For', 'apartment_mgt' ) ;?></th>-->
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
							<!--<th><?php esc_html_e('Visible For', 'apartment_mgt' ) ;?></th>-->
							<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					   
					</tfoot>
			 
					<tbody>
					 <?php 
						$documentdata=$obj_document->amgt_get_all_documents();
					 if(!empty($documentdata))
					 {
						foreach ($documentdata as $retrieved_data){ ?>
						<tr>
							<td class="title"><a href="?page=amgt-legal-documents&tab=add_document&action=edit&document_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->doc_title);?></a></td>
							<td class="unit"><?php echo esc_html($retrieved_data->unit_name);?></td>
							<td class="member"><?php echo amgt_get_display_name($retrieved_data->member_id);?></td>
							
							<td class="from"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->created_date));?></td>
							
							<td class="description"><?php echo wp_trim_words( $retrieved_data->description,5);?></td>
							<td class="action">
							   <a href="?page=amgt-legal-documents&tab=add_document&action=edit&document_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
							 
								<a href="?page=amgt-legal-documents&tab=Activitylist&action=delete&document_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
								onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
								<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
								<?php 
								if($retrieved_data->document_content!='') {  ?>
								
								<a target="blank" href="<?php echo esc_attr($retrieved_data->document_content); ?>" class="btn btn-default"> <i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
								<?php } ?>
							
							</td>
						   
						</tr>
						<?php 
						} 
						
					} ?>
				 
					</tbody>
					
					</table><!---END DOCUMENT_LIST TABLE--->
					</div><!---END TABLE-RESPONSIVE--->
					</div><!--END PANEL BODY-->
				   
			</form>
<?php 
			}
			if($active_tab == 'add_document')
			{ 
				require_once AMS_PLUGIN_DIR.'/admin/ducuments/add-document.php';
			} ?>
		</div>
	</div>
	</div>
</div>