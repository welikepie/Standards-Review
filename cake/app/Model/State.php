<?php

	class State extends AppModel {
	
		public $name = 'State';
		
		public $useTable = 'states';
		public $primaryKey = 'state_id';
		public $displayField = 'name';
	
	}

?>