$(document).ready(function() {
	var table = $('#dataTables-examples').DataTable({
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
        ],
		columnDefs: [
			{ "width": "10%", "targets": "_all" }
		  ],
		rowsGroup: [// Always the array (!) of the column-selectors in specified order to which rows groupping is applied
					// (column-selector could be any of specified in https://datatables.net/reference/type/column-selector)
			0,
			1
		],
		paging: false,
		ordering: false,
		searching: false,
		scrollX: "100%",
	});
	$(".basic-multiple").select2();
});