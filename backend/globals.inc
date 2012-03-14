<?php

	/* GLOBAL SETTINGS
	 * ************************************
	 * All the overall settings of the site will be kept here for the ease
	 * of modification. Attempt to describe what's kept here as well.
	 */
	
	define("BASE_URL",   (string) 'http://dev.welikepie.com/standards-review/');
	
	// TWITTER APP SETTINGS
	// These here are the keys and secrets used by the pre-configured
	// app on Twitter. App data is used for all the interactions.
	
	define("APP_KEY",    (string) 'zlLmVFVkCa04O7l6B2A');
	define("APP_SECRET", (string) '8NqoqL82FZ9pTcAd6uRnBaTlgS1Rt79DLkxtvXbv9eo');
	
	// DATABASE SETTINGS
	// Database access credentials, as goes.
	
	define("DB_HOST",     (string) 'localhost');
	define("DB_PORT",     (int) 3306);
	define("DB_USER",     (string) '');
	define("DB_PASSWORD", (string) '');
	define("DB_DATABASE", (string) '');
	
	/* INTERNALS
	 * ************************************
	 * The functions that are to be available from any point of code.
	 * Do not modify anything past this bit if you're not sure if you need to.
	 */
	
	function except(Exception $e) { throw $e; }
	
	class mysqliException extends Exception { public function __construct($errno, $error) { parent::__construct("MySQL error encountered: [$errno] $error"); } }
	class TwitterException extends Exception { public function __construct($response) { parent::__construct("Twitter API Error:\n" . print_r(json_decode($response, true))); } }
	
	function handle_exception($e) { header("HTTP/1.1 500 Internal Server Error"); exit($e->getMessage()); return true; }
	function convert_to_exception($no,$str,$file,$line) { throw new ErrorException($str,$no,0,$file,$line); return true; }
	set_exception_handler("handle_exception");
	set_error_handler("convert_to_exception");

?>