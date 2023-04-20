<?php

$obj_account =new Amgt_Accounts;
$obj_payment= new Amgt_Accounts();
$p 	= new Amgt_paypal_class(); // paypal class
	//$p->admin_mail 	= GMS_EMAIL_ADD; // set notification email
//$action 		= $_REQUEST["fees_pay_id"];

if(isset($_REQUEST["invoice_id"]))
{
	//echo $_REQUEST["mp_id"];
	
	$inv_amount=0;
	/* $feepaydata = $obj_account->amgt_get_single_invoice_by_id($_REQUEST["mp_id"]);*/
	$invoiceid=$_REQUEST['invoice_id'];
	//$invoice_res = $obj_account->amgt_get_single_invoice($feepaydata->invoice_id);
	$invoice_res =$obj_payment->amgt_get_single_invoice_by_id($_REQUEST['invoice_id']);	
	
	$item_name='Invoice';
	$inv_amount=$invoice_res->total_amount;
	$user_id  = $invoice_res->member_id;
}

$user_info = get_userdata($user_id);
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$p->add_field('business', get_option('apartment_paypal_email')); // Call the facilitator eaccount

$p->add_field('cmd', '_cart'); // cmd should be _cart for cart checkout
$p->add_field('upload', '1');
$p->add_field('return', home_url().'/?apartment-dashboard=user&page=accounts&action=success'); // return URL after the transaction got over
$p->add_field('cancel_return', home_url().'/?apartment-dashboard=user&page=accounts&action=cancel'); // cancel URL if the trasaction was cancelled during half of the transaction
$p->add_field('notify_url', home_url().'/?apartment-dashboard=user&page=accounts&action=ipn'); // Notify URL which received IPN (Instant Payment Notification)
$p->add_field('currency_code', get_option( 'apartment_currency_code' ));
$p->add_field('invoice', date("His").rand(1234, 9632));
$p->add_field('item_name_1','invoice');
$p->add_field('item_number_1', 4);
$p->add_field('quantity_1', 1);
//$p->add_field('amount_1', get_membership_price(get_user_meta($user_id,'membership_id',true)));
$p->add_field('amount_1', $_POST['amount']);
//$p->add_field('amount_1', 1);//Test purpose
$p->add_field('first_name',$user_info->first_name);
$p->add_field('last_name', $user_info->last_name);
$p->add_field('address1',$user_info->address);
$p->add_field('city', $user_info->city_name);
$p->add_field('custom', $user_id."_".$invoiceid."_".$inv_amount);
$p->add_field('rm',2);
		
$p->add_field('state', get_user_meta($user_id,'state_name',true));
$p->add_field('country', get_option( 'amgt_contry' ));
//$p->add_field('zip', get_user_meta($user_id,'zip_code',true));
$p->add_field('email',$user_info->user_email);
$p->submit_paypal_post(); // POST it to paypal
//$p->dump_fields(); // Show the posted values for a reference, comment this line before app goes live
//exit;
?>