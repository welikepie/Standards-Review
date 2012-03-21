<?php

	class IssueReference extends AppModel {
	
		public $name = 'IssueReference';
		
		public $useTable = 'issue_references';
		public $primaryKey = 'reference_id';
		public $displayName = 'name';
		
		public $belongsTo = array(
			'IssueRevision' => array(
				'className' => 'IssueRevision',
				'foreignKey' => 'revision_id'
			)
		);
	
	}

?>