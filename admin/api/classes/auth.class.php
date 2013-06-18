<?php

class Auth {

	protected $auth_password_salt = '934dnfid932df20';

	/*
	|	Auth::Login($username, $password);
	*/
	public function login($username, $password){

		if($username == NULL || $password == NULL){

			return false;

		}

		//$password = crypt($password, $this->auth_password_salt);

		// Query the accounts table, see if it even exists...
		$account = DB::q('SELECT id, username, password FROM accounts WHERE username = ?s AND password = ?s', $username, $password);
		$account->setFetchMode(PDO::FETCH_OBJ);

			$account = $account->fetch();

			if( $account != false ){

				// Gen the unique session string
				$session = uniqid('', true) . $account->id . time();

					// Encrypt the session
					$session_encrypt = crypt($session, $this->auth_password_salt);

				// Save the encrypted session string in the accounts table
				DB::x('UPDATE accounts SET session = ?s WHERE id = ?i', $session_encrypt, $account->id);

				// All is good... return session
				return $session;

			}else{

				// Invalid return false...
				return false;

			}

	}


	/*
	|	Auth::Verify($session);
	*/
	public function verify(){

		$session = $_COOKIE['fp_session'];

		if($session == NULL){

			return false;

		}

		// Encrypt the session
		$session = crypt($session, $this->auth_password_salt);

		// Query the accounts table, see if it even exists...
		$account = DB::q('SELECT id FROM accounts WHERE session = ?s', $session);
		$account->setFetchMode(PDO::FETCH_OBJ);

			$account = $account->fetch();

			if( $account != false ){

				// All is good... return true
				return true;

			}else{

				// Remove invalid session cookie
				setcookie ("fp_session", "", time() - 3600);

				// Invalid return false...
				return false;

			}

	}

	/*
	|	Auth::Logout();
	*/
	public static function logout(){

		// Remove session cookie
		setcookie ("fp_session", "", time() - 3600);

		return true;

	}

}