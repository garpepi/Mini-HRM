$(document).ready(function() {
	/*
	$('#dataTables-active').DataTable({
		responsive: true
	});
	$('#dataTables-inactive').DataTable({
		responsive: true
	});
	*/
	var base_url = window.location.origin;

	$('#dataTables-rejected').DataTable({ 
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"order": [], //Initial no order.
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"/overtime/queue_reject",
			"type": "POST"
		},
		//Set column definition initialisation properties.
		"columns": [
			{"data": "no"},
			{"data": "date"},
			{"data": "emp_id"},
			{"data": "name"},
			{"data": "employee_name"},
			{"data": "start_in"},
			{"data": "end_out"},
			{"data": "upload_status"},
			{"data": "desc_status"}
		],
		"order": [[ 0, "desc" ]]

	});
});