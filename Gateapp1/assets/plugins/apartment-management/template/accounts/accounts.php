<?php
error_reporting(0);
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
$obj_account =new Amgt_Accounts;
$obj_units=new Amgt_ResidentialUnit;

$active_tab = isset($_GET['tab'])?$_GET['tab']:'invoice-list';

if(isset($_POST['add_own_payment']))
{
	//POP UP DATA SAVE IN PAYMENT HISTORY
	if($_POST['payment_method'] == 'Paypal')
	{				
		require_once AMS_PLUGIN_DIR. '/lib/paypal/paypal_process.php';				
	}
	elseif($_POST['payment_method'] == 'Stripe')
	{
		require_once PM_PLUGIN_DIR. '/lib/stripe/index.php';			
	}
	elseif($_POST['payment_method'] == 'Instamojo')
	{
		require_once PM_PLUGIN_DIR. '/lib/instamojo/instamojo.php';
	}
	elseif($_POST['payment_method'] == 'PayUMony')
	{
		require_once PM_PLUGIN_DIR. '/lib/OpenPayU/payuform.php';			
	}
	elseif($_REQUEST['payment_method'] == '2CheckOut'){				
		require_once PM_PLUGIN_DIR. '/lib/2checkout/index.php';
	}
	elseif($_POST['payment_method'] == 'Skrill')
	{		
		require_once PM_PLUGIN_DIR. '/lib/skrill/skrill.php';
	}
	elseif($_POST['payment_method'] == 'Paystack')
	{
		require_once PM_PLUGIN_DIR. '/lib/paystack/paystack.php';
	}
	elseif($_POST['payment_method'] == 'paytm')
	{
		require_once PM_PLUGIN_DIR. '/lib/PaytmKit/index.php';
	}
	else
	{
		$result=$obj_account->amgt_add_own_payment($_POST);			
		if($result)
		{ 
			wp_redirect ( home_url() . '?apartment-dashboard=user&page=accounts&tab=invoice-list&action=success');
		}	
	}
}
//------------------ INSTAMOJO PAYMENT ----------//
if(isset($_REQUEST['amount']) && (isset($_REQUEST['donet_pay_id'])))
{
	$paymentdata['invoice_id']=$_REQUEST['donet_pay_id'];
	$paymentdata['amount']=$_REQUEST['amount'];
	$paymentdata['payment_method']='Instamojo';	
	$paymentdata['member_id']=get_current_user_id();
	$paymentdata['created_by']=get_current_user_id();
	 
	$obj_account =new Amgt_Accounts;
	$result = $obj_account->amgt_add_own_payment($paymentdata);	
	
	if($result)
	{ 
		wp_redirect ( home_url() . '?apartment-dashboard=user&page=accounts&tab=invoice-list&action=success');
	}		
}
//------------------ SKRILL PAYMENT ------------//
if(isset($_REQUEST['fees_pay_id']) && (isset($_REQUEST['amount'])))
{
	$paymentdata['invoice_id']=$_REQUEST['donetion_type'];
	$paymentdata['amount']=$_REQUEST['amount'];
	$paymentdata['payment_method']='Skrill';	
	$paymentdata['member_id']=get_current_user_id();
	$paymentdata['created_by']=get_current_user_id();
	 
	$obj_account =new Amgt_Accounts;
	$result = $obj_account->amgt_add_own_payment($paymentdata);	
	
	if($result)
	{ 
		wp_redirect ( home_url() . '?apartment-dashboard=user&page=accounts&tab=invoice-list&action=success');
	}		
}
//------------PAYSTACK SUCCESS ----------------------//
$reference='';
$reference = isset($_GET['reference']) ? $_GET['reference'] : '';
if($reference)
{
      $paystack_secret_key=get_option('paystack_secret_key');
	  $curl = curl_init();
	  curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_HTTPHEADER => [
		"accept: application/json",
		"authorization: Bearer $paystack_secret_key",
		"cache-control: no-cache"
	  ],
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	if($err)
	{
		// there was an error contacting the Paystack API
	  die('Curl returned error: ' . $err);
	}
	$tranx = json_decode($response);
	if(!$tranx->status)
	{
	  // there was an error from the API
	  die('API returned error: ' . $tranx->message);
	}
	if('success' == $tranx->data->status)
	{
		$paymentdata['invoice_id']=$tranx->data->metadata->custom_fields->donetion_type;
		$paymentdata['amount']=$tranx->data->amount / 100;
		$paymentdata['payment_method']='Paystack';	
		$paymentdata['member_id']=get_current_user_id();
		$paymentdata['created_by']=get_current_user_id();
		 
		$obj_account =new Amgt_Accounts;
		$result = $obj_account->amgt_add_own_payment($paymentdata);	
		
		if($result)
		{ 
			wp_redirect ( home_url() . '?apartment-dashboard=user&page=accounts&tab=invoice-list&action=success');
		}		
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'ipn')
{		
	$trasaction_id  = $_POST["txn_id"];
	$custom_array = explode("_",$_POST['custom']);
	$paymentdata['invoice_id']=$custom_array[1];

	$paymentdata['amount']=$_POST['mc_gross_1'];
	$paymentdata['payment_method']='paypal';	
	$paymentdata['transaction_id']=$trasaction_id;
	$paymentdata['member_id']=$custom_array[0];
	$paymentdata['inv_amount ']=$custom_array[2];
	
	$obj_account->amgt_save_invoice_payment($paymentdata);	
}
if(isset($_POST['add_expense']))		
{		
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')//EDIT ACCOUNTS
	{
		$result=$obj_account->amgt_add_expense($_POST);
		if($result)
		{
			wp_redirect (  home_url().'?apartment-dashboard=user&page=accounts&tab=expense-list&message=6');
		}
	}
	else
	{
		$result=$obj_account->amgt_add_expense($_POST);
		if($result)
		{
			wp_redirect (  home_url().'?apartment-dashboard=user&page=accounts&tab=expense-list&message=5');
		}
	}
}
if(isset($_POST['add_charges']))//ADD CHARGES		
{	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
		$result=$obj_account->amgt_save_charges_payment($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=charges-list&message=2');
		}
	}
	else
	{
		$result=$obj_account->amgt_save_charges_payment($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=charges-list&message=1');
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
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=charges-list&message=2');
		}
	}
	else
	{
		$result=$obj_account->amgt_save_charges_payment_all_member($_POST);
		
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=charges-list&message=1');
		}
	}		
}
if(isset($_POST['add_charges_all_member_with_create_invoice']))	//ADD_CHARGES_ALL_MEMBER_WITH_CREATE_INVOICE	
{
    set_time_limit(0);	
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
					$invoice_data['created_by']=get_current_user_id();
					$result=$wpdb->insert( $amgt_amgt_created_invoice_list, $invoice_data );
					global $wpdb;
					$user_invoiceid = $wpdb->insert_id;	
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
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=charges-list&message=2');
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
				$invoice_data['created_by']=get_current_user_id();
				$result=$wpdb->insert( $amgt_amgt_created_invoice_list, $invoice_data );
				
			
				
				global $wpdb;
				$user_invoiceid = $wpdb->insert_id;
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
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=charges-list&message=1');
		}
	}		
}

