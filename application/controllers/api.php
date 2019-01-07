<?php

class api extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->word();
	}

	public function getNeighbours($word = '') {

		$data = $this->model->getNeighbours($word);

		echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	public function getWordOfTheDay() {

		date_default_timezone_set('Asia/Kolkata');

		$data = $this->model->getWordOfTheDay(date("Y-n-j"));
		echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}

?>
