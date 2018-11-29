$(document).ready(function() {
	var table = $('#dataTables-example').DataTable({
		columnDefs: [
			{ "width": "10%", "targets": "_all" }
		  ],
		paging: false,
		ordering: false,
		searching: false,
		scrollX: "100%"
		//responsive: true
	});
	
});
