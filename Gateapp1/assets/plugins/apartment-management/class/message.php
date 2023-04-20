<?php 
class Amgt_message
{	
	 //ADD MESSAGE FUNCTION
	public function amgt_add_message($data)
	{
		$result="";
		global $wpdb;
		$table_message=$wpdb->prefix."amgt_message";
		$created_date = date("Y-m-d H:i:s");
		$subject = MJamgt_strip_tags_and_stripslashes($data['subject']);
		$message_body = MJamgt_strip_tags_and_stripslashes($data['message_body']);
		$role=$data['receiver'];
		$roless=$data['receiver'];		
		$sendmail=0;
		if(isset($_POST['amgt_send_message_mail']))
		{
			$sendmail=$_POST['amgt_send_message_mail'];
		}
		
		if($roless == 'member' || $roless=='staff_member' || $roless == 'accountant' || $roless == 'gatekeeper' || $roless=='committee_member')
		{ 
			if($roless=='committee_member')
			{
				$args = array('meta_key'=>'committee_member','meta_value'=>'yes');
				$userdata = get_users($args);
			}
			else{			
				$userdata=get_users(array('role'=>$roless));	
			}
		
			if(!empty($userdata))
			{		
				$mail_id = array();			
				foreach($userdata as $user)
				{
					$mail_id[]=$user->ID;
				}
			
				$post_id = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'amgt_message',
						'post_title' => $subject,
						'post_content' =>$message_body
				) );
				foreach($mail_id as $user_id)
				{				
					if($sendmail==1)
					{
						$userdata=get_userdata( $user_id );
						$to = $userdata->user_email;
						$subject = $subject;
						$body = $message_body;
						wp_mail( $to, $subject, $body );
					}
					$reciever_id = $user_id;
					$message_data=array('sender'=>get_current_user_id(),
							'receiver'=>$user_id,
							'msg_subject'=>$subject,
							'message_body'=>$message_body,
							'msg_date'=>$created_date,
							'post_id'=>$post_id,
							'msg_status' =>0
					);
					$result=$wpdb->insert( $table_message, $message_data);
					//ADD MESAAGE THAT TIME SEND  MAIL 
					$reciever_data=get_userdata($user_id);
					$reciever_name=$reciever_data->user_login;
					$to=$reciever_data->user_email;
					$sender_id=get_current_user_id();
					$senderdata=get_userdata($sender_id);
					$sendername_name=$senderdata->user_login;
					$apartmentname=get_option('amgt_system_name');
				    if (is_super_admin ())
					{
					   $page_link=admin_url().'admin.php?page=amgt-message';
					}
					else
					{
					 $page_link=home_url().'/?apartment-dashboard=user&page=message';
					}
					$subject1 =get_option('wp_amgt_Message_Received_subject');
					$subject_search=array('{{Sender Name}}','{{Apartment Name}}');
					$subject_replace=array($sendername_name,$apartmentname);
					$mail_subject=str_replace($subject_search,$subject_replace,$subject1);
					$message_content=get_option('wp_amgt_Message_Received_Template');
					$search=array('{{Receiver Name}}','{{Sender Name}}','{{Message Content}}','{{Apartment Name}}','{{Message_Link}}');
					$replace = array($reciever_name,$sendername_name,$message_body,$apartmentname,$page_link);
					$message_content = str_replace($search, $replace, $message_content);
					amgtSendEmailNotification($to,$mail_subject,$message_content);
			         
				}	
				$resultss=add_post_meta($post_id, 'message_for',$roless);
				$result =1;
			}
		}
		else 
		{
			$user_id = $data['receiver'];			
			if(strpos($user_id,"rp_") == false ) // Only user selected but not group/building
			{  
				$post_id = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'amgt_message',
						'post_title' => $subject,
						'post_content' =>$message_body			
				) );
				if($sendmail==1)
				{
					$userdata=get_userdata( $user_id );
						$to = $userdata->user_email;
						$subject = $subject;
						$body = $message_body;
						wp_mail( $to, $subject, $body );
				}
				$message_data=array('sender'=>get_current_user_id(),	
						'receiver'=>$user_id,
						'msg_subject'=>$subject,
						'message_body'=>$message_body,
						'post_id'=>$post_id,
						'msg_date'=>$created_date,
						'msg_status' =>0
				);
				$result=$wpdb->insert($table_message, $message_data );
				$result=add_post_meta($post_id, 'message_for','user');
				$result=add_post_meta($post_id, 'message_for_userid',$user_id);
				//ADD MESAAGE THAT TIME SEND  MAIL 
				 if (is_super_admin ()) 
				   {
					 $page_link=admin_url().'admin.php?page=amgt-message';
				   }
				  else
				  {
				   $page_link=home_url().'/?apartment-dashboard=user&page=message';
				  }
				$reciever_data=get_userdata($user_id);
				$reciever_name=$reciever_data->user_login;
				$to=$reciever_data->user_email;
				$sender_id=get_current_user_id();
				$senderdata=get_userdata($sender_id);
				$sendername_name=$senderdata->user_login;
				$apartmentname=get_option('amgt_system_name');
				$subject1 =get_option('wp_amgt_Message_Received_subject');
				$subject_search=array('{{Sender Name}}','{{Apartment Name}}');
		        $subject_replace=array($sendername_name,$apartmentname);
			    $mail_subject=str_replace($subject_search,$subject_replace,$subject1);
				$message_content=get_option('wp_amgt_Message_Received_Template');
			    $search=array('{{Receiver Name}}','{{Sender Name}}','{{Message Content}}','{{Apartment Name}}','{{Message_Link}}');
				$replace = array($reciever_name,$sendername_name,$message_body,$apartmentname,$page_link);
				$message_content = str_replace($search, $replace, $message_content);
				amgtSendEmailNotification($to,$mail_subject,$message_content);
			// end send mail
			}
			else 
			{ 
	
				$building_id = explode("_",$user_id);
				$building_id = $building_id[1];
				$user_list = amgt_get_building_members($building_id);
				if(!empty($user_list))
				{
					foreach($user_list as $users)
					{
						if(!empty($users->ID))
						{
							$user_id = (int)$users->ID;
							$post_id = wp_insert_post( array(
							'post_status' => 'publish',
							'post_type' => 'amgt_message',
							'post_title' => $subject,
							'post_content' =>$message_body			
							) );
							if($sendmail==1)
							{
								$userdata=get_userdata( $user_id );
									$to = $userdata->user_email;
									$subject = $subject;
									$body = $message_body;
									wp_mail( $to, $subject, $body );
							}
							$message_data=array('sender'=>get_current_user_id(),	
									'receiver'=>$user_id,
									'msg_subject'=>$subject,
									'message_body'=>$message_body,
									'post_id'=>$post_id,
									'msg_date'=>$created_date,
									'msg_status' =>0
							);
							$result=$wpdb->insert($table_message, $message_data );
							$result=add_post_meta($post_id, 'message_for','user');
							$result=add_post_meta($post_id, 'message_for_userid',$user_id);	
							
							//ADD MESAAGE THAT TIME SEND  MAIL 
							
							$reciever_data=get_userdata($user_id);
							$reciever_name=$reciever_data->user_login;
							$to=$reciever_data->user_email;
							$sender_id=get_current_user_id();
							$senderdata=get_userdata($sender_id);
							$sendername_name=$senderdata->user_login;
							$apartmentname=get_option('amgt_system_name');
							if (is_super_admin ())
								{
									$page_link=admin_url().'admin.php?page=amgt-message';
								}
								else{
									$page_link=home_url().'/?apartment-dashboard=user&page=message';
								}
							$message_content=get_option('wp_amgt_Message_Received_Template');
							$subject1 =get_option('wp_amgt_Message_Received_subject');
							$search=array('{{Receiver Name}}','{{Sender Name}}','{{Message Content}}','{{Apartment Name}}','{{Message_Link}}');
							$replace = array($reciever_name,$sendername_name,$message_body,$apartmentname,$page_link);
							$message_content = str_replace($search, $replace, $message_content);
							amgtSendEmailNotification($to,$subject1,$message_content);
						   // end send mail
						}
					}
				}
				
			}
		}
		return $result;
	}
	// DELETE MESSAGE
	public function amgt_delete_message($mid)
	{
		global $wpdb;
		$table_hmgt_message = $wpdb->prefix. 'amgt_message';
		$result = $wpdb->query("DELETE FROM $table_hmgt_message where message_id= ".$mid);
		return $result;
	}
	//COUNT SEND MESSGAE FUNCTION
	public function amgt_count_send_item($user_id)
	{
		global $wpdb;
		$posts = $wpdb->prefix."posts";
		$total =$wpdb->get_var("SELECT Count(*) FROM ".$posts." Where post_type = 'amgt_message' AND post_author = $user_id");
		return $total;
	}
	//COUNT INBOX MESSGAE FUNCTION
	public function amgt_count_inbox_item($user_id)
	{
		global $wpdb;
		$tbl_name_message = $wpdb->prefix .'amgt_message';
				
		$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name_message where receiver = $user_id AND msg_status = 0");
		return $inbox;
	}
	// GET INBOX MESSAGE
	public function amgt_get_inbox_message($user_id,$p=0,$lpm1=10)
	{
		global $wpdb;
		$tbl_name_message = $wpdb->prefix .'amgt_message';
		$tbl_name_message_replies = $wpdb->prefix .'amgt_message_replies';
		$inbox = $wpdb->get_results("SELECT DISTINCT b.message_id, a.* FROM $tbl_name_message a LEFT JOIN $tbl_name_message_replies b ON a.post_id = b.message_id WHERE ( a.receiver = $user_id OR b.receiver_id =$user_id) group by a.post_id ORDER BY msg_date DESC limit $p , $lpm1");
		return $inbox;
	}
	//MESSAGE PAGINATION FUNCTUION
	public function amgt_pagination($totalposts,$p,$prev,$next,$page)
	{
		$pagination = "";
		if($totalposts > 1)
		{
			$pagination .= '<div class="btn-group">';
		
			if ($p > 1)
				$pagination.= "<a href=\"?$page&pg=$prev\" class=\"btn btn-default\"><i class=\"fa fa-angle-left\"></i></a> ";
			else
				$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-left\"></i></a> ";
		
			if ($p < $totalposts)
				$pagination.= " <a href=\"?$page&pg=$next\" class=\"btn btn-default next-page\"><i class=\"fa fa-angle-right\"></i></a>";
			else
				$pagination.= " <a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-right\"></i></a>";
			$pagination.= "</div>\n";
		}
		
		return $pagination;
	}
	//SEND MAIL FUNCTION
	public function amgt_get_send_message($user_id,$max=10,$offset=0)
	{	
		$args['post_type'] = 'amgt_message';
		$args['posts_per_page'] =$max;
		$args['offset'] = $offset;
		$args['post_status'] = 'public';
		$args['author'] = $user_id;			
		$q = new WP_Query();
		$sent_message = $q->query( $args );
		return $sent_message;
	}
	// GET MESSAGE BY ID
	public function amgt_get_message_by_id($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "amgt_message";
		return $retrieve_subject = $wpdb->get_row( "SELECT * FROM $table_name WHERE message_id=".$id);
	
	}
	//SEND REPLY MESSAGE 
	public function amgt_send_replay_message($data)
	{
		
		global $wpdb;
		$table_name = $wpdb->prefix . "amgt_message_replies";
		$messagedata['message_id'] = $data['message_id'];
		$messagedata['sender_id'] = $data['user_id'];
		$messagedata['receiver_id'] = $data['receiver_id'];
		$messagedata['message_comment'] = $data['replay_message_body'];
		$messagedata['created_date'] = date("Y-m-d h:i:s");
		$result=$wpdb->insert( $table_name, $messagedata );
		if($result)	
		return $result;
			
	}
	//GET ALL REPLY FUNCTION
	public function amgt_get_all_replies($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "amgt_message_replies";
		return $result =$wpdb->get_results("SELECT *  FROM $table_name where message_id = $id");
	}
	//DELETE REPLY
	public function amgt_delete_reply($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "amgt_message_replies";
		$reply_id['id']=$id;
		return $result=$wpdb->delete( $table_name, $reply_id);
	}
	//COUNT REPLY ITEM FUNCTION
	public function amgt_count_reply_item($id)
	{
		global $wpdb;
		$tbl_name = $wpdb->prefix .'amgt_message_replies';
		$result=$wpdb->get_var("SELECT count(*)  FROM $tbl_name where message_id = $id");
		return $result;
	}
}
?>