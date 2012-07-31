<?php

	echo $this->Form->create();
	
	echo $this->Form->hidden('Solution.author', array('value' => $this->Session->read('loggedIn.id')));
	echo $this->Form->hidden('Solution.issue_id', array('value' => $issue_id));
	echo $this->Form->hidden('SolutionRevision.0.revisionist', array('value' => $this->Session->read('loggedIn.id')));
	
	echo $this->Form->input('SolutionRevision.0.title');
	echo $this->Form->input('SolutionRevision.0.source');
	echo $this->Form->input('SolutionRevision.0.description');
	
	echo $this->Form->select('SolutionRevision.0.state', $states);
	
	echo $this->Form->hidden('SolutionRevision.0.SolutionReference.0.name', array('value' => '__demo__'));
	echo $this->Form->input('SolutionRevision.0.SolutionReference.0.reference', array('label' => 'Demo'));
	
	echo $this->Form->input('SolutionRevision.0.SolutionReference.1.name', array('label' => 'Reference #1', 'div' => false));
	echo $this->Form->input('SolutionRevision.0.SolutionReference.1.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->input('SolutionRevision.0.SolutionReference.2.name', array('label' => 'Reference #2', 'div' => false));
	echo $this->Form->input('SolutionRevision.0.SolutionReference.2.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->input('SolutionRevision.0.SolutionReference.3.name', array('label' => 'Reference #3', 'div' => false));
	echo $this->Form->input('SolutionRevision.0.SolutionReference.3.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->input('SolutionRevision.0.SolutionReference.4.name', array('label' => 'Reference #4', 'div' => false));
	echo $this->Form->input('SolutionRevision.0.SolutionReference.4.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->input('SolutionRevision.0.SolutionReference.5.name', array('label' => 'Reference #5', 'div' => false));
	echo $this->Form->input('SolutionRevision.0.SolutionReference.5.reference', array('label' => false, 'div' => false));
	
	echo $this->Form->end('Add New Solution');

?>