<?php

class describeModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function getWordDetails($word) {
	
		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;

		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE . ' WHERE word = ? ORDER BY word');

		$sth->execute(array($word));
		$data = array();
		
		while($result = $sth->fetch(PDO::FETCH_ASSOC)) {

			// Extract head words and alias words along with their corresponding notes
			$result = $this->extractDetailsFromDescription($result);

			// Form proper html from xml elements
			$result['description'] = $this->xmlToHtml($result['description'], $result['word']);

			array_push($data, $result);
		}
		$dbh = null;
		return $data;
	}
}

?>
