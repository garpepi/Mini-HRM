$(document).ready(function() {
	$('#dataTables-active').DataTable({
		responsive: true
	});
	$('#dataTables-inactive').DataTable({
		responsive: true
	});
	$('.confirmation').on('click', function () {
        return confirm('Are you sure?');
    });
	
	$('.timeep').datetimepicker({
		format: 'HH:mm:ss'		
	});
});