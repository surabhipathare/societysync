<?php $user_type=isset($_REQUEST['user_type'])?$_REQUEST['user_type']:'member'; ?>  
<div class="panel-body">		
<?php
if($user_type=="member")
{
	require_once AMS_PLUGIN_DIR.'/admin/member/add_member.php';
}
elseif($user_type=="accountant")
{
	require_once AMS_PLUGIN_DIR.'/admin/member/add_accountant.php';
}
elseif($user_type=="staff-Member")
{
	require_once AMS_PLUGIN_DIR.'/admin/member/add_staff_member.php';
}
else
{
	require_once AMS_PLUGIN_DIR.'/admin/member/add_gatekeeper.php';
}
?></div>
