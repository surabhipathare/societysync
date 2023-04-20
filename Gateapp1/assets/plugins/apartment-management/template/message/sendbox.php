<?php 
	// This is Class at admin side!!!!!!!!! 
?> 
	<div class="mailbox-content">
	<div class="table-responsive">
 	<table class="table">
 		<thead>
 			<tr>
 				<th class="text-right" colspan="5">
               	<?php 
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
               	$totlal_message1 = $obj_message->amgt_count_send_item(get_current_user_id());
               	$totlal_message = ceil($totlal_message1 / $max);
               	$lpm1 = $totlal_message - 1;               	
               	$offest_value = ($p-1) * $max;
               	echo $obj_message->amgt_pagination($totlal_message,$p,$lpm1,$prev,$next);
               	
               	?>
                </th>
 			</tr>
 		</thead>
 		<tbody>
 		<tr>
 			<th class="hidden-xs">
            	<b><span><?php esc_html_e('Message For','apartment_mgt');?></b></span>
            </th>
			   
            <th><b><?php esc_html_e('Subject','apartment_mgt');?></b></th>
             <th>
                 <b> <?php esc_html_e('Description','apartment_mgt');?></b>
            </th>
            </tr>
 		<?php 
 		$offset = 0;
 		if(isset($_REQUEST['pg']))
 			$offset = $_REQUEST['pg'];
 		$message = $obj_message->amgt_get_send_message(get_current_user_id(),$max,$offset);
 		foreach($message as $msg_post)
 		{
 			if($msg_post->post_author==get_current_user_id())
 			{ ?>
 			<tr>
 			<td class="hidden-xs">
            	<span><?php 
            		if(get_post_meta( $msg_post->ID, 'message_for',true) == 'user')
					{
						if(get_post_meta( $msg_post->ID, 'message_for_userid',true) == 'employee')
						{
							echo "Employee";
						}
						else
						{
            			   echo amgt_get_display_name(get_post_meta( $msg_post->ID, 'message_for_userid',true));
						}
					}
            		else 
					{
            			echo amgt_get_role_name(get_post_meta($msg_post->ID, 'message_for',true));
					}
					?></span>
            </td>
			<td><a href="?apartment-dashboard=user&page=message&tab=view_message&from=sendbox&id=<?php echo  esc_attr($msg_post->ID);?>"><?php echo wordwrap($msg_post->post_title,10,"<br>\n",TRUE);?><?php if($obj_message->amgt_count_reply_item($msg_post->ID)>=1){?><span class="badge badge-success pull-right"><?php echo $obj_message->amgt_count_reply_item($msg_post->ID);?></span><?php } ?></a></td>
             <td>                  
				  <?php 					
					echo wordwrap($msg_post->post_content,30,"<br>\n",TRUE);
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
 </div>
 <?php ?>