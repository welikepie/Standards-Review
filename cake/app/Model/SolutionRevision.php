<?php

	class SolutionRevision extends AppModel {
	
		public $name = 'SolutionRevision';
		
		public $useTable = 'solution_revisions';
		public $primaryKey = 'revision_id';
		public $displayName = 'title';
		
		public $belongsTo = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'revisionist'
			),
			'Solution' => array(
				'className' => 'Solution',
				'foreignKey' => 'solution_id'
			),
			'State' => array(
				'className' => 'State',
				'foreignKey' => 'state_id'
			)
		);
		public $hasMany = array(
			'IssueReference' => array(
				'className' => 'SolutionReference',
				'foreignKey' => 'revision_id',
				'dependent' => true
			)
		);
	
	}

?>