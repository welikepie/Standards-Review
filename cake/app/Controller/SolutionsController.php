<?php

	class SolutionsController extends AppController {
	
		public $uses = array('Solution', 'SolutionRevision', 'SolutionReference', 'State', 'User');
		public $components = array('RequestHandler');
		public $helpers = array('Html', 'Form');
	
		public function view($issue_id, $solution_id) {
		
			$this->Solution->Behaviors->attach('Containable');
			$data = $this->Solution->find('first', array(
				'conditions' => 'Solution.solution_id = ' . intval($solution_id, 10),
				'contain' => array(
					'Author',
					'SolutionRevision' => array(
						'State',
						'Revisionist',
						'SolutionReference'
					)
				)
			));
			
			// Redirect to the problem if solution is not matched
			if (intval($issue_id, 10) !== intval($data['Solution']['issue_id'], 10)) {
				$this->redirect(array(
					'controller' => 'issues',
					'action' => 'view',
					intval($issue_id, 10)
				));
			}
			
			$solution = array(
				'solution_id' => intval($data['Solution']['solution_id'], 10),
				'created' => $data['Solution']['created'],
				'author' => $data['Author']['name'],
				'revised' => $data['SolutionRevision'][0]['revised'],
				'revisionist' => $data['SolutionRevision'][0]['Revisionist']['name'],
				'title' => $data['SolutionRevision'][0]['title'],
				'state' => array(
					'state_id' => intval($data['SolutionRevision'][0]['State']['state_id'], 10),
					'name' => $data['SolutionRevision'][0]['State']['name']
				),
				'source' => $data['SolutionRevision'][0]['source'],
				'description' => $data['SolutionRevision'][0]['description'],
				'references' => array(
					'special' => array(),
					'normal' => array()
				)
			);
			foreach($data['SolutionRevision'][0]['SolutionReference'] as &$ref) {
				$match = array();
				if (preg_match('/^__(\w+)__$/', $ref['name'], $match)) {
					$solution['references']['special'][$match[1]] = $ref['reference'];
				} else {
					$solution['references']['normal'][$ref['name']] = $ref['reference'];
				}
			} unset($ref); unset($match);
			
			$this->set('issue_id', intval($data['Solution']['issue_id'], 10));
			$this->set('is_ajax', $this->RequestHandler->isAjax());
			$this->set('solution', $solution);
		
		}
	
	}
	
?>