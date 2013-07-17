<?php
App::uses('AnswersAppController', 'Answers.Controller');

class _AnswersController extends AnswersAppController {
    
/**
 * Name
 * 
 * @var string
 */
	public $name = 'Answers';
    
/**
 * Uses
 * 
 * @var string
 */
	public $uses = array('Answers.Answer', 'Answers.AnswerAnswer', 'Contact.Contact', 'Answers.AnswerSubmission');
	
/**
 * Allowed Methods from the Classes used to filter the class action methods
 * 
 * @var string
 */
	public $allowedMethods = array('add', 'edit');
	
	
	/**
	 * index view of all forms created
	 */
	 
	public function index() {
		$this->Answer->recursive = 0;
		$this->set('forms', $this->paginate());
	} 
	
	
	/**
	 * Add Function
	 */
	
	public function add() {
			
		if(!empty($this->request->data) && $this->request->isPost()) {
			if($this->Answer->save($this->request->data)) {
				$this->Session->setFlash('Form Saved');
				$this->redirect($this->referer());
			}else {
				throw new MethodNotAllowedException('Error');
			}
		}
		$this->set('models', $this->_getModels());
		$urls = array(
			'referrer' => 'Previous Page',
			'form' => 'A new Empty Form',
			'url' => 'Another url',
		);
		$this->set('urls', $urls);
		$this->set('title_for_layout', 'Form Buildrr - add a new form');
		$this->layout = 'formbuilder';
			
		
	}
	
	public function view($id) {
		if($id) {
			$form = $this->Answer->find('first', array(
				'conditions' => array('id' => $id),
			));
		}else {
					throw new NotFoundException('Form not Found');
				}
		if($form['Answer']['success_url'] == 'referrer') {
			$form['Answer']['success_url'] = $this->referer();
		}
			
		$this->set('form', $form);
		$this->set('submit', $this->_checkSubmissions($form));
		
	}
	
	public function edit($id) {
		
		$this->view = 'add';
		
		if(!empty($this->request->data)) {
			if($this->Answer->save($this->request->data)) {
				$this->Session->setFlash('Form Saved');
				$this->redirect($this->referer());
			}else {
				throw new MethodNotAllowedException('Error');
			}
		}
		
		if($id) {
			$form = $this->Answer->find('first', array(
				'conditions' => array('id' => $id),
			));
		}else {
			throw new NotFoundException('Form not Found');
		}
		
		$this->request->data = $form;
		$this->set('models', $this->_getModels());
		$urls = array(
			'referrer' => 'Previous Page',
			'form' => 'A new Empty Form',
			'url' => 'Another url',
		);
		$this->set('urls', $urls);
		$this->set('form', $this->request->data);
		$this->set('title_for_layout', 'Form Buildrr - edit ' . $form['Answer']['title']);
		$this->layout = 'formbuilder';
		
	}
	
	public function formProcess () {
		if(empty($this->request->data)) {
			throw new MethodNotAllowedException('No data');
		}
		
		// Grab the needed variables for the form and unset them
		$id = $this->request->data['Answer']['id'];
		unset($this->request->data['Answer']['id']);
		$message = !empty($this->request->data['Answer']['message']) ? $this->request->data['Answer']['message'] : 'The form has been submitted';
		unset($this->request->data['Answer']['message']);
		$redirect = $this->request->data['Answer']['redirect'];
		unset($this->request->data['Answer']['redirect']);
		$answers = array();
		foreach($this->request->data['Answer'] as $key => $value) {
			$answers[] = array(
				'answer_id' => $id,
				'form_input_name' => $key,
				'value' => $value,
			);
		}
		
		App::uses('Sanitize', 'Utility');
		$answers = Sanitize::clean($answers, array('encode' => false));
		try {
			if($this->AnswerAnswer->saveMany($answers)) {
				//$this->_submission($id);
				$this->Session->setFlash($message);
				$this->Answer->process($id, $answers, $redirect);
			}
		}catch(Exception $e) {	
			debug($e->getMessage());
		}
		switch ($redirect) {
				case 'form':
					$this->redirect($this->referer());
					break;
				default:
					$this->redirect($redirect);
					break;
		}
		
	}
	
	/**
	 * Function to load all models with behavior attached to them
	 * 
	 * @todo Need to come up with a way to register and cache those models.
	 * Right now the model only returns the AnswerAnswer Model to save
	 */
	
