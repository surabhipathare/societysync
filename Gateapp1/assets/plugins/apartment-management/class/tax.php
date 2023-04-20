<?php
class Amgt_Tax
{	
    //ADD TAX FUNCTON
	public function amgt_add_tax($data)
	{
		
		global $wpdb;
		$table_amgt_taxes = $wpdb->prefix. 'amgt_taxes';
		$taxdata['tax_title']=MJamgt_strip_tags_and_stripslashes($data['tax_title']);
		$taxdata['tax']=MJamgt_strip_tags_and_stripslashes($data['tax']);
		$taxdata['created_at']=date('Y-m-d');
		if($data['action']=='edit')
		{
			$whereid['id']=$data['tax_id'];
			$result=$wpdb->update( $table_amgt_taxes, $taxdata ,$whereid);
			return $result;
		}
		else
		{
			
			$result=$wpdb->insert( $table_amgt_taxes, $taxdata );
			return $result;
		}
	
	}
	//GET ALL TAX FUNCTION
	public function Amgt_get_all_tax()
	{
		global $wpdb;
		$table_amgt_taxes = $wpdb->prefix. 'amgt_taxes';
	
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_taxes");
		return $result;
	
	}
	// GET ALL TAX BY CHARGE ID
	public function amgt_get_all_tax_by_charge_id($id)
	{
		global $wpdb;
		$table_amgt_invoice_tax = $wpdb->prefix. 'amgt_invoice_tax';
	
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_invoice_tax where invoice_id= ".$id);
		return $result;	
	}
	//GET SINGLE TAX FUNCTION
	public function amgt_get_single_tax($id)
	{
		global $wpdb;
		$table_amgt_taxes = $wpdb->prefix. 'amgt_taxes';
		$result = $wpdb->get_row("SELECT * FROM $table_amgt_taxes where id=".$id);
		return $result;
	}
	//DELETE TAX FUNCTION
	public function amgt_delete_tax($id)
	{
		global $wpdb;
		$table_amgt_taxes = $wpdb->prefix. 'amgt_taxes';
		$result = $wpdb->query("DELETE FROM $table_amgt_taxes where id= ".$id);
		return $result;
	}
	//GET INVOICE TAX FUNCTION
	public function amgt_GetInvoiceTax($invoiceid)
	{
		
		global $wpdb;
		$table_amgt_invoice_tax = $wpdb->prefix. 'amgt_invoice_tax';
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_invoice_tax where invoice_id=".$invoiceid);
		return $result;
	}
	// GET INVOICE TAX BY MEMBER ID
	public function amgt_GetInvoiceTax_by_memberid($invoiceid,$member_id)
	{
		
		global $wpdb;
		$table_amgt_invoice_tax = $wpdb->prefix. 'amgt_invoice_tax';
		$result = $wpdb->get_results("SELECT * FROM $table_amgt_invoice_tax where invoice_id=$invoiceid and member_id=$member_id");
		return $result;
	}
	//GET TEXT TITLE FUNCTION
	public function amgt_GetTaxtitle($id)
	{
		global $wpdb;
		$tax_title=esc_html__('Tax Title','apartment_mgt');
		$table_amgt_taxes = $wpdb->prefix. 'amgt_taxes';
		$result = $wpdb->get_row("SELECT * FROM $table_amgt_taxes where id=".$id);
		if(!empty($result))
		{
			$tax_title=$result->tax_title;
		}
		return $tax_title;
	}
	
}
?>