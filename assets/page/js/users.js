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
	
	var password = document.getElementById("password"), confirm_password = document.getElementById("confirm_password");

	function validatePassword(){
	  if(password.value != confirm_password.value) {
		confirm_password.setCustomValidity("Passwords Don't Match");
	  } else {
		confirm_password.setCustomValidity('');
	  }
	}

	password.onchange = validatePassword;
	confirm_password.onkeyup = validatePassword;
});