if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	if(isset($_REQUEST['invoice_id']))
	{
		$result=$obj_account->amgt_delete_invoice($_REQUEST['invoice_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=invoice-list&message=3');
		}
	}
	if(isset($_REQUEST['expense_id']))
	{
		$result=$obj_account->amgt_delete_expense($_REQUEST['expense_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=expense-list&message=3');
		}
	}
	if(isset($_REQUEST['payment_id']))
	{
		$result=$obj_account->amgt_delete_payment($_REQUEST['payment_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=payment-list&message=3');
		}
	}
	
	if(isset($_REQUEST['pay_charges_id']))
	{
		$result=$obj_account->amgt_delete_paid_charges($_REQUEST['pay_charges_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accounts&tab=charges-list&message=3');
		}
	}
}	
	
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
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='success')
{ 
	?>
	<div id="message" class="updated below-h2 notice is-dismissible">
		<p>
		<?php 
			esc_html_e('Payment successfully','apartment_mgt');
		?>
		</p>
	</div>
<?php
}	
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='cancel')
{?>
	<div id="message" class="updated below-h2 notice is-dismissible">
		<p>
		<?php 
			esc_html_e('Payment Cancel','apartment_mgt');
		?>
		</p>
	</div>
	<?php
}?>
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
    <div class="overlay-content_invoice account_popup">
		<div class="modal-content account_popup_margin">
		<div class="invoice_data">
		 </div>
		</div>
    </div>
