<?php
class Amgt_Accounts
{    
	//OWN PAYMENT FUNCTION
	public function amgt_add_own_payment($data)
	{

		global $wpdb;
		$table_invoice = $wpdb->prefix. 'amgt_created_invoice_list';		
		$table_amgt_invoice_payment_history = $wpdb->prefix .'amgt_invoice_payment_history';
		
		$invoiceid=$data['invoice_id'];
		$member_id=$data['member_id'];
		$history_data['invoice_id']=$data['invoice_id'];
		$history_data['member_id']=$data['member_id'];
		$history_data['date']=date("Y-m-d"); 
		$history_data['amount']=MJamgt_strip_tags_and_stripslashes($data['amount']);
		$history_data['payment_method']=$data['payment_method'];		
		$history_data['description']=$data['description'];		
		
		
		$invoicedata = $wpdb->get_row("SELECT * FROM $table_invoice where id=$invoiceid");
		
		$paid_amount=$invoicedata->paid_amount;
		
		$invoice_length=strlen($invoicedata->invoice_no);
		if($invoice_length == '5')
		{
			$total_amount=$invoicedata->total_amount;		
		}
		else
		{
			$total_amount=$invoicedata->invoice_amount;
		}	
		$uddate_data['paid_amount'] = $paid_amount + $data['amount'];
		$uddate_data['due_amount'] = $total_amount - $uddate_data['paid_amount'];
		$uddate_data['total_amount'] = $total_amount;
		$uddate_data['payment_status'] = $total_amount;
		
		if(round($uddate_data['paid_amount']) < round($uddate_data['total_amount']))
		{
			$uddate_data['payment_status']="Partially Paid";
		}
		elseif(round($uddate_data['paid_amount']) >= round($uddate_data['total_amount']))
		{
			$uddate_data['payment_status']="Fully Paid";
		}
		else
		{
			$uddate_data['payment_status']="Unpaid";
		}	
		
		$id['id'] = $invoiceid;
		
		$update_invoice=$wpdb->update( $table_invoice,$uddate_data,$id);
		
		$result=$wpdb->insert($table_amgt_invoice_payment_history,$history_data);
		//---------------- SEND  SMS ------------------//
		include_once(ABSPATH.'wp-admin/includes/plugin.php');
		if(is_plugin_active('sms-pack/sms-pack.php'))
		{
			if(!empty(get_user_meta($data['member_id'], 'phonecode',true))){ $phone_code=get_user_meta($data['member_id'], 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
							
			$user_number[] = $phone_code.get_user_meta($data['member_id'], 'mobile',true);
			$apartmentname=get_option('amgt_system_name');
			$message_content ="You have Paid Your Invoice in $apartmentname .";
			$current_sms_service 	= get_option( 'smgt_sms_service');
			$args = array();
			$args['mobile']=$user_number;
			$args['message_from']="Complaint";
			$args['message']=$message_content;					
			if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
			{				
				$send = send_sms($args);							
			}
		}
				
		//----SEND NOTIFICATION MAIL--------
		$user_invoiceid=$data['invoice_id'];
		$invoice_no=$this->amgt_get_invoiceno_by_invoice_id($data['invoice_id']);
		$retrieved_data = get_userdata($data['member_id']);
		$to = $retrieved_data->user_email; 
		$subject =get_option('wp_amgt_paid_invoice_subject');
		$apartmentname=get_option('amgt_system_name');
		
		$page_link=home_url().'/?apartment-dashboard=user&page=notice-event&tab=event_list';
		$subject_search=array('{{apartment_name}}','{{invoiceno}}');
		$subject_replace=array($apartmentname,$invoice_no);
		$subject=str_replace($subject_search,$subject_replace,$subject);
		$message_content=get_option('wp_amgt_paid_invoice_email_template');
		$search=array('{{member_name}}','{{apartment_name}}','{{invoiceno}}');
		$replace = array($retrieved_data->display_name,$apartmentname,$invoice_no);
		$message_content = str_replace($search, $replace, $message_content);
		require AMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
		amgt_send_invoice_generate_mail($to,$subject,$message_content,$user_invoiceid);
		return $result;
	}
	//GET INVOICE NO BY INVOICE ID
	public function amgt_get_invoiceno_by_invoice_id($invoice_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_row("SELECT invoice_no FROM $table_name where id=$invoice_id");
		return $result->invoice_no;	
	}	
	//GET SINGLE INVOICE PAYMENT HISTORY BY ID
	public function amgt_get_single_invoice_payment_history_by_id($invoice_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_invoice_payment_history';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where invoice_id=$invoice_id ORDER BY id DESC");
		return $result;	
	}	
	//GET ALL INVOICE
	public function amgt_get_all_invoice()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where status!='Deactivate'");
		return $result;
	
	}
	//GET OWN INVOICE
	public function amgt_get_own_invoice($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where status!='Deactivate' AND created_by=".$user_id);
		return $result;
	
	}
	//GET MEMBER ALL INVOICE
	public function amgt_get_member_all_invoice()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		$current_user_id = get_current_user_id();
		$result = $wpdb->get_results("SELECT * FROM $table_name where member_id=$current_user_id");
		return $result;	
	}
	//CHECK INVOICE GENERATED OR NOT
	public function amgt_check_invoice_generated($building_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where building_id=$building_id");
		return $result;	
	}
	//CHECK INVOICE REGENERATED 
	public function amgt_check_invoice_regenerated($building_id,$from_date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		
		$result = $wpdb->get_row("SELECT * FROM $table_name where building_id=$building_id AND to_date<'$from_date' AND generated_status=0");
		return $result;	
	} 
	//CHECK ALLREADY GENERATED MEMBER INVOICE
	public function amgt_check_allready_generate_member_invoice($invoiceid,$memberid)
	{
		global $wpdb;
		$table_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';
		
		$result = $wpdb->get_row("SELECT * FROM $table_amgt_created_invoice_list where invoice_id=$invoiceid AND member_id=$memberid");
		return $result;	
	} 
	//GET BUILDING INVOICE ID
	public function amgt_get_building_invoice_id($building_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		
		$result = $wpdb->get_row("SELECT id FROM $table_name where building_id=$building_id AND generated_status=0");
		return $result->id;
	} 
	//GET ALL CERATED INVOCIE
	public function amgt_get_all_crated_invoice()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where status='Activate'");
		return $result;	
	}
	//GET ALL UNPAID CERATED INVOCIE
	public function amgt_get_all_unpaid_crated_invoice()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where status='Activate' and payment_status = 'Unpaid' OR payment_status = 'Partially Paid'");
		return $result;	
	}
	//GET ALL UNPAID CERATED INVOCIE MEMBER ID
	public function amgt_get_all_unpaid_crated_invoice_memberid($member_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where member_id=$member_id and status='Activate' and payment_status = 'Unpaid' OR payment_status = 'Partially Paid'");
		return $result;	
	}
	//GET SINGLE CREATED INVOCIE
	public function amgt_get_single_crated_invoice($invoiceid,$memberid)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_row("SELECT * FROM $table_name where charges_id=$invoiceid AND member_id=$memberid");
		return $result;	
	}
	//GET SINGLE INVOCIE BY ID
	public function amgt_get_single_invoice_by_id($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=$id");
		return $result;	
	}	
	//GET SINGLE OLD INVOCIE BY ID
	public function amgt_get_single_old_invoice_by_id($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=$id");
		return $result;	
	}	
	//GET SINGLE INVOCIE BY ID	
	public function amgt_get_single_invoice($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}	
	//DELETE INVOCIE
	public function amgt_delete_invoice($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		if($result)
		{
			return $result;
		}
		
	}
	//ADD EXPENSE FUNCTION
	public function amgt_add_expense($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
		if(isset($data['expense_type']))		
		$expensedata['type_id']=$data['expense_type'];
		if(isset($data['vender_name']))	
		$expensedata['vender_name']=$data['vender_name'];
		if(isset($data['bill_date']))			
		$expensedata['bill_date']=amgt_get_format_for_db($data['bill_date']);
		if(isset($data['amount']))	
		$expensedata['amount']=$data['amount'];
		if(isset($data['payment_date']))	
		$expensedata['payment_date']=amgt_get_format_for_db($data['payment_date']);		
		if(isset($data['description']))	
		$expensedata['description']=$data['description'];
		$expensedata['created_date']=date('Y-m-d');
		$expensedata['created_by']=get_current_user_id();
		$expensedata['type']='expense';
		if($data['action']=='edit')
		{
			$whereid['id']=$data['expense_id'];
			$result=$wpdb->update( $table_name, $expensedata ,$whereid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_name, $expensedata );
			return $result;
		}	
	}
	//GET ALL EXPENSE
	public function amgt_get_all_expense()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where type='expense'");
		return $result;	
	} 
	//GET OWN EXPENSE
	public function amgt_get_own_expense($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where type='expense' and created_by=".$user_id);
		return $result;	
	}
	//GET SINGLE EXPENSE
	public function amgt_get_single_expense($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	//DELETE EXPENSE
	public function amgt_delete_expense($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	//SAVE INVOCIE PAYMENT
	public function amgt_save_invoice_payment($data)
	{		
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
		if(isset($data['invoice_id']))		
		$paymentdata['invoice_id']=$data['invoice_id'];
		if(isset($data['member_id']))		
		$paymentdata['member_id']=$data['member_id'];
		if(isset($data['amount']))	
		$paymentdata['amount']=$data['amount'];
		if(isset($data['payment_date']))	
		$paymentdata['payment_date']=amgt_get_format_for_db($data['payment_date']);
		if(isset($data['description']))	
		$paymentdata['description']=$data['description'];
		$paymentdata['created_date']=date('Y-m-d');
		$paymentdata['created_by']=get_current_user_id();
		$paymentdata['type']='income';
		
		$check_unit_invoice=$this->amgt_get_single_invoice($data['invoice_id']);
		
		$payment_res=$this->amgt_check_invoice_payment($data['member_id'],$data['invoice_id']);
		$old_payment = $this->amgt_get_prv_payments($data['member_id'],$data['invoice_id']);
		$paid_amount = 0 ;
		$invoice_amount= 0 ;
		if(!empty($old_payment))
		{				
			$paid_amount = $old_payment->paid_amount;									
			$invoice_amount = $old_payment->invoice_amount;									
		}
		$amount=0;
		if(isset($data["amount"]))
			$amount=(int)$data["amount"];
		if(isset($data["inv_amount"]))
			$amount=(int)$data["inv_amount"];
		$new_amt = $amount + (int)$paid_amount;
		
		$payment_status = ($new_amt == $invoice_amount || $new_amt >= $invoice_amount)? "Paid" : "Partially Paid";
		
		if(!empty($payment_res))
		{
			
				$check_unit_invoice=$this->amgt_get_single_invoice($data['invoice_id']);	
								
				if($check_unit_invoice->unit_name!='')
				{						
					$invoice_whereid['id']=$payment_res->id;
					$result=$wpdb->update( $table_name, $paymentdata,$invoice_whereid );
				
					$table_invoice=$wpdb->prefix. 'amgt_created_invoice_list';
					$invoicedata['payment_status']=$payment_status;
					$invoicedata['paid_amount']=$new_amt;
					$whereid['invoice_id']=$data['invoice_id'];						
					$whereid['member_id']=$data['member_id'];
						$transaction_id="";
						$payment_method="";
						if(isset($data['transaction_id']))
						$transaction_id=$data['transaction_id'];
						if(isset($data['payment_method']))
						$payment_method=$data['payment_method'];						
					if($transaction_id!="" && $payment_method!="")
					{
						$sql = "UPDATE `$table_invoice` SET payment_status='{$payment_status}',paid_amount='{$new_amt}' ,transaction_id='{$transaction_id}',payment_method='{$payment_method}' WHERE invoice_id = {$data['invoice_id']} AND member_id = {$data['member_id']}";
						$result = $wpdb->query( $sql );		
					}	
					else
					{
						$sql = "UPDATE `$table_invoice` SET payment_status='{$payment_status}',paid_amount='{$new_amt}' WHERE invoice_id = {$data['invoice_id']} AND member_id = {$data['member_id']}";
						$result = $wpdb->query( $sql );		
					}							
				}
				else
				{
					$invoice_whereid['id']=$payment_res->id;
					$result=$wpdb->update( $table_name, $paymentdata,$invoice_whereid );
					if($result)	
					{
						$table_invoice=$wpdb->prefix. 'amgt_created_invoice_list';
						$invoicedata['payment_status']=$payment_status;
						$invoicedata['paid_amount']=$new_amt;
						$whereid['invoice_id']=$data['invoice_id'];
						$whereid['member_id']=$data['member_id'];
						$transaction_id="";
						$payment_method="";
						if(isset($data['transaction_id']))
						$transaction_id=$data['transaction_id'];
						if(isset($data['payment_method']))
						$payment_method=$data['payment_method'];
							
						if($transaction_id!="" && $payment_method!="")
						{
							$sql = "UPDATE `$table_invoice` SET payment_status='{$payment_status}',paid_amount='{$new_amt}',transaction_id='{$transaction_id}',payment_method='{$payment_method}' WHERE invoice_id = {$data['invoice_id']} AND member_id = {$data['member_id']}";
							$result = $wpdb->query( $sql );	
							//---------Notification send mail code---------------------
							$retrieved_data=get_userdata($data['member_id']);
							$to = $retrieved_data->user_email; 
							$invoiceno=$payment_res->invoice_no;
							$subject =get_option('wp_amgt_paid_invoice_subject');
							$apartmentname=get_option('amgt_system_name');
							$subject_search=array('{{invoiceno}}');
							$subject_replace=array($invoiceno);
							$subject=str_replace($subject_search,$subject_replace,$subject);
							$message_content=get_option('wp_amgt_paid_invoice_email_template');
							$search=array('{{member_name}}','{{apartment_name}}','{{invoiceno}}');
							$replace = array($retrieved_data->display_name,$apartmentname,$invoiceno);
							$message_content = str_replace($search, $replace, $message_content);
							$resultInvoice=getHTMLInvoice($payment_res->id,'invoice');
							$message_content.=$resultInvoice;
							amgtSendEmailNotificationWithHTML($to,$subject,$message_content);	
							
						}
						else
						{
								$sql = "UPDATE `$table_invoice` SET payment_status='{$payment_status}',paid_amount='{$new_amt}' WHERE invoice_id = {$data['invoice_id']} AND member_id = {$data['member_id']}";
								$result = $wpdb->query( $sql );	
								//---------Notification send mail code---------------------
								$retrieved_data=get_userdata($data['member_id']);
								$to = $retrieved_data->user_email; 
								$subject =get_option('wp_amgt_paid_invoice_subject');
								$apartmentname=get_option('amgt_system_name');
								$message_content=get_option('wp_amgt_paid_invoice_email_template');
								$invoiceno=$payment_res->invoice_no;
								$subject_search=array('{{invoiceno}}');
								$subject_replace=array($invoiceno);
								$search=array('{{member_name}}','{{apartment_name}}','{{invoiceno}}');
								$replace = array($retrieved_data->display_name,$apartmentname,$invoiceno);
								$subject=str_replace($subject_search,$subject_replace,$subject);
								$message_content = str_replace($search, $replace, $message_content);
								$resultInvoice=getHTMLInvoice($payment_res->id,'invoice');
								$message_content.=$resultInvoice;
								$headers = 'From: '.get_option('amgt_system_name'). "\r\n";
								$headers = 'MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1' . "\r\n";
								amgtSendEmailNotification($to,$subject,$message_content,$headers);
						}
					}
				}				
		}
		else
		{				
			$invoice_data=$this->amgt_get_user_invoice($data['member_id'],$data['invoice_id']);
			if($check_unit_invoice->unit_name!='')
			{ 
				$result=$wpdb->insert( $table_name, $paymentdata );
				if($result)	
				{
					$table_invoice=$wpdb->prefix. 'amgt_created_invoice_list';
					$invoicedata['payment_status'] = $payment_status;
					$invoicedata['paid_amount']=$new_amt;
					$whereid['invoice_id']=$data['invoice_id'];
					$whereid['member_id']=$data['member_id'];											
					$sql = "UPDATE `$table_invoice` SET payment_status='{$payment_status}',paid_amount='{$new_amt}' WHERE invoice_id = {$data['invoice_id']} AND member_id = {$data['member_id']}";
					$result = $wpdb->query( $sql );	
				   //---------Notification send mail code---------------------
					$retrieved_data=get_userdata($data['member_id']);
					$to = $retrieved_data->user_email; 
					$subject =get_option('wp_amgt_paid_invoice_subject');
					$apartmentname=get_option('amgt_system_name');
					$message_content=get_option('wp_amgt_paid_invoice_email_template');
					$invoiceno=$invoice_data->invoice_no;
					$subject_search=array('{{invoiceno}}');
					$subject_replace=array($invoiceno);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$search=array('{{member_name}}','{{apartment_name}}','{{invoiceno}}');
					$replace = array($retrieved_data->display_name,$apartmentname,$invoiceno);
					$message_content = str_replace($search, $replace, $message_content);
					$resultInvoice=getHTMLInvoice($invoice_data->id,'invoice');
					$message_content.=$resultInvoice;
					amgtSendEmailNotificationWithHTML($to,$subject,$message_content);
				}
			}
			else{
				
				$result=$wpdb->insert( $table_name, $paymentdata );
				if($result)	
				{
					$table_invoice=$wpdb->prefix. 'amgt_created_invoice_list';
					$invoicedata['payment_status'] = $payment_status;
					$invoicedata['paid_amount']=$new_amt;
					$whereid['invoice_id']=$data['invoice_id'];
					$whereid['member_id']=$data['member_id'];
					$result=$wpdb->update( $table_invoice, $invoicedata ,$whereid);	
				
					$sql = "UPDATE `$table_invoice` SET payment_status='{$payment_status}',paid_amount='{$new_amt}' WHERE invoice_id = {$data['invoice_id']} AND member_id = {$data['member_id']}";
					$result = $wpdb->query( $sql );	
					//---------Notification send mail code---------------------
					$retrieved_data=get_userdata($data['member_id']);
					$to = $retrieved_data->user_email; 
					$subject =get_option('wp_amgt_paid_invoice_subject');
					$apartmentname=get_option('amgt_system_name');
					$message_content=get_option('wp_amgt_paid_invoice_email_template');
					$invoiceno=$invoice_data->invoice_no;
					$subject_search=array('{{invoiceno}}');
					$subject_search=array('{{invoiceno}}');
					$subject_replace=array($invoiceno);
					$subject=str_replace($subject_search,$subject_replace,$subject);
					$search=array('{{member_name}}','{{apartment_name}}','{{invoiceno}}');
					$replace = array($retrieved_data->display_name,$apartmentname,$invoiceno);
					$message_content = str_replace($search, $replace, $message_content);
					$resultInvoice=getHTMLInvoice($invoice_data->id,'invoice');
					$message_content.=$resultInvoice;
					amgtSendEmailNotificationWithHTML($to,$subject,$message_content);
				}
			}
		}
		return $result;	
	}	
	//GET PREVIOUS PAYMENT
	public function amgt_get_prv_payments($member_id,$invoice_id)
	{
		global $wpdb;
		$tbl = $wpdb->prefix . "amgt_created_invoice_list";
		$data = $wpdb->get_row("SELECT * FROM {$tbl} WHERE member_id={$member_id} AND invoice_id = {$invoice_id}");
		return $data;
	}
	//GET ALL INCOME
	public function amgt_get_all_income()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where type='income'");
		return $result;
	
	}
	//GET SINGLE INCOME
	public function amgt_get_single_income($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	public function amgt_delete_payment($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		$result = $wpdb->query("DELETE FROM $table_name where id= ".$id);
		return $result;
	}
	//SAVE CHARGES PAYMENT	
	public function amgt_save_charges_payment($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_charges_payments';
		$entry_value=$this->amgt_get_entry_records($data);
		
		if(isset($data['building_id']))		
		$chargesdata['building_id']=$data['building_id'];
		if(isset($data['unit_cat_id']))		
		$chargesdata['unit_cat_id']=$data['unit_cat_id'];
	   if(isset($data['unit_name']))		
		$chargesdata['unit_name']=$data['unit_name'];
		if(isset($data['member_id']))		
		$chargesdata['member_id']=$data['member_id'];
		if(isset($data['charges_id']))		
		$chargesdata['charges_type_id']=$data['charges_id'];	
		if(isset($data['discount_amount']))	
		$chargesdata['discount_amount']=$data['discount_amount'];
		$chargesdata['charges_payment']=$entry_value;
		if(isset($data['description']))	
		$chargesdata['description']=$data['description'];
		$chargesdata['created_date']=date('Y-m-d');
		$chargesdata['created_by']=get_current_user_id();
	
		if($data['action']=='edit')
		{
			$whereid['id']=$data['pay_charges_id'];
			$result=$wpdb->update( $table_name, $chargesdata ,$whereid);
			return $result;
		}
		else
		{
			$result=$wpdb->insert( $table_name, $chargesdata );
			$user_invoiceid=$wpdb->insert_id;
			//---------Notification send mail code---------------------
			$retrieved_data=get_userdata($data['member_id']);
			$to = $retrieved_data->user_email; 
			$subject =get_option('wp_amgt_add_charges_subject');
			$apartmentname=get_option('amgt_system_name');
			$page_link=home_url().'/?apartment-dashboard=user&page=message';
			$message_content=get_option('wp_amgt_add_charges_email_template');
			$invoiceno=$invoice_data->invoice_no;
			$search=array('{{member_name}}','{{apartment_name}}');
			$replace = array($retrieved_data->display_name,$apartmentname);
			$message_content = str_replace($search, $replace, $message_content);
			$resultInvoice=getHTMLInvoice($user_invoiceid,'charges');
			$message_content.=$resultInvoice;
			amgtSendEmailNotificationWithHTML($to,$subject,$message_content);
			return $result;
		}	
	}
	//SAVE CHARGES PAYMENT ALL MEMBER
	public function amgt_save_charges_payment_all_member($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_charges_payments';		
		$amgt_amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
		if($data['charge_cal']=='fix_charge')
		{
			$entry_value=$this->amgt_get_entry_records($data);
		}
		elseif($data['charge_cal']=='measurement_charge')
		{
			$entry_value=$this->amgt_get_entry_records_by_measurement($data);
		}
		if(isset($data['select_serveice']))		
		$charges_recuring_data['invoice_options']=$data['select_serveice'];
	    if(isset($data['building_id']))		
		$charges_recuring_data['building_id']=$data['building_id'];
		if(isset($data['unit_cat_id']))		
		$charges_recuring_data['unit_cat_id']=$data['unit_cat_id'];
	    if(isset($data['unit_name']))		
		$charges_recuring_data['unit_name']=$data['unit_name'];
	    if(isset($data['member_id']))		
		$charges_recuring_data['member_id']=$data['member_id'];
		if(isset($data['charges_id']))		
		$charges_recuring_data['charges_type_id']=$data['charges_id'];
		if(isset($data['charge_cal']))		
		$charges_recuring_data['charges_calculate_by']=$data['charge_cal'];
	    if(isset($data['amgt_charge_period']))		
		$charges_recuring_data['amgt_charge_period']=$data['amgt_charge_period'];
		if(isset($data['discount_amount']))	
		$charges_recuring_data['discount_amount']=$data['discount_amount'];
		$charges_recuring_data['charges_payment']=$entry_value;
		if(isset($data['description']))	
	    $charges_recuring_data['description']=$data['description'];
		if(isset($data['amount']))	
		$charges_recuring_data['amount']=$data['amount'];
	    if(isset($data['tax_amount']))	
		$charges_recuring_data['tax_amount']=$data['tax_amount'];
	    if(isset($data['total_amount']))
		$charges_recuring_data['total_amount']=$data['total_amount'];	
		$charges_recuring_data['delete_status']='0';	    
		
		if($data['action']=='edit')
		{			
			$whereid['id']=$data['pay_charges_id'];
			$result=$wpdb->update( $amgt_amgt_generat_invoice, $charges_recuring_data ,$whereid);
			
			$last_id=$data['pay_charges_id'];
			
			global $wpdb;
			$table_amgt_invoice_tax = $wpdb->prefix. 'amgt_invoice_tax';
			$result_delete_tax = $wpdb->query("DELETE FROM $table_amgt_invoice_tax where invoice_id= ".$last_id);			
			
			if($data['charge_cal']=='fix_charge')
			{
				if(!empty($data['tax_entry']))
				{
					foreach($data['tax_entry'] as $key=>$tax)
					{
						$table_invoice_tax=$wpdb->prefix.'amgt_invoice_tax';
						$invoicetax['invoice_id']=$last_id;
						$invoicetax['tax_id']=$data['tax_title'][$key];
						$invoicetax['tax']=$tax;
						//$invoicetax['tax_amount']=$data['tax_amount_entry'][$key];
						$invoicetax['created_at']=date('Y-m-d');
						$invoicetax['created_by']=get_current_user_id();
						
						$result=$wpdb->insert( $table_invoice_tax, $invoicetax );
					}
				}
			}
			elseif($data['charge_cal']=='measurement_charge')
			{
				if(!empty($data['tax_entry']))
				{
					foreach($data['tax_entry'] as $key=>$tax)
					{
						$table_invoice_tax=$wpdb->prefix.'amgt_invoice_tax';
						$invoicetax['invoice_id']=$last_id;
						$invoicetax['tax_id']=$data['tax_title'][$key];
						$invoicetax['tax']=$tax;					
						$invoicetax['created_at']=date('Y-m-d');
						$invoicetax['created_by']=get_current_user_id();
						$result=$wpdb->insert( $table_invoice_tax, $invoicetax );
					}
				}
			}			
			return $last_id;
		}
		else
		{
			$charges_recuring_data['created_by']= get_current_user_id();
			$charges_recuring_data['created_date']=date('Y-m-d');
			$result=$wpdb->insert($amgt_amgt_generat_invoice, $charges_recuring_data );
			
			$last_id=$wpdb->insert_id;
			if($data['charge_cal']=='fix_charge')
			{
				if(!empty($data['tax_entry']))
				{
					foreach($data['tax_entry'] as $key=>$tax)
					{
						$table_invoice_tax=$wpdb->prefix.'amgt_invoice_tax';
						$invoicetax['invoice_id']=$last_id;
						$invoicetax['tax_id']=$data['tax_title'][$key];
						$invoicetax['tax']=$tax;
						//$invoicetax['tax_amount']=$data['tax_amount_entry'][$key];
						$invoicetax['created_at']=date('Y-m-d');
						$invoicetax['created_by']=get_current_user_id();						
						$result=$wpdb->insert( $table_invoice_tax, $invoicetax );						
					}
				}
			}
			elseif($data['charge_cal']=='measurement_charge')
			{
				if(!empty($data['tax_entry']))
				{
					foreach($data['tax_entry'] as $key=>$tax)
					{
						$table_invoice_tax=$wpdb->prefix.'amgt_invoice_tax';
						$invoicetax['invoice_id']=$last_id;
						$invoicetax['tax_id']=$data['tax_title'][$key];
						$invoicetax['tax']=$tax;					
						$invoicetax['created_at']=date('Y-m-d');
						$invoicetax['created_by']=get_current_user_id();
						$result=$wpdb->insert( $table_invoice_tax, $invoicetax );
						
					}
				}
			}
			
			return $last_id;
		}		
	}
	//GET ENTRY RECORD
	public function amgt_get_entry_records($data)
	{
		$all_income_entry=$data['income_entry'];
		$all_income_amount=$data['income_amount'];
		
		$entry_data=array();
		$i=0;
		foreach($all_income_entry as $one_entry)
		{
			$entry_data[]= array('entry'=>$one_entry,
						'amount'=>$all_income_amount[$i]);
				$i++;
		}
		return json_encode($entry_data);
	}
	//GET ENTRY RECORD BY MEASUREMENT
	public function amgt_get_entry_records_by_measurement($data)
	{
			
		$all_income_amount=$data['income_amount'];
		
		$entry_data=array();
		$i=0;
		foreach($all_income_amount as $one_entry)
		{
			$entry_data[]= array('amount'=>$one_entry);
			$i++;
		}
		return json_encode($entry_data);
	}
	//GET ALL PAID CHARGES
	public function amgt_get_all_paid_charges()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_charges_payments';
		$result = $wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	
	}
	//GET ALL CHARGES
	public function amgt_get_all_charges()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		$result = $wpdb->get_results("SELECT * FROM $table_name where delete_status='0'");
		return $result;
	}
	public function amgt_get_own_charges($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		$result = $wpdb->get_results("SELECT * FROM $table_name where delete_status='0' and created_by=".$user_id);
		return $result;
	}
	//GET ALL RECURING CHARGES
	public function amgt_get_all_recuring_charges()
	{
		global $wpdb;
		$amgt_recuring_charges_payments = $wpdb->prefix. 'amgt_recuring_charges_payments';
		$result = $wpdb->get_results("SELECT * FROM $amgt_recuring_charges_payments");
		return $result;	
	}
	//GET SINGLE RECURING CHARGES LIST
	public function amgt_get_single_recuring_charges_list($id)
	{
		global $wpdb;
		$amgt_recuring_charges_payments = $wpdb->prefix. 'amgt_recuring_charges_payments';
		$result = $wpdb->get_row("SELECT * FROM $amgt_recuring_charges_payments where id=".$id);
		return $result;
	}
	//GET SINGLE CHARGES LIST
	public function amgt_get_single_charges_list($id)
	{
		global $wpdb;
		$amgt_amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
		$result = $wpdb->get_row("SELECT * FROM $amgt_amgt_generat_invoice where id=".$id);
		return $result;
	}
	//DELETE RECURING CHARGES
	public function amgt_delete_recuring_charges($id)
	{
		global $wpdb;
		$amgt_recuring_charges_payments = $wpdb->prefix. 'amgt_recuring_charges_payments';
		$result = $wpdb->query("DELETE FROM $amgt_recuring_charges_payments where id= ".$id);
		return $result;
	}
	//GET CHARGES TOTAL
	public function amgt_get_charges_total($data)
	{
		$all_income_entry=$data['income_entry'];
		 $all_income_amount=$data['income_amount'];
		
		$entry_data=array();
		$i=0;
		foreach($all_income_entry as $one_entry)
		{
			$entry_data[]= array('entry'=>$one_entry,
						'amount'=>$all_income_amount[$i]);
				$i++;
		}
		return json_encode($entry_data);
	}
	//GET SINGLE PAID CHARGES LIST
	public function amgt_get_single_paid_charges_list($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_charges_payments';
		$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$id);
		return $result;
	}
	//DELETE PAID CHARGES
	public function amgt_delete_paid_charges($id)
	{		
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';		
		$data['delete_status']='1';
		$whereid['id']=$id;
		$result = $wpdb->update( $table_name, $data, $whereid );
		
		return $result;
	}
	//GET USER INVOCIE
	function amgt_get_user_invoice($member,$invoiceid)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		$result = $wpdb->get_row("SELECT * FROM $table_name where invoice_id=".$invoiceid." AND member_id=".$member);
		return $result;
	}
	//GET CHARGES MONTHALY
	function amgt_get_chargis_monthaly($chargis_option,$recuring_date_new1)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		$result = $wpdb->get_results("SELECT * FROM $table_name where amgt_charge_period=$chargis_option and invoice_end_date <='$recuring_date_new1' AND delete_status='0'");
		return $result;
	}
	//GET CHARGES QUARTLY
	function amgt_get_chargis_quarterly($chargis_option,$recuring_date_new1)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		$result = $wpdb->get_results("SELECT * FROM $table_name where amgt_charge_period=$chargis_option and invoice_end_date<='$recuring_date_new1' AND delete_status='0'");
		return $result;
	}
	function amgt_get_chargis_yearly($chargis_option,$recuring_date_new1)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		$result = $wpdb->get_results("SELECT * FROM $table_name where amgt_charge_period=$chargis_option and invoice_end_date<='$recuring_date_new1' AND delete_status='0'");
		return $result;
	}
	//Check ALLREADY Member Generated Invocie //
	function amgt_member_invoice_allready_generated($member_id,$start_date,$end_date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		$result = $wpdb->get_row("SELECT * FROM $table_name where member_id=$member_id AND start_date='$start_date' AND end_date='$end_date'");
		
		if(!empty($result))
		{
			$generated_status='1';
		}
		else
		{
			$generated_status='0';
		}
		return $generated_status;
	}
	//CHECK INVOCIE PAYMENT
	function amgt_check_invoice_payment($member,$invoiceid)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_income_expense';
		$result = $wpdb->get_row("SELECT * FROM $table_name where invoice_id=".$invoiceid." AND member_id=".$member);
		
		return $result;
	}
	//GET RECURING MAINTENANCE CHARGES
	function amgt_get_recuring_maintenance_charge($to_date,$building_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		return $result = $wpdb->get_results("SELECT * FROM $table_name where to_date='$to_date' AND building_id=$building_id");		
	}
	//CHECK INVOCIE GENERATED
	function amgt_check_invoice_gebnerated($building_id,$year,$from_date,$to_date)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_generat_invoice';
		return $result = $wpdb->get_row("SELECT * FROM $table_name where building_id=".$building_id." 
										AND year='".$year."'
										AND from_date='".$from_date."' 
										AND to_date='".$to_date."' 
										");														
	}
	//CHECK MEMBER INVOCIE
	function amgt_CheckMemberInvoice($member_id,$invoice_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		return $result = $wpdb->get_row("SELECT * FROM $table_name where invoice_id=".$invoice_id." AND member_id=".$member_id);
	}
	//CHECK MEMBER INVOCIE PAYMENT STATUS	
	function amgt_CheckMemberInvoice_payment_status($member_id,$start_date)
	{		
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		return $result = $wpdb->get_row("SELECT * FROM $table_name where created_date=".$start_date." AND member_id=".$member_id);
	}
	//INVOCIE PAYMENT BY MEMBER
	function amgt_invoice_payment_by_member($invoice_id,$member_id)
	{		
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_invoice_payment_history';
		$result = $wpdb->get_results("SELECT * FROM $table_name where invoice_id=$invoice_id AND member_id=$member_id");
		return $result;
	}
	//GET ALL INVOICE DASHBOARD
	public function amgt_get_all_invoice_dashboard()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where status!='Deactivate' order by 'created_date' DESC limit 3");
		return $result;
	
	}
	//GET ALL INVOICE OF MEMBER FORDASHBOARD
	public function amgt_get_member_all_invoice_dashboard()
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
		$current_user_id = get_current_user_id();
		$result = $wpdb->get_results("SELECT * FROM $table_name where member_id='$current_user_id' order by 'created_date' DESC limit 3");
		return $result;	
	}
	//GET OWN INVOICE OF MEMBER FORDASHBOARD
	public function amgt_get_own_invoice_dashboard($user_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_created_invoice_list';
	
		$result = $wpdb->get_results("SELECT * FROM $table_name where status!='Deactivate' AND created_by='$user_id' order by 'created_date' DESC limit 3");
		return $result;
	
	}
	
}
?>