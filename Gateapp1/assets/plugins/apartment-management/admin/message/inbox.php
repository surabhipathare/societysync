<?php 
?>
<div class="mailbox-content"><!-- MAIL BOIX CONTENT DIV -->
 	<div class="table-responsive"><!--TABLE RESPONSIVE-->	
 	<table class="table"><!-- TABLE -->
 		<thead>
 			<tr>
 				<th class="text-right" colspan="5">
					 <?php 
					$message = $obj_message->amgt_count_inbox_item(get_current_user_id());
				  
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
				    echo $obj_message->amgt_pagination($totlal_message,$p,$prev,$next,'page=amgt-message&tab=inbox');?>
                </th>
 			</tr>
 		</thead>
 		<tbody>
 		<tr>
 			<th class=""><!--HIDDEN-XS -->
            	<span><?php esc_html_e('Message For','apartment_mgt');?></span>
            </th>
            <th><?php esc_html_e('Subject','apartment_mgt');?></th>
            <th>
                  <?php esc_html_e('Description','apartment_mgt');?>
            </th>
			<th>
                  <?php esc_html_e('Date','apartment_mgt');?>
            </th>
        </tr>
 		<?php 
 		$message = $obj_message->amgt_get_inbox_message(get_current_user_id(),$limit,$max);
 		foreach($message as $msg)
 		{
 			?>
 		<tr>
 			
			<td><?php echo amgt_get_display_name($msg->sender);?></td>
			<td>
				 <a href="?page=amgt-message&tab=inbox&tab=view_message&from=inbox&id=<?php echo esc_attr($msg->message_id
				 );?>"> <?php echo wordwrap($msg->msg_subject,10,"<br>\n",TRUE);?><?php if($obj_message->amgt_count_reply_item($msg->post_id)>=1){?><span class="badge badge-success pull-right"><?php echo $obj_message->amgt_count_reply_item($msg->post_id);?></span><?php } ?></a>
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
 	</table><!-- TABLE -->
 </div><!--TABLE RESPONSIVE END-->
</div> <!-- END MAIL BOIX CONTENT DIV -->
 <?php ?>