</div>
<!-- END POP-UP CODE -->
<div class="panel-body panel-white"><!-- PANEL WHITE-->
	 <ul class="nav nav-tabs panel_tabs" role="tablist">	
		 <li class="<?php if($active_tab=='invoice-list'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=accounts&tab=invoice-list" class="tab <?php echo $active_tab == 'invoice-list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Invoice List', 'apartment_mgt'); ?></a>
			  </a>
		 </li>
		<?php
		if($obj_apartment->role=='accountant' || $obj_apartment->role=='staff_member')
		{ 
		?>	
			<li class="<?php if($active_tab=='charges-list'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=accounts&tab=charges-list" class="tab <?php echo $active_tab == 'charges-list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Charges List', 'apartment_mgt'); ?></a>
			  </a>
			</li>
			<li class="<?php if($active_tab=='add-charges'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['pay_charges_id']))
				{ ?>
				<a href="?apartment-dashboard=user&page=accounts&tab=add-charges&action=edit&pay_charges_id=<?php echo $_REQUEST['pay_charges_id'];?>" class="nav-tab <?php echo $active_tab == 'add-payment' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php esc_html_e('Edit Charges', 'apartment_mgt'); ?></a>
				 <?php }
				else
				{ 
					if($user_access['add']=='1')
					{ ?>
						<a href="?apartment-dashboard=user&page=accounts&tab=add-charges" class="tab margin_top_10_res <?php echo $active_tab == 'add-charges' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Charges', 'apartment_mgt'); ?></a>
		  <?php		 } 
				}?>
		  
			</li>		
			<li class="<?php if($active_tab=='expense-list'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=accounts&tab=expense-list" class="tab margin_top_10_res <?php echo $active_tab == 'expense-list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Expense List', 'apartment_mgt'); ?></a>
			  </a>
			</li>
			<li class="<?php if($active_tab=='add-expense'){?>active<?php }?>">
			  <?php 
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['expense_id']))
				{ ?>
					<a href="?apartment-dashboard=user&page=accounts&tab=add-expense&action=edit&expense_id=<?php echo $_REQUEST['expense_id'];?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'visitor-checkin' ? 'nav-tab-active' : ''; ?>">
					<i class="fa fa"></i> <?php esc_html_e('Edit Expense', 'apartment_mgt'); ?></a>
				 <?php
				}
				else
				{ 
					if($user_access['add']=='1')
					{ ?>
						<a href="?apartment-dashboard=user&page=accounts&tab=add-expense" class="tab margin_top_10_res <?php echo $active_tab == 'add-expense' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Expense', 'apartment_mgt'); ?></a>
		  <?php 	} 
				}	?>	  	
			</li>
		<?php
		} 
		?>
	</ul>
	<div class="tab-content">
	  <!-- INVOICE LIST TAB-->
	<?php if($active_tab == 'invoice-list')
		//INVOICE-LIST
	{ 
	?>
		<script type="text/javascript">
			$(document).ready(function()
			{
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
			});
		</script>	
    	<div class="panel-body"><!-- PANEL BODY -->
        	<div class="table-responsive"><!---TABLE RESPONSIVE--->
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
						$user_id=get_current_user_id();
						//--- INVOICE DATA FOR MEMBER  ------//
						if($obj_apartment->role=='member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$invoice_data= $obj_account->amgt_get_member_all_invoice();		
							}
							else
							{
								$invoice_data= $obj_account->amgt_get_all_invoice();		
							}
						} 
						//--- INVOICE DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$invoice_data= $obj_account->amgt_get_own_invoice($user_id);	
							}
							else
							{
								$invoice_data= $obj_account->amgt_get_all_invoice();	
							}
						}
						//--- INVOICE DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$invoice_data= $obj_account->amgt_get_own_invoice($user_id);	
							}
							else
							{
								$invoice_data= $obj_account->amgt_get_all_invoice();	
							}
						}
						//--- INVOICE DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							
							if($own_data == '1')
							{ 
								$invoice_data= $obj_account->amgt_get_own_invoice($user_id);	
							}
							else
							{
								$invoice_data= $obj_account->amgt_get_all_invoice();
															
							}
						}
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
										$charge_cal_by=esc_html__('Fix Charges', 'apartment_mgt' );
									}
									else
									{
										
										$charge_cal_by=esc_html__('Measurement Charges', 'apartment_mgt' );
									}
									if($retrieved_data->charges_type_id=='0')
									{
										
										$charge_cal_by=esc_html__('Maintenance Charges', 'apartment_mgt' );
									}
									else
									{
										$charge_type=get_the_title($retrieved_data->charges_type_id);
									}
								}
								
								$userdata=get_userdata($member_id);
									
								?>
							<tr>	
								  <td class="income_type"><?php echo esc_html(get_option('invoice_prefix').''.$retrieved_data->invoice_no);?></td>
								  <td class="income_type"><?php echo esc_html($userdata->display_name);?></td>
								  <td class="income_type"><?php echo esc_html($charge_type);?></td>
								  <td class="income_type"><?php echo esc_html($charge_cal_by);?></td>
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
											//$payment_status=$retrieved_data->payment_status;
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
										$paid_amount=$retrieved_data->paid_amount;
									}
									?>
								 <td class="building_id"><?php   echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($total_amount);?></td>
								 <td class="building_id"><?php   echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round(abs($due_amount));?></td>
								  <td class="building_id"><?php  echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));  echo round($paid_amount);?></td>
								  <td class="building_id"><span class="btn btn-success btn-xs"><?php  echo $payment_status;?></span></td>				
								   <?php 
								    if($retrieved_data->start_date =='')
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
								<?php 		 
								if($obj_apartment->role=='member' || $obj_apartment->role=='accountant')
								{									
									if($due_amount>'0')
									{									
									?>					
										<a href="#"  class="show-payment-popup btn btn-success" invoice_id="<?php echo esc_attr($retrieved_data->id); ?>" member_id="<?php echo esc_attr($member_id);?>" view_type="payment" due_amount="<?php echo round($due_amount);?>"><?php esc_html_e('Add Income','apartment_mgt');?></a>
									<?php
									}
								}
								?>	
								<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->id; ?>" invoice_type="invoice">
								<i class="fa fa-eye"></i> <?php esc_html_e('View Invoice', 'apartment_mgt');?></a>
								<?php
								if(!empty($retrieved_data->paid_amount))
								{
									 if($invoice_length != '9')
									 { 
									?>
										<a href="?page=payment_receipt&print=print&invoice_id=<?php echo esc_attr($retrieved_data->id); ?>&member_id=<?php echo esc_attr($retrieved_data->member_id); ?>&invoice_type=payment_receipt" target="_blank" class="btn btn-info"> <?php esc_html_e('Print Payment Receipt', 'apartment_mgt' ) ;?></a> 
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
			</div><!---END TABLE RESPONSIVE--->
        </div><!-- END PANEL BODY -->
		<?php 
	}
	//EXPENSE-LIST TAB
	if($active_tab == 'expense-list')
	{ ?>
		<script type="text/javascript">
			$(document).ready(function() {
				"use strict";
				jQuery('#expence_list').DataTable({
					"order": [[ 0, "asc" ]],
					"aoColumns":[
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": false}],
								  language:<?php echo amgt_datatable_multi_language();?>
					});
			} );
		</script>		
		<div class="panel-body"><!--PANEL-BODY--->
			<div class="table-responsive"><!---TABLE-RESPONSIVE--->
				<table id="expence_list" class="display" cellspacing="0" width="100%"><!---EXPENCE_LIST--->
					<thead>
						<tr>
							<th><?php esc_html_e('Expense type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Vendor Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Amount', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Payment Date', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Expense type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Vendor Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Amount', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Payment Date', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>			   
					</tfoot> 
					<tbody>
					 <?php	
						$user_id=get_current_user_id();
						//--- EXPENSE DATA FOR MEMBER  ------//
						if($obj_apartment->role=='member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$expensedata=$obj_account->amgt_get_own_expense($user_id);
							}
							else
							{
								$expensedata=$obj_account->amgt_get_all_expense();
							}
						} 
						//--- EXPENSE DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$expensedata=$obj_account->amgt_get_own_expense($user_id);
							}
							else
							{
								$expensedata=$obj_account->amgt_get_all_expense();
							}
						}
						//--- EXPENSE DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$expensedata=$obj_account->amgt_get_own_expense($user_id);
							}
							else
							{
								$expensedata=$obj_account->amgt_get_all_expense();
							}
						}
						//--- EXPENSE DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$expensedata=$obj_account->amgt_get_own_expense($user_id);
							}
							else
							{
								$expensedata=$obj_account->amgt_get_all_expense();
							}
						}
						if(!empty($expensedata))
						{
							foreach ($expensedata as $retrieved_data)
							{ ?>
								<tr>
									<td class="expense_type"><a href="?apartment-dashboard=user&page=accounts&tab=add-expense&action=edit&expense_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo get_the_title($retrieved_data->type_id);?></a></td>
									<td class="name"><?php echo esc_html($retrieved_data->vender_name);?></td>
									<td class="amount"><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($retrieved_data->amount);?></td>
									<td class="paymentdate"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->payment_date));?></td>
									<td class="action">
										<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="expense">
										<i class="fa fa-eye"></i> <?php esc_html_e('View Invoice', 'apartment_mgt');?></a>
										<?php
										if($user_access['edit']=='1')
										{  ?>
											<a href="?apartment-dashboard=user&page=accounts&tab=add-expense&action=edit&expense_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
										<?php
										}
										if($user_access['delete']=='1')
										{
										?>
											<a href="?apartment-dashboard=user&page=accounts&tab=invoice-list&action=delete&expense_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
										<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
										<?php
										}
										?>
									</td>							   
								</tr>
							<?php 
							} 						
						} ?>			 
					</tbody>
				</table>
			</div><!---END TABLE-RESPONSIVE--->
		</div><!--END PANEL-BODY--->
	<?php 
	}
	//ADD-EXPENSE TAB
	if($active_tab == 'add-expense')
	{
	?>
		<script type="text/javascript">
			$(document).ready(function()
			{
				"use strict";
			$('#expense_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
			var date = new Date();
					date.setDate(date.getDate()-0);
					jQuery('#payment_date').datepicker({
					dateFormat: "yy-mm-dd",
					minDate:'today',
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
             jQuery('#bill_date').datepicker({
					dateFormat: "yy-mm-dd",
					minDate:'today',
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
		<style>
			.dropdown-menu
			{
				min-width: 240px;
			}
		</style>		 
		 <?php 
		$expense_id=0;
		if(isset($_REQUEST['expense_id']))
			$expense_id=$_REQUEST['expense_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_account->amgt_get_single_expense($expense_id);
		} ?>
		<div class="panel-body"><!--PANEL-BODY--->
		    <!---EXPENSE_FORM--->
			<form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="expense_id" value="<?php echo esc_attr($expense_id);?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="expense_type">
					<?php esc_html_e('Expense Type','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] expense_types" name="expense_type" id="expense_types">
							<option value=""><?php esc_html_e('Select Expense Type','apartment_mgt');?></option>
							<?php 
							if($edit)
								$category =$result->type_id;
							elseif(isset($_REQUEST['expense_type']))
								$category =$_REQUEST['expense_type'];  
							else 
								$category = "";
							
							$activity_category=amgt_get_all_category('expense_types');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
							} ?>		
						</select>
					</div>
					<div class="col-sm-2 margin_top_10_res"><button id="addremove" model="expense_types"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				</div>				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="from_date">
					<?php esc_html_e('Vendor Name','apartment_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="vender_name" maxlength="50" class="form-control validate[required] onlyletter_number_space_validation" type="text" value="<?php if($edit){ echo esc_attr($result->vender_name);}
						elseif(isset($_POST['vender_name'])) echo esc_attr($_POST['vender_name']);?>" name="vender_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="bill_date">
					<?php esc_html_e('Bill Date','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input id="bill_date" class="form-control" type="text"  
						value="<?php if($edit){ echo esc_attr($result->bill_date);}
						elseif(isset($_POST['bill_date'])) echo esc_attr($_POST['bill_date']);?>" name="bill_date">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="amount">
					<?php esc_html_e('Amount','apartment_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="amount" class="form-control validate[required]" type="number" min="0"  
						value="<?php if($edit){ echo esc_attr($result->amount);}
						elseif(isset($_POST['amount'])) echo esc_attr($_POST['amount']);?>" name="amount">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="due_date">
					<?php esc_html_e('Payment Date','apartment_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="payment_date" class="form-control validate[required]" type="text"  
						value="<?php if($edit){ echo esc_attr($result->payment_date);}
						elseif(isset($_POST['payment_date'])) echo esc_attr($_POST['payment_date']);?>" name="payment_date">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?></label>
					<div class="col-sm-8">
						 <textarea name="description" maxlength="150" class="form-control text-input"><?php if($edit) echo esc_textarea($result->description);?></textarea>
					</div>
				</div>				
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" 
					value="<?php if($edit){ esc_html_e('save','apartment_mgt'); }else{ esc_html_e('Add Expense','apartment_mgt');}?>" 
					name="add_expense" class="btn btn-success"/>
				</div>			
			</form> <!---END EXPENSE_FORM--->
        </div> <!--END PANEL-BODY--->       
     <?php 
	}
	//CHARGES LIST TAB
	if($active_tab == 'charges-list')
	{ ?>
		<script type="text/javascript">
			$(document).ready(function()
			{
				"use strict";
				jQuery('#charges_list').DataTable({
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
    	<div class="panel-body"><!--PANEL BODY-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
				<table id="charges_list" class="display" cellspacing="0" width="100%"><!---CHARGES LIST--->
					<thead>
						<tr>
							<th><?php esc_html_e('Charges type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Charge Period', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Charge Calculate By', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Discount Amount', 'apartment_mgt' ) ;?></th>		
							<th><?php esc_html_e('Amount After Discount', 'apartment_mgt' ) ;?></th>										
							<th><?php esc_html_e('Tax Amount', 'apartment_mgt' ) ;?></th>				
							<th><?php esc_html_e('Total Amount ', 'apartment_mgt' ) ;?></th>				
							<th><?php esc_html_e('Created Date', 'apartment_mgt' ) ;?></th>	
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Charges type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Charge Period', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Charge Calculate By', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Discount Amount', 'apartment_mgt' ) ;?></th>		
							<th><?php esc_html_e('Amount After Discount', 'apartment_mgt' ) ;?></th>										
							<th><?php esc_html_e('Tax Amount', 'apartment_mgt' ) ;?></th>				
							<th><?php esc_html_e('Total Amount ', 'apartment_mgt' ) ;?></th>				
							<th><?php esc_html_e('Created Date', 'apartment_mgt' ) ;?></th>	
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>           
					</tfoot>		 
				<tbody>
					 <?php 		
						$obj_account=new Amgt_Accounts();
						$user_id=get_current_user_id();
						//--- CHARGE DATA FOR MEMBER  ------//
						if($obj_apartment->role=='member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$chargesdata=$obj_account->amgt_get_own_charges($user_id);
							}
							else
							{
								$chargesdata=$obj_account->amgt_get_all_charges();
							}
						} 
						//--- CHARGE DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$chargesdata=$obj_account->amgt_get_own_charges($user_id);
							}
							else
							{
								$chargesdata=$obj_account->amgt_get_all_charges();
							}
						}
						//--- CHARGE DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$chargesdata=$obj_account->amgt_get_own_charges($user_id);
							}
							else
							{
								$chargesdata=$obj_account->amgt_get_all_charges();
							}
						}
						//--- CHARGE DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$chargesdata=$obj_account->amgt_get_own_charges($user_id);
							}
							else
							{
								$chargesdata=$obj_account->amgt_get_all_charges();
							}
						}
						
					 if(!empty($chargesdata))
					 {
						foreach ($chargesdata as $retrieved_data)
						{				
							$building_name=get_the_title($retrieved_data->building_id);
							if($retrieved_data->amgt_charge_period=='0')
							{
								$charge_period=esc_html__('One Time', 'apartment_mgt' );
							}
							elseif($retrieved_data->amgt_charge_period=='1')
							{
								$charge_period=esc_html__('Monthly', 'apartment_mgt' );
							}
							elseif($retrieved_data->amgt_charge_period=='3')
							{
								$charge_period=esc_html__('Quarterly', 'apartment_mgt' );
							}
							elseif($retrieved_data->amgt_charge_period=='12')	
							{
								$charge_period=esc_html__('Yearly', 'apartment_mgt' );
							}
							
							if($retrieved_data->charges_calculate_by=='fix_charge')
							{
								$charge_period=esc_html__('Fix Charges', 'apartment_mgt' );
							}
							elseif($retrieved_data->charges_calculate_by=='measurement_charge')
							{
								$charge_period=esc_html__('Measurement Charges', 'apartment_mgt' );
							}					
							?>
							<tr>
								<td><?php 
									if($retrieved_data->charges_type_id=='0')
									{	
										echo 'Maintenance Charges'; 
									}
									else
									{
										echo get_the_title($retrieved_data->charges_type_id); 
									}		
									?>
								</td>
								<td class="amount"><?php echo esc_html($charge_period); ?></td>
								<td class="amount"><?php echo esc_html($charge_cal_by); ?></td>
								<td class="amount"><?php if(!empty($retrieved_data->discount_amount)){ echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo $retrieved_data->discount_amount; }else{echo '-';}?></td>
								<td class="amount"><?php if($retrieved_data->charges_calculate_by=='measurement_charge'){ echo '-'; }else{ echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo $retrieved_data->amount;} ?></td>
								<td class="amount"><?php if($retrieved_data->charges_calculate_by=='measurement_charge'){echo '-'; }else{ 
									echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($retrieved_data->tax_amount); }?></td>
								<td class="amount"><?php if($retrieved_data->charges_calculate_by=='measurement_charge'){echo '-'; }else{
									echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($retrieved_data->total_amount); } ?></td>
								<td><?php echo  date(amgt_date_formate(),strtotime($retrieved_data->created_date));?></td>
								<td class="action">
								<?php
								if($user_access['edit']=='1')
								{  ?>
									<a href="?apartment-dashboard=user&page=accounts&tab=add-charges&action=edit&pay_charges_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>
									<a href="?apartment-dashboard=user&page=accounts&tab=add-charges&action=delete&pay_charges_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
									<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
								<?php
								}
								?>
								</td>						   
							</tr>
						<?php 
						}
						
					} ?>			 
				</tbody>			
			</table><!---END CHARGES LIST--->
        </div><!---TABLE-RESPONSIVE--->
        </div><!--END PANEL BODY-->
		<?php 
	}
	//ADD CHARGES TAB
	if($active_tab == 'add-charges')
	{
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				"use strict";
				$('#recuring_charges_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
				
			   var date = new Date();
						date.setDate(date.getDate()-0);
						
						 jQuery('.date').datepicker({
						dateFormat: "yy-mm-dd",
						minDate:'today',
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
			} );
		</script>
		<?php 	
		$pay_charges_id=0;
		if(isset($_REQUEST['pay_charges_id']))
			$pay_charges_id=$_REQUEST['pay_charges_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result = $obj_account->amgt_get_single_charges_list($pay_charges_id);
			} 
			?>
		<div class="panel-body"><!--PANEL BODY-->
		   <!---RECURING_CHARGES_FORM--->
		   <form name="recuring_charges_form" action="" method="post" class="form-horizontal" id="recuring_charges_form">
				 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="pay_charges_id" value="<?php echo esc_attr($pay_charges_id);?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="Charge Period"><?php esc_html_e('Charge Period','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<?php  $charge_period='0';
						if($edit)
						{
						   $charge_period=$result->amgt_charge_period;				  
						}
						 ?>						
						<label class="radio-inline front_radio">
						  <input type="radio" value="0" class="tog validate[required] radio_border_radius" name="amgt_charge_period"  <?php  checked( '0', $charge_period);  if($edit){ if($charge_period != '0'){ echo 'disabled="disabled"'; } }?>/><?php esc_html_e('One Time','apartment_mgt');?> 
						</label>
						<label class="radio-inline front_radio">
						  <input type="radio" value="1" class="tog validate[required] radio_border_radius" name="amgt_charge_period"  <?php  checked( '1', $charge_period);  if($edit){ if($charge_period != '1'){ echo 'disabled="disabled"'; } }?>/><?php esc_html_e('Monthly','apartment_mgt');?>
						</label>
						<label class="radio-inline front_radio">
						  <input type="radio" value="3" class="tog validate[required] radio_border_radius" name="amgt_charge_period"  <?php  checked( '3', $charge_period);  if($edit){ if($charge_period != '3'){ echo 'disabled="disabled"'; } }?>/><?php esc_html_e('Quarterly','apartment_mgt');?> 
						</label>
						 <label class="radio-inline front_radio margin_left_0_res">
						  <input type="radio" value="12" class="tog  validate[required] radio_border_radius" name="amgt_charge_period"  <?php  checked( '12', $charge_period);  if($edit){ if($charge_period != '12'){ echo 'disabled="disabled"'; } }?>/><?php esc_html_e('Yearly','apartment_mgt');?> 
						 </label>
					</div>
				</div> 
				<div class="form-group">
					<label class="col-sm-2 control-label " for="enable"><?php esc_html_e('Select Invoice Option','apartment_mgt');?></label>
					<div class="col-sm-8">
						<?php 
						$select_serveice ='all_member';
						if($edit)
						 {
							 $select_serveice=$result->invoice_options;
							 $select_option=$result->invoice_options;
						 } 					 
					  ?>
						<div class="radio">
							<label class="front_radio">
								<input  type="radio" name="select_serveice" class="radio_border_radius" <?php  checked( 'all_member', $select_serveice);  ?>  value="all_member" 
								<?php if($edit){ if($select_option != 'all_member'){ echo 'disabled="disabled"'; } }?> > <?php esc_html_e('All Member','apartment_mgt');?> 
							</label> 
							&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="front_radio">
								<input  type="radio" name="select_serveice" class="radio_border_radius" <?php  checked( 'Building', $select_serveice);  ?> value="Building" <?php if($edit){ if($select_option != 'Building'){ echo 'disabled="disabled"'; }}?>> <?php esc_html_e('Building Member','apartment_mgt');?> 
							</label> 
							&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="front_radio margin_top_5_res">
								<input type="radio"  name="select_serveice" class="radio_border_radius" <?php  checked( 'Unit Category', $select_serveice);  ?> value="Unit Category" <?php if($edit){ if($select_option != 'Unit Category'){ echo 'disabled="disabled"'; } }?>>  <?php esc_html_e('Unit Category Member','apartment_mgt');?>
							</label class="front_radio">
							&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="front_radio">
								<input type="radio"  name="select_serveice" class="radio_border_radius" <?php  checked( 'one_member', $select_serveice);  ?> value="one_member" <?php if($edit){ if($select_option != 'one_member'){ echo 'disabled="disabled"'; } }?>>  <?php esc_html_e('One Member','apartment_mgt');?>
							</label class="front_radio">
							&nbsp;&nbsp;&nbsp;&nbsp;
						</div>						 
					</div>
				</div>		
				<?php 
				if($edit)
				{
					$select_option=$result->invoice_options;
					if($select_option == "one_member")
					{ ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] building_category" name="building_id">
									<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
									<?php 
									if($edit)
										$category =$result->building_id;
									elseif(isset($_REQUEST['building_id']))
										$category =$_REQUEST['building_id'];  
									else 
										$category = "";
									
									$activity_category=amgt_get_all_category('building_category');
									if(!empty($activity_category))
									{
										foreach ($activity_category as $retrive_data)
										{
											echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
										}
									} ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] unit_categorys" name="unit_cat_id">
								<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
								<?php 
								if($edit)
									$category =$result->unit_cat_id;
								elseif(isset($_REQUEST['unit_cat_id']))
									$category =$_REQUEST['unit_cat_id'];  
								else 
									$category = "";
								
								$activity_category=amgt_get_all_category('unit_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
									}
								} ?>
								</select>
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Unit','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] account_unit_name" name="unit_name" >
								<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
								<?php 
								if($edit)
								{
									$unitname =$result->unit_name;
									$building_id=$result->building_id;
									$unitsarray=$obj_units->amgt_get_single_cat_units($building_id,$result->unit_cat_id);
									$all_entry=json_decode($unitsarray);
									
									if(!empty($all_entry))
									{
										foreach($all_entry as $unit)
										{ ?>
											<option value="<?php echo esc_attr($unit->value); ?>" <?php selected($unitname,$unit->value);?>><?php echo esc_html($unit->value);?> </option>
										<?php 
										}
									}							
								} ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"  for="member"><?php esc_html_e('Member','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] member_id" id="member_id" name="member_id">
								<option value=""><?php esc_html_e('Select Member','apartment_mgt');?></option>
									<?php if($edit)
									{
										$memberid =$result->member_id;
										$unitname =$result->unit_name;
										$category =$result->unit_cat_id;
										$building =$result->building_id;
										
										$args = array(
												'role' => 'member',
												'meta_query'=>
												 array(
													'relation' => 'AND',
													array(
														'relation' => 'AND',
													array(
														'key'	  =>'building_id',
														'value'	=>	$building,
														'compare' => '=',
													),
													array(
														'key'	  =>'unit_cat_id',
														'value'	=>	$category,
														'compare' => '=',
													),
													array(
														'key'	  =>'unit_name',
														'value'	=>	$unitname,
														'compare' => '=',
													)
												  ),
												  array(
														'relation' => 'OR',
													array(
														'key'	  =>'occupied_by',
														'value'	=>	'Owner',
														'compare' => '=',
													),
													array(
														'key'	  =>'occupied_by',
														'value'	=>	'tenant',
														'compare' => '=',
													)
												  )
											   )
											);

											$allmembers = get_users($args);
											 
										   foreach($allmembers as $allmembers_data)
										  {
											 echo '<option value="'.$allmembers_data->ID.'" '.selected($memberid,$allmembers_data->ID).'>'.$allmembers_data->display_name.'</option>';
										  }
									}
									?>
								</select>
							</div>							
						</div>						
					 <?php
					 }
					 elseif($select_option == "Unit Category")
					 { ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] building_category" name="building_id">
								  <option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
								<?php 
								if($edit)
									$category =$result->building_id;
								elseif(isset($_REQUEST['building_id']))
									$category =$_REQUEST['building_id'];  
								else 
									$category = "";
								
								$activity_category=amgt_get_all_category('building_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
									}
								} ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] unit_categorys" name="unit_cat_id">
								<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
								<?php 
								if($edit)
									$category =$result->unit_cat_id;
								elseif(isset($_REQUEST['unit_cat_id']))
									$category =$_REQUEST['unit_cat_id'];  
								else 
									$category = "";
								
								$activity_category=amgt_get_all_category('unit_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
									}
								} 	
							?>
								</select>
							</div>
						</div>						 
					<?php  
					}
					elseif($select_option == 'Building')
					{ ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] building_category" name="building_id">
								  <option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
								<?php 
								if($edit)
									$category =$result->building_id;
								elseif(isset($_REQUEST['building_id']))
									$category =$_REQUEST['building_id'];  
								else 
									$category = "";
								
								$activity_category=amgt_get_all_category('building_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
									}
								} ?>
								</select>
							</div>
						</div>						
					<?php 
					}					
					elseif($select_option == 'all_member')
					{
						
					}			
				} 				
				if($edit)
				{ ?> 
					  <div > </div>
				  <?php  
				}
				else
				{ ?>
					 <div id="invoice_setting_block"> </div> <!---INVOICE_SETTING_BLOCK--->
					 <hr>
				<?php  
				} 
				?>
				<div class="form-group"><!---FORM-GROUP-->
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Charges','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] charges_category" name="charges_id" id="">
						<option value="0"><?php esc_html_e('Maintenance Charges','apartment_mgt');?></option>
						<?php 
						if($edit)
							$category =$result->charges_type_id;
						elseif(isset($_REQUEST['charges_id']))
							$category =$_REQUEST['charges_id'];  
						else 
							$category = 0;
						
						$activity_category=amgt_get_all_category('charges_category');
						if(!empty($activity_category))
						{
							foreach ($activity_category as $retrive_data)
							{
								echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
							}
						} ?>
						</select>
					</div>
					<div class="col-sm-2 margin_top_10_res"><button id="addremove" model="charges_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				</div>				
				<?php
				$select_charge_cal ='fix_charge';
				if($edit)
				{
					$select_charge_cal=$result->charges_calculate_by;
				} 
				?>
			    <div class="form-group">
					<label class="col-sm-2 control-label " for="enable"><?php esc_html_e('Charge Calculate By ','apartment_mgt');?></label>
					<div class="col-sm-8">
						 <div class="radio">				    
							<label class="front_radio">
								<input  type="radio" class="tax_div_clear radio_border_radius" name="charge_cal" <?php  checked( 'fix_charge', $select_charge_cal);  ?> value="fix_charge" <?php if($edit){ if($select_charge_cal != 'fix_charge'){ echo 'disabled="disabled"'; } }?>> <?php esc_html_e('Fix Charge','apartment_mgt');?> 
							</label> 
							&nbsp;&nbsp;&nbsp;&nbsp; 
							<label class="front_radio">
								<input  type="radio" class="tax_div_clear radio_border_radius" name="charge_cal" <?php  checked( 'measurement_charge', $select_charge_cal);  ?>  value="measurement_charge" <?php if($edit){ if($select_charge_cal != 'measurement_charge'){ echo 'disabled="disabled"'; } }?>> <?php esc_html_e('Measurement Charge','apartment_mgt');?> 
							</label> 
							&nbsp;&nbsp;&nbsp;&nbsp; 					
						</div>						 
					</div>
				</div>
					<?php 
					if($edit)
					{
						$all_entry=json_decode($result->charges_payment);
					}
					
					if(!empty($all_entry))
					{
						foreach($all_entry as $entry)
						{
							if($select_charge_cal=='fix_charge')
							{
							?>
								<div id="charges_entry"><!--CHARGES_ENTRY---->
									<div class="form-group">
										<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Charges Payment','apartment_mgt'); ?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>						
										<div class="col-sm-2">
											<input id="income_amount" class="form-control margin_top_10_res validate[required] text-input income_amount" type="number" min="0" value="<?php echo $entry->amount;?>" name="income_amount[]" >
										</div>						
										<div class="col-sm-4">
											<input id="income_entry" maxlength="50" class="form-control margin_top_10_res validate[required] text-input onlyletter_number_space_validation" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
										</div>										
										<div class="col-sm-2">
											<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
											<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
											</button>
										</div>
									</div>	
								</div>
							<?php 
							}
							else if($select_charge_cal=='measurement_charge')
							{
								$unit_measerment_type=get_option( 'amgt_unit_measerment_type' );
							?>
								<div id="charges_entry">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Charges Payment','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
										<div class="col-sm-2">
											<input id="income_amount" class="form-control margin_top_10_res validate[required] text-input income_amount" type="number" min="0" value="<?php echo esc_attr($entry->amount);?>" name="income_amount[]" >
										</div>	
										<div class="float_left_top_font_size_13">
											/ per <?php echo esc_html($unit_measerment_type);?>
										</div>								
									</div>	
								</div>
							<?php	
							}						
						}						
					}
					else
					{
						?>
						<div id="charges_entry"><!--CHARGES ENTRY---->
							<div class="form-group">
								<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Charges Payment','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>						
								<div class="col-sm-2">
									<input id="income_amount" class="form-control margin_top_10_res validate[required] text-input income_amount" type="number" min="0" value="" name="income_amount[]" placeholder="<?php esc_html_e('Charges Amount','apartment_mgt');?>">
								</div>										
								<div class="col-sm-4">
									<input id="income_entry" maxlength="50" class="form-control margin_top_10_res validate[required] text-input onlyletter_number_space_validation " type="text" value="" name="income_entry[]" placeholder="<?php esc_html_e('Charges Entry Label','apartment_mgt');?>">
								</div>								
								<div class="col-sm-2">
									<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
									<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
									</button>
								</div>
							</div>	
						</div>	<!--END CHARGES ENTRY---->				
					<?php 
					} 
				if($edit)
				{
					if($select_charge_cal=='fix_charge')
					{
					?>
					<div class="form-group measurement_hide_div">
						<label class="col-sm-2 control-label" for="expense_entry"></label>
						<div class="col-sm-3">
							<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left new_entry_charges" type="button"   name="add_new_entry"><?php esc_html_e('Add Charges Entry','apartment_mgt'); ?>
							</button>
						</div>
					</div>	
					<?php
					}
				}
				else
				{
				?>
					<div class="form-group measurement_hide_div">
						<label class="col-sm-2 control-label" for="expense_entry"></label>
						<div class="col-sm-3">
							<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left new_entry_charges" type="button"   name="add_new_entry"><?php esc_html_e('Add Charges Entry','apartment_mgt'); ?>
							</button>
						</div>
					</div>	
				<?php
				}				
				?>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discount-amount">
						<?php esc_html_e('Discount Amount','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)</label>
						<div class="col-sm-8">
							<input id="discount-amount" class="form-control discount-amount" type="number" min="0"  
							value="<?php if($edit){ echo esc_attr($result->discount_amount);}
							elseif(isset($_POST['discount_amount'])) echo esc_attr($_POST['discount_amount']);?>" name="discount_amount">
						</div>			
					</div>
				<?php
				if($edit)
				{
					if($select_charge_cal=='fix_charge')
					{
					?>
					<div class="form-group measurement_hide_div">
						<label class="col-sm-2 control-label" for="amount">
						<?php esc_html_e('Amount After Discount','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="amount" class="form-control validate[required,custom[number]] amount" type="number" min="0"  
							value="<?php if($edit){ echo esc_attr($result->amount);}
							elseif(isset($_POST['amount'])) echo esc_attr($_POST['amount']);?>" name="amount">
						</div>
					</div>
					<?php
					}	
				}
				else
				{
				?>
					<div class="form-group measurement_hide_div">
						<label class="col-sm-2 control-label" for="amount">
						<?php esc_html_e('Amount After Discount','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="amount" class="form-control validate[required,custom[number]] amount" type="text"  
							value="<?php if($edit){ echo esc_attr($result->amount);}
							elseif(isset($_POST['amount'])) echo esc_attr($_POST['amount']);?>" name="amount">
						</div>
					</div>
				<?php	
				}		
				if($edit)
				{
					?>	
					<div id="charges_entry1">
					<?php 
					$obj_tax =new Amgt_Tax;
					$tax_data_value= $obj_tax->amgt_get_all_tax_by_charge_id($pay_charges_id);
					if(!empty($tax_data_value))
					{	$i=1;			
						foreach ($tax_data_value as $data)
						{
							$i--;
							if($select_charge_cal=='fix_charge')
							{						
							?>
								<div class="form-group">
									<input type="hidden" id="increament_val" name="increament_val" value="<?php echo $i;?>">
									<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field"></span></label>
									<div class="col-sm-4">
										<select name="tax_title[]" id="<?php echo $i;?>" class="form-control valid tax_selection">
											<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>
											<?php 
											$obj_tax =new Amgt_Tax;
											$tax_data= $obj_tax->Amgt_get_all_tax();
											$tax_id=$data->tax_id;
											 if(!empty($tax_data))
											 {
												foreach ($tax_data as $retrive_data)
												{ 
													echo '<option value="'.$retrive_data->id.'" '.selected($tax_id,$retrive_data->id).'>'.$retrive_data->tax_title.'</option>';						
												}
											 }	
											 ?>
										</select>
									</div>
									<div class="col-sm-2">
										<input id="tax_entry_<?php echo $i;?>" class="form-control  margin_top_10_res text-input" type="text" value="<?php echo $data->tax;?>" name="tax_entry[]" placeholder="<?php esc_html_e('Tax','apartment_mgt');?>" readonly>
									</div>											
									<div class="col-sm-1">
										<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
										<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
										</button>
									</div>
								</div>	
								<?php
							}
							elseif($select_charge_cal=='measurement_charge')
							{
								?>
								<div class="form-group">
									<input type="hidden" id="increament_val" name="increament_val" value="<?php echo $i;?>">
									<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field"></span></label>
									<div class="col-sm-4">
										<select name="tax_title[]" id="<?php echo $i;?>" class="form-control valid tax_selection">
											<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>
											<?php 
											$obj_tax =new Amgt_Tax;
											$tax_data= $obj_tax->Amgt_get_all_tax();
											$tax_id=$data->tax_id;
											 if(!empty($tax_data))
											 {
												foreach ($tax_data as $retrive_data)
												{ 
													echo '<option value="'.$retrive_data->id.'" '.selected($tax_id,$retrive_data->id).'>'.$retrive_data->tax_title.'</option>';						
												}
											 }	
											 ?>
										</select>
									</div>
									<div class="col-sm-2">
										<input id="tax_entry_<?php echo $i;?>" class="form-control  margin_top_10_res text-input" type="text" value="<?php echo esc_attr($data->tax);?>" name="tax_entry[]" placeholder="<?php esc_html_e('Tax','apartment_mgt');?>" readonly>
									</div>					
									<div class="col-sm-1">
										<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
										<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
										</button>
									</div>
								</div>
							<?php	
							}	
						}
					}
					?>
					</div>				
					<?php 			
				}
				else
				{ ?>
					<div id="charges_entry1"><!--CHARGES_ENTRY--->
						<div class="form-group">
							<input type="hidden" id="increament_val" name="increament_val" value="1">
							<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-4">
							<select name="tax_title[]" id="1" class="form-control valid tax_selection">
								<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>
								<?php $obj_tax =new Amgt_Tax;
								$tax_data= $obj_tax->Amgt_get_all_tax();
									 if(!empty($tax_data))
									 {
										foreach ($tax_data as $retrieved_data){ ?>
											<option value="<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->tax_title);?></option>
									<?php }
									 }	?>
							</select>
						</div>
							<div class="col-sm-2">
								<input id="tax_entry_1" class="form-control validate[required] margin_top_10_res text-input" type="text" value="" name="tax_entry[]" placeholder="<?php esc_html_e('Tax','apartment_mgt');?>" readonly>
							</div>							
							<div class="col-sm-1">
								<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
								<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
								</button>
							</div>
						</div>	
					</div>
				<?php 
				} 
				?>				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="expense_entry"></label>
					<div class="col-sm-3">
						<button id="add_new_entry1" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry1()"><?php esc_html_e('Add More Tax','apartment_mgt'); ?>
						</button>
					</div>
				</div>
				<?php
				if($edit)
				{
					if($select_charge_cal=='fix_charge')
					{
					?>	
					<div class="form-group measurement_hide_div">
						<label class="col-sm-2 control-label" for="taxamount">
						<?php esc_html_e('Tax Amount','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="taxamount" value="<?php if($edit){ echo esc_attr($result->tax_amount);}elseif(isset($_POST['tax_amount'])) echo esc_attr($_POST['tax_amount']);?>" class="form-control validate" type="text"  
							 name="tax_amount">
						</div>					
					</div>		
					<div class="form-group measurement_hide_div">
						<label class="col-sm-2 control-label" for="taxamount">
						<?php esc_html_e('Total Amount','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="totalamount"  class="form-control validate" type="text"
							value="<?php if($edit){ echo esc_attr($result->total_amount); }elseif(isset($_POST['total_amount'])) echo esc_attr($_POST['total_amount']);?>"				
							 name="total_amount">
						</div>					
					</div>
					<?php
					}
				}
				else
				{
				?>
				<div class="form-group measurement_hide_div">
					<label class="col-sm-2 control-label" for="taxamount">
					<?php esc_html_e('Tax Amount','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="taxamount" value="<?php if($edit){ echo esc_attr($result->tax_amount);}elseif(isset($_POST['tax_amount'])) echo esc_attr($_POST['tax_amount']);?>" class="form-control validate" type="text"  
						 name="tax_amount">
					</div>			
				</div>		
				<div class="form-group measurement_hide_div">
					<label class="col-sm-2 control-label" for="taxamount">
					<?php esc_html_e('Total Amount','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="totalamount"  class="form-control validate" type="text"
						value="<?php if($edit){ echo esc_attr($result->total_amount); }elseif(isset($_POST['total_amount'])) echo esc_attr($_POST['total_amount']);?>"				
						 name="total_amount">
					</div>			
				</div>
				<?php
				}	
				?>
				<hr>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?></label>
					<div class="col-sm-8">
						 <textarea name="description" maxlength="150" class="form-control text-input"><?php if($edit) echo esc_textarea($result->description);?></textarea>
					</div>
				</div>						
				<div class="col-sm-offset-2 col-sm-2">
					<input type="submit" value="<?php  esc_html_e('Save Charges','apartment_mgt'); ?>" name="add_charges_all_member" class="btn btn-success"/>
				</div>
				<div class="col-sm-2 margin_top_10_res">
					<input type="submit" value="<?php if($edit){ esc_html_e('Save Charges & Update Invoice','apartment_mgt'); }else{ esc_html_e('Save Charges & Create Invoice','apartment_mgt');}?>" name="add_charges_all_member_with_create_invoice" class="btn btn-success"/>
				</div>
			</form> <!---END RECURING_CHARGES_FORM--->
        </div>
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
		<script>
			// CREATING BLANK INVOICE ENTRY
			var blank_custom_label ='';
			
			function add_entry1()
			{
				increament_val = $('#increament_val').val();
				var charge_cal = $("input[name='charge_cal']:checked").val();		
				if(charge_cal=='fix_charge')
				{
					increamentval= parseInt(increament_val) + 1;
					$('#increament_val').val(increamentval);
					blank_custom_label='<div class="form-group">';
					blank_custom_label+='<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field">*</span></label>';
					blank_custom_label+='<div class="col-sm-4">';
					blank_custom_label+='<select name="tax_title[]" id="'+increamentval+'" class="form-control valid tax_selection">';
					blank_custom_label+='<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>';
					<?php $obj_tax =new Amgt_Tax;
								$tax_data= $obj_tax->Amgt_get_all_tax();
									 if(!empty($tax_data))
									 {
										foreach ($tax_data as $retrieved_data){ ?>
					blank_custom_label+='<option value="<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->tax_title;?></option>';
										<?php }
									 } ?>
					blank_custom_label+='</select>';
					blank_custom_label+='</div>';
					blank_custom_label+='<div class="col-sm-2">';
					blank_custom_label+='<input id="tax_entry_'+increamentval+'" class="form-control margin_top_10_res validate[required] text-input" type="text" value="" name="tax_entry[]" placeholder="Tax" readonly>';
					blank_custom_label+='</div>';		
					blank_custom_label+='<div class="col-sm-1">';
					blank_custom_label+='<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">';
					blank_custom_label+='<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>';
					blank_custom_label+='</button>';
					blank_custom_label+='</div>';
					blank_custom_label+='</div>';
				}
				else if(charge_cal=='measurement_charge')
				{
					
					increamentval= parseInt(increament_val) + 1;
					$('#increament_val').val(increamentval);
					blank_custom_label='<div class="form-group">';
					blank_custom_label+='<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field">*</span></label>';
					blank_custom_label+='<div class="col-sm-4">';
					blank_custom_label+='<select name="tax_title[]" id="'+increamentval+'" class="form-control valid tax_selection">';
					blank_custom_label+='<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>';
					<?php $obj_tax =new Amgt_Tax;
								$tax_data= $obj_tax->Amgt_get_all_tax();
									 if(!empty($tax_data))
									 {
										foreach ($tax_data as $retrieved_data){ ?>
					blank_custom_label+='<option value="<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->tax_title;?></option>';
										<?php }
									 } ?>
					blank_custom_label+='</select>';
					blank_custom_label+='</div>';
					blank_custom_label+='<div class="col-sm-2">';
					blank_custom_label+='<input id="tax_entry_'+increamentval+'" class="form-control margin_top_10_res validate[required] text-input" type="text" value="" name="tax_entry[]" placeholder="Tax" readonly>';
					blank_custom_label+='</div>';				
					blank_custom_label+='<div class="col-sm-2">';
					blank_custom_label+='<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">';
					blank_custom_label+='<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>';
					blank_custom_label+='</button>';
					blank_custom_label+='</div>';
					blank_custom_label+='</div>';
				}
				$("#charges_entry1").append(blank_custom_label);				
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
	<?php 
	}
	?>
</div>