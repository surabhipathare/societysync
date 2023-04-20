jQuery(document).ready(function($) {	
	//Category Add and Remove
  $("body").off("click", "#addremove").on("click", "#addremove", function(event){
	  //alert(category_name);
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var model  = $(this).attr('model') ;
	 
		
	   var curr_data = {
	 					action: 'amgt_add_or_remove_category',
	 					model : model,
	 					dataType: 'json'
	 					};	
										
	 					$.post(amgt.ajax, curr_data, function(response) { 
				
							$('.popup-bg').show().css({'height' : docHeight});
							$('.category_list').html(response);	
							return true; 					
	 					});	
	
  });
  
 
  //END MEMBER SIDE ADD REMOVE CATEGORY
 
  $("body").on("click", ".close-btn", function(){		
		
		$( ".category_list" ).empty();
		
		$('.popup-bg').hide(); // hide the overlay
		});  
	$("body").on("click", ".invoice-close-btn", function(){		
		
		$( ".invoice_generate" ).empty();
		
		$('.invoice-popup-bg').hide(); // hide the overlay
		});  
	$("body").on("click", ".bill-close-btn", function(){		
		
		$( ".invoice_data" ).empty();
		
		$('.bill-popup-bg').hide(); // hide the overlay
		}); 
		$("body").on("click", ".complaint-close-btn", function(){		
		
		$( ".complaint_content" ).empty();
		
		$('.complaint-popup-bg').hide(); // hide the overlay
		}); 
			
  
  $("body").on("click", ".btn-delete-cat", function(){		
		var cat_id  = $(this).attr('id') ;	
		 var model  = $(this).attr('model') ;
		
		if(confirm(language_translate.add_remove))
		{
			var curr_data = {
					action: 'amgt_remove_category',
					model : model,
					cat_id:cat_id,			
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
                       	
						$('#cat-'+cat_id).hide();
						$("#"+model).find('option[value='+cat_id+']').remove();	
						$("."+model).find('option[value='+cat_id+']').remove();	
                    					
						return true;				
					});			
		}
	});
	
	
 $("body").on("click", "#btn-add-cat", function(){	

		var category_name  = $('#category_name').val();
		var model  = $(this).attr('model');
		
		
		if(category_name != "")
		{
			var curr_data = {
					action: 'amgt_add_category',
					model : model,
					category_name: category_name,			
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
					
						 var json_obj = $.parseJSON(response);//parse JSON	
						 
                        if(json_obj[2]=="1")
						{
								 
							$('.category_listbox .table').append(json_obj[0]);
							$('#category_name').val("");
							
							if(model=="unit_category")
							{
								//$("#unit_category").append(json_obj[1]);
								$(".unit_category").append(json_obj[1]);
								$(".unit_categorys").append(json_obj[1]);
								$(".popup_member_unit_category").append(json_obj[1]);
							}
							else
							{
								//$('#'+model).append(json_obj[1]);
								$('.'+model).append(json_obj[1]);
								$('.popup_member_building_category').append(json_obj[1]);
							}
						}
						else 
						{
							
							alert(json_obj[3]);
						}
						
						
						return false;					
					});	
		
		}
		else
		{
			
			alert(language_translate.enter_category_alert);
		} 
	});
 
  /*-----------------LOAD STAFF BY BASE ID---------------------------- */
  
	$("#badge_id").change(function(){
		$('#staff-data').html('');	
		var selection = $(this).val();
	
			var curr_data = {
					action: 'amgt_load_staff_checkin_data',
					badge_id: selection,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						
						$('#staff-data').append(response);	
						return true;
					});
						
					
	});
  

	/*-----------load building category with class in popup ------------- */
	 jQuery("body").on("change",".building_category_member",function(){
		$('.unit_category_member').html('');	
		var selection = $(this).val();
		var curr_data = {
			action: 'amgt_load_unit_cat',
			building_id: selection,				
			dataType: 'json'
		};
		$.post(amgt.ajax, curr_data, function(response) {			
			$('.unit_category_member').html('');	
			$('.unit_category_member').append(response);			
					
			return true;
		});
						
					
	});
/*-----------load unit with class in pop-up ------------- */	
	jQuery("body").on("change",".unit_category_member",function(){
		
		var selection = $(this).val();
		var building_id = $('.building_category_member').val();
	
			var curr_data = {
					action: 'amgt_load_units',
					unit_cat_id: selection,				
					building_id: building_id,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
					
						$('.unit_name_member').html('');	
						$('.unit_name_member').append(response);	
					});
						
					
	});	
