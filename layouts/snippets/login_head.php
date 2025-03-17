	<head>
		<title><?php echo TITLE; ?></title>
		<? include(SNIPPETS . 'gui_head.php'); ?>
		<script type="text/javascript">
			function load() {
				$('input[name="browserwidth"]').val($(window).width());
				setTimeout(function() { $("input#login_name").focus(); }, 100);
			}

			function logon() {
				if (typeof(window.innerWidth) == 'number') {
					width = window.innerWidth;
					height = window.innerHeight;
				}
				else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
					width = document.documentElement.clientWidth;
					height = document.documentElement.clientHeight;
				}
				else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
					width = document.body.clientWidth;
					height = document.body.clientHeight;
				}
				document.login.browserwidth.value = width;
				document.login.browserheight.value = height;
				document.login.submit();
			}

			document.onkeydown = function(ev) {
				var key;
				ev = ev || event;
				key = ev.keyCode;
				if (key == 13) {
					document.login.anmelden.click();
				}
			}

			var password_check = '<? echo PASSWORD_CHECK; ?>';
			var password_minlength = <? echo PASSWORD_MINLENGTH; ?>;

		</script>
	</head>