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

	$('#dataTables-active').DataTable({ 
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"order": [], //Initial no order.
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"/overtime/table/active",
			"type": "POST"
		},
		//Set column definition initialisation properties.
		"columns": [
			{"data": "date"},
			{"data": "employee_name"},
			{"data": "reason"},
			{"data": "action"}
		],
		"order": [[ 0, "desc" ]]

	});
	
	$('#dataTables-inactive').DataTable({ 
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"order": [], //Initial no order.
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": base_url+"/overtime/table/inactive",
			"type": "POST"
		},
		//Set column definition initialisation properties.
		"columns": [
			{"data": "date"},
			{"data": "employee_name"},
			{"data": "reason"},
			{"data": "action"}
		],
		"order": [[ 0, "desc" ]]
	});
});