/*-----------load unit ------------- */	
	jQuery("body").on("change",".unit_categorys",function()
	{		
		$('.unit_name').html('');
		
		$('#member_id').html('');			
		var select_message1="<option value>"+language_translate.Select_Member+"</option>";
		$(select_message1).appendTo('#member_id');
		var selection = $(this).val();
		var building_id = $('.building_category').val();
		$('.unit_name').prepend($('<option>Loading...</option>').html('Loading...'));	
			var curr_data = {
					action: 'amgt_load_units',
					unit_cat_id: selection,				
					building_id: building_id,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response)
					{
						var json_obj = $.parseJSON(response);
						
						//alert(json_obj);return false;
						var val = [];					
						var i=0;
						var j=0;
						
						 $.each(json_obj, function() {
							
							val[i] = this['value'];						
								
							i++;
						});
						$('.unit_name').html('');	

						var select_message1="<option value>"+language_translate.select_unit_name+"</option>";
						$(select_message1).appendTo('.unit_name'); 		
						
						for(j=0;j<val.length;j++)
						{			
							var unit_data='<option value="'+val[j]+'">'+val[j]+'</option>';
							
							$(unit_data).appendTo('.unit_name'); 						
							$(unit_data).appendTo('.account_unit_name'); 						
							
						}						
						return false;
					});	
	});
	
	jQuery("body").on("change",".popup_member_unit_category",function()
	{
		$('.popup_member_unit_name').html('');
		
		var selection = $(this).val();
		var building_id = $('.popup_member_building_category').val();
		
			var curr_data = {
					action: 'amgt_load_units',
					unit_cat_id: selection,				
					building_id: building_id,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						var json_obj = $.parseJSON(response);
						
						//alert(json_obj);return false;
						var val = [];					
						var i=0;
						var j=0;
						
						 $.each(json_obj, function() {
							
							val[i] = this['value'];						
								
							i++;
						});
						$('.popup_member_unit_name').html('');	
						var select_message1="<option value>Select Unit Name</option>";
						$(select_message1).appendTo('.popup_member_unit_name'); 		
						
						for(j=0;j<val.length;j++)
						{			
							var unit_data="<option value="+val[j]+">"+val[j]+"</option>";
								
							$(unit_data).appendTo('.popup_member_unit_name'); 						
							
						}						
						
					});	
	});
	/*-------Delete gate Start----------------*/
	
	 jQuery("body").on("click", "#del_curr", function(event){
	  var gate_id = $(this).attr('gate_id');
	  var id=$(this).attr('test_id');
	 
	   var curr_data = {
	 					action: 'amgt_delete_gate',
	 					gate_id: gate_id,			
	 					dataType: 'json'
	 					};
	 					
							$.post(amgt.ajax, curr_data, function(response) {
								$('#'+id).remove();
	 					});	
	 		}); 
	/*----------- View Unit ------------- */
	 $("body").on("click", ".view-unit", function(event){		
		
		var unit_cat_id  = $(this).attr('data-unit_cat_id');
		var building_id  = $(this).attr('data-building_id');
	
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	 
		
			var curr_data = {
					action: 'amgt_view_unit',
					unit_cat_id : unit_cat_id,
					building_id: building_id,			
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
							
							$('.popup-bg').show().css({'height' : docHeight});
							$('.category_list').html(response);	
							return true; 			
					});	
		
		
	});
	/*----------- Delete Unit ------------- */
	 jQuery("body").on("click", ".delete-units", function(event){
	 
	 var unit_cat_id  = $(".unit_cat_id").val();
		var building_id  = $(".building_id").val();
		var unit_name  = $(this).attr('data-unit-name');
	  
	   var curr_data = {
	 					action: 'amgt_delete_units',
	 					unit_cat_id : unit_cat_id,
						building_id: building_id,			
						unit_name: unit_name,		
	 					};
	 					
							$.post(amgt.ajax, curr_data, function(response) {
								
								$('tr#cat-'+unit_name).remove();
	 					});	
	 		}); 
	/*----------- Add Unit  ------------- */
	 $("body").on("click", "#btn-add-unit", function(event){		
		
		var unit_cat_id  = $(this).attr('data-unit_cat_id');
		var building_id  = $(this).attr('data-building_id');
		var unit_name  = $("#category_name").val();
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	
			var curr_data = {
					action: 'amgt_add_unitname',
					unit_cat_id : unit_cat_id,
					building_id: building_id,			
					unit_name: unit_name,			
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
						
							$('.unit_list').append(response);	
							return true; 			
					});	
		
		
	});
	
  /* ------- Book Facility -----*/
  
  $("#facility_id").change(function(){
		$('#select_facility_block').html('');	
		var selection = $(this).val();
		
			var curr_data = {
					action: 'amgt_facility_booking_period',
					facility_id: selection,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						$('#select_facility_block').html('');
						$('#select_facility_block').append(response);	
					});
						
					
	});
	
	/*-----------View Events------------------*/
	 $("body").on("click", ".view-event", function(event){
		   //alert("hello");
	  var evnet_id = $(this).attr('id');
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	   //alert(evnet_id);
	   var curr_data = {
	 					action: 'amgt_view_event',
	 					evnet_id: evnet_id,			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
	 						
	 						$('.complaint-popup-bg').show().css({'height' : docHeight});
							$('.complaint_content').html(response);	
	 						return true;
	 					
	 					});	
	 		});
			
	 $("body").on("click", ".view-notice", function(event){
		   //alert("hello");
	  var notice_id = $(this).attr('id');
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	
	   var curr_data = {
	 					action: 'amgt_view_notice',
	 					notice_id: notice_id,			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
	 						
	 						$('.complaint-popup-bg').show().css({'height' : docHeight});
							$('.complaint_content').html(response);	
	 						return true;
	 					
	 					});	
	 		});		
			
	/*---------View Complaints------------------------*/		
	$("body").on("click", ".view-complaint", function(event){
		 
	  var complaint_id = $(this).attr('id');
	  
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  
	   var curr_data = {
	 					action: 'amgt_view_complaint',
	 					complaint_id: complaint_id,			
	 					dataType: 'json'
	 					};
	 				
	 					$.post(amgt.ajax, curr_data, function(response) {
	 						
	 						$('.complaint-popup-bg').show().css({'height' : docHeight});
							$('.complaint_content').html(response);	
	 						return true;
	 					
	 					});	
	 		});		
			
			
			
			
			/*---------View Service------------------------*/	
      $("body").on("click", ".view-service", function(event){
		
	  var service_id = $(this).attr('id');
	  
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	
	   var curr_data = {
	 					action: 'amgt_service_complaint',
	 					service_id: service_id,			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
	 					
	 						$('.complaint-popup-bg').show().css({'height' : docHeight});
							$('.complaint_content').html(response);	
	 						return true;
	 					
	 					});	
	 		});		
		
		/*---------View Service------------------------*/	
      $("body").on("click", ".add-category", function(event){
		
	  var service_id = $(this).attr('id');
	  
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	
	   var curr_data = {
	 					action: 'amgt_service_complaint',
	 					service_id: service_id,			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
	 					
	 						$('.complaint-popup-bg').show().css({'height' : docHeight});
							$('.complaint_content').html(response);	
	 						return true;
	 					
	 					});	
	 		});		
			
			
	/*---------View Documents------------------------*/		
	$("body").on("click", ".view-unit-document", function(event){
		
	  var unit_name = $(this).attr('unit_name');
	  var building_id = $(this).attr('building_id');
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  
	   var curr_data = {
	 					action: 'amgt_unit_document_view',
	 					unit_name: unit_name,			
	 					building_id: building_id,			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
	 					
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.category_list').html(response);	
	 						return true;
	 					});	
	 		});				
			
		/*---------Checkout--------------------*/		
	$("body").on("click", ".check-out", function(event){		
	  var checkin_id = $(this).attr('checkin_id');
	  var checkout_type = $(this).attr('checkout-type');
		
	  event.preventDefault(); 
	  var docHeight = $(document).height(); 
	  var scrollTop = $(window).scrollTop();
	 
	   var curr_data = {
	 		action: 'amgt_checkout_popup',
	 		checkin_id: checkin_id,			
	 		checkout_type: checkout_type,			
	 		dataType: 'json'
	 	};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
	 					
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.checkout_content').html(response);	
	 						return true;
	 					});	
	 		});

	/*-----------Load Invoice Amount-------------------*/			
	 
	$("body").on('change','#invoice', function (event) {		
		
		var curr_data = {
	 					action: 'amgt_load_invoice_amount',
	 					invoice_id: $(this).val(),			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
							
							$('#amount').val(response);	
	 						return true;
					});	
		 
		 return false;
		 });
		
	//count date wise facility chargis function
	 $("body").on('focus','.facility_charge', function (event) {		
		$( this ).blur();
		$('.facility_charge').val('');
		
		var period_type = $('#period_type').val();
		
		if(period_type == 'hour_type')
		{
			var start_time  = $(".start_time").val();
			var hours = Number(start_time.match(/^(\d+)/)[1]);
			var minutes = Number(start_time.match(/:(\d+)/)[1]);
			var AMPM = start_time.match(/\s(.*)$/)[1];
			if(AMPM == "PM" && hours<12) hours = hours+12;
			if(AMPM == "AM" && hours==12) hours = hours-12;
			var sHours = hours.toString();
			var sMinutes = minutes.toString();
			if(hours<10) sHours = "0" + sHours;
			if(minutes<10) sMinutes = "0" + sMinutes;
			
			
			var end_time  = $(".end_time").val();
			var end_hours = Number(end_time.match(/^(\d+)/)[1]);
			var end_minutes = Number(end_time.match(/:(\d+)/)[1]);
			var ENDAMPM = start_time.match(/\s(.*)$/)[1];
			if(ENDAMPM == "PM" && end_hours<12) end_hours = end_hours+12;
			if(ENDAMPM == "AM" && end_hours==12) end_hours = end_hours-12;
			var eHours = end_hours.toString();
			var eMinutes = end_minutes.toString();
			if(end_hours<10) eHours = "0" + eHours;
			if(end_minutes<10) eMinutes = "0" + eMinutes;
		
			/* if(sHours > eHours)
			{			
			  //alert('End Time should be greater than Start Time');
              alert(language_translate.count_facility_popup);			  
			}
	        else if(sHours ==  eHours && sMinutes > eMinutes )
			{
			  //alert('End Time should be greater than Start Time');
              alert(language_translate.end_time_facility);				  
			} */
			else
			{
				var curr_data = {
	 					action: 'amgt_count_facility_charge',
	 					start: $('.start').val(),			
	 					end: $('.end').val(),			
	 					period_type: $('#period_type').val(),			
	 					facility_id: $('#facility_id').val(),			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
							$('.facility_charge').val(response);	
	 						 return false;
					});
				
			}
		}
		else
		{
			var curr_data = {
	 					action: 'amgt_count_facility_charge',
	 					start: $('.start').val(),			
	 					end: $('.end').val(),			
	 					period_type: $('#period_type').val(),			
	 					facility_id: $('#facility_id').val(),			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response) {
							/* alert(response);
							return false; */
							$('.facility_charge').val(response);	
	 						return false;
					});
		}
			
		 });
		 
	$("body").on('change','#frequency', function (event) {	
		$('#period').html('');	
		
		var curr_data = {
	 					action: 'amgt_load_period',
	 					selected: $(this).val(),			
	 					dataType: 'json'
	 					};
	 					
	 					$.post(amgt.ajax, curr_data, function(response)
						{
							
							$('#period').append(response);	
	 						return true;
					});	
		 
		
		 });	 
	/*----------- Generate Invoice ------------- */
	 $("body").on("click", "#generate_invoice", function(event){	
		
		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	 
		
			var curr_data = {
					action: 'amgt_generate_invoice_form',
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
							
							$('.invoice-popup-bg').show().css({'height' : docHeight});
							$('.invoice_generate').html(response);	
							return true; 			
					});	
		
		
	});	 
	 $("body").on("click", ".view-member", function(event){	
    	
		var unit_name = $(this).attr('unit_name') ;
		var building_id = $(this).attr('building_id') ;
		
		event.preventDefault(); 
		var docHeight = $(document).height(); 
		var scrollTop = $(window).scrollTop(); 
	
		
		var curr_data = {
					action: 'amgt_unit_member_view',
					unit_name: unit_name,			
					building_id: building_id,			
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
						
						$('.popup-bg').show().css({'height' : docHeight});
						
						$('.category_list').html(response);	
					});	
		});
		$("body").on("click", ".view-member-history", function(event)
		{	
    	
		var unit_name = $(this).attr('unit_name') ;
		var building_id = $(this).attr('building_id') ;	
			
		event.preventDefault(); 
		var docHeight = $(document).height(); 
		var scrollTop = $(window).scrollTop(); 
	
		
		var curr_data = {
					action: 'amgt_unit_member_history_view',
					unit_name: unit_name,			
					building_id: building_id,			
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
						
						$('.popup-bg').show().css({'height' : docHeight});
						
						$('.category_list').html(response);	
				});	
		});
			//----------DELETE GROUP MEMBER----------------		
		$("body").on("click", "#delete_unitmember", function(){		
			
		 var member_id  = $(this).attr('mem_id');
		
		if(confirm(language_translate.add_remove))
		{
			var curr_data = {
					action: 'amgt_remove_unit_member',
					member_id:member_id,			
					
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {						
						$('#cat-'+member_id).hide();						
						return true;				
					});			
		}
	});	

	 $("body").on("change", "#committee_member", function(event){	
		 var check_status='unchecked';
		if($(this).is(":checked")) {
					check_status='checked';
				}
				else
				{
					check_status='unchecked';
				}
		
		 
		var curr_data = {
					action: 'amgt_load_member_designation',
					check_status: check_status,	
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
						
						$('#designaion_area').html(response);	
					});	
		});	
		//----------view Invoice popup--------------------
	 $("body").on("click", ".show-invoice-popup", function(event){
	

	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var idtest  = $(this).attr('idtest');
	  var invoice_type  = $(this).attr('invoice_type');
	 
	   var curr_data = {
	 					action: 'amgt_invoice_view',
	 					idtest: idtest,
	 					invoice_type: invoice_type,
	 					dataType: 'json'
	 					};	 	
										
	 					$.post(amgt.ajax, curr_data, function(response) { 	
	 						
						$('.bill-popup-bg').show().css({'height' : docHeight});	
						$('.invoice_data').html(response);	
						return true; 					
	 					});	
	
		});
	//Payment Module pop up
	 $("body").on("click", ".show-payment-popup", function(event){
				
			
			  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			  var docHeight = $(document).height(); //grab the height of the page
			  var scrollTop = $(window).scrollTop();
			  var invoice_id  = $(this).attr('invoice_id');
			  var member_id  = $(this).attr('member_id');
			  var idtest  = $(this).attr('idtest');
			  var view_type  = $(this).attr('view_type');
			  var due_amount  = $(this).attr('due_amount');
			  
				
			   var curr_data = {
			 					action: 'amgt_member_add_payment',
			 					invoice_id: invoice_id,
			 					member_id: member_id,
			 					idtest: idtest,
			 					view_type: view_type,
			 					due_amount: due_amount,
			 					dataType: 'json'
			 					};	 	
								//alert('hello');					
			 					$.post(amgt.ajax, curr_data, function(response) { 	
			 					 
			 					$('.bill-popup-bg').show().css({'height' : docHeight});							
								$('.invoice_data').html(response);	
								return true; 					
			 					});	
			
		  });	
		  /*---------Verify licence key-----------------*/
