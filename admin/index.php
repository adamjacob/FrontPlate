<?php

	require '../config.php';

	// Load in out common classes
	require '../common/classes/db.class.php';

	// Load in our admin classes
	require 'api/classes/auth.class.php';

	// We will need this later...setup now.
	$auth = new Auth();

		if($auth->verify()){ 

			$sessionData = $_COOKIE["fp_session"];

		}else{

			$sessionData = '';

		}

	
	$base = explode('/admin', $frontplate_config->request_root);

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Admin</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<noscript>This application requires Javascript to be turned on.</noscript>
		<script type="text/javascript">

		// For Backbone, an others...
		window.FrontPlateAdmin = { base: 'http://<?=$base[0]?>/admin/', session:'<?=$sessionData?>' };

		// Require.js is not loaded just yet...
		window.onload = function(){

			// For Require.js
			require.config({ baseUrl: 'http://<?=$base[0]?>/admin/js/' });

		};
		
		</script>
		<script data-main="js/main.js" src="js/lib/require-2.1.6-min.js"></script>
	</body>
</html>