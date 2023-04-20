<?php 
$obj_complaint = new Amgt_Complaint();
$open_complaints = $obj_complaint->amgt_complaint_countby_status('open');
$close_complaints = $obj_complaint->amgt_complaint_countby_status('close');

	$chart_array = array();
	//$chart_array[] = array('Source','Count');	
	$chart_array[] = array(esc_html__('Source','apartment_mgt'),esc_html__('Count','apartment_mgt'));	
	//$chart_array[]=array( "Activated",(int)$open_complaints);
	$chart_array[]=array( esc_html__('Activated','apartment_mgt'),(int)$open_complaints);
	//$chart_array[]=array( "Deactivated",(int)$close_complaints);
	$chart_array[]=array( esc_html__('Deactivated','apartment_mgt'),(int)$close_complaints);
	$options = Array(
		//'title' => esc_html__('Device BY Activated/Deactivated Status')
		'title' =>  esc_html__('Device BY Activated/Deactivated Status','apartment_mgt')
	); 
$GoogleCharts = new GoogleCharts;
$chart = $GoogleCharts->load( 'PieChart' , 'chart_div' )->get( $chart_array , $options );
?>
<div id="chart_div" class="width_100_height_500"></div>
<script type="text/javascript"><?php echo $chart;?></script>