$("body").on("click", "#varify_key", function(event){
	$(".gmgt_ajax-img").show();
	$(".page-inner").css("opacity","0.5");
	
	event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	var res_json;
	var licence_key = $('#licence_key').val();
	var enter_email = $('#enter_email').val();
	
	var curr_data = {
		action: 'amgt_verify_pkey',
	 	licence_key : licence_key,
	 	enter_email : enter_email,
	 	dataType: 'json'
	};	
	$.post(amgt.ajax, curr_data, function(response) {
			
		$(".gmgt_ajax-img").hide();
		res_json = JSON.parse(response);
	
		$('#message').html(res_json.message);
		$("#message").css("display","block");
		
		$(".page-inner").css("opacity","1");
		if(res_json.amgt_verify == '0')
		{
			window.location.href = res_json.location_url;
		}
		return true; 			
	});	
	
  });
  
  /*-----------load unit ------------- */	
	jQuery("body").on("change","#unit_name",function(){
		$('#unnit_measurement').val('');	
		$('#unnit_chanrges').val('');
	
		var selection= $(this).val();
		var building_id = $('.building_category').val();
		var unit_category = $('.unit_category').val();
		
			var curr_data = {
					action: 'amgt_load_unit_measurements',
					unit_cat_id: unit_category,				
					building_id: building_id,				
					unit_name: selection,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						
						var json_obj = $.parseJSON(response);//parse JSON	
						$('.unnit_measurement').val(json_obj[0]);	
						$('#unnit_measurement').val(json_obj[0]);	
						$('#unnit_chanrges').val(json_obj[1]);	
						$('.unnit_chanrges').val(json_obj[1]);	
							
					});
				
	});
	
	/* ------- load tax amount-----*/
  jQuery("body").on("change",".tax_selection",function(){
		var selection = $(this).val();
		
		var old_tax_amount =0;
		var total_amount =0;
		var amount =0;
		amount = $('#amount').val();
		old_tax_amount =$('#taxamount').val();
		total_amount =$('#totalamount').val();
		var id = $(this).attr('id');
			var curr_data = { 
					action: 'amgt_load_tax_amount',
					tax_id: selection,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						$('#tax_entry_'+id).val(response);
						if(amount!=''){
							var tax_amount= (parseInt(response) *parseInt(amount))/100;
							$('#tax_amount_'+id).val(tax_amount);
							
						}
					});
						
	});
	/* ------- view member list by unit name-----*/
	$("body").on("change", ".unit_name", function(event){
		$('.member_id').empty();
		var building_category = $('.building_category').val();
		var unit_category = $('.unit_categorys').val();
		var unit_name = $('.unit_name').val();
		var curr_data = {
					action: 'amgt_unit_wise_view_member',
					unit_name: unit_name,			
					building_category: building_category,			
					unit_category: unit_category,			
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						var json_obj = $.parseJSON(response);//parse JSON
						
						$('.member_id').append(json_obj);
					});	
		});
		
 		$("body").on("change", ".account_unit_name", function(event){
		$('.member_id').empty();
		var building_category = $('.building_category').val();
		var unit_category = $('.unit_categorys').val();
		var unit_name = $('.account_unit_name').val();
		var curr_data = {
					action: 'amgt_account_unit_wise_view_member',
					unit_name: unit_name,			
					building_category: building_category,			
					unit_category: unit_category,			
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						var json_obj = $.parseJSON(response);//parse JSON
								
						$('.member_id').append(json_obj);
					});	
		}); 
		/*-----------load unnit_measurement an charges in member popup ------------- */	
	jQuery("body").on("change",".unit_name_member",function(){
		$('.unnit_measurement').val('');	
		$('.unnit_chanrges').val('');	
		
		var selection= $(this).val();
		var building_id = $('.building_category_member').val();
		var unit_category = $('.unit_category_member').val();
		
			var curr_data = {
					action: 'amgt_load_unit_measurements',
					unit_cat_id: unit_category,				
					building_id: building_id,				
					unit_name: selection,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						
						var json_obj = $.parseJSON(response);//parse JSON	
						$('.unnit_measurement').val(json_obj[0]);	
						$('.unnit_chanrges').val(json_obj[1]);	
							
					});
								
	});

