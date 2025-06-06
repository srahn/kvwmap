var keyp = function(e) {
	// ctrl s
/*
	if (e.ctrlKey && e.key && (e.key == 's' || e.key == 'S')) {
		if (typeof keypress_bound_ctrl_s_button_id !== 'undefined') {
			e.preventDefault();
			console.log('keypress')
			if (e.type == 'keyup') {
				document.getElementById(keypress_bound_ctrl_s_button_id).focus();
				document.getElementById(keypress_bound_ctrl_s_button_id).click();
			}
		}
	}
*/
	if ((e.ctrlKey || e.metaKey) && String.fromCharCode(e.which).toLowerCase() == 's') {
		e.preventDefault();
		console.log('focus and click on Button id: %s', keypress_bound_ctrl_s_button_id);
		document.getElementById(keypress_bound_ctrl_s_button_id).focus();
		document.getElementById(keypress_bound_ctrl_s_button_id).click();
	}
}
document.addEventListener("keydown", keyp);
document.addEventListener("keyup", keyp);