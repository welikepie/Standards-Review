<?php

	class IssuesController extends AppController {

		public $uses = array('User', 'Issue', 'IssueRevision');
		public $helpers = array("Html", "Form");
		
		public function index() {
		
			/* This method is used to obtain N latest issues in a simple
			 * description format, to be displayed several per page.
			 */
			
			// Obtain issues from the database
			$this->Issue->Behaviors->attach('Containable');
			$data = $this->Issue->find('all', array(
				'order' => 'Issue.created DESC',
				'contain' => array(
					'Author',
					'IssueRevision' => array(
						'Revisionist',
						'State'
					)
				)
			));
			
			// Process the data for display
			$result = array();
			foreach ($data as &$issue) {
			
				$result[] = array(
					'issue_id' => intval($issue['Issue']['issue_id'], 10),
					'created' => $issue['Issue']['created'],
					'author' => $issue['Author']['name'],
					'revised' => $issue['IssueRevision'][0]['revised'],
					'revisionist' => $issue['IssueRevision'][0]['Revisionist']['name'],
					'title' => $issue['IssueRevision'][0]['title'],
					'state' => array(
						'state_id' => intval($issue['IssueRevision'][0]['State']['state_id'], 10),
						'name' => $issue['IssueRevision'][0]['State']['name']
					),
					'description' => (
						strlen($issue['IssueRevision'][0]['description']) > 100 ?
						substr($issue['IssueRevision'][0]['description'], 0, 99) . '…' :
						$issue['IssueRevision'][0]['description']
					)
				);
			
			} unset($issue);
			
			// Set the data to be used in the response
			if (isset($this->params['requested'])) {
				return array('issues' => $result);
			} else {
				$this->set('issues', $result);
			}
		
		}
		
		public function view($id) {
		
			/* This method is used to obtain the full details of
			 * one selected issue, along with excerpts of the suggested solutions.
			 */
			
			// Obtain specified issue from the database
			$this->Issue->Behaviors->attach('Containable');
			$data = $this->Issue->find('first', array(
				'conditions' => 'Issue.issue_id = ' . intval($id, 10),
				'contain' => array(
					'Author',
					'IssueRevision' => array(
						'State',
						'Revisionist',
						'IssueReference'
					),
					'Solution' => array(
						'Author',
						'SolutionRevision' => array(
							'State',
							'Revisionist'
						)
					)
				)
			));
			
			// Process the issue data for display
			$issue = array(
				'issue_id' => intval($data['Issue']['issue_id'], 10),
				'created' => $data['Issue']['created'],
				'author' => $data['Author']['name'],
				'revised' => $data['IssueRevision'][0]['revised'],
				'revisionist' => $data['IssueRevision'][0]['Revisionist']['name'],
				'title' => $data['IssueRevision'][0]['title'],
				'state' => array(
					'state_id' => intval($data['IssueRevision'][0]['State']['state_id'], 10),
					'name' => $data['IssueRevision'][0]['State']['name']
				),
				'source' => $data['IssueRevision'][0]['source'],
				'description' => $data['IssueRevision'][0]['description'],
				'references' => array(
					'special' => array(),
					'normal' => array()
				)
			);
			foreach($data['IssueRevision'][0]['IssueReference'] as &$ref) {
				$match = array();
				if (preg_match('/^__(\w+)__$/', $ref['name'], $match)) {
					$issue['references']['special'][$match[1]] = $ref['reference'];
				} else {
					$issue['references']['normal'][$ref['name']] = $ref['reference'];
				}
			} unset($ref); unset($match);
			
			// Process the solutions' bits
			$solutions = array();
			foreach($data['Solution'] as &$sol) {
				$solutions[] = array(
					'solution_id' => intval($sol['solution_id'], 10),
					'created' => $sol['created'],
					'author' => $sol['Author']['name'],
					'revised' => $sol['SolutionRevision'][0]['revised'],
					'revisionist' => $sol['SolutionRevision'][0]['Revisionist']['name'],
					'upvotes' => intval($sol['upvotes'], 10),
					'title' => $sol['SolutionRevision'][0]['title'],
					'state' => array(
						'state_id' => intval($sol['SolutionRevision'][0]['State']['state_id'], 10),
						'name' => $sol['SolutionRevision'][0]['State']['name']
					),
					'description' => (
						strlen($sol['SolutionRevision'][0]['description']) > 100 ?
						substr($sol['SolutionRevision'][0]['description'], 0, 99) . '…' :
						$sol['SolutionRevision'][0]['description']
					)
				);
			}
			
			/*if (isset($this->params['requested'])) {
				return array(
					'issue' => $issue,
					'solutions' => $solutions
				);
			} else {*/
				$this->set('issue', $issue);
				$this->set('solutions', $solutions);
			/*}*/
		
		}
	
	}

?>