<script>
jQuery(document).ready(function() {
  jQuery("span.timeago").timeago();
});
</script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-timeago.js'; ?>"></script>
<?php 
//SUBJECT
$obj_message = new Amgt_message();
if($_REQUEST['from']=='sendbox')
{
	$message = get_post($_REQUEST['id']);
	if(isset($_REQUEST['delete']))
	{
	wp_delete_post($_REQUEST['id']);
	wp_safe_redirect(home_url()."?apartment-dashboard=user&page=message&tab=sentbox" );
	exit();
	}
	
	$box='sendbox';
}
if($_REQUEST['from']=='inbox')//INBOX
{
	amgt_change_read_status($_REQUEST['id']);
	$message = $obj_message->amgt_get_message_by_id($_REQUEST['id']);
	$box='inbox';
	
}
	if(isset($_REQUEST['delete']))
	{
			echo $_REQUEST['delete'];
			
			$obj_message->amgt_delete_message($_REQUEST['id']);
			wp_safe_redirect(home_url()."?apartment-dashboard=user&page=message&tab=inbox&message=2" );
			exit();
	}
if(isset($_POST['replay_message']))//REPLAY MESSAGE
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	$result=$obj_message->amgt_send_replay_message($_POST);
	if($result)
		wp_safe_redirect(home_url()."?apartment-dashboard=user&page=message&tab=view_message&from=".$message_from."&id=$message_id&message=1" );
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete-reply')//DELETE REPLAY
{
				$message_id=$_REQUEST['id'];
				$message_from=$_REQUEST['from'];
				
				$result=$obj_message->amgt_delete_reply($_REQUEST['reply_id']);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=message&tab=view_message&from='.$message_from.'&id='.$message_id.'&message=2');
				}
}
?>
<div class="mailbox-content"><!--MAILBOX-CONTENT--->
 	<div class="message-header"><!--MESSAGE-HEADER--->
		<h3><span><?php esc_html_e('Subject','apartment_mgt')?> :</span>  <?php if($box=='sendbox'){ echo esc_attr($message->post_title); 
	} else{ echo esc_attr($message->msg_subject); } ?></h3>
        <p class="message-date"><?php  if($box=='sendbox') {  echo  date(amgt_date_formate(),strtotime($message->post_date )); } else  {  echo  date(amgt_date_formate(),strtotime($message->msg_date )) ;  }?></p>
	</div><!--END MESSAGE-HEADER--->
	<div class="message-sender"> <!--MESSAGE-SENDER--->                               
    	<p><?php if($box=='sendbox')
		{
			$message_for=get_post_meta($_REQUEST['id'],'message_for',true);
			echo "From: ".amgt_get_display_name($message->post_author)."<span>&lt;".amgt_get_emailid_byuser_id($message->post_author)."&gt;</span><br>";
			if($message_for == 'user')
			{
				if(get_post_meta( $_REQUEST['id'], 'message_for_userid',true) == 'employee')
				{
					echo "To: ".esc_html__('Employee','apartment_mgt');
				}
				else
				{
					 echo "To: ".amgt_get_display_name(get_post_meta($_REQUEST['id'],'message_for_userid',true))."<span>&lt;".amgt_get_emailid_byuser_id(get_post_meta($_REQUEST['id'],'message_for_userid',true))."&gt;</span><br>";
				}
			}
			else
			{
			  echo "To: ".esc_html__('Group','apartment_mgt');
			}?>
			<?php 
	    } 
		else
		{ 
			echo "From: ".amgt_get_display_name($message->sender)."<span>&lt;".amgt_get_emailid_byuser_id($message->sender)."&gt;</span><br> To: ".amgt_get_display_name($message->receiver);  ?> <span>&lt;<?php echo amgt_get_emailid_byuser_id($message->receiver);?>&gt;</span>
		<?php 
		}?>
		</p>
    </div><!--END MESSAGE-SENDER--->
   
     <div class="message-content"><!--MESSAGE CONTENT--->
    	<p><?php 
		$receiver_id=0;
		if($box=='sendbox')
		{ echo wordwrap($message->post_content,120,"<br>\n",TRUE); $receiver_id=(get_post_meta($_REQUEST['id'],'message_for_userid',true)); } else{ echo wordwrap($message->message_body,120,"<br>\n",TRUE);
		$receiver_id=$message->sender;}?></p>
		<?php
		if($user_access['delete']=='1' && $message->sender == get_current_user_id())
		{?>
		<div class="message-options pull-right">
			<a class="btn btn-default" href="?apartment-dashboard=user&page=message&tab=view_message&id=<?php echo esc_attr($_REQUEST['id']);?>&from=<?php echo esc_attr($box);?>&delete=1" onclick="return confirm('<?php esc_html_e('Are you sure you want to delete this record?','apartment_mgt');?>');"><i class="fa fa-trash m-r-xs"></i><?php esc_html_e('Delete','apartment_mgt')?></a> 
	   </div>
	   <?php
		}?>
    </div><!--END MESSAGE CONTENT--->
    <?php if(isset($_REQUEST['from']) && $_REQUEST['from']=='inbox')
				$allreply_data=$obj_message->amgt_get_all_replies($message->post_id);
			else
				$allreply_data=$obj_message->amgt_get_all_replies($_REQUEST['id']);
		foreach($allreply_data as $reply)
		{?>
			<div class="message-content">
				<p><?php echo $reply->message_comment;?><br><h5>Reply By: <?php echo amgt_get_display_name($reply->sender_id);
				if($reply->sender_id==get_current_user_id()){ ?>
					<?php
				if($user_access['delete']=='1')
				{?>
				<span class="comment-delete">
				<a href="?apartment-dashboard=user&page=message&tab=view_message&action=delete-reply&from=<?php echo $_REQUEST['from'];?>&id=<?php echo esc_attr($_REQUEST['id']);?>&reply_id=<?php echo esc_attr($reply->id);?>"><?php esc_html_e('Delete','apartment_mgt');?></a></span> 
					 <?php
				}?>
				<?php } ?>
				<span class="timeago" title="<?php echo amgt_convert_time($reply->created_date);?>"></span></h5> 
			
				</p>
			</div>
		<?php } ?>
   <form name="message-replay" method="post" id="message-replay"><!--MESSAGE REPLAY FORM--->
   <input type="hidden" name="message_id" value="<?php if($_REQUEST['from']=='sendbox') echo esc_attr($_REQUEST['id']); else echo esc_attr($message->post_id);?>">
   <input type="hidden" name="user_id" value="<?php echo get_current_user_id();?>">		
	<input type="hidden" name="receiver_id" value="<?php echo esc_attr($receiver_id);?>">   
   <input type="hidden" name="from" value="<?php echo esc_attr($_REQUEST['from']);?>">
  
    <div class="message-content">
     <div class="col-sm-8">
        <textarea name="replay_message_body" maxlength="150" id="replay_message_body" class="form-control text-input"></textarea>
		
	   </div>
	   <div class="message-options pull-right reply-message-btn">
			<button type="submit" name="replay_message" class="btn btn-default"><i class="fa fa-reply m-r-xs"></i><?php esc_html_e('Reply','apartment_mgt')?></button>
	   </div>
    </div>
	</form><!--END MESSAGE REPLAY FORM--->
 </div><!--END MAILBOX-CONTENT--->
 <?php ?>