$("body").on("click",'#add_new_document_entry',function() {	

		
	 var curr_data = {
		action: 'amgt_load_document_html',		
		dataType: 'json'
	};
	
	$.post(amgt.ajax, curr_data, function(response) {
		$("#document_entry").append(response);
		return false;
	});
});

$("body").on("click",'#add_new_document_entry_frontend',function() {

	  var curr_data = {
		action: 'amgt_load_document_html_frontend',		
		dataType: 'json'
	};
	
	$.post(amgt.ajax, curr_data, function(response) {
		
		$("#document_entry_frontend").append(response);
		return false;
	});
});
/*----------- Generate Invoice for all member ------------- */
	jQuery("body").on("change",".member_list",function(){
		$('.invoice').empty();
		//var member_id = $('.member_list').val();
		var member_id = $(this).val();
		var curr_data = {
					action: 'amgt_member_wise_view_invoice',
					member_id: member_id,			
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) {
						/* alert(response);
						return false; */
						var json_obj = $.parseJSON(response);//parse JSON
						$('.invoice').append(json_obj);
					});	
		});
/*----------- Select invoice option for charges module------------- */
		 $('input:radio[name="select_serveice"]').change(function(){
		var invoice_option = $(this).val();
		 var curr_data = {
						action: 'amgt_invoice_option_html',
						invoice_option: invoice_option,		
						dataType: 'json'
						};					
						
						$.post(amgt.ajax, curr_data, function(response) {
                      	
						$('#invoice_setting_block').html(response);
						});
    });
	
	$('.charges_category').on('change', function(){
		
          var val = $(this).val();
		  
		  var curr_data = {
			  
					action: 'amgt_generate_invoice_form_allmember',
					val: val,	
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {
						
					$('#maintance_invoice_setting_block').html(response);		
					});	
		
            });
		
 
	  jQuery("body").on('focus','.amount', function (event)
	  {		
			$( this ).blur();
			  var total = 0;
			  var discount_amount = 0;
			 $(".income_amount" ).each(function(){
				  total += parseFloat($(this).val());
				 //alert(total);
			  })
			var discount_amount = $('.discount-amount').val();
			var total_amount_discount= total - discount_amount;
			$('.amount').val(total_amount_discount);
	});
	 
	jQuery(document).on('keyup', '#discount-amount', function(event) 
	{		
		var charge_calculate_by = $("input[name='charge_cal']:checked").val();
		
		if(charge_calculate_by=='fix_charge')
		{
			var total = 0;
			var discount_amount = 0;
			 $(".income_amount" ).each(function(){
				  total += parseFloat($(this).val());
				
			  })
			var discount_amount = $('.discount-amount').val();
			if(discount_amount>total)
			{
				//alert('discount amount can not greater than total amount');
				alert(language_translate.discount_amount__alert);
				$('.discount-amount').val('');
				return false;
			}
		}		
			
	});
	jQuery("body").on('focus','#taxamount', function (event) 
	{		
		$( this ).blur();
		  var total_tax_amount = 0;
		  var tax_value = new Array();
			$("input[name='tax_entry[]']").each(function()
			{
				
				var tax_value=$(this).val();
				var amount_after_discount=$('#amount').val(); 
				
				total_tax_amount += parseFloat(amount_after_discount*tax_value/100);
				
			})		
		
		$('#taxamount').val(total_tax_amount.toFixed(2)); 
		
	
	});
	jQuery("body").on('focus','#totalamount', function (event) 
	{		
		$( this ).blur();
		var total_amount = 0;
		var amount=$('#amount').val();
		var total_tax_amount =$('#taxamount').val();
		
		total_amount=parseFloat(amount)+parseFloat(total_tax_amount);
		$('#totalamount').val(total_amount.toFixed(2));
	
	});
	/* ------- load tax amount-----*/
   jQuery("body").on("change",".allready_occupied",function()
   {
		
		var building_category = $('.building_category').val();
		var unit_category = $('.unit_categorys').val();
		var unit_name = $('.unit_name').val();		
		
			var curr_data = { 
					action: 'amgt_unit_allready_occupied',
					building_category: building_category,				
					unit_category: unit_category,				
					unit_name: unit_name,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) 
					{		
						var json_obj = $.parseJSON(response);//parse JSON
											
						if(json_obj['allready_occupied']== "1")
						{
							
								event.preventDefault(); 
								var docHeight = $(document).height(); 
								var scrollTop = $(window).scrollTop(); 
								
								$('.popup-bg').show().css({'height' : docHeight});
							
								$('.category_list').html(json_obj['form']);		
									
						}	 
					});
	});
	$("body").on("click", "#delete_occupied_unitmember", function()
	{		
			
		 var member_id  = $(this).attr('mem_id');
		
		if(confirm(language_translate.add_remove))
		{
			var curr_data = {
					action: 'amgt_remove_allready_occupied_member',
					member_id:member_id,			
					
					dataType: 'json'
					};
					
					$.post(amgt.ajax, curr_data, function(response) {	
					
					$( ".category_list" ).empty();
		
					$('.popup-bg').hide();			
						return true;				
					});			
		}
	});	
	$("body").on("click", ".close-btn-occupied-popup", function()
	{		
		$( ".category_list" ).empty();
		
		$('.popup-bg').hide(); // hide the overlay
		$('.allready_occupied').prop('selectedIndex',0); 
	}); 	
	$('input:radio[name="charge_cal"]').change(function()
	{
		var charge_cal_option = $(this).val();
		if(charge_cal_option=='measurement_charge')	
		{
			 $(".measurement_hide_div").css("display", "none");			 
		}
		else if(charge_cal_option=='fix_charge')	
		{
			 $(".measurement_hide_div").css("display", "block");
		}		
		var curr_data = {
						action: 'amgt_charge_cal_option_html',
						charge_cal_option: charge_cal_option,		
						dataType: 'json'
						};					
						
						$.post(amgt.ajax, curr_data, function(response) {
                      					 
						$('#charges_entry').html(response);
						});
    });
	$('.tax_div_clear').change(function()
	{
		var charge_cal_option = $(this).val();
		
		$('#charges_entry1').empty(); 
			
		var curr_data = {
						action: 'amgt_tax_div_html',
						charge_cal_option: charge_cal_option,		
						dataType: 'json'
						};					
						
						$.post(amgt.ajax, curr_data, function(response) {
                      					 
						$('#charges_entry1').html(response);
						});
    });
	$("body").on("click",'.new_entry_charges',function()
	{	 
	    var charge_cal_option = $("input[name='charge_cal']:checked").val();
		 var curr_data = {
			action: 'amgt_charge_cal_option_html',
			charge_cal_option: charge_cal_option,				
			dataType: 'json'
		};
		
		$.post(amgt.ajax, curr_data, function(response) 
		{
			$("#charges_entry").append(response);
			return false;
		});
	});
	$('#member_type').change(function()
	{
		var member_type = $(this).val();
		
		if(member_type== 'Owner' || member_type== 'tenant')
		{
			$('.occupied_div').show();	
			$('.occupied_div_edit').show();	
		}
		else
		{
			$('.occupied_div').hide();	
			$('.occupied_div_edit').hide();	
		}		
    });
	
	$('.onlyletter_number_space_validation').keypress(function( e ) 
	{     
		var regex = new RegExp("^[0-9a-zA-Z \b]+$");
		var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
		if (!regex.test(key)) 
		{
			event.preventDefault();
			return false;
		} 
   });  
   //user profile update pop up
   $("body").on("click","#profile_change",function() 
	{
		
		//event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		 var docHeight = $(document).height(); //grab the height of the page
		var scrollTop = $(window).scrollTop();
	   //alert(evnet_id);
		 var curr_data = {
					action: 'amgt_change_profile_photo',
					dataType: 'json'
					};					
					
					$.post(amgt.ajax, curr_data, function(response) {	
					
						$('.popup-bg').show().css({'height' : docHeight});
						$('.profile_picture').html(response);	
					});
	});
