$(function () {
	$('.datefrom').datetimepicker({
		viewMode: 'months',
		format: 'MM/YYYY'		
	});
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
});

$(document).ready(function() {
	var table = $('#dataTables-example').DataTable({
		/*
		dom: 'Bfrtip',
        buttons: [
			 {
                extend: 'print',
				footer: true
            },
			 {
                extend: 'excelHtml5',
				footer: true,
				title: 'Summary Attendace ' + period +''
            },
			{
                extend: 'pdfHtml5',
				footer: true,
                orientation: 'landscape',
                pageSize: 'LEGAL',
				title: 'Summary Attendace ' + period +''
            },
			 {
                extend: 'csvHtml5',
				footer: true,
				title: 'Summary Attendace ' + period +''
            },
        ],*/
		columnDefs: [
			{ "width": "10%", "targets": "_all" }
		  ],
		paging: false,
		ordering: false,
		searching: false,
		scrollX: "100%",
	});
	$(".basic-multiple").select2();
});

