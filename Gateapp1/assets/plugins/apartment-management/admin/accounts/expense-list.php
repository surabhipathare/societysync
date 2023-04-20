<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	"use strict";
	//EXPENCE_LIST
	jQuery('#expence_list').DataTable({
		"responsive": true,
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
  
<div class="panel-body"><!--PANEL-BODY-->  
    <div class="table-responsive"><!---TABLE-RESPONSIVE--->
        <table id="expence_list" class="display" cellspacing="0" width="100%"><!---EXPENCE_LIST TABLE--->
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
				$expensedata=$obj_account->amgt_get_all_expense();
				if(!empty($expensedata))
				{
					foreach ($expensedata as $retrieved_data)
					{ ?>
						<tr>
							<td class="expense_type"><a href="?page=amgt-accounts&tab=add-expense&action=edit&expense_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo get_the_title($retrieved_data->type_id);?></a></td>
							<td class="name"><?php echo esc_html($retrieved_data->vender_name);?></td>
							<td class="amount"><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($retrieved_data->amount);?></td>
							<td class="paymentdate"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->payment_date));?></td>
							<td class="action">
							<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo esc_attr($retrieved_data->id); ?>" invoice_type="expense"><i class="fa fa-eye"></i> <?php esc_html_e('View Invoice', 'apartment_mgt');?></a>
						  <a href="?page=amgt-accounts&tab=add-expense&action=edit&expense_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
							<a href="?page=amgt-accounts&tab=invoice-list&action=delete&expense_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
							<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
							</td>
						   
						</tr>
					<?php 
					} 			
				}
				?>     
			</tbody>        
        </table><!---END EXPENCE_LIST TABLE--->
    </div><!---END TABLE-RESPONSIVE--->
</div><!--END PANEL-BODY-->
<?php  ?>