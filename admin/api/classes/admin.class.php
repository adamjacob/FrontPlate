<?php

class Admin {

	public static $sidebar_menu = array(	'Pages'		=>'admin/pages',
											'Media'		=>'admin/media',
											'Plugins'	=>'admin/plugins',
											'Settings'	=>'admin/settings'
										);

	function __construct(){

		// Sessions for Auth
		session_start();

		Hook::execute('admin_init');

		// Index
		Route::reserve('admin', function(){

			if( isset($_SESSION['user']) ){

				header("Location: " . Config::$base_url . '/admin/pages');

			}else{

				header("Location: " . Config::$base_url . '/admin/login');

			}

		});

		// Login
		Route::reserve('admin/login', function(){

			if(isset($_POST['login'])){

				if(Auth::login($_POST['username'],$_POST['password']) == true){

					header("Location: " . Config::$base_url . '/admin/pages');

				}else{

					$error = true;

				}

			}

			include Config::$doc_root . '/application/admin/template/login.php';

		});

		// Logout
		Route::reserve('admin/logout', function(){

			Auth::logout();

		});

		// Forgot Password
		Route::reserve('admin/login/forgot', function(){

			if($_POST['forgot']){

				if(Auth::forgot($_POST['username'])==true){

					$success = true;

				}else{

					$error = true;

				}

			}

			include Config::$doc_root . '/application/admin/template/forgot-password.php';

		});

		// Reset Password
		Route::reserve('admin/login/reset/(?)', function($key){

			if(isset($key)){

				if( isset($_POST['reset'])){

					if( $_POST['password'] != $_POST['confirm'] || $_POST['password'] == NULL ){

						$error = true;

					}else{

						Auth::resetPassword($key,$_POST['password']);

						$success = true;

					}


				}

				include Config::$doc_root . '/application/admin/template/reset-password.php';

			}else{

				header("Location: " . Config::$base_url . '/admin/login');

			}

		});

		// Pages Overview
		Route::reserve('admin/pages', function(){

			if( isset($_GET['edit'])){

				if( isset($_POST['save']) ){

					Object::save($_GET['edit'], $_POST);
				
					$saved = true;
				
				}

				include Config::$doc_root . '/application/admin/page_edit.php';

			}else{

				include Config::$doc_root . '/application/admin/pages.php';

			}

		}, array($this, 'auth_check'));

			// Pages Overview
			Route::reserve('admin/pages/edit/(?)', function($page){

					if( isset($_POST['save']) ){

						Object::save($page, $_POST);
					
						$saved = true;
					
					}

					include Config::$doc_root . '/application/admin/page_edit.php';

			}, array($this, 'auth_check'));

			// Pages Rest Routes
			Route::reserve('admin/pages/create', function(){

				echo json_encode(Object::type('page')->create('Untitled Page','untitled-page-'.rand(0,999)));

			});

		// Media
		Route::reserve('admin/media', function(){

			$media = Media::all();

			include Config::$doc_root . '/application/admin/media.php';

		}, array($this, 'auth_check'));

			Route::reserve('admin/media/upload', function(){

				$path = Media::store($_FILES['file']);

					$media_object = Object::type('media')->create($_FILES['file']['name'],'');

						Object::type('media')->save_single_meta($media_object['id'],'original_path',$path);

				echo json_encode( array('status'=>'true', 'path'=>$path, 'id'=>$media_object['id']) );

			}, array($this, 'auth_check'));

		// Plugins
		Route::reserve('admin/plugins', function(){

			include Config::$doc_root . '/application/admin/plugins.php';

		}, array($this, 'auth_check'));

			// Activate
			Route::reserve('admin/plugins/activate/(?)', function($plugin){

				$active_plugins = Option::get('active-plugins');
				$active_plugins = json_decode($active_plugins);

				if($active_plugins == NULL){

					$active_plugins = array();

				}
					array_push($active_plugins, $plugin);

					$active_plugins = json_encode($active_plugins);

					Option::save('active-plugins', $active_plugins);

				header("Location: " . Config::$base_url . '/admin/plugins');

			}, array($this, 'auth_check'));

			// Deactivate
			Route::reserve('admin/plugins/deactivate/(?)', function($plugin){

				$active_plugins = Option::get('active-plugins');
				$active_plugins = json_decode($active_plugins);

				if($active_plugins == NULL){

					$active_plugins = array();

				}

					if( ( $pos = array_search($plugin, $active_plugins) ) !== false) {

					    unset($active_plugins[$pos]);

					}

					$active_plugins = json_encode($active_plugins);

					Option::save('active-plugins', $active_plugins);

				header("Location: " . Config::$base_url . '/admin/plugins');

			}, array($this, 'auth_check'));

		// Settings
		Route::reserve('admin/settings', function(){

			header("Location: " . Config::$base_url . '/admin/settings/site');

		}, array($this, 'auth_check'));

			// Accounts
			Route::reserve('admin/settings/accounts', function($section){

				include Config::$doc_root . '/application/admin/accounts.php';

			}, array($this, 'auth_check'));

			// Create Account
			Route::reserve('admin/settings/accounts/create', function($account){

				if( isset($_POST['save']) ){

					if(Auth::create($_POST['username'],$_POST['password'],$_POST['email']) !== false){
					
						header("Location: " . Config::$base_url . '/admin/settings/accounts');

					}else{

						$error = true;

					}

				}

				include Config::$doc_root . '/application/admin/create-accounts.php';

			}, array($this, 'auth_check'));

			// Edit Account
			Route::reserve('admin/settings/accounts/edit/(?)', function($account){

				if( isset($_POST['save']) ){

					if(Auth::update($account, $_POST['username'],$_POST['password'],$_POST['email']) !== false){
					
						$saved = true;

					}else{

						$error = true;

					}


				}

				include Config::$doc_root . '/application/admin/edit-accounts.php';

			}, array($this, 'auth_check'));

			// Delete Account
			Route::reserve('admin/settings/accounts/delete/(?)', function($account){

				Auth::delete($account);

				header("Location: " . Config::$base_url . '/admin/settings/accounts');

			}, array($this, 'auth_check'));

		// Settings
		Route::reserve('admin/settings/(?)', function($section){

			if( isset($_POST['save']) ){

				// Remove save button
				unset($_POST['save']);

				foreach ($_POST as $key => $value) {

					Option::save($key, $value);

				}

				$saved = true;

			}

			include Config::$doc_root . '/application/admin/settings.php';

		}, array($this, 'auth_check'));

				Route::reserve('admin/install', function(){

					if(isset($_POST['install'])){

						// Username Check
						if(trim($_POST['username']) == NULL){

							// The user did not give a username so default to 'admin'...
							$username = 'admin';

						}else{

							$username = $_POST['username'];

						}

						// Site Title Check
						if(trim($_POST['site-title']) == NULL){

							// The user did not give a site title...
							$siteTitle = 'Yo New Site';

						}else{

							$siteTitle = $_POST['site-title'];

						}

						/*$sql = "
								CREATE TABLE IF NOT EXISTS `objects` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `title` varchar(255) NOT NULL,
								  `route` varchar(255) NOT NULL,
								  `template` varchar(255) NOT NULL,
								  `parent` int(11) NOT NULL,
								  `type` varchar(255) NOT NULL,
								  `order` int(5) NOT NULL,
								  PRIMARY KEY (`id`),
								  KEY `title` (`title`)
								) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=108 ;

								CREATE TABLE IF NOT EXISTS `object_metas` (
								  `object_id` int(11) NOT NULL,
								  `name` varchar(255) NOT NULL,
								  `value` longtext NOT NULL,
								  PRIMARY KEY (`object_id`,`name`)
								) ENGINE=InnoDB DEFAULT CHARSET=latin1;

								CREATE TABLE IF NOT EXISTS `options` (
								  `name` varchar(255) NOT NULL,
								  `value` longtext NOT NULL,
								  PRIMARY KEY (`name`)
								) ENGINE=InnoDB DEFAULT CHARSET=latin1;
						";

						DB::x($sql);*/

						// Save the site title
						Option::save('site-title', $siteTitle);

						// Create the user account...
						Auth::create($username, $_POST['password'], $_POST['email']);

						$installed = true;

					}

					include Config::$doc_root . '/application/admin/install.php';

				});

	}

	/*
	|	Admin Login Functions
	*/
	public function auth_check(){

		if(Auth::verify($_COOKIE['session']) != true){

			header("Location: " . Config::$base_url . '/admin/login');

		}

	}

	/*
	|	Admin Template Rendering Functions
	*/

	public static function header(){

		include Config::$doc_root . '/application/admin/template/header.php';

	}

	public static function footer(){

		include Config::$doc_root . '/application/admin/template/footer.php';

	}

	public static function render($file, $variables){

		extract($variables);

		self::header();

			include Config::$doc_root . $file;

		self::footer();

	}

}