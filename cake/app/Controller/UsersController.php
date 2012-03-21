<?php

	class UsersController extends AppController {
	
		public $uses = array('User', 'Issue', 'IssueRevision');
		public $helpers = array("Html", "Form");
		
		public function index() {
		
			$this->set('users', $this->Issue->find('all'));
			$this->set('debug', print_r($this->User->_schema, true));
		
		}
	
	}

?>