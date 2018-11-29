$(function () {
	$('.datefrom').datetimepicker({
		format: 'DD/MM/YYYY'		
	});
	$('.dateto').datetimepicker({
		format: 'DD/MM/YYYY',
		useCurrent: false		
	});
	$(".datefrom").on("dp.change", function (e) {
		$('.dateto').data("DateTimePicker").minDate(e.date);
	});
	$(".dateto").on("dp.change", function (e) {
		$('.datefrom').data("DateTimePicker").maxDate(e.date);
	});
});


