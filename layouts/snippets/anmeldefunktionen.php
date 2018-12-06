<script type="text/javascript">
	function onFormLoad() {
		document.login.passwort.focus(); <?php
		foreach ($formvars AS $key => $value) {
			if (!in_array($key, array('username', 'passwort'))) { ?>
				$('<input>').attr({
					type: 'hidden',
					name: '<?php echo $key; ?>',
					value: '<?php echo $value; ?>'
				}).appendTo('form'); <?php
			}
		} ?>
	}

	function onLogon() {
		if(typeof(window.innerWidth) == 'number'){
			width = window.innerWidth;
			height = window.innerHeight;
		}else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)){
			width = document.documentElement.clientWidth;
			height = document.documentElement.clientHeight;
		}else if(document.body && (document.body.clientWidth || document.body.clientHeight)){
			width = document.body.clientWidth;
			height = document.body.clientHeight;
		}
		$('<input>').attr({
			type: 'hidden',
			name: 'browserwidth',
			value: width
		}).appendTo('form');
		$('<input>').attr({
			type: 'hidden',
			name: 'browserheight',
			value: height
		}).appendTo('form');
		$('form').submit();
	}

	document.onkeydown = function(ev) {
		var key;
		ev = ev || event;
		key = ev.keyCode;
		if (key == 13) {
			document.login.anmelden.click();
		}
	}
</script>