var keyp = function(e) {
	// ctrl s
	if (e.ctrlKey &&	e.key && (e.key == 's' || e.key == 'S')) {
		if (typeof keypress_bound_ctrl_s_button_id !== 'undefined') {
			e.preventDefault();
			// ToDo vorher muss der Change des Feldes in dem man gerade ist durchgeführt werden, weil sonst der alte Wert gespeichert wird.
			if (e.type == 'keyup') {
				document.getElementById(keypress_bound_ctrl_s_button_id).click();
			}
		}
	}
}
document.addEventListener("keydown", keyp);
document.addEventListener("keyup", keyp);