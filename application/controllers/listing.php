<?php

class listing extends Controller {

	public function __construct() {

		parent::__construct();
	}

	public function index() {

		$this->alphabet();
	}

	public function alphabet($letter = DEFAULT_LETTER) {

		$word = $this->model->listWordsOfAlphabet($letter);
		//~ var_dump($word);
		($word) ? $this->view('listing/alphabet', $word) : $this->view('error/index');
	}
}

?>
