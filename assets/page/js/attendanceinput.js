$(document).ready(function() {
	$('.timeep').datetimepicker({
		format: 'HH:mm'		
	});
	var table = $('#dataTables-example').DataTable({
		columnDefs: [
			{ "width": "10%", "targets": "_all" }
		  ],
		paging: false,
		ordering: false,
		searching: false,
		//scrollX: "100%",
		stripeClasses:[],
		responsive: true
	});
	
	$( ".attend" ).change(function() {
	  var sum = 0;
		$('.attend').each(function() {
			sum += Number($(this).val());
		});
	//	alert(sum);
		$( "#span_total_attend" ).text(sum);
	});
	
	$( ".leaves" ).change(function() {
	  var sum = 0;
		$('.leaves').each(function() {
			sum += Number($(this).val());
		});
	//	alert(sum);
		$( "#span_total_leaves" ).text(sum);
	});
	
	$( ".sick" ).change(function() {
	  var sum = 0;
		$('.sick').each(function() {
			sum += Number($(this).val());
		});
	//	alert(sum);
		$( "#span_total_sick" ).text(sum);
	});
	
	$( ".overtime" ).change(function() {
	  var sum = 0;
		$('.overtime').each(function() {
			sum += Number($(this).val());
		});
	//	alert(sum);
		$( "#span_total_overtime" ).text(sum);
	});
	
	$( ".late" ).change(function() {
	  var sum = 0;
		$('.late').each(function() {
			sum += Number($(this).val());
		});
	//	alert(sum);
		$( "#span_total_late" ).text(sum);
	});
	if(status_view == 1)
	{
		$("#formulir :input").prop("disabled", true);
	}
});
