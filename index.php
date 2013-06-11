<?php

	require 'config.php';

	// Hold up! Is the app installed yet?
	if( !file_exists('application/common/database.sqlite3')){

		// Not installed...to the installer we go...
		header('Location: /application/install.php');

		die;

	}

	// Load in out common classes
	require 'application/common/classes/db.class.php';

	// Request method
	$method = $_SERVER['REQUEST_METHOD'];

	$route = explode('/', $_GET['route']);

	$route = array_filter($route);

	if(isset($route) && isset($method) ){

		// Fetch page by slug
		$page = DB::q('SELECT * FROM pages WHERE slug = ?s', $route[0]);
		
			$page->setFetchMode(PDO::FETCH_OBJ);

				$page = $page->fetch();

				// Fetch page meta
				$meta = DB::q('SELECT * FROM page_meta WHERE page_id = ?i', $page->id);

				$meta->setFetchMode(PDO::FETCH_OBJ);

				$meta = $meta->fetchAll();

				foreach ($meta as $value) {
					
					$keyName = $value->key;

					$page->meta->$keyName = $value->value;

				}

		// See if the template exists
		if( file_exists('template/'.$page->template.'.php')){

			if($frontplate_config->autoload == true){

				foreach ($frontplate_config->autoload_before as $value) {
					
					include 'template/'.$value;

				}

			}

			// Yup! Include it...
			include 'template/'.$page->template.'.php';

			if($frontplate_config->autoload == true){

				foreach ($frontplate_config->autoload_after as $value) {
					
					include 'template/'.$value;

				}

			}

		}else{

			// Page not found!
			//header("HTTP/1.0 404 Not Found");
			//die;

			echo 'No: '.'template/'.$page->template.'.php';

		}

	}else{

		// Page not found!
		header("HTTP/1.0 404 Not Found");
		die;

	}