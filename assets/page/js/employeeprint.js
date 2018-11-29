$(document).ready(function() {
	var table = $('#dataTables-example').DataTable({
		dom: 'Bfrtip',
        buttons: [
			 {
                extend: 'print',
				footer: true
            },
			 {
                extend: 'excelHtml5',
				footer: true,
				title: 'Period ' + period +''
            },
			{
                extend: 'pdfHtml5',
				footer: true,
                orientation: 'landscape',
                pageSize: 'LEGAL',
				title: 'Period ' + period +''
            },
			 {
                extend: 'csvHtml5',
				footer: true,
				title: 'Period ' + period +''
            },
        ],
		columnDefs: [
			{ "width": "10%", "targets": "_all" }
		  ],
		paging: false,
		ordering: false,
		searching: false,
		scrollX: "100%",
		footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 12 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 12 ).footer() ).html(
                total.toLocaleString()
            );
        }
	});
	$(".basic-multiple").select2();
	$('#datetimepicker6').datetimepicker({
		format: 'DD/MM/YYYY'
	});
	 $('#datetimepicker7').datetimepicker({
		 format: 'DD/MM/YYYY'	,
			 useCurrent: false //Important! See issue #1075
	 });
	 $("#datetimepicker6").on("dp.change", function (e) {
			 $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
	 });
	 $("#datetimepicker7").on("dp.change", function (e) {
			 $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
	 });
});
