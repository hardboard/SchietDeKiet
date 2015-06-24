<?php

class Database
{
	private $dbh;						// Database handler

	function __construct() {
		$hbdb = require_once('/var/www/database.php');
		// Connect to database
		try {
			$this->dbh = new PDO('mysql:host='.$hbdb['connections']['SchietDeKiet']['host'].';dbname='.$hbdb['connections']['SchietDeKiet']['database'], $hbdb['connections']['SchietDeKiet']['username'], $hbdb['connections']['SchietDeKiet']['password']); 
		} catch (PDOException $e) {
			die( "Error!: " . $e->getMessage() . PHP_EOL );
		}
		//$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT );
	}

	function __destruct() {
		// Close database connections
		$this->dbh = null;
	}

	/**
	 * set highscores
	 * @param string $name, int $score
	 * @return  void 
	 */
	function setHighscore($name,$score) {
		// Statement with named placeholders for inserting new addresses in database
		$stmt = $this->dbh->prepare("INSERT INTO schietdekiet (name, score) VALUES (?,?)");
		$stmt->execute(array($name, $score));
	}

	/**
	 * Get highscores from database and store them in $this->highscores
	 * @return void 
	 */
	function getHighscore() {
		// Get all highscores from database (as object)
		$sthHS = $this->dbh->query('SELECT * FROM schietdekiet ORDER BY score DESC');
		$sthHS->setFetchMode(PDO::FETCH_OBJ);
		while($dbHighscores = $sthHS->fetch()) { 	// Loop
			$this->highscores[] = $dbHighscores;
		}
	}	

	/**
	 * Get kietimages from database and store them in $this->kietimages
	 * @return void 
	 */
	function getKietimages() {
		$sthHS = $this->dbh->query("SELECT users.picture AS users_picture
									FROM hbcms_users users 
									LEFT JOIN hbcms_node node_users ON users.uid = node_users.uid AND node_users.type = 'profile'
									INNER JOIN hbcms_content_type_profile node_users_node_data_field_profile_lid_status ON node_users.vid = node_users_node_data_field_profile_lid_status.vid
									WHERE (node_users_node_data_field_profile_lid_status.field_profile_lid_status_value = '30') AND (node_users_node_data_field_profile_lid_status.field_profile_kite_niveau_value IN ('2', '3', '4'))
									");
		$sthHS->setFetchMode(PDO::FETCH_OBJ);
		while($dbR = $sthHS->fetch()) { 	// Loop
			$this->kietimages[] = $dbR;
		}
	}

	/**
	 * Delete low hihgscores from the database
	 * @return void 
	 */
	function deleteLowHS() {
		$sthHS = $this->dbh->query("DELETE t
									FROM 
									    schietdekiet AS t
									  JOIN
									    ( SELECT score AS ts
									      FROM schietdekiet
									      ORDER BY ts DESC
									      LIMIT 1 OFFSET 50
									    ) tlimit
									    ON t.score < tlimit.ts;");
		$sthHS->setFetchMode(PDO::FETCH_OBJ);
		while($dbR = $sthHS->fetch()) { 	// Loop
		}
	}
}