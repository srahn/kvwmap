	<head>
		<title><?php echo TITLE; ?></title>
		<? include(SNIPPETS . 'gui_head.php'); ?>
		<script type="text/javascript">
			function load() {
				$('input[name="browserwidth"]').val($(window).width());
				setTimeout(function() { $("input#login_name").focus(); }, 100);
			}

			function logon() {
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
				document.login.browserwidth.value = width;
				document.login.browserheight.value = height;
				document.login.submit();
			}

			document.onkeydown = function(ev){
				var key;
				ev = ev || event;
				key = ev.keyCode;
				if (key == 13) {
					document.login.anmelden.click();
				}
			}

			var password_check = '<? echo PASSWORD_CHECK; ?>';
			var password_minlength = <? echo PASSWORD_MINLENGTH; ?>;

			function getRandomString(chars, length){
				var i = 1;
				randomString = '';
				while ( i <= length ) {
					$max = chars.length-1;
					$num = Math.floor(Math.random()*$max);
					$temp = chars.substr($num, 1);
					randomString += $temp;
					i++;
				}
				return randomString;
			}
			
			function shuffle(string) {
				var parts = string.split('');
				for (var i = parts.length; i > 0;) {
						var random = parseInt(Math.random() * i);
						var temp = parts[--i];
						parts[i] = parts[random];
						parts[random] = temp;
				}
				return parts.join('');
			}

			function setRandomPassword() {
				var check_condition = password_check.split('');
				var check_count = password_check.substring(1).split('1').length - 1;
				if(check_condition[0] == '0')check_count = 4;
				if(check_count == 0)check_count = 1;
				var lower_chars = 'abcdefghijklmnopqrstuvwxyz';
				var upper_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				var numbers = '0123456789';
				var special_chars = '!@#$_+?%^&)';
				var length = Math.ceil(password_minlength/check_count);
				var randomPassword;

				randomPassword = getRandomString(lower_chars, length);
				
				if(check_condition[0] == '0' || check_condition[2] == '1'){
					randomPassword += getRandomString(upper_chars, length);
				}
				if(check_condition[0] == '0' || check_condition[3] == '1'){
					randomPassword += getRandomString(numbers, length);
				}
				if(check_condition[0] == '0' || check_condition[4] == '1'){
					randomPassword += getRandomString(special_chars, length);
				}

				$('#new_password, #new_password_2').val(shuffle(randomPassword));
			}

			function togglePasswordVisibility(t, p1, p2) {
				$(t).toggleClass('fa-eye fa-eye-slash');

				if ($('#' + p1).attr('type') == 'text') {
					$('#' + p1 + ', #' + p2).attr('type', 'password');
				}
				else {
					$('#' + p1 + ', #' + p2).attr('type', 'text');
				}
			}

			//Copies a string to the clipboard. Must be called from within an 
			//event handler such as click. May return false if it failed, but
			//this is not always possible. Browser support for Chrome 43+, 
			//Firefox 42+, Safari 10+, Edge and IE 10+.
			//IE: The clipboard feature may be disabled by an administrator. By
			//default a prompt is shown the first time the clipboard is 
			//used (per session).
			function copyToClipboard(text) {
				if (window.clipboardData && window.clipboardData.setData) {
					//IE specific code path to prevent textarea being shown while dialog is visible.
					return clipboardData.setData("Text", text); 
				} else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
					var textarea = document.createElement("textarea");
					textarea.textContent = text;
					textarea.style.position = "fixed";	//Prevent scrolling to bottom of page in MS Edge.
					document.body.appendChild(textarea);
					textarea.select();
					try {
							return document.execCommand("copy");	//Security exception may be thrown by some browsers.
					} catch (ex) {
							console.warn("Copy to clipboard failed.", ex);
							return false;
					} finally {
							document.body.removeChild(textarea);
					}
				}
			}
		</script>
	</head>