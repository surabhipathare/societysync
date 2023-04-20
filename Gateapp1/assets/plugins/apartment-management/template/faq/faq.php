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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'faq-list';
?>
<div class="panel-body panel-white"><!-- PANEL WHITE DIV -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--PANEL_TABS -->
        <li class="<?php if($active_tab=='faq-list'){?>active<?php }?>">
			<a href="?apartment-dashboard=user&page=faq&tab=faq-list" class="tab <?php echo $active_tab == 'faq-list' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php esc_html_e('FAQ List', 'apartment_mgt'); ?></a>
        </li>
	</ul> 
	<div class="tab-content"><!-- TAB CONTENT DIV -->
		<?php if($active_tab == 'faq-list')
		{ ?>
			<div class="panel-body"><!--PANEL BODY-->
				<div class="panel-group" id="accordion">
			     <?php 
					$i = 0;
					$faq_data=amgt_get_all_category('amgt_faq');
					foreach ( $faq_data as $faq ) 
					{
						$i ++;
						?>
							<div class="panel panel-default"> <!---PANEL-DEFAULT--->
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion"
											href="#collapse<?php echo $i;?>">
												<?php echo $faq->post_title; ?></a>
									</h4>
								</div>
								<div id="collapse<?php echo $i;?>" class="panel-collapse collapse">
									<div class="panel-body">
										<?php echo $faq->post_content; //POST_CONTENT ?>
										
									</div>
								</div>
							</div>
				    <?php
				    }
					?>
		 	    </div>
	        </div><!--END PANEL BODY-->
	    <?php
	    }
		?>
    </div><!-- END TAB CONTENT DIV -->
</div><!-- END PANEL WHITE DIV -->