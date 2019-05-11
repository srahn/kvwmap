var keyp = function(e) {
	if (
		e.ctrlKey &&
		e.key &&
		(e.key == 's' || e.key == 'S')
	) {
		e.preventDefault();
		// ToDo vorher muss der Change des Feldes in dem man gerade ist durchgeführt werden, weil sonst der alte Wert gespeichert wird.
		$("#" + keypress_bound_submit_button_id).focus().click();
	}
}
document.addEventListener("keydown", keyp); 