	private function _getModels() {
		$models = array(
			'Answer' => 'Save to Database',
		);
		//Comented out not ready yet
		// foreach(CakePlugin::loaded() as $model) {
			// $models[$model] = $model;
		// }
		return $models;
	}
	
	/**
	 * Function to get the actions of the controller selected
	 * 
	 */
	
	
	public function getActions() {
		$this->layout = null;
		$plugin = $this->request->data['plugin'];
		$actions = array('add' => 'Add to Database');
		
		//Commented out because not used yet
		// if(!empty($plugin)) {
			// $actions = array();
			// $controller = $plugin . 'Controller';
			// App::uses($controller, $plugin.'.Controller');
			// $controller = new $controller;
			// foreach (get_class_methods($controller) as $key => $val) { 
                // /* Get a reflection object for the class method */ 
                // $reflect = new ReflectionMethod($controller, $val); 
// 				
                // /* For private, use isPrivate().  For protected, use isProtected() */  
                // if($reflect->isPublic() && in_array($val, $this->allowedMethods))  { 
                   // $actions[] = $val;  
                // } 
            // } 
// 			
		// }
		$this->set('actions', $actions);
	}
	
	public function getColumns() {
		$this->layout = null;
		$plugin = $this->request->data['plugin'];
		if(!empty($plugin)) {
			$actions = array();
			$model = Inflector::singularize($plugin);
			App::uses($model, $plugin.'.Model');
			$model = new $model;
			$props = array();
			foreach($model->schema() as $k => $p) {
				$props[$k] = $k;
			};
		}
		$this->set('properties', $props);
	}
	
	/**
	 * submission function - Create a sumbission if exists, else creates one
	 * Increments the Submission Count
	 * 
	 * @param $answerID -  The Answer(Form) ID
	 * @return the count
	 */
	
	private function _submission ($answerId) {
		//get the user id
		$uid = $this->Session->read('Auth.User.id');
		$conditions = array('answer_id' => $answerId, 'creator_id' => $uid);
		if($this->AnswerSubmission->hasAny($conditions)) {
			$answerSubmission = $this->AnswerSubmission->find('first', array($conditions));
		}else {
			$answerSubmission = array('AnswerSubmission' => array(
				'answer_id' => $answerId,
				'count' => 0,
				'from_ip' => $_SERVER['REMOTE_ADDR'],
			));
		}
		//increment count
		$answerSubmission['AnswerSubmission']['count'] = $answerSubmission['AnswerSubmission']['count'] + 1;
		$this->AnswerSubmission->save($answerSubmission);
		
		return $answerSubmission['AnswerSubmission']['count'];
	}
	
	private function _submissionCount ($answerId) {
		//get the user id
		$uid = $this->Session->read('Auth.User.id');
		$conditions = array('answer_id' => $answerId, 'creator_id' => $uid);
		$count = 0;
		if($this->AnswerSubmission->hasAny($conditions)) {
			$count = $this->AnswerSubmission->field('count', array($conditions));
		}
		
		return $count;
	}

/**
 * Used to display a form using requestAction in the default layout.
 *
 * @param {id}			The form id to call.
 * @return {string} 	Rendered From View
 */
	public function display($id) {
		$this->Answer->id = $id;
		if (!$this->Answer->exists()) {
			throw new NotFoundException(__('Invalid form.'));
		}
		$form = $this->Answer->find('first', array('conditions' => array('id' => $id)));
		$this->layout = null;
		$this->set('form', $form);
		$this->set('submit', $this->_checkSubmissions($form));
		$html = $this->render('view');
	}

	private function _checkSubmissions ($answer) {
		$submit = true;
		//If user is guest skip this step
		if($this->Session->read('Auth.User.user_role_id') != __SYSTEM_GUESTS_USER_ROLE_ID) {
			//Check the user submissions 
			$count = $this->_submissionCount($form['Answer']['id']);
			$message = '';
			if($count != 0) {
				if($count == 1) {
					$message = __('You have already submitted this form');
				}else {
					$message = __('You have already submitted this form ' . $count . ' times');
					}
			}
			
			$allowedCount = $form['Answer']['allowed_user_submissions'];
			if($allowedCount != 0 || !empty($allowedCount)) {
				if($count >= $allowedCount) {
					$message = __('You have submitted the form the max amount of times');
					$submit = false;
				}
			}
			
			if(!empty($message)) {
				$this->Session->setFlash($message, 'default', array(), 'formmessage');
			}
		}
		
		return $submit;
	}
    

}

if (!isset($refuseInit)) {
	class AnswersController extends _AnswersController {}
}