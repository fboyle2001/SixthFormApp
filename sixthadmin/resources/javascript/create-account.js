$(document).ready(function () {
	
	$("#admin").change(function () {
		
		if(value == "checked") {
			$("#year_row").hide();
		} else {
			$("#year_row").show();
		}
	});
	
});