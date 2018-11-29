$(function () {
	$('.datep').datetimepicker({
		format: 'DD/MM/YYYY'		
	});

	$( "#employee_status" ).change(function() {
		if($( "#employee_status" ).val() == 2){
			$( ".contract" ).show();
		}else{
			$( ".contract" ).hide();
		}
	});
	if($( "#employee_status" ).val() == 2){
			$( ".contract" ).show();
		}else{
			$( ".contract" ).hide();
		}
});


