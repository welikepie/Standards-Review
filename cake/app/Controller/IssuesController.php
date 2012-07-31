<?php

	/* ISSUES
	 * This controller manages the issues discussed in the system.
	 * Retrieving the issues from the database, listing them, adding
	 * new ones and editing existing ones happens here.
	 */

	class IssuesController extends AppController {
	
		const PAGINATION = 1;

		public $uses = array('User', 'Issue', 'IssueRevision', 'Solution', 'State');
		public $components = array('RequestHandler');
		public $helpers = array("Html", "Form");
		
		public function index() {
		
			/* This method is used to provide the initial view at
			 * the latest issues entered into the system. Pagination
			 * is included as well, stacking at 30 results per page.
			 */
			
			// Obtain the current page and the total number of
			// pages (correct current page if out of range).
			$page =
			( isset($_GET['page']) ?
			  ( intval($_GET['page'], 10) >= 1 ?
			    intval($_GET['page'], 10) :
			    1 ) :
			  1 );
			$totalPages = $this->Issue->find('count', array('recursive' => -1));
			$totalPages = ceil($totalPages / self::PAGINATION);
			if ($page > $totalPages) { $page = $totalPages; }
			
			// Get the basic issue details according to
			// pagination setup. Only include the associations
			// needed for basic display.
			$data = $this->Issue->find('all', array(
				'order' => 'Issue.created DESC',
				'contain' => array(
					'Author',
					'IssueRevision' => array(
						'Revisionist',
						'State'
					)
				),
				'limit' => self::PAGINATION,
				'page' => $page
			));
			
			// Process the data for display in page
			// (flatten the tree, parse values and so on)
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
			
			// Following bits are passed to the view:
			// - list of issues to render
			// - paging data - for link generation
			// - whether the request is AJAX (we only want to return actual issues in AJAX request)
			$this->set('issues', $result);
			$this->set('pages', array('current' => $page, 'total' => $totalPages));
			$this->set('is_ajax', $this->RequestHandler->isAjax());
		
		}
		
		public function view($id) {
		
			/* This method is used to obtain the full details of one selected issue,
			 * along with excerpts of the suggested solutions. Two distinct sets of
			 * data are being pulled - the full details of specified issue and the
			 * paginated collection of solutions.
			 */
			
			// Pull the data from the database
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
							'Revisionist',
							'State'
						)
					)
				)
			));
			
			// Process the issue data for correct display
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

			// Process the references.
			// The normal, custom references are put in one array, whereas
			// any special references (demos and so), having the name bracketed
			// by underscores, are put in another array.
			foreach($data['IssueRevision'][0]['IssueReference'] as &$ref) {
				$match = array();
				if (preg_match('/^__(\w+)__$/', $ref['name'], $match)) {
					$issue['references']['special'][$match[1]] = $ref['reference'];
				} else {
					$issue['references']['normal'][$ref['name']] = $ref['reference'];
				}
			} unset($ref); unset($match);

			// Obtain the current page and the total number of
			// pages (correct current page if out of range).
			/*$page =
			( isset($_GET['page']) ?
			  ( intval($_GET['page'], 10) >= 1 ?
			    intval($_GET['page'], 10) :
			    1 ) :
			  1 );
			$totalPages = $this->Solution->find('count', array('conditions' => 'Solution.issue_id = ' . intval($id, 10), 'recursive' => -1));
			$totalPages = ceil($totalPages / self::PAGINATION);
			if ($page > $totalPages) { $page = $totalPages; }*/
			
			// Get the basic solution details as the pagination
			// setup goes. Only the basic details are obtained,
			// the full setup is to be linked to.
			/*$data = $this->Solution->find('all', array(
				'order' => 'Solution.created DESC',
				'contain' => array(
					'Author',
					'SolutionRevision' => array(
						'Revisionist',
						'State'
					)
				),
				'limit' => self::PAGINATION,
				'page' => $page
			));*/
			
			// Process the solutions' data for display
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
			
			// Following bits are passed to the view:
			// - full description of requested issue (only if non-AJAX)
			// - solutions paged data
			// - paging data - for link generation
			// - whether the request is AJAX
			$this->set('issue', $issue);
			$this->set('solutions', $solutions);
		
		}
		
		public function add() {
		
			if (!$this->Session->check('loggedIn')) { $this->redirect($this->referer()); }
		
			if ($this->request->is('post')) {
			
				$saved = $this->Issue->saveAssociated(
					$this->request->data,
					array(
						'atomic' => true,
						'deep' => true,
						'validate' => 'only'
					)
				);
				if ($saved) { $this->redirect("/issues"); }
				else { die(); }
			
			} else {
			
				$this->set('states', $this->State->find('list', array(
					'fields' => array('State.state_id', 'State.name'),
					'recursive' => -1
				)));
			
			}
		
		}
	
	}

?>