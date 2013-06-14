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
	public $uses = 'Answers.Answer';
	
	
	public function add() {
			
			if(!empty($this->request->data) && $this->request->isPost()) {
				debug($this->request->data);
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
	
	/*
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