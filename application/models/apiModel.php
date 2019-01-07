<?php

class apiModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function getNeighbours($word) {
	
		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;

		// Prev
		$sth = $dbh->prepare('SELECT word FROM ' . METADATA_TABLE . ' WHERE word < ? ORDER BY word DESC LIMIT 1');
		$sth->execute(array($word));
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		$data['prev'] = $result['word'];

		// Next
		$sth = $dbh->prepare('SELECT word FROM ' . METADATA_TABLE . ' WHERE word > ? ORDER BY word LIMIT 1');
		$sth->execute(array($word));
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		$data['next'] = $result['word'];

		$data['word'] = $word;

		return $data;
	}

	public function getWordOfTheDay($date) {
		
		$fragments = explode('-', $date);
		$year = intval($fragments[0]);
		$month = intval($fragments[1]);
		$day = intval($fragments[2]);

		$numberOfTheDay = (($year) * ($month * $month) * ($day * $day * $day)) % TOTAL_WORDS;
		
		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;

		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE . ' ORDER BY word LIMIT ? ,1');
		$sth->bindParam(1, $numberOfTheDay, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		$result['date'] = date_format(date_create($date), "j F Y");
		$result = $this->reformWordOfTheDayDescription($result);

		return $result;
	}

	public function reformWordOfTheDayDescription($word) {

		$word = $this->extractDetailsFromDescription($word);
		$word['description'] = preg_replace('/\s+/', " ", $word['description']);
		$word['description'] = preg_replace('/.*?<p>(.*?)<\/p>\s*<p>(.*?)<\/p>.*/', "<p>$1</p><p>$2</p>", $word['description']);
		return $word;
	}
}

?>
