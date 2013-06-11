<?php

	ini_set('error_reporting', E_ALL);

	// Load in out common classes
	require '../common/classes/db.class.php';

	// Load in our admin classes
	require 'classes/auth.class.php';

	// We will need this later...setup now.
	$auth = new Auth();

	// Request method
	$method = $_SERVER['REQUEST_METHOD'];

	$route = explode('/', $_GET['route']);

	$route = array_filter($route);

	if(isset($route) && isset($method) ){

		// Routes
		switch ($route[0]) {

			case 'auth':

				if($method == 'POST'){

					$session = $auth->login($_POST['username'], $_POST['password']);

					if($session != NULL){

						// Set the non-encrypted string as the cookie
						setcookie("fp_session", $session, time()+3600, "/", "");

						// Correct json headers
						header('Content-Type: application/json');

						// Print out status json
						echo json_encode( array('status' =>  true, 'session' => $session) );

					}else{

						// Correct json headers
						header('Content-Type: application/json');

						// Print out status json
						echo json_encode( array('status' =>  false) );

					}

				}

				break;

			case 'pages':

				if($method == 'GET'){
				
					$auth = new Auth();

					if($auth->verify()){

						$pages = DB::q('SELECT * FROM pages');
						
						$pages->setFetchMode(PDO::FETCH_OBJ);

							$pages = $pages->fetchAll();

							header('Content-Type: application/json');

							echo json_encode($pages);

					}else{

						header('HTTP/1.0 401 Unauthorized');
						die;

					}

				}

				break;

			case 'page':

				if($method == 'POST'){
				// Create

					$auth = new Auth();

					if($auth->verify()){

						$data = file_get_contents("php://input");
						$data = json_decode($data);

						$page = DB::x('INSERT INTO pages (title,slug,template) VALUES (?s,?s,?s)', $data->title, $data->slug, $data->template);
						
						$page = DB::q('SELECT * FROM pages WHERE id = ?i', DB::lastInsertId());

						$page->setFetchMode(PDO::FETCH_OBJ);

							$page = $page->fetch();

							// Fetch page meta
							$meta = DB::q('SELECT * FROM page_meta WHERE page_id = ?i', $page->id);

							$meta->setFetchMode(PDO::FETCH_OBJ);

							$meta = $meta->fetchAll();

							// Attach the meta
							$page->meta = $meta;

								// jSON header
								header('Content-Type: application/json');

									// Echo the page data
									echo json_encode($page);

					}else{

						// Yo, you need to login!
						header('HTTP/1.0 401 Unauthorized');
						die;

					}

				}elseif($method == 'GET'){
				// Get

					$auth = new Auth();

					if($auth->verify()){

						$page = DB::q('SELECT * FROM pages WHERE id = ?s', $route[1]);
						
						$page->setFetchMode(PDO::FETCH_OBJ);

							$page = $page->fetch();

							// Fetch page meta
							$meta = DB::q('SELECT * FROM page_meta WHERE page_id = ?i', $page->id);

							$meta->setFetchMode(PDO::FETCH_OBJ);

							$meta = $meta->fetchAll();

							foreach ($meta as $value) {
								
								$keyName = $value->key;

								$page->meta->$keyName = $value;

							}

								// jSON header
								header('Content-Type: application/json');

									// Echo the page data
									echo json_encode($page);

						}else{

							// Yo, you need to login!
							header('HTTP/1.0 401 Unauthorized');
							die;

						}

					}elseif($method == 'PUT'){

						$auth = new Auth();

						if($auth->verify()){

							$data = file_get_contents("php://input");
							$data = json_decode($data);

							$data->template = trim($data->template);

							$page = DB::x('UPDATE pages SET title = ?s, slug = ?s, template = ?s WHERE id = ?i', $data->title, $data->slug, $data->template, $route[1]);

							foreach ($data->meta as $value) {
								
								// Does this meta exist...
								$meta = DB::q('SELECT * FROM page_meta WHERE page_id = ?i AND key = ?s', $data->id, $value->name);
								$meta->setFetchMode(PDO::FETCH_OBJ);
								$meta = $meta->fetchAll();

									if( count($meta) != 0){

										DB::x('UPDATE page_meta SET value = ?s WHERE id = ?i', $value->value, $meta[0]->id);

									}else{

										DB::x('INSERT INTO page_meta (key, value, page_id, type) VALUES (?s, ?s, ?i, ?s)', $value->name, $value->value, $data->id, $value->type);

									}

							}

							$page = DB::q('SELECT * FROM pages WHERE id = ?i', $route[1]);

							$page->setFetchMode(PDO::FETCH_OBJ);

								$page = $page->fetch();

								// Fetch page meta
								$meta = DB::q('SELECT * FROM page_meta WHERE page_id = ?i', $page->id);

								$meta->setFetchMode(PDO::FETCH_OBJ);

								$meta = $meta->fetchAll();

								foreach ($meta as $value) {
									
									$keyName = $value->key;

									$page->meta->$keyName = $value;

								}

									// jSON header
									header('Content-Type: application/json');

										// Echo the page data
										echo json_encode($page);

						}else{

							// Yo, you need to login!
							header('HTTP/1.0 401 Unauthorized');
							die;

						}

					}else{
						
						// Yo, you need to login!
						header('HTTP/1.0 401 Unauthorized');
						die;

					}

				break;

				case "template_values":

					if( file_exists('../../template/'. $route[1] .'.php') ){

						$template_data = file_get_contents('../../template/'. $route[1] .'.php'); // Load the plugin we want

					}else{

						$template_data = "@value:body[text]";

					}

			        preg_match_all ( '|@value:(.*)$|mi', $template_data, $values );

			        if(count(array_filter($values)) == 0){

						$template_data = "@value:body[text]";
			        	preg_match_all ( '|@value:(.*)$|mi', $template_data, $values );

			        }

			        $counter = 0;

			        $template_values = array();

					foreach ($values[1] as $key => $value) {

						$value = explode('[', $value);

						$name = $value[0];
						$type = substr($value[1], 0, -1);

						array_push($template_values, array('name'=>$name, 'type'=>$type));

						/*if(isset($current_page->$name)){

							$value = $current_page->$name;

						}else{

							$value = '';

						}*/

					}

						echo json_encode($template_values);

					break;

			default:
				# code...
				break;

		}
	
	}