<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'unit_by_building';
?>
<div class="page-inner min_height_1088"><!-- PAGE-INNER -->
<?php 
$message = '';
?>
	<div id="main-wrapper"><!-- MAIN WRAPPER -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL WHITE -->
					<div class="panel-body"><!-- PANEL BODY-->
					    <!--NAV TAB WRAPPER -->
					    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--TABLIST--> 
							<li class="<?php if($active_tab=='unit_by_building'){?>active<?php }?>">
								<a href="?apartment-dashboard=user&page=report&tab=unit_by_building" class="tab <?php echo $active_tab == 'unit_by_building' ? 'active' : ''; ?>">
									<i class="fa fa-align-justify"></i> <?php esc_html_e('Unit By Building', 'apartment_mgt'); ?>
								</a>
							</li>
							<li class="<?php if($active_tab=='member_by_building'){?>active<?php }?>">
								<a href="?apartment-dashboard=user&page=report&tab=member_by_building" class="tab <?php echo $active_tab == 'member_by_building' ? 'active' : ''; ?>">
									<i class="fa fa-align-justify"></i> <?php esc_html_e('Number Of Members By Building', 'apartment_mgt'); ?>
								</a>
							</li>
							<li class="<?php if($active_tab=='payment'){?>active<?php }?>">
								<a href="?apartment-dashboard=user&page=report&tab=payment" class="tab <?php echo $active_tab == 'payment' ? 'active' : ''; ?>">
									<i class="fa fa-align-justify"></i> <?php esc_html_e('Payment', 'apartment_mgt'); ?>
								</a>
							</li>
							<li class="<?php if($active_tab=='expense'){?>active<?php }?>">
								<a href="?apartment-dashboard=user&page=report&tab=expense" class="tab <?php echo $active_tab == 'expense' ? 'active' : ''; ?>">
									<i class="fa fa-align-justify"></i> <?php esc_html_e('Expense', 'apartment_mgt'); ?>
								</a>
							</li>
							<li class="<?php if($active_tab=='complaint'){?>active<?php }?>">
								<a href="?apartment-dashboard=user&page=report&tab=complaint" class="tab <?php echo $active_tab == 'complaint' ? 'active' : ''; ?>">
									<i class="fa fa-align-justify"></i> <?php esc_html_e('Complain Status', 'apartment_mgt'); ?>
								</a>
							</li>
						</ul>
						 <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
						 <?php 
							require_once AMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';	
							require_once AMS_PLUGIN_DIR.'/admin/report/'.$active_tab.'.php';
						 ?>
                    </div><!-- END PANEL BODY-->
	            </div><!--END PANEL WHITE -->
	        </div>
        </div>
    </div>
</div><!-- END PAGE-INNER -->