<?php

class searchModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function formExactMatchQuery($data, $table, $orderBy = '') {

		$sqlStatement = 'SELECT * FROM ' . $table . ' WHERE word = ?  ' . $orderBy;

		$data['query'] = $sqlStatement;
		$data['words'] = array($data['word']);
		return $data;
	}

	public function formPartialMatchQuery($data, $table, $orderBy = '') {

		$word = $data['aliasWord'];
		unset($data['word']);

		$data = $this->regexFilter($data);

		$sqlFilter = (count($data['filter'] > 1)) ? implode(' and ', $data['filter']) : array_values($data['filter']);

		$data['query'] = 'SELECT * FROM ' . $table . ' WHERE (' . $sqlFilter . ') AND (aliasWord != ?)' . $orderBy;

		array_push($data['words'], $word);
		return $data;
	}

	public function formDescriptionMatchQuery($data, $table, $orderBy = '') {

		$word = $data['word'];
		$data['description'] = $word;
		unset($data['word']);
		unset($data['aliasWord']);

		$data = $this->regexFilter($data);
		$sqlFilter = (count($data['filter'] > 1)) ? implode(' and ', $data['filter']) : array_values($data['filter']);

		$sqlStatement = 'SELECT * FROM ' . $table . ' WHERE (' . $sqlFilter . ') AND (word NOT REGEXP ?) ' . $orderBy;

		array_push($data['words'], explode(' ', $word)[0]);
		$data['query'] = $sqlStatement;
		return $data;
	}

	public function executeQuery($query, $parameters, $searchWord)  {

		$dbh = $this->db->connect(DB_NAME);

		$sth = $dbh->prepare($query);
		$sth->execute($parameters);

		$data = [];
		$words = [];
		while($result = $sth->fetch(PDO::FETCH_ASSOC)) {
			
			// Extract head words and alias words along with their corresponding notes
			$result = $this->extractDetailsFromDescription($result);

			// Form proper html from xml elements
			$result['description'] = $this->xmlToHtml($result['description'], $result['word']);
			$result['description'] = $this->extractSnippet($result['description'], $searchWord);

			array_push($words, $result['word']);
			array_push($data, $result);
		}
		$data['words'] = $words;
		$dbh = null;

		return $data;
	}

	public function regexFilter($var) {

		$data['filter'] = array();
		$data['words'] = array();

		if (empty($var)) return $data;

		while (list($key, $val) = each($var)) {

			$filterArr = array();

			$val = html_entity_decode($val, ENT_QUOTES);

			// Only paranthesis and hyphen will be quoted to include them in search
		    $val = preg_replace('/(\(|\)|\-)/', "\\\\$1", $val);
		    $words = preg_split('/ /', $val);
		    $words = array_filter($words, 'strlen');
		    
			$data['words'] = array_merge($data['words'], $words);
		    foreach($words as $word) {
		    	$filterArr[] = $key . ' REGEXP ?';
		    }

		    $filter[$key] = implode(' ' . SEARCH_OPERAND . ' ', $filterArr);

		}

		$data['filter'] = $filter;

		return $data;
	}

	public function retainRequiredWords($words, $searchedWord) {

		$allWords = implode(' ', $words);
		$diacriticWords = explode(' ', $allWords); 
		$noDiacriticWords = explode(' ', $this->removeDiacrtics($allWords)); 
		$res = preg_grep('/.*' . str_replace(' ', '|', $this->removeDiacrtics($searchedWord)) . '.*/i', $noDiacriticWords);

		$data['word'] = [];
		foreach ($res as $key => $value) {
			
			array_push($data['word'], $diacriticWords[$key]);
		}
		
		return (!$res) ? $searchedWord : $searchedWord . '|' . implode('|', array_unique($data['word']));
	}

	public function extractSnippet($text, $searchWord) {

		$snippetExtent = 10;
		$searchWord = preg_replace('/(.*?) .*/', "$1", $searchWord);

		$text = strip_tags($text);
		$text = preg_replace('/\s+/', ' ', $text);

		$words = explode(' ', $text);

		$inKey = preg_grep('/' . $searchWord . '/i', $words);

		if(sizeof($inKey) > 0) {

			$inKey = array_pop(array_keys($inKey));

			$left = $inKey - $snippetExtent;
			$right = $inKey + $snippetExtent;

			if($left < 0) $left = 0;
			if($right >= sizeof($words)) $right = sizeof($words) - 1;

			return implode(' ', array_slice($words, $left, $right - $left + 1));
		}
		else{

			return '';
		}
	}
}

?>
