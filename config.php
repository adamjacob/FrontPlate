<?php

##### DO NOT EDIT START #####

	// Create the config object...
	$frontplate_config = (object) array();

#####  DO NOT EDIT END  #####


# Template Config

	// Autoload template parts
	// This will enable autoloading of the template parts below...
	$frontplate_config->autoload = true;

		// Before template
		// This will be an array of files within the /template dir,
		// that you wish to be autoloaded (before) the template.
		$frontplate_config->autoload_before = array('/parts/header.php');

		// After template
		// This will be an array of files within the /template dir,
		// that you wish to be autoloaded (after) the template.
		$frontplate_config->autoload_after = array('/parts/footer.php');

		$frontplate_config->request_root = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];