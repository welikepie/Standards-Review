<?php

	class Solution extends AppModel {
	
		public $name = 'Solution';
		
		public $useTable = 'solutions';
		public $primaryKey = 'solution_id';
		public $displayName = 'solution_id';
		
		public $belongsTo = array(
			'Author' => array(
				'className' => 'User',
				'foreignKey' => 'author'
			),
			'Issue' => array(
				'className' => 'Issue',
				'foreignKey' => 'issue_id'
			)
		);
		public $hasMany = array(
			'SolutionRevision' => array(
				'className' => 'SolutionRevision',
				'foreignKey' => 'solution_id',
				'order' => array('SolutionRevision.revised' => 'DESC'),
				'limit' => 1
			)
		);
	
	}

?>