<script type="text/javascript">
	$(document).ready(function()
	{
		$(".sdate").datepicker({
       dateFormat: "yy-mm-dd",
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".edate").datepicker("option", "minDate", dt);
        }
	    });
	    $(".edate").datepicker({
	      dateFormat: "yy-mm-dd",
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() - 0);
	            $(".sdate").datepicker("option", "maxDate", dt);
	        }
	    });	
	 
	} );
</script>

<div class="panel-body"><!-- PANEL BODY DIV START-->
	<form method="post"> 
		<div class="form-group col-md-3 col-xs-9">
			<label for="exam_id"><?php _e('Start Date','apartment_mgt');?><span class="require-field">*</span></label>
				<input type="text"  class="form-control sdate validate[required]" name="sdate"   value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date("Y-m-d");?>">
		</div>
		<div class="form-group col-md-3 col-xs-9">
			<label for="exam_id"><?php _e('End Date','apartment_mgt');?><span class="require-field">*</span></label>
				<input type="text"  class="form-control edate validate[required]" name="edate"   value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date("Y-m-d");?>">
		</div>
		<div class="form-group col-md-3 col-xs-9 button-possition">
			<label for="subject_id">&nbsp;</label>
			<input type="submit" name="download_report" Value="<?php _e('Download Report','apartment_mgt');?>"  class="btn btn-success"/>
		</div>
	</form>
</div><!-- PANEL BODY DIV END-->

<?php
if(isset($_POST['download_report']))
{
	global $wpdb;
	$sdate=date('Y-m-d',strtotime($_POST['sdate']));
	$edate=date('Y-m-d',strtotime($_POST['edate']));
	$table_name=$wpdb->prefix.'amgt_invoice_payment_history';
	$result = $wpdb->get_results("select *from $table_name where date BETWEEN '$sdate' AND '$edate'"); 
	$num_rows = count($result);
	if($num_rows >= 1)
	{					
		$filename="Income_Expense Report.csv";
		$fp = fopen($filename, "w");	   
		// Get The Field Name
		$output="";
		$output .= '"'.__('Id','apartment_mgt').'",';
		foreach($result[0] as $key=>$rec)
		{
			if($key=='member_id')
				$output .= '"'.__('Member Name','apartment_mgt').'",';
			if($key=='date')
				$output .= '"'.__('Date','apartment_mgt').'",';
			if($key=='amount')
				$output .= '"'.__('Amount','apartment_mgt').'",';
		}
		$output .="\n";
		$i=1;
		foreach($result as $single_rec)
		{
			$output .='"'.$i.'",';
			foreach($single_rec as $key=>$row)
			{	
				if($key=='member_id' || $key=='date' || $key=='amount')
				{
				if($key=='member_id') 
					$output .='"'.apartment_get_display_name($row).'",';
				elseif($key=='member_id') 
					$output .='"'.get_amount($row).'",';
				else
					$output .='"'.$row.'",';
				}
			}
			$output .="\n";
			$i++;
		}
		// Download the file
		fputs($fp,$output);
		fclose($fp);
	   ?>
		  <div class="clear col-md-12"><?php _e("Your file is ready. You can download it from");?> <a href='<?php echo$filename;?>'>Download!</a> <?php
	}
}