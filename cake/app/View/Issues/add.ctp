<?php

	echo $this->Form->create();
	
	echo $this->Form->hidden('Issue.author', array('value' => $this->Session->read('loggedIn.id')));
	echo $this->Form->hidden('IssueRevision.0.revisionist', array('value' => $this->Session->read('loggedIn.id')));
	
	echo $this->Form->input('IssueRevision.0.title');
	echo $this->Form->input('IssueRevision.0.source');
	echo $this->Form->input('IssueRevision.0.description');
	
	echo $this->Form->select('IssueRevision.0.state', $states);
	
	echo $this->Form->hidden('IssueRevision.0.IssueReference.0.name', array('value' => '__demo__'));
	echo $this->Form->input('IssueRevision.0.IssueReference.0.reference', array('label' => 'Demo'));
	
	echo $this->Form->input('IssueRevision.0.IssueReference.1.name', array('label' => 'Reference #1', 'div' => false));
	echo $this->Form->input('IssueRevision.0.IssueReference.1.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->input('IssueRevision.0.IssueReference.2.name', array('label' => 'Reference #2', 'div' => false));
	echo $this->Form->input('IssueRevision.0.IssueReference.2.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->input('IssueRevision.0.IssueReference.3.name', array('label' => 'Reference #3', 'div' => false));
	echo $this->Form->input('IssueRevision.0.IssueReference.3.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->input('IssueRevision.0.IssueReference.4.name', array('label' => 'Reference #4', 'div' => false));
	echo $this->Form->input('IssueRevision.0.IssueReference.4.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->input('IssueRevision.0.IssueReference.5.name', array('label' => 'Reference #5', 'div' => false));
	echo $this->Form->input('IssueRevision.0.IssueReference.5.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->end('Add New Issue');

?>