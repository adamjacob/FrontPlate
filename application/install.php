<?php

	if( file_exists('common/database.sqlite3') ){

		// Whoh there tiger...the database is already installed...
		die;

	}

?>

<h1>FrontPlate</h1>
<p>Welcome to FrontPlate, looks like you need to run the installer to get everything setup!</p>

<?php

	if( isset($_GET['install']) ){

	// Load in out common classes
	require 'common/classes/db.class.php';

		// Accounts table
		DB::x("CREATE TABLE IF NOT EXISTS accounts (
	                    id INTEGER PRIMARY KEY, 
	                    username TEXT, 
	                    password TEXT, 
	                    session TEXT)");

		// Pages table
		DB::x("CREATE TABLE IF NOT EXISTS pages (
	                    id INTEGER PRIMARY KEY, 
	                    title TEXT, 
	                    slug TEXT, 
	                    template TEXT)");

		// Pages Meta table
		DB::x("CREATE TABLE IF NOT EXISTS page_meta (
	                    id INTEGER PRIMARY KEY, 
	                    key TEXT, 
	                    value TEXT,
	                    type TEXT,
	                    page_id INTEGER)");

		// Media table
		DB::x("CREATE TABLE IF NOT EXISTS medias (
                    id INTEGER PRIMARY KEY, 
                    filename TEXT)");

		// Insert stock admin user...
		DB::x("INSERT INTO accounts (username, password) VALUES (?s, ?s)", 'admin', 'admin');

		echo 'Everything Installed! <a href="/admin/">Go to admin</a>';

	}else{

		echo '<a href="?install=true">Install FrontPlate</a>';

	}