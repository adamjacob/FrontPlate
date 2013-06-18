<?php

	// Load in out common classes
	require '../../../common/classes/db.class.php';

	// Fetch site title and icon...
	$option = DB::q('SELECT * FROM options WHERE key = ?s', 'site_title');
	
		$option->setFetchMode(PDO::FETCH_OBJ);

			$option = $option->fetch();

?>
<div class="site-info-block">

	<div class="icon"></div>
	<div class="title"><?=$option->value?></div>

</div>