<?php 
error_reporting(0);
$active_tab = isset($_GET['tab'])?$_GET['tab']:'invoice-list';
$obj_units=new Amgt_ResidentialUnit;
$obj_account =new Amgt_Accounts;
?>
<!-- POP UP CODE -->
<div class="popup-bg">
    <div class="overlay-content_invoice">
		<div class="modal-content">
			<div class="category_list">	
			</div>
		</div>
    </div> 
 </div>
<!-- END POP-UP CODE -->
<!-- POP UP CODE -->
<div class="invoice-popup-bg">
    <div class="overlay-content_invoice">
		<div class="modal-content">
			 <div class="invoice_generate">	
			 </div>
		</div>
    </div> 
 </div>
<!-- END POP-UP CODE -->
<!-- POP UP CODE -->
<div class="bill-popup-bg">
    <div class="overlay-content_invoice">
		<div class="modal-content">
			<div class="invoice_data">
			</div>
		</div>
    </div>
</div>
<!-- END POP-UP CODE -->
<div class="page-inner min_height_1088">
	<div class="page-title"><!-- PAGE-TITLE -->
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" 
		class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div><!--END PAGE-TITLE -->
	<?php 
	if(isset($_POST['add_own_payment']))
	{
		//POP UP DATA SAVE
		$result=$obj_account->amgt_add_own_payment($_POST);			
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=amgt-accounts&tab=invoice-list&message=4');
		}
	}	
	//ADD_EXPENSE
	if(isset($_POST['add_expense']))		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'add_expense_nonce' ) )
		{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_account->amgt_add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=expense-list&message=6');
			}
		}
		else
		{
			$result=$obj_account->amgt_add_expense($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=expense-list&message=5');
			}
		}
	    }
	}	
	if(isset($_POST['add_charges_all_member']))//ADD_CHARGES_ALL_MEMBER		
	{	
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_account->amgt_save_charges_payment_all_member($_POST);
			
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=Recurring-list&message=2');
			}
		}
		else
		{
			$result=$obj_account->amgt_save_charges_payment_all_member($_POST);
			
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=Recurring-list&message=1');
			}
		}		
	}
	if(isset($_POST['add_charges_all_member_with_create_invoice']))		
	{
		set_time_limit(0);
        $nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'add_charges_all_member_with_create_invoice_nonce') )
		{
        //ADD_CHARGES_ALL_MEMBER_WITH_CREATE_INVOICE EDIT
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_account->amgt_save_charges_payment_all_member($_POST);
			
			$invoiceid=$result;
			global $wpdb;
			$table_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';
			$result_invoice_data=$wpdb->get_results("SELECT * FROM $table_amgt_created_invoice_list where charges_id=$invoiceid");
			
			if(empty($result_invoice_data))
			{
				//CREATE MEMBER INVOICE
					
				global $wpdb;
				$amgt_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';
				$amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
				$obj_account=new Amgt_Accounts;
				if($_POST['charge_cal']=='fix_charge')
				{
					$entry_value=$obj_account->amgt_get_entry_records($_POST);
				}
				elseif($_POST['charge_cal']=='measurement_charge')
				{
					$entry_value=$obj_account->amgt_get_entry_records_by_measurement($_POST);
				}
				
				if($_POST['select_serveice']=='all_member')
				{
					$member_data=amgt_get_all_member_data();
				}
				elseif($_POST['select_serveice']=='Building')	
				{
					$building_id=$_POST['building_id'];
					
					$member_data=amgt_get_all_member_data_by_building_id($building_id);
				}
				elseif($_POST['select_serveice']=='Unit Category')	
				{
					$building_id=$_POST['building_id'];
					$unit_id=$_POST['unit_cat_id'];
					$member_data=amgt_get_all_member_data_by_building_unit_id($building_id,$unit_id);
				}
				elseif($_POST['select_serveice']=='one_member')
				{
					$member_data=array();
					$member_data[]=$_POST['member_id'];						
				}					
				
				if(!empty($member_data))
				{
					require AMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';
					foreach ($member_data as $retrieved_data)
					{							
						$invoice_data['charges_id']=$invoiceid;
						$result_invoice_no=$wpdb->get_results("SELECT * FROM $amgt_amgt_created_invoice_list");						
					
						if(empty($result_invoice_no))
						{							
							$invoice_no='00001';
						}
						else
						{							
							$result_no=$wpdb->get_row("SELECT invoice_no FROM $amgt_amgt_created_invoice_list where id=(SELECT max(id) FROM $amgt_amgt_created_invoice_list)");
							
							$last_invoice_number=$result_no->invoice_no;
							$invoice_length=strlen($result_no->invoice_no);
							if($invoice_length == '9')
							{
								$invoice_no='00001';
							}
							else
							{								
								$invoice_no = str_pad($last_invoice_number+1, 5, 0, STR_PAD_LEFT);
							}	
						} 
						
						if($_POST['select_serveice']=='one_member')
						{	
							$invoice_data['member_id']=$retrieved_data;	
						}
						else
						{
							$invoice_data['member_id']=$retrieved_data->ID;	
						}	
						if(isset($_POST['charges_id']))	
						$invoice_data['charges_type_id']=$_POST['charges_id'];
						$invoice_data['invoice_no']=$invoice_no;
						if(isset($_POST['description']))	
						$invoice_data['description']=$_POST['description'];
						if(isset($_POST['discount_amount']))	
						$invoice_data['discount_amount']=(int)$_POST['discount_amount'];
						$invoice_data['charges_payment']=$entry_value;
						$invoice_data['paid_amount']=0;
						$invoice_data['payment_status']='Unpaid';
						
						$invoice_data['created_date']=date('Y-m-d');
						$invoice_data['amgt_charge_period']=$_POST['amgt_charge_period'];
						
						if($_POST['amgt_charge_period']=='1')
						{
							$start_date=date('Y-m-d');
							$invoice_data['start_date']=$start_date;
							$add_month_to_date=date('Y-m-d', strtotime("+1 months", strtotime($start_date)));	
							$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));	
							$invoice_data['end_date']=$end_date;
						}
						elseif($_POST['amgt_charge_period']=='3')
						{
							$start_date=date('Y-m-d');
							$invoice_data['start_date']=$start_date;
							$add_month_to_date=date('Y-m-d', strtotime("+3 months", strtotime($start_date)));	
							$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));	
							$invoice_data['end_date']=$end_date;
						}
						elseif($_POST['amgt_charge_period']=='12')
						{
							$start_date=date('Y-m-d');
							$invoice_data['start_date']=$start_date;
							$add_month_to_date=date('Y-m-d', strtotime("+12 months", strtotime($start_date)));	
							$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));	
							$invoice_data['end_date']=$end_date;
						}						
						
						if($_POST['charge_cal']=='fix_charge')
						{
							$income_amount=$_POST['income_amount'];
							$amount=0;
							
							foreach ($income_amount as $retrieved_data)
							{								
								$amount+=$retrieved_data;
							}	
							
							if(isset($_POST['discount_amount']))	
							{	
								$amount_after_discount=$amount-(int)$_POST['discount_amount'];
							}
							else
							{
								$amount_after_discount=$amount;
							}	
							$tax_entry=$_POST['tax_entry'];
							$tax_amount=0;
							
							foreach ($tax_entry as $tax_data)
							{	
								$tax_amount+=$amount_after_discount/100*$tax_data;
							}						
							$total_amount=$amount_after_discount+$tax_amount;
							$invoice_data['amount']=$amount_after_discount;							
							$invoice_data['tax_amount']=$tax_amount;							
							$invoice_data['total_amount']=$total_amount;
							$invoice_data['due_amount']=$total_amount;
						}
						//MEASUREMENT_CHARGE
						elseif($_POST['charge_cal']=='measurement_charge')
						{
							$income_amount=$_POST['income_amount'];
							$amount=0;
							$member_id=$invoice_data['member_id'];
							$unit=amgt_get_single_member_unit_size($member_id);
							
							foreach ($income_amount as $retrieved_data)
							{								
								$amount=$retrieved_data*$unit;
							}								
							
							if(isset($_POST['discount_amount']))	
							{	
								$amount_after_discount=$amount-(int)$_POST['discount_amount'];
							}
							else
							{
								$amount_after_discount=$amount;
							}
							$tax_entry=$_POST['tax_entry'];
							$tax_amount=0;
							
							foreach ($tax_entry as $tax_data)
							{	
								$tax_amount+=$amount_after_discount/100*$tax_data;
							}						
							$total_amount=$amount_after_discount+$tax_amount;
							$invoice_data['amount']=$amount_after_discount;							
							$invoice_data['tax_amount']=$tax_amount;							
							$invoice_data['total_amount']=$total_amount;
							$invoice_data['due_amount']=$total_amount;
						}
						$invoice_data['created_by']= get_current_user_id();
						$result=$wpdb->insert( $amgt_amgt_created_invoice_list, $invoice_data );
						global $wpdb;
						$user_invoiceid = $wpdb->insert_id;	
						//---------------- SEND  SMS ------------------//
						include_once(ABSPATH.'wp-admin/includes/plugin.php');
						if(is_plugin_active('sms-pack/sms-pack.php'))
						{
							if(!empty(get_user_meta($invoice_data['member_id'], 'phonecode',true))){ $phone_code=get_user_meta($invoice_data['member_id'], 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
											
							$user_number[] = $phone_code.get_user_meta($invoice_data['member_id'], 'mobile',true);
							$apartmentname=get_option('amgt_system_name');
							$message_content ="You have a new invoice from $apartmentname .";
							$current_sms_service 	= get_option( 'smgt_sms_service');
							$args = array();
							$args['mobile']=$user_number;
							$args['message_from']="INVOICE";
							$args['message']=$message_content;					
							if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
							{				
								$send = send_sms($args);							
							}
						}
						//---------NOTIFICATION SEND MAIL CODE---------------------
						$payment_link='<a href='.home_url().'?apartment-dashboard=user&page=accounts>Payment</a>';
						$retrieved_data=get_userdata($invoice_data['member_id']);
						$to = $retrieved_data->user_email; 
						$subject =get_option('wp_amgt_generate_invoice_subject');
						$apartmentname=get_option('amgt_system_name');
						$subject_search=array('{{apartment_name}}');
						$subject_replace=array($apartmentname);
						$subject=str_replace($subject_search,$subject_replace,$subject);
						$message_content=get_option('wp_amgt_generate_invoice_email_template');
						$search=array('{{member_name}}','{{apartment_name}}','{{Payment Link}}');
						$replace = array($retrieved_data->display_name,$apartmentname,$payment_link);
						$message_content = str_replace($search, $replace, $message_content);
						
						$enable_notofication=get_option('apartment_enable_notifications');
						if($enable_notofication=='yes')
						{
							amgt_send_invoice_generate_mail($to,$subject,$message_content,$user_invoiceid);
						}
						
						
					}	
				}	
				if($result)
				{						
					//INSERT START AND END DATE
					$whereid['id']=$invoiceid;
					$invoice_generate_date['invoice_start_date']=date('Y-m-d');
					if($_POST['amgt_charge_period']!='0')
					{
						$invoice_generate_date['invoice_end_date']=$end_date;
					}
					$result_update_generate_date=$wpdb->update( $amgt_generat_invoice,$invoice_generate_date ,$whereid);	
				}				
			}
			else
			{
				  //UPDATE MEMBER INVOICE
				
				global $wpdb;
				$table_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';
				$table_amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
				
				$obj_account=new Amgt_Accounts;
				if($_POST['charge_cal']=='fix_charge')
				{
					$entry_value=$obj_account->amgt_get_entry_records($_POST);
				}
				elseif($_POST['charge_cal']=='measurement_charge')
				{
					$entry_value=$obj_account->amgt_get_entry_records_by_measurement($_POST);
				}
				
				$result=$wpdb->get_results("SELECT * FROM $table_amgt_created_invoice_list where charges_id=$invoiceid");
				
				foreach ($result as $data)
				{
					$charges_id=$data->charges_id;			
					$paid_amount=$data->paid_amount;			
					
					$chargedata=$wpdb->get_row("SELECT * FROM $table_amgt_generat_invoice where id=$charges_id");
					
					$invoice_data['charges_type_id']=$chargedata->charges_type_id;			
					$invoice_data['description']=$chargedata->description;			
					$invoice_data['discount_amount']=$chargedata->discount_amount;			
					$invoice_data['charges_payment']=$entry_value;		
					if($chargedata->charges_calculate_by=='fix_charge')
					{
						$invoice_data['payment_status']='Unpaid';	
						
						$income_amount=$_POST['income_amount'];
						
						$amount=0;
						
						foreach ($income_amount as $retrieved_data)
						{								
							$amount+=$retrieved_data;
						}
						
						if(isset($_POST['discount_amount']))	
						{	
							$amount_after_discount=$amount-(int)$_POST['discount_amount'];
						}
						else
						{
							$amount_after_discount=$amount;
						}	
						if(isset($_POST['tax_entry']))	
						{
							$tax_entry=$_POST['tax_entry'];
						}
						$tax_amount=0;
						if(!empty($tax_entry))
						{
							foreach ($tax_entry as $tax_data)
							{	
								$tax_amount+=$amount_after_discount/100*$tax_data;
							}
						}							
						$total_amount=$amount_after_discount+$tax_amount;
						$invoice_data['amount']=$amount_after_discount;							
						$invoice_data['tax_amount']=$tax_amount;							
						$invoice_data['total_amount']=$total_amount;
						$invoice_data['due_amount']=$total_amount-$paid_amount;
						
					}
					elseif($chargedata->charges_calculate_by=='measurement_charge')
					{
						$income_amount=$_POST['income_amount'];
						$amount=0;
						$member_id=$data->member_id;
						$unit=amgt_get_single_member_unit_size($member_id);
						
						foreach ($income_amount as $retrieved_data)
						{								
							$amount=$retrieved_data*$unit;
						}
						
						if(isset($_POST['discount_amount']))	
						{	
							$amount_after_discount=$amount-(int)$_POST['discount_amount'];
						}
						else
						{
							$amount_after_discount=$amount;
						}
						
						$tax_entry=$_POST['tax_entry'];
						$tax_amount=0;
						
						foreach ($tax_entry as $tax_data)
						{	
							$tax_amount+=$amount_after_discount/100*$tax_data;
						}						
						$total_amount=$amount_after_discount+$tax_amount;
						$invoice_data['amount']=$amount_after_discount;							
						$invoice_data['tax_amount']=$tax_amount;							
						$invoice_data['total_amount']=$total_amount;
						$invoice_data['due_amount']=$total_amount-$paid_amount;
						if($paid_amount>=$invoice_data['total_amount'])
						{
							$invoice_data['payment_status']='FullyPaid';
						}
						elseif($invoice_data['due_amount']==$invoice_data['total_amount'])
						{
							$invoice_data['payment_status']='Unpaid';
						}
						else
						{
							$invoice_data['payment_status']='PartialPaid';
						} 
					}
					$whereid['id']=$data->id;
					$result=$wpdb->update( $table_amgt_created_invoice_list,$invoice_data ,$whereid);
				}
			}			
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=Recurring-list&message=2');
			}
		}
		else
		{
			$result=$obj_account->amgt_save_charges_payment_all_member($_POST);
			
			$invoiceid=$result;
			//CREATE MEMBER INVOICE				
			global $wpdb;
			$amgt_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';
			$amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
			$obj_account=new Amgt_Accounts;
			if($_POST['charge_cal']=='fix_charge')
			{
				$entry_value=$obj_account->amgt_get_entry_records($_POST);
			}
			elseif($_POST['charge_cal']=='measurement_charge')
			{
				$entry_value=$obj_account->amgt_get_entry_records_by_measurement($_POST);
			}
			
			if($_POST['select_serveice']=='all_member')
			{
				$member_data=amgt_get_all_member_data();
			}
			elseif($_POST['select_serveice']=='Building')	
			{
				$building_id=$_POST['building_id'];
				
				$member_data=amgt_get_all_member_data_by_building_id($building_id);
			}
			elseif($_POST['select_serveice']=='Unit Category')	
			{
				$building_id=$_POST['building_id'];
				$unit_id=$_POST['unit_cat_id'];
				$member_data=amgt_get_all_member_data_by_building_unit_id($building_id,$unit_id);
			}
			elseif($_POST['select_serveice']=='one_member')
			{
				$member_data=array();
				$member_data[]=$_POST['member_id'];						
			}
			
			if(!empty($member_data))
			{
				require AMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
				foreach ($member_data as $retrieved_data)
				{							
					$invoice_data['charges_id']=$invoiceid;
					
					$result_invoice_no=$wpdb->get_results("SELECT * FROM $amgt_amgt_created_invoice_list");						
					
					if(empty($result_invoice_no))
					{							
						$invoice_no='00001';
					}
					else
					{							
						$result_no=$wpdb->get_row("SELECT invoice_no FROM $amgt_amgt_created_invoice_list where id=(SELECT max(id) FROM $amgt_amgt_created_invoice_list)");
						
						$last_invoice_number=$result_no->invoice_no;
						$invoice_length=strlen($result_no->invoice_no);
						if($invoice_length == '9')
						{
							$invoice_no='00001';
						}
						else
						{
							$invoice_no = str_pad($last_invoice_number+1, 5, 0, STR_PAD_LEFT);
						}		
					} 
					if($_POST['select_serveice']=='one_member')
					{	
						$invoice_data['member_id']=$retrieved_data;	
					}
					else
					{
						$invoice_data['member_id']=$retrieved_data->ID;	
					}							
					if(isset($_POST['charges_id']))	
					$invoice_data['charges_type_id']=$_POST['charges_id'];
					$invoice_data['invoice_no']=$invoice_no;
					if(isset($_POST['description']))	
					$invoice_data['description']=$_POST['description'];
					if(isset($_POST['discount_amount']))	
					$invoice_data['discount_amount']=(int)$_POST['discount_amount'];
					$invoice_data['charges_payment']=$entry_value;	
					$invoice_data['paid_amount']=0;
					$invoice_data['payment_status']='Unpaid';
					
					$invoice_data['created_date']=date('Y-m-d');
					$invoice_data['amgt_charge_period']=$_POST['amgt_charge_period'];
					
					if($_POST['amgt_charge_period']=='1')
					{
						$start_date=date('Y-m-d');
						$invoice_data['start_date']=$start_date;
						$add_month_to_date=date('Y-m-d', strtotime("+1 months", strtotime($start_date)));	
						$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));	
						$invoice_data['end_date']=$end_date;
					}
					elseif($_POST['amgt_charge_period']=='3')
					{
						$start_date=date('Y-m-d');
						$invoice_data['start_date']=$start_date;
						$add_month_to_date=date('Y-m-d', strtotime("+3 months", strtotime($start_date)));	
						$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));	
						$invoice_data['end_date']=$end_date;
					}
					elseif($_POST['amgt_charge_period']=='12')
					{
						$start_date=date('Y-m-d');
						$invoice_data['start_date']=$start_date;
						$add_month_to_date=date('Y-m-d', strtotime("+12 months", strtotime($start_date)));	
						$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));	
						$invoice_data['end_date']=$end_date;
					}						
					
					
					if($_POST['charge_cal']=='fix_charge')
					{
						$income_amount=$_POST['income_amount'];
						$amount=0;
						
						foreach ($income_amount as $retrieved_data)
						{								
							$amount+=$retrieved_data;
						}
					
						if(isset($_POST['discount_amount']))	
						{	
							$amount_after_discount=$amount-(int)$_POST['discount_amount'];
						}
						else
						{
							$amount_after_discount=$amount;
						}	
						
						$tax_entry=$_POST['tax_entry'];
						$tax_amount=0;
						
						foreach ($tax_entry as $tax_data)
						{	
							$tax_amount+=$amount_after_discount/100*$tax_data;
						}						
						$total_amount=$amount_after_discount+$tax_amount;
						$invoice_data['amount']=$amount_after_discount;							
						$invoice_data['tax_amount']=$tax_amount;							
						$invoice_data['total_amount']=$total_amount;
						$invoice_data['due_amount']=$total_amount;
					}
					elseif($_POST['charge_cal']=='measurement_charge')
					{
						$income_amount=$_POST['income_amount'];
						$amount=0;
						$member_id=$invoice_data['member_id'];
						$unit=amgt_get_single_member_unit_size($member_id);
						
						foreach ($income_amount as $retrieved_data)
						{								
							$amount=$retrieved_data*$unit;
						}
						
						if(isset($_POST['discount_amount']))	
						{	
							$amount_after_discount=$amount-(int)$_POST['discount_amount'];
						}
						else
						{
							$amount_after_discount=$amount;
						}
						
						$tax_entry=$_POST['tax_entry'];
						$tax_amount=0;
						
						foreach ($tax_entry as $tax_data)
						{	
							$tax_amount+=$amount_after_discount/100*$tax_data;
						}						
						$total_amount=$amount_after_discount+$tax_amount;
						$invoice_data['amount']=$amount_after_discount;							
						$invoice_data['tax_amount']=$tax_amount;							
						$invoice_data['total_amount']=$total_amount;
						$invoice_data['due_amount']=$total_amount;
					}
					
					$result=$wpdb->insert( $amgt_amgt_created_invoice_list, $invoice_data );					
					
					global $wpdb;
					$user_invoiceid = $wpdb->insert_id;
					//---------------- SEND  SMS ------------------//
					include_once(ABSPATH.'wp-admin/includes/plugin.php');
					if(is_plugin_active('sms-pack/sms-pack.php'))
					{
						if(!empty(get_user_meta($invoice_data['member_id'], 'phonecode',true))){ $phone_code=get_user_meta($invoice_data['member_id'], 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
										
						$user_number[] = $phone_code.get_user_meta($invoice_data['member_id'], 'mobile',true);
						$apartmentname=get_option('amgt_system_name');
						$message_content ="You have a new invoice from $apartmentname .";
						
						$current_sms_service 	= get_option( 'smgt_sms_service');
						$args = array();
						$args['mobile']=$user_number;
						$args['message_from']="INVOICE";
						$args['message']=$message_content;					
						if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
						{				
							$send = send_sms($args);							
						}
					}
				
					//---------NOTIFICATION SEND MAIL CODE---------------------
					$payment_link='<a href='.home_url().'?apartment-dashboard=user&page=accounts>Payment</a>';
					$retrieved_data=get_userdata($invoice_data['member_id']);
					$to=array();
					$to[] = $retrieved_data->user_email; 
					$subject =get_option('wp_amgt_generate_invoice_subject');
					$apartmentname=get_option('amgt_system_name');
					$subject_search=array('{{apartment_name}}');
					$subject_replace=array($apartmentname);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$message_content=get_option('wp_amgt_generate_invoice_email_template');
					$search=array('{{member_name}}','{{apartment_name}}','{{Payment Link}}');
					$replace = array($retrieved_data->display_name,$apartmentname,$payment_link);
					$message_content = str_replace($search, $replace, $message_content);
					$enable_notofication=get_option('apartment_enable_notifications');
					if($enable_notofication=='yes')
					{
						amgt_send_invoice_generate_mail($to,$subject,$message_content,$user_invoiceid);	
					}
				}		 
			}
			if($result)
			{
				//INSERT START AND END DATE
				$whereid['id']=$invoiceid;
				$invoice_generate_date['invoice_start_date']=date('Y-m-d');
				if($_POST['amgt_charge_period']!='0')
				{
					$invoice_generate_date['invoice_end_date']=$end_date;
				}
				$result_update_generate_date=$wpdb->update( $amgt_generat_invoice,$invoice_generate_date ,$whereid); 
				
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=Recurring-list&message=1');
			}
		}		
	}
	}
	//INVOICE DELETE\
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		if(isset($_REQUEST['memebr_invoice_id']))
		{
			$result=$obj_account->amgt_delete_invoice($_REQUEST['memebr_invoice_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=invoice-list&message=3');
			}
		}
		if(isset($_REQUEST['expense_id']))
		{
			$result=$obj_account->amgt_delete_expense($_REQUEST['expense_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=expense-list&message=3');
			}
		}
		if(isset($_REQUEST['payment_id']))
		{
			$result=$obj_account->amgt_delete_payment($_REQUEST['payment_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=payment-list&message=3');
			}
		}
		
		if(isset($_REQUEST['pay_charges_id']))
		{
			$result=$obj_account->amgt_delete_paid_charges($_REQUEST['pay_charges_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=Recurring-list&message=3');
			}
		}
		if(isset($_REQUEST['recuring_charges_id']))
		{
			
			$result=$obj_account->amgt_delete_recuring_charges($_REQUEST['recuring_charges_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-accounts&tab=Recurring-list&message=3');
			}
		}				
	}
	 //MESSAGES
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Charge Save successfully','apartment_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Charge updated successfully.",'apartment_mgt');
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
				esc_html_e('Payment Successfully','apartment_mgt');
			?></div></p>
		<?php				
		}
	if($message == 5)
		{?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Expense Save successfully','apartment_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 6)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Expense updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}		
	}
	 //END MEASSAGES
	?>
  	
	<div id="main-wrapper"><!--MAIN-WRAPPER-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL-WHITE-->
					<div class="panel-body"><!--PANEL-BODY-->
					    <!--TABS--->
						<h2 class="nav-tab-wrapper">	 
							<a href="?page=amgt-accounts&tab=invoice-list" 
								class="nav-tab <?php echo $active_tab == 'invoice-list' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Invoice List', 'apartment_mgt'); ?>
							</a>		  
							<a href="?page=amgt-accounts&tab=Recurring-list" 
								class="nav-tab <?php echo $active_tab == 'Recurring-list' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Charges List', 'apartment_mgt'); ?>
							</a>
							 <?php 
							if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' &&  $_REQUEST['tab'] == 'add-Recurring-Charges')
							{ ?>
								<a href="?page=amgt-accounts&tab=add-Recurring-Charges&action=edit&pay_charges_id=<?php echo $_REQUEST['pay_charges_id'];?>" class="nav-tab <?php echo $active_tab == 'add-Recurring-Charges' ? 'nav-tab-active' : ''; ?>">
									<?php echo esc_html__('Edit Charges', 'apartment_mgt'); ?>
								</a>
							<?php 
							}	
							else
							{ ?>
								<a href="?page=amgt-accounts&tab=add-Recurring-Charges"	class="nav-tab <?php echo $active_tab == 'add-Recurring-Charges' ? 'nav-tab-active' : ''; ?>">
									<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Charges', 'apartment_mgt'); ?>
								</a>
							<?php 
							}
							?>		  
							<a href="?page=amgt-accounts&tab=expense-list" 
								class="nav-tab <?php echo $active_tab == 'expense-list' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Expense List', 'apartment_mgt'); ?>
							</a>
							<?php  
							if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' &&  $_REQUEST['tab'] == 'add-expense')
							{ ?>
								<a href="?page=amgt-accounts&tab=add-expense&action=edit&expense_id=<?php echo $_REQUEST['expense_id'];?>" class="nav-tab <?php echo $active_tab == 'add-expense' ? 'nav-tab-active' : ''; ?>">
									<?php echo esc_html__('Edit Expense', 'apartment_mgt'); ?>
								</a>
							<?php 
							}	
							else
							{ ?>
								<a href="?page=amgt-accounts&tab=add-expense" 
									class="nav-tab <?php echo $active_tab == 'add-expense' ? 'nav-tab-active' : ''; ?>">
									<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Expense', 'apartment_mgt'); ?>
								</a>
							<?php 
							}
							?>	
						</h2><!-----END TABES----->
						<!--INVOICE-LIST-->
						<?php
                         //INVOICE LIST TAB						
						if($active_tab == 'invoice-list')
						{ ?>	
							<script type="text/javascript">
								$(document).ready(function() {
									"use strict";
									jQuery('#invoice_list').DataTable({
										"responsive":true,
										"order": [[ 1, "asc" ]],
										"aoColumns":[
													  {"bSortable": true},
													  {"bSortable": true},
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
							<form name="member_form" action="" method="post"><!----MEMBER FORM---->    
								<div class="panel-body"><!--PANEL BODY-->
									<div class="table-responsive">
										<table id="invoice_list" class="display" cellspacing="0" width="100%">
											<thead>
												<tr>
												<th><?php esc_html_e('Invoice Number', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Member Name', 'apartment_mgt' ) ;?></th>				
												<th><?php esc_html_e('Charge Type', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Charge Calculate By', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Total Amount', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Due Amount', 'apartment_mgt' ) ;?></th>				
												<th><?php esc_html_e('Paid Amount', 'apartment_mgt' ) ;?></th>				
												<th><?php esc_html_e('Payment Status', 'apartment_mgt' ) ;?></th>			
												<th><?php esc_html_e('From Date', 'apartment_mgt' ) ;?></th>	
												<th><?php esc_html_e('To Date', 'apartment_mgt' ) ;?></th>	
												<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
												<th><?php esc_html_e('Invoice Number', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Member Name', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Charge Type', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Charge Calculate By', 'apartment_mgt' ) ;?></th>				
												<th><?php esc_html_e('Total Amount', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Due Amount', 'apartment_mgt' ) ;?></th>				
												<th><?php esc_html_e('Paid Amount', 'apartment_mgt' ) ;?></th>				
												<th><?php esc_html_e('Payment Status', 'apartment_mgt' ) ;?></th>			
												<th><?php esc_html_e('From Date', 'apartment_mgt' ) ;?></th>	
												<th><?php esc_html_e('To Date', 'apartment_mgt' ) ;?></th>	
												<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
												</tr>
											</tfoot> 
											<tbody>
											 <?php 
											 $invoice_data= $obj_account->amgt_get_all_invoice();
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
													  <td class="income_type"><?php echo esc_html(get_option('invoice_prefix').''.$invoice_no);?></td>
													  <td class="income_type"><?php echo esc_html($userdata->display_name);?></td>
													  <td class="income_type"><?php _e("$charge_type","apartment_mgt");?></td>
													  <td class="income_type"><?php _e("$charge_cal_by","apartment_mgt");?></td>
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
															$total_amount=round($after_discount_amount);
															$due_amount='0';
															$paid_amount=round($after_discount_amount);
															$payment_status=$retrieved_data->payment_status;
														}
														else
														{													  
															$invoice_length=strlen($retrieved_data->invoice_no);
															if($invoice_length == '9')
															{
																$total_amount=round($retrieved_data->invoice_amount);
																$due_amount=round($retrieved_data->invoice_amount) - round($retrieved_data->paid_amount);
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
																$total_amount=round($retrieved_data->total_amount);
																$due_amount=round($retrieved_data->due_amount);
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
													 <td class="building_id"><?php   echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($total_amount);?></td>
													 
													 <td class="building_id"><?php   echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo abs($due_amount);?></td>
													  <td class="building_id"><?php  echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));  echo round($paid_amount);?></td>
													  <td class="building_id"><span class="btn btn-success btn-xs"><?php _e("$payment_status","apartment_mgt");?></span></td>

                                                    <?php if($retrieved_data->start_date =='')
													{ ?>
														<td class="building_id"><?php  if($retrieved_data->amgt_charge_period=='0'){echo '-'; }else{ echo '-'; }?>
														</td>
													<?php
													}
													else
													{ ?>
														<td class="building_id"><?php  if($retrieved_data->amgt_charge_period=='0'){echo '-'; }else{ echo date(amgt_date_formate(),strtotime($retrieved_data->start_date)); }?>
														</td>
													<?php 
													} 
													 if($retrieved_data->end_date =='')
													{ ?>
														<td class="building_id"><?php  if($retrieved_data->amgt_charge_period=='0'){echo '-'; }else{ echo '-'; }?>
														</td>
													<?php
													}
													else
													{ ?>
														<td class="building_id"><?php  if($retrieved_data->amgt_charge_period=='0'){echo '-'; }else{ echo date(amgt_date_formate(),strtotime($retrieved_data->end_date)); }?>
														</td>
													<?php 
													} 
													?>	
													<td class="action">
													<a href="?page=amgt-accounts&tab=invoice-list&action=delete&memebr_invoice_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
							                        <?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
													<?php	
													if($due_amount>'0')
													{
													?>
													 <a href="#"  class="show-payment-popup btn btn-success" invoice_id="<?php echo esc_attr($retrieved_data->id); ?>" member_id="<?php echo esc_attr($member_id);?>" view_type="payment" due_amount="<?php echo esc_attr($due_amount);?>"><?php esc_html_e('Add Income','apartment_mgt');?></a>
													 <?php
													}
													 ?>
													<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="invoice">
													<i class="fa fa-eye"></i> <?php esc_html_e('View Invoice', 'apartment_mgt');?></a>
													<?php
													if(!empty(round($retrieved_data->paid_amount)))
													{
														
													    if($invoice_length != '9')
														{ ?>
															<a href="?page=payment_receipt&print=print&invoice_id=<?php echo esc_attr($retrieved_data->id); ?>&member_id=<?php echo esc_attr($retrieved_data->member_id); ?>&invoice_type=payment_receipt" target="_blank" class="btn btn-info"> <?php esc_html_e('Print Payment Receipt', 'apartment_mgt' ) ;?>
															</a> 
													    <?php
													    }
													}
													?>
													
													</td>               
												</tr>
												<?php 
												}			
											}
											?>     
											</tbody>        
										</table>
									</div>
								</div> <!--END PANEL BODY-->      
							</form><!----END MEMBER FORM---->  
						<?php 
						}
                      					
						if($active_tab == 'expense-list')
						{	
							require_once AMS_PLUGIN_DIR.'/admin/accounts/expense-list.php';
						}
						
						if($active_tab == 'add-expense')
						{	
							require_once AMS_PLUGIN_DIR.'/admin/accounts/add-expense.php';
						}
                    					
						if($active_tab == 'add-Recurring-Charges')
						{	
							require_once AMS_PLUGIN_DIR.'/admin/accounts/add-charges-all-member.php';
						}
						
						if($active_tab == 'Recurring-list')
						{	
							require_once AMS_PLUGIN_DIR.'/admin/accounts/recuring_charg_list.php';
						}
						?>
					</div><!--END PANEL-BODY-->			
				</div><!--END PANEL-WHITE-->
			</div>
		</div>
	</div><!--END MAIN-WRAPPER-->
</div>