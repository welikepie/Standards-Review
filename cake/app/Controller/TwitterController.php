<?php

App::import('Vendor', 'tmhOAuth/tmhOAuth');
App::import('Vendor', 'tmhOAuth/tmhUtilities');

class TwitterController extends AppController {

	public $uses = array('User');
	public $components = array('RequestHandler', 'Session');
	
	public $autoRender = false;
	
	protected function get_twitter_creds($oauth) {
	

		// Obtain Twitter ID and screen name
		$code = $oauth->request("GET", $oauth->url('1/account/verify_credentials'));
		$resp = json_decode($oauth->response["response"]);
		
		// Try to find the user with matching Twitter ID
		$user = $this->User->find('first', array(
			'conditions' => array('User.twitter_id' => $resp->id),
			'recursive' => -1
		));
		
		// If the user is already in the database, put his details in the session
		if ($user) {
			
			$this->Session->write('loggedIn', array('id' => intval($user['User']['user_id'], 10), 'name' => $user['User']['name']));
		
		// If the user has not been included yet, add him to the database
		} else {
		
			$this->User->save(array('twitter_id' => $resp->id, 'name' => $resp->screen_name));
			$this->Session->write('loggedIn', array('id' => intval($this->User->id, 10), 'name' => $resp->screen_name));
		
		}

	}
	
	public function entry() {
	
		/* CALLBACK FROM TWITTER */
		
		if ($this->Session->check("loggedIn")) {
		
			// If data already in the session, return
			$this->redirect($this->referer());
		
		} else {
		
			$oauth = new tmhOAuth(array(
				'consumer_key'    => 'Tv2qLpjwWCUxa9bR90Q',
				'consumer_secret' => 'nOK2zL2veQkFNFQjFWkRLGFlx1FCT5rDHHes4B1g'
			));
			
			// Access token available, but bo login yet
			if ($this->Session->check('Twitter.access_token')) {
			
				$oauth->config['user_token']  = $this->Session->read('Twitter.access_token.oauth_token');
				$oauth->config['user_secret'] = $this->Session->read('Twitter.access_token.oauth_token_secret');
				
				$this->get_twitter_creds($oauth);
				
				$this->redirect($this->referer());
			
			// Twitter callback
			} elseif (array_key_exists('oauth_verifier', $this->request->query)) {
			
				$oauth->config['user_token']  = $this->Session->read('Twitter.oauth.oauth_token');
				$oauth->config['user_secret'] = $this->Session->read('Twitter.oauth.oauth_token_secret');
				$code = $oauth->request('POST', $oauth->url('oauth/access_token', ''), array( 'oauth_verifier' => $this->request->query['oauth_verifier'] ));
				
				$this->Session->write('Twitter.access_token', $oauth->extract_params($oauth->response['response']));
				
				$oauth->config['user_token']  = $this->Session->read('Twitter.access_token.oauth_token');
				$oauth->config['user_secret'] = $this->Session->read('Twitter.access_token.oauth_token_secret');
				
				$this->get_twitter_creds($oauth);
				
				$this->redirect($this->Session->check("Twitter.referer") ? $this->Session->read("Twitter.referer") : "/", null, false);
				$this->Session->delete("Twitter.referer");
			
			// Initial login attempt
			} else {
			
				$this->Session->write('Twitter.referer', $this->referer());
				
				$code = $oauth->request('POST', $oauth->url('oauth/request_token', ''), array(
					'oauth_callback' => tmhUtilities::php_self(),
					'x_auth_access_type' => 'read'
				));
				$this->Session->write("Twitter.oauth", $oauth->extract_params($oauth->response['response']));
				$url = $oauth->url('oauth/authorize', '') . "?oauth_token=" . $this->Session->read('Twitter.oauth.oauth_token');
				
				$this->redirect($url);
			
			}
		
		}
	
	}

}

?>