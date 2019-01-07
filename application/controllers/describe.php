<?php

class describe extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->word();
	}

	public function word($word = '') {

		$data = $this->model->getWordDetails($word);
		($data) ? $this->view('describe/word', $data) : $this->view('error/index');
	}
}

?>
