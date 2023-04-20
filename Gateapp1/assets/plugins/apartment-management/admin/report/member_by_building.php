<?php 
$obj_resident = new Amgt_ResidentialUnit();
$result=$obj_resident->amgt_member_by_building();
$chart_array = array();
//$chart_array[] = array('Building Name','Number Of Member ');
$chart_array[] = array(esc_html__('Building Name','apartment_mgt'),esc_html__('Number Of Member','apartment_mgt'));
foreach($result as $r)
{
	$chart_array[]=array( get_the_title($r['building_id']),(int)$r['no_of_member']);
}

 $options = Array(
			'title' => esc_html__('Member By Building','apartment_mgt'),
			'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
			'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
			
			'hAxis' => Array(
				'title' => esc_html__('Building Name','apartment_mgt'),
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'textStyle' => Array('color' => '#66707e','fontSize' => 11),
				'maxAlternation' => 2
				),
			'vAxis' => Array(
				'title' => esc_html__('Number Of Member','apartment_mgt'),
				 'minValue' => 0,
				'maxValue' => 5,
				 'format' => '#',
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'textStyle' => Array('color' => '#66707e','fontSize' => 12)
				),
 		    'colors' => array('#22BAA0')
			);
			require_once AMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';

			$GoogleCharts = new GoogleCharts;

			$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
           ?>
		   
		 <?php if(empty($result))
		{?>
		<div class="nodata">
		
		  <?php  esc_html_e('Records Not Found','apartment_mgt');?>
		</div>
		<?php } ?>
		   
 <div id="chart_div" class="width_100_height_500"></div>
 <!-- Javascript --> 
    <script type="text/javascript">
  			<?php if(!empty($result))
			{
				echo $chart;
			}?>
    </script>