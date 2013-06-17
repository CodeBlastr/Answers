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
	public $uses = array('Answers.Answer', 'Answers.AnswerAnswer');
	
	
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
		if($form['success_url'] == 'referrer') {
			$form['success_url'] = $this->referer();
		}
		$this->set('form', $form);
		
	}
	
	public function edit($id) {
		
		if(!empty($this->request->data) && $this->request->isPost()) {
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
		$this->set('form', $this->request->data);
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
		
		try {
			if($this->AnswerAnswer->saveMany($answers)) {
				$this->Answer->process($id, $answers, $redirect);
				$this->Session->setFlash($message);
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
			'Answers.Answer' => 'Save to Database',
		);
		//debug(CakePlugin::loaded());
		//break;
		return $models;
	}
	
	public function getActions() {
		$this->layout = null;
		// $model = $this->request->data['model'];
		// if(!empty($model)) {
			// $model = explode('.', $model);
			// if (count($model) > 1) {
				// $model = $model[1];
			// }else {
				// $model = $model[0];
			// }
		// }else {
			// $this->response->statusCode(403);
		// }
		// $plugin = ZuhaInflector::pluginize($model);
		// $controller = Inflector::pluralize($model);
		$actions = array('add' => 'Add to Database');
		$this->set('actions', $actions);
	}
    

}

if (!isset($refuseInit)) {
	class AnswersController extends _AnswersController {}
}