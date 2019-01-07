<?php

class dataModel extends Model {

	public function __construct() {

		parent::__construct();
	}
	
	public function getWords() {
		
		$fileName = XML_SRC_URL . DB_PREFIX . '.xml';

		if (!(file_exists(PHY_XML_SRC_URL . DB_PREFIX . '.xml'))) {
			return False;
		}
		
		$xml = simplexml_load_file($fileName);

		$words = array();

		foreach ($xml->volume as $volume)
		{
			foreach ($volume->entry as $entry) {
				$data['word'] = (string) $entry->head->word;
				array_push($words, $data);
			}
		}

	}

	public function getMetadaData() {
		
		$fileName = XML_SRC_URL . DB_PREFIX . '.xml';
		echo $fileName . "<br />";

		if (!(file_exists(PHY_XML_SRC_URL . DB_PREFIX . '.xml'))) {
			return False;
		}
		
		$xml = simplexml_load_file($fileName);

		$metaData = array();
		//time being commented following two lines for replacing head words in description
		// $wordList = $this->getWords();
		// array_shift($wordList);

		foreach ($xml->volume as $volume)
		{
			$data['vnum'] = (string) $volume['vnum'];
			foreach ($volume->entry as $entry)
			{
				$data['word'] = (string) $entry->head->word;
				$data['description'] = $entry->saveXML();
				$aliasword = $data['word'];
				$aliasword = $this->removeDiacrtics($aliasword);
				$data['aliasWord'] = $aliasword;
				
				//time being commented following two lines for replacing head words in description
				// $data['description'] = $this->replaceHeadWords($wordList,$data['description']);	
				// $data['description'] = $this->replaceJunk($data['description']);	

				array_push($metaData, $data);
			}
		}
		return $metaData;
	}
	
	public function replaceHeadWords($wordList,$description)
	{

		foreach($wordList as $eachWord)
		{
			$ucfirst = ucfirst($eachWord['word']);
			$lcfirst = lcfirst($eachWord['word']);
			
			if(preg_match('/^[Ā|ā|Ś|ś|Ū|ū|Ṣ|ṣ|Ī|ī|Ṅ|ṅ|Ṛ|ṛ|Ṭ|ṭ|Ṇ|ṇ|Ḍ|ḍ|Ṁ|ṁ|Ñ|ñ|Ḥ|ḥ|Ḷ|ḷ|Ṝ|ṝ]/', $eachWord['word']))
			{
				$description = preg_replace('/' . $eachWord['word'] . '/', '<span class="linkword">' . $eachWord['word'] . '</span>', $description);
			}
			elseif (preg_match('/[Ā|ā|Ś|ś|Ū|ū|Ṣ|ṣ|Ī|ī|Ṅ|ṅ|Ṛ|ṛ|Ṭ|ṭ|Ṇ|ṇ|Ḍ|ḍ|Ṁ|ṁ|Ñ|ñ|Ḥ|ḥ|Ḷ|ḷ|Ṝ|ṝ]$/', $eachWord['word']))
			{
				if(preg_match('/^[a-z]/', $eachWord['word']))
				{
					$description = preg_replace('/' . $ucfirst . '/', '<span class="linkword">' . $ucfirst . '</span>', $description);
				}
				elseif(preg_match('/^[A-Z]/', $eachWord['word']))
				{
					$description = preg_replace('/' . $lcfirst . '/', '<span class="linkword">' . $lcfirst . '</span>', $description);
				}
				$description = preg_replace('/' . $eachWord['word'] . '/', '<span class="linkword">' . $eachWord['word'] . '</span>', $description);
			}
			else
			{
				if(preg_match('/^[a-z]/', $eachWord['word']))
				{
					$description = preg_replace('/\b' . $ucfirst . '\b/', '<span class="linkword">' . $ucfirst . '</span>', $description);
				}
				elseif(preg_match('/^[A-Z]/', $eachWord['word']))
				{
					$description = preg_replace('/\b' . $lcfirst . '\b/', '<span class="linkword">' . $lcfirst . '</span>', $description);
				}
				$description = preg_replace('/\b' . $eachWord['word'] . '\b/', '<span class="linkword">' . $eachWord['word'] . '</span>', $description);
			}
			$description = preg_replace('/<word><span class="linkword">(.*)<\/span><\/word>/', '<word>\1</word>', $description);
			$description = preg_replace('/<word>(.*)<span class="linkword">(.*)<\/span>(.*)<\/word>/', '<word>\1\2\3</word>', $description);
			$description = preg_replace('/<note>(.*)<span class="linkword">(.*)<\/span>(.*)<\/note>/', '<note>\1\2\3</note>', $description);
		}

		return $description;
	}

	public function replaceJunk($description)
	{
		//~ $data['description'] = preg_replace('/<span class="linkword">([a-zA-Z|À-ž|Ḁ-ẕ]+)<\/span>([a-zA-Z|À-ž|Ḁ-ẕ]+)/', '\1\2', $data['description']);
		$description = preg_replace('/"<span class="linkword">(.*?)<\/span>/', '"\1', $description);
		$description = preg_replace('/<span class="linkword">(.*?)<\/span>">/', '\1">', $description);
		$description = preg_replace('/<span class="linkword">([a-zA-Z|À-ž|Ḁ-ẕ]+)<span class="linkword">([a-zA-Z|À-ž|Ḁ-ẕ]+)<\/span><\/span>/', '<span class="linkword">\1\2</span>', $description);
		$description = preg_replace('/-<span class="linkword">([a-zA-Z|À-ž|Ḁ-ẕ]+)<\/span>/', '-\1', $description);
		$description = preg_replace('/<span class="linkword">([a-zA-Z|À-ž|Ḁ-ẕ]+)<\/span>-/', '\1-', $description);
		$description = preg_replace('/<\/span><span class="linkword">/', '', $description);
		$description = preg_replace('/([a-zA-Z]+)<span class="linkword">([a-zA-Z|À-ž|Ḁ-ẕ]+)<\/span>/', '\1\2', $description);
		$description = preg_replace('/<span class="linkword">([a-zA-Z|À-ž|Ḁ-ẕ]+)<\/span>([a-zA-Z]+)/', '\1\2', $description);

		return $description;			
	}
}

?>
