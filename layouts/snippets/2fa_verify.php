<?

$_SESSION['angemeldet'] = false;

if (!isset($_SESSION['2fa_verification'])) {
	header("Location: index.php");
	exit;
}

?>
<html>
	<head>
		<style>
			body {
				height: 100vh;
				display: flex;
				justify-content: center;
				align-items: center;
				flex-flow: column;
			}
			div {
				text-align: center;
			}
		</style>
	</head>
	<body>
		<div>
			<form method="post">
				<h2>2FA-Code eingeben</h2>
				<input name="code" placeholder="6-stelliger Code" required><br>
				<button type="submit">Bestätigen</button>
			</form>
		</div>
	</body>
</html>