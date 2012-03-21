<?php

	class IssueRevision extends AppModel {
	
		public $name = 'IssueRevision';
		
		public $useTable = 'issue_revisions';
		public $primaryKey = 'revision_id';
		public $displayName = 'title';
		
		public $belongsTo = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'revisionist'
			),
			'Issue' => array(
				'className' => 'Issue',
				'foreignKey' => 'issue_id'
			),
			'State' => array(
				'className' => 'State',
				'foreignKey' => 'state_id'
			)
		);
		public $hasMany = array(
			'IssueReference' => array(
				'className' => 'IssueReference',
				'foreignKey' => 'revision_id',
				'dependent' => true
			)
		);
	
	}

?>