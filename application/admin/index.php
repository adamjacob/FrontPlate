<?php

	// Load in out common classes
	require '../common/classes/db.class.php';

	// Load in our admin classes
	require '../api/classes/auth.class.php';

	// We will need this later...setup now.
	$auth = new Auth();

		if($auth->verify()){ 

			$sessionData = $_COOKIE["fp_session"];

		}else{

			$sessionData = '';

		}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Admin</title>
		<script data-main="../application/admin/js/main.js" src="../application/admin/js/lib/require-2.1.6-min.js"></script>
		<link rel="stylesheet" type="text/css" href="../application/admin/css/style.css">
		<script type="text/javascript">
		window.session = {session_id:'<?=$sessionData?>'}
		</script>
	</head>
	<body>
		<noscript>This application requires Javascript to be turned on.</noscript>
	</body>
</html>