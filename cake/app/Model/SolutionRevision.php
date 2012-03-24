<?php

	class SolutionRevision extends AppModel {
	
		public $name = 'SolutionRevision';
		
		public $useTable = 'solution_revisions';
		public $primaryKey = 'revision_id';
		public $displayName = 'title';
		
		public $belongsTo = array(
			'Revisionist' => array(
				'className' => 'User',
				'foreignKey' => 'revisionist'
			),
			'Solution' => array(
				'className' => 'Solution',
				'foreignKey' => 'solution_id'
			),
			'State' => array(
				'className' => 'State',
				'foreignKey' => 'state'
			)
		);
		public $hasMany = array(
			'SolutionReference' => array(
				'className' => 'SolutionReference',
				'foreignKey' => 'revision_id',
				'order' => array('SolutionReference.name' => 'ASC'),
				'dependent' => true
			)
		);
	
	}

?>