/* ------- load tax amount-----*/
   jQuery("body").on("change",".complain_type,.complaint_nature123",function()
   {
	   var selValue = $('input[name=complaint_nature]:checked').val();
	   var soc_type = $('input[name=type]:checked').val();
	   if(soc_type == 'individual')
	   {
		  $('.single_member').css('display','block');
		  
	   }
	   else
	   {
		  $('.single_member').css('display','none');
	   }
	   
	   
	   if(selValue == 'Maintenance Request' )
	   {
		$('.category_div').css('display','none');
		$('.time_date_div').css('display','block');
		$('.status_div').css('display','block');
		  
	   }
	   else
	   {
		$('.category_div').css('display','block');
		$('.time_date_div').css('display','none');
	    $('.status_div').css('display','none');
		  
	   }
	   
   });
   
   	//jQuery("body").on("click",".visitor_details_serch",function()
	$("body").on("click",".visitor_details_search",function() 
	{
		var visitor_name = $('.visitor_name').val();
		
			 var curr_data = {
					action: 'amgt_load_visitor_data_by_id',
					visitor_name: visitor_name,				
					dataType: 'json'
					};
					$.post(amgt.ajax, curr_data, function(response) 
					{ 
						var json_obj = $.parseJSON(response);
						if(json_obj[0] == '0')
						{
							 alert('No Records Found !');
						}
						else
						{ 
							$("input[name=gate][value=" + json_obj[1]+ "]").attr('checked', 'checked');
							$('.visit_reason_append').val(json_obj[2]);
							$('.visitor_compound_append').val(json_obj[3]);
							$('.visitor_unit_cat_append').val(json_obj[4]);
							$('.visitor_des_append').val(json_obj[6]);
							$('.visitor_unit_name_append').append(json_obj[7]);
							$('.visitor_unit_name_append').val(json_obj[5]);
							$('.visitor_id_number').val(json_obj[8]);	
							$('.visitor_vehicle_number').val(json_obj[9]);
						}
					}); 
	});
	$("#chk_sms_sent").change(function(){
				
			 if($(this).is(":checked"))
			{
				 //alert("chekked");
				 $('#hmsg_message_sent').addClass('hmsg_message_block');
				 
			}
			 else
			{
				 $('#hmsg_message_sent').addClass('hmsg_message_none');
				 $('#hmsg_message_sent').removeClass('hmsg_message_block');
			}
	});
	$("body").on("click",'#add_new_document_entry_member',function() {

		  var curr_data = {

			action: 'amgt_load_document_html_member',		

			dataType: 'json'

		};

		$.post(amgt.ajax, curr_data, function(response) {

			$("#document_entry_member").append(response);

			return false;

		});

	});
});