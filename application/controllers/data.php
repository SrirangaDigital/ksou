<?php

class data extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->insertDetails();
	}

	public function insertMetaData(){

		$metaData = $this->model->getMetadaData();
		
		$this->model->db->createDB(DB_NAME, DB_SCHEMA);

		$dbh = $this->model->db->connect(DB_NAME);

		$this->model->db->dropTable(METADATA_TABLE, $dbh);

		$this->model->db->createTable(METADATA_TABLE, $dbh, METADATA_TABLE_SCHEMA);

		foreach ($metaData as $row)
		{
			$this->model->db->insertData(METADATA_TABLE, $dbh, $row);
		}
	}
}

?>
