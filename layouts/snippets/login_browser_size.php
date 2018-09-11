<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<head>
		<title><?php echo TITLE; ?></title>
		<META http-equiv=Content-Type content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="layouts/main.css.php">
		<script type="text/javascript">
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
		</script>
	</head>
	<body onload="logon();">
		<form name="login" action="index.php" method="post">
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars); ?>
			<table align="center" cellspacing="4" cellpadding="22" bgcolor="<? echo BG_DEFAULT; ?>" border="0" style="background-color: <? echo BG_DEFAULT; ?>; box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
				<tr>
					<td align="center"><h1>Bitte warten...</h1></td>
				</tr>
			</table>
		</form>
	</body>
	</html>
