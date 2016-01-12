/**
 * Gets a parameter from the url the same way php would
 * 
 * @param name
 *            the desired parameter
 * @returns the value of the parameter
 */
function $_GET(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"), results = regex
			.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g,
			" "));
}

function isset_GET(name) {
	return $_GET(name) === null;
}