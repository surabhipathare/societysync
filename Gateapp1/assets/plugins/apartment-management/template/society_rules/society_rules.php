<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
MJamgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=amgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJamgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
//RULE LIST TAB
$active_tab = isset($_GET['tab'])?$_GET['tab']:'rules-list';
?>
<div class="panel-body panel-white"><!--PANEL WHITE-->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--TAB LIST-->
		 <li class="<?php if($active_tab=='rules-list'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=society_rules&tab=rules-list" class="tab <?php echo $active_tab == 'rules-list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Society Rules List', 'apartment_mgt'); ?></a>
			  
		  </li>
	</ul> 
<div class="tab-content">
    <!--RULE LIST TAB-->
	<?php if($active_tab == 'rules-list')
	{ ?>
		<div class="panel-body"><!--PANEL BODY DIV-->
			<div class="panel-group" id="accordion"><!--PANEL GROUP DIV-->
		     <?php 
				$i = 0;
				$faq_data=amgt_get_all_category('amgt_society_rules');
				foreach ( $faq_data as $faq ) {
				  $i ++;?>
					<div class="panel panel-default">
						<div class="panel-heading"><!--PANEL HEADING DIV-->
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion"
									href="#collapse<?php echo esc_attr($i);?>">
										<?php echo esc_html($faq->post_title); ?></a>
							</h4>
						</div>
						<div id="collapse<?php echo $i;?>" class="panel-collapse collapse">
							<div class="panel-body"><!--END PANEL BODY DIV-->
								<?php echo esc_html($faq->post_content);?>
								
							</div><!--END PANEL BODY DIV-->
						</div>
					</div>
				<?php } ?>
		    </div><!--END PANEL GROUP DIV-->
	    </div><!--END PANEL BODY DIV-->
	<?php
	} ?>
</div>	
<?php ?>