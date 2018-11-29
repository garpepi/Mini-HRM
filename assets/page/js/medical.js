$(function () {
	$('.date').datetimepicker({
		format: 'DD/MM/YYYY',
			
	});
});

$(document).ready(function() {
	var real_nominal = 0;
	var saldo = 0;
	var url      = document.domain; 
	
	if($('#emp_id').val() != '')
	{
		$.get("http://"+ url +"/medical/get_reimbursment_record/"+$('#emp_id').val(), function(data, status){
			saldo = parseInt(data);
			 $('#balance').text(saldo);
		});
	}
	$('#emp_id').change(function() {
		if($('#date').val() =='')
		{
		  $.get("http://"+ url +"/medical/get_reimbursment_record/"+$('#emp_id').val(), function(data, status){
				real_nominal = $("#real_nominal").val();
				saldo = parseInt(data);
				if(saldo >= real_nominal){
					$("#nominal").val(real_nominal);
				}else{
					$("#nominal").val(saldo);
				}
				 $('#balance').text(saldo);
			});	
		}else{
			$.get("http://"+ url +"/medical/get_reimbursment_record/"+$('#emp_id').val()+"/"+$('#date').val(), function(data, status){
				real_nominal = $("#real_nominal").val();
				saldo = parseInt(data);
				if(saldo >= real_nominal){
					$("#nominal").val(real_nominal);
				}else{
					$("#nominal").val(saldo);
				}
				 $('#balance').text(saldo);
			});
		}
	});
	
	$('.date').on("dp.change", function(e) {
		if($('#emp_id').val() =='')
		{
		  $('#balance').text(0);
		}else{
			$.get("http://"+ url +"/medical/get_reimbursment_record/"+$('#emp_id').val()+"/"+$('#date').val(), function(data, status){
				real_nominal = $("#real_nominal").val();
				saldo = parseInt(data);
				if(saldo >= real_nominal){
					$("#nominal").val(real_nominal);
				}else{
					$("#nominal").val(saldo);
				}
				 $('#balance').text(saldo);
			});
		}
	});
	$('#real_nominal').change(function() {
		real_nominal = $("#real_nominal").val();
		if(saldo >= real_nominal){
			$("#nominal").val(real_nominal);
		}else{
			$("#nominal").val(saldo);
		}
		
	});

});
