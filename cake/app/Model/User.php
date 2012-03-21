<?php

	class User extends AppModel {
	
		public $name = 'User';
		
		public $useTable = 'users';
		public $primaryKey = 'user_id';
		public $displayField = 'name';
		
		public $validate = array(
			'twitter_id' => 'numeric',
		);
		
		public $hasMany = array(
			'Issue' => array(
				'className' => 'Issue',
				'foreignKey' => 'author'
			),
			'IssueRevision' => array(
				'className' => 'IssueRevision',
				'foreignKey' => 'revisionist'
			),
			'Solution' => array(
				'className' => 'Solution',
				'foreignKey' => 'author'
			),
			'SolutionRevision' => array(
				'className' => 'SolutionRevision',
				'foreignKey' => 'revisionist'
			)
		);
	
	}

?>