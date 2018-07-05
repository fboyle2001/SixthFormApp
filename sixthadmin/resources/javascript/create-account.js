$(document).ready(function () {
	$("#admin").change(function () {
		if($("#admin").is(":checked")) {
			$("#year_row").hide();
			$("#year").val(12);
		} else {
			$("#year_row").show();
		}
	});
});
