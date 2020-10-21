function loadIPS() {
	$.post("php/ajax/sb.php?action=getIPS", function(data) {
		$("#retIPs").html(data);

	}).complete(function() {
		setTimeout(function() {
			loadIPS();
		}, 1000);
	});
}
setTimeout(loadIPS, 5000);