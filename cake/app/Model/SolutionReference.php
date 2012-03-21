<?php

	class SolutionReference extends AppModel {
	
		public $name = 'SolutionReference';
		
		public $useTable = 'solution_references';
		public $primaryKey = 'reference_id';
		public $displayName = 'name';
		
		public $belongsTo = array(
			'SolutionRevision' => array(
				'className' => 'SolutionRevision',
				'foreignKey' => 'revision_id'
			)
		);
	
	}

?>