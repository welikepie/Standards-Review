<?php

	class IssueRevision extends AppModel {
	
		public $name = 'IssueRevision';
		
		public $useTable = 'issue_revisions';
		public $primaryKey = 'revision_id';
		public $displayName = 'title';
		
		public $belongsTo = array(
			'Revisionist' => array(
				'className' => 'User',
				'foreignKey' => 'revisionist'
			),
			'Issue' => array(
				'className' => 'Issue',
				'foreignKey' => 'issue_id'
			),
			'State' => array(
				'className' => 'State',
				'foreignKey' => 'state'
			)
		);
		public $hasMany = array(
			'IssueReference' => array(
				'className' => 'IssueReference',
				'foreignKey' => 'revision_id',
				'order' => array('IssueReference.name' => 'ASC'),
				'dependent' => true
			)
		);
	
	}

?>