$(function () {
	$('.datefrom').datetimepicker({
		viewMode: 'months',
		format: 'MM/YYYY'		
	});
/*
	$('.dateto').datetimepicker({
		viewMode: 'months',
		format: 'MM/YYYY',
		useCurrent: false		
	});
	$(".datefrom").on("dp.change", function (e) {
		var date = new Date(e.date), y = date.getFullYear(), m = ("0" + (date.getMonth() + 1)).slice(-2);
		$('.dateto').data("DateTimePicker").minDate(moment("01-"+m+"-"+y,"DD/MM/YYYY"));
	});
	$(".dateto").on("dp.change", function (e) {
		$('.datefrom').data("DateTimePicker").maxDate(e.date);
	});
*/
});



