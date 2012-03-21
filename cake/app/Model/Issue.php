<?php

	class Issue extends AppModel {
	
		public $name = 'Issue';
		
		public $useTable = 'issues';
		public $primaryKey = 'issue_id';
		public $displayName = 'issue_id';
		
		public $belongsTo = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'author'
			)
		);
		public $hasMany = array(
			'IssueRevision' => array(
				'className' => 'IssueRevision',
				'foreignKey' => 'issue_id',
				'order' => array('IssueRevision.revised' => 'DESC'),
				'limit' => 1
			),
			'Solution' => array(
				'className' => 'Solution',
				'foreignKey' => 'issue_id'
			)
		);
	
	}

?>