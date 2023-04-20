<?php 
//Subject
//var_dump($school_obj->subject);

?>
<div class="mailbox-content">
<div class="table-responsive">
 	<table class="table">
 		<thead>
 			<tr>
 				
                <th class="text-right" colspan="5">
               <?php $message = $obj_message->amgt_count_inbox_item(get_current_user_id());
              // var_dump($message);
 		$max = 10;
 		if(isset($_GET['pg'])){
 			$p = $_GET['pg'];
 		}else{
 			$p = 1;
 		}
 		 
 		$limit = ($p - 1) * $max;
 		$prev = $p - 1;
 		$next = $p + 1;
 		$limits = (int)($p - 1) * $max;
 		$totlal_message =count($message);
 		$totlal_message = ceil($totlal_message / $max);
 		$lpm1 = $totlal_message - 1;
 		$offest_value = ($p-1) * $max;
 		echo $obj_message->amgt_pagination($totlal_message,$p,$prev,$next,'church-dashboard=user&&page=message&tab=inbox');?>
                </th>
 			</tr>
 		</thead>
 		<tbody>
 		<tr>
 			
 			<th class="hidden-xs">
            	<b><span><?php esc_html_e('Message For','apartment_mgt');?></b></span>
            </th>
            <th><b><?php esc_html_e('Subject','apartment_mgt');?></b></th>
             <th><b><?php esc_html_e('Description','apartment_mgt');?></b></th>
             <th><b><?php esc_html_e('Date','apartment_mgt');?></b></th>
            </tr>
 		<?php 
 		
 		
 		$message = $obj_message->amgt_get_inbox_message(get_current_user_id(),$limit,$max);
 		
 		foreach($message as $msg)
 		{
 			?>
 			 <tr>
 			
            <td><?php echo amgt_get_display_name($msg->sender);//echo get_user_name_byid($msg->sender);?></td>
             <td>
                 <a href="?apartment-dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo esc_attr($msg->message_id);?>"> <?php echo wordwrap($msg->msg_subject,10,"<br>\n",TRUE);?><?php if($obj_message->amgt_count_reply_item($msg->post_id)>=1){?><span class="badge badge-success pull-right"><?php echo $obj_message->amgt_count_reply_item($msg->post_id);?></span><?php } ?></a>
            </td>
            <td>
			<?php echo wordwrap($msg->message_body,30,"<br>\n",TRUE);?>
            </td>
            <td>
                <?php  echo date(amgt_date_formate(),strtotime($msg->msg_date ));?> 
            </td>
            </tr>
 			<?php 
 		}
 		?>
 		
 		</tbody>
 	</table>
 	</div>
 </div>
 <?php ?>