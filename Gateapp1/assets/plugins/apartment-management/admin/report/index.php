<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'unit_by_building';
?>
<div class="page-inner min_height_1088"><!-- PAGE-INNER -->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
$message = '';
?>
	<div id="main-wrapper"><!-- MAIN WRAPPER -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL WHITE -->
					<div class="panel-body"><!-- PANEL BODY-->
					    <!--NAV TAB WRAPPER -->
						<h2 class="nav-tab-wrapper report-tab">
							<a href="?page=amgt-report&tab=unit_by_building" 
							class="nav-tab <?php echo $active_tab == 'unit_by_building' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Unit By Building', 'apartment_mgt'); ?></a>
							<a href="?page=amgt-report&tab=member_by_building" 
							class="nav-tab <?php echo $active_tab == 'member_by_building' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Number Of Members By Building', 'apartment_mgt'); ?></a>
							<a href="?page=amgt-report&tab=payment" 
							class="nav-tab <?php echo $active_tab == 'payment' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Payment', 'apartment_mgt'); ?></a>
							<a href="?page=amgt-report&tab=expense" 
							class="nav-tab <?php echo $active_tab == 'expense' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Expense', 'apartment_mgt'); ?></a>
							<a href="?page=amgt-report&tab=complaint" 
							class="nav-tab <?php echo $active_tab == 'complaint' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Complain Status', 'apartment_mgt'); ?>
							</a>
							<a href="?page=amgt-report&tab=download_income_expense_report" 
							class="nav-tab <?php echo $active_tab == 'download_income_expense_report' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Download Income Report', 'apartment_mgt'); ?>
							</a>
						</h2> <!--END NAV TAB WRAPPER -->
						 <script type="text/javascript" src=	"https://www.google.com/jsapi"></script> 
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