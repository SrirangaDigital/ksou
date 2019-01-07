<?php

class search extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->field();
	}

	public function field() {
		
		$data = $this->model->getGetData();
		$data['aliasWord'] = $this->model->removeDiacrtics($data['word']);
		$words = [];
		unset($data['url']);

		// Check if any data is posted.
		if($data) {

			$data = $this->model->preProcessPOST($data);

			// Exact match
			$query = $this->model->formExactMatchQuery($data, METADATA_TABLE);
			$result['exactMatch'] = $this->model->executeQuery($query['query'], $query['words'], $data['word']);

			$words = array_merge($words, $result['exactMatch']['words']);
			unset($result['exactMatch']['words']);
			
			// Patial match
			$query = $this->model->formPartialMatchQuery($data, METADATA_TABLE);
			$result['partialMatch'] = $this->model->executeQuery($query['query'], $query['words'], $data['word']);

			$words = array_merge($words, $result['partialMatch']['words']);
			unset($result['partialMatch']['words']);

			// Prepare a string containing all exact match words containing the search word
			$data['word'] = $this->model->retainRequiredWords($words, $data['word']);

			// Description match
			$query = $this->model->formDescriptionMatchQuery($data, METADATA_TABLE, 'ORDER BY word');
			$result['descriptionMatch'] = $this->model->executeQuery($query['query'], $query['words'], $data['word']);

			unset($result['descriptionMatch']['words']);

			$result['word'] = $data['word'];
			($result) ? $this->view('search/result', $result) : $this->view('error/noResults', 'search/index/');
		}
		else {

			$this->view('error/index');
		}
	}
}

?>
