<?php

/***************************************************
 * ShorterURL (URL Shortner Library)
 *
 * @author      John Cuppi
 * @version     1.0
 * @updated     1:09 AM Thursday, December 29, 2016
 * @description library for shorterurl 
 *
 ****************************************************/

class ShorterURL
{	
	public $DB;
	public $base_url = "http://127.0.0.1/shorterurl/r.php?u=";
	public $htaccess_url = "http://127.0.0.1/shorterurl/";
	public $htaccess = 1;
	
	/**
	* Set up variables 
	*
	* @return void
	*/
	
	public function __construct() {
		
		// Utilize htaccess or not
		if($this->htaccess) {
			$this->base_url = $this->htaccess_url;
		}
		
		// Create a PDO instance
		$dsn      = "mysql:host=localhost;dbname=shorterurl;charset=utf8;";
		$db_user  = "root";
		$password = "";

		try {
			$this->DB = new PDO($dsn, $db_user, $password);
		} catch (PDOException $e) {
			die("Unable to connect to database.");
		}
		
	}
	
	/**
	* Shorten a URL and insert it, return the shortened url
	*
	* @return str
	*/	
	
	public function shortenURL( $str ) {
	global $account;
	
		// Validate URL
		if (filter_var($str, FILTER_VALIDATE_URL) === FALSE) {
			return false;
		}
		
		// Insert and get the last ID, then convert it
		$this->DB->exec("INSERT INTO urls VALUES ('', '{$str}', '{$_SERVER['REMOTE_ADDR']}', '".time()."', '0', '', 0);");
		$last_id = $this->DB->lastInsertId();
		
		// Increase total URLs hosted statistic
		$this->DB->exec("UPDATE stats SET TOTAL_URLS=TOTAL_URLS+1;");
		
		// Base url and the identifier concat'd
		return $this->base_url . $this->int_toBase36( $last_id  );
		
	}

	/**
	* Return integer to base36
	*
	* @return str
	*/	
	
	public function int_toBase36( $int ) {
		
		// Cast as integer
		$int = (int) $int;
		
		// Integers only
		if(!is_numeric($int)) return false;
	
		// Base 36 to Integer
		$int = intval($int, 36);
				
		// String to base 36
		return base_convert($int, 10, 36);
		 
	}

	/**
	* Return base36 conversion of an int
	*
	* @return int
	*/	
	
	public function base36_toInt( $str ) {
	
	return base_convert($str, 36, 10);
		 
	}
	
	/**
	* Return url from base36 id
	*
	* @return str
	*/	
	
	public function retrieveURL( $base36_str ) {
	
		// Get database identifier	
		$id = $this->base36_toInt($base36_str);
		
		// Select the URL 
		$stmt = $this->DB->query("SELECT url FROM `urls` WHERE id='{$id}';");
		$row = $stmt->fetch();
		
		if(!$row['url']) { 
			return false;
		} else {
			return $row['url'];
		}
		
	}
	
	/**
	* Log hit/visit, and increase count
	*
	* @return void
	*/
	
	public function log_hit( $base36_str ) {
		
		// Get URL integer
		$url_id = $this->base36_toInt($base36_str);
		
		// Log individual URL data
		$this->DB->exec("UPDATE urls SET hits=hits+1 WHERE id='{$url_id}';");
		$this->DB->exec("INSERT INTO hits (`url_id`, `ip`, `time`) VALUES ('{$url_id}','{$_SERVER['REMOTE_ADDR']}', '".time()."');;");
		
		// Log hit served
		$this->DB->exec("UPDATE stats SET TOTAL_SERVE=TOTAL_SERVE+1;");

	}

	/**
	* Delete a url from the database based on DB entry ID
	*
	* @return void
	*/
	
	public function delete_url( $id ) {
		$this->DB->exec("DELETE FROM urls WHERE id='$id';");
		$this->DB->exec("DELETE FROM hits WHERE url_id='$id';");
		$this->DB->exec("UPDATE stats SET TOTAL_URLS=TOTAL_URLS-1;");
	}
	
}
	
?>
