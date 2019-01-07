<?php

class listingModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function listWordsOfAlphabet($letter = DEFAULT_LETTER){

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;

		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE . ' WHERE word LIKE :letter ORDER BY word');
		$sth->execute(array("letter"=>$letter.'%'));

		$data = array();

		while($result = $sth->fetch(PDO::FETCH_OBJ)) {

			array_push($data, $result);
		}
		$dbh = null;
		return $data;
	}
}
