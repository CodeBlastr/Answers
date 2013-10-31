<?php
App::uses('AnswersAppController', 'Answers.Controller');
class AppAnswersController extends AnswersAppController {

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
	public $uses = array(
		'Answers.Answer',
		'Answers.AnswerAnswer',
		'Contact.Contact',
		'Answers.AnswerSubmission'
	);

/**
 * Allowed Methods from the Classes used to filter the class action methods
 *
 * @var string
 */
	public $allowedMethods = array(
		'add',
		'edit'
	);

/**
 * index view of all forms created
 */
	public function index() {
		$this->Answer->recursive = 0;
		$this->set('forms', $this->paginate());
	}

/**
 * Add method
 *
 * @throws MethodNotAllowedException
 */
	public function add($foreignModel = null, $foreignKey = null) {
		if (!empty($this->request->data) && $this->request->isPost()) {
			$this->request->data['Answer']['plugin'] = ZuhaInflector::pluginize($this->request->data['Answer']['model']);
			//For right now I have the actions hard coded
			if ($this->request->data['Answer']['model'] == 'AnswerAnswer') {
				$this->request->data['Answer']['action'] = 'saveAll';
			} else {
				$this->request->data['Answer']['action'] = 'save';
			}
			if (empty($this->request->data['Answer']['allowed_user_submissions'])) {
				$this->request->data['Answer']['allowed_user_submissions'] = 0;
			}
			// saving!
			if ($this->Answer->save($this->request->data)) {
				$this->Session->setFlash('Form Saved');
				$this->redirect($this->referer());
			} else {
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

/**
 * View method
 *
 * @param uuid
 * @param uuid
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 */
	public function view($id, $editId = null) {
		if ($id) {
			$form = $this->Answer->find('first', array('conditions' => array('id' => $id), ));
		} else {
			throw new NotFoundException('Form not Found');
		}
		if ($form['Answer']['success_url'] == 'referrer') {
			$form['Answer']['success_url'] = $this->referer();
		}
		if (!empty($editId)) {
			$model = $form['Answer']['model'];
			$plugin = $form['Answer']['plugin'];
			$this->loadModel($plugin . $model);
			if (!$this->$model->exists($editId)) {
				throw new MethodNotAllowedException('Record does not exist');
			}
		}
		$this->set('form', $form);
		$this->set('submitButtonText', !empty($form['Answer']['submit_button_text']) ? $form['Answer']['submit_button_text'] : 'Submit');
		$this->set('submit', $this->_checkSubmissions($form));
	}

/**
 * Edit method
 *
 * @param uuid
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function edit($id) {
		$this->view = 'add';
		if (!empty($this->request->data)) {
			//needed to do this so if we pasted content into the html field it wouldn't
			// delete it
			if (empty($this->request->data['Answer']['content']) || strlen($this->request->data['Answer']['content']) < 30) {
				unset($this->request->data['Answer']['content']);
			}
			if ($this->Answer->save($this->request->data)) {
				$this->Session->setFlash('Form Saved');
				$this->redirect($this->referer());
			} else {
				throw new MethodNotAllowedException('Error');
			}
		}
		if ($id) {
			$form = $this->Answer->find('first', array('conditions' => array('id' => $id), ));
		} else {
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

/**
 * Delete method
 *
 * @throws Exception
 */
	public function delete($id = null) {
		if (empty($id)) {
			throw new Exception("Error Processing Request, No ID", 1);
		}
		$this->Answer->delete($id);
		$this->Session->setFlash('From Deleted');
		$this->redirect($this->referer());
	}

/**
 * formProcess method
 *
 * @throws MethodNotAllowedException
 */
	public function formProcess() {
		if (empty($this->request->data)) {
			throw new MethodNotAllowedException('No data');
		}
		// Grab the needed variables for the form and unset them
		$id = $this->request->data['Answer']['id'];
		$answer = $this->Answer->findById($id);
		$model = $answer['Answer']['model'];
		$plugin = $answer['Answer']['plugin'];
		$action = $answer['Answer']['action'];
		unset($this->request->data['Answer']['id']);
		$message = !empty($this->request->data['Answer']['message']) ? $this->request->data['Answer']['message'] : 'The form has been submitted';
		unset($this->request->data['Answer']['message']);
		$redirect = $this->request->data['Answer']['redirect'];
		unset($this->request->data['Answer']['redirect']);
		$answers = array();
		$answerdata = json_decode($answer['Answer']['content_json']);
		foreach ($this->request->data['Answer'] as $key => $value) {
			$answers[] = array(
				'answer_id' => $id,
				'form_input_name' => $key,
				'value' => is_array($value) ? implode(' / ', $value) : $value,
			);
		}
		App::uses('Sanitize', 'Utility');
		$answers = Sanitize::clean($answers, array('encode' => false));
		try {
			if ($model == 'AnswerAnswer') {
				$this->$model->$action($answers);
			} else {
				$this->loadModel($plugin . '.' . $model);
				$data[$model] = $this->request->data['Answer'];
				$this->$model->$action($data);
			}
			//$this->_submission($id);
			$this->Session->setFlash($message);
			$this->Answer->process($answer, $answers);
		} catch(Exception $e) {
			debug($e->getMessage());
		}
		switch ($redirect) {
			case 'form' :
				$this->redirect($this->referer());
				break;
			default :
				$this->redirect($redirect);
				break;
		}
	}

/**
 * Function to load all models with behavior attached to them
 *
 * @codingStandardsIgnoreStart
 * @todo Need to come up with a way to register and cache those models.
 * Right now the model only returns the AnswerAnswer Model to save
 * @codingStandardsIgnoreEnd
 */
	protected function _getModels() {
		$models = array('AnswerAnswer' => 'Save to Database', );
		if (CakePlugin::loaded('Courses')) {
			$models['CourseGradeAnswer'] = 'Save to Grade Table';
		}
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
		$actions = array(
			'save' => 'Add to Database',
			'saveMany' => 'Save all Answers'
		);
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
		if (!empty($plugin)) {
			$actions = array();
			$model = Inflector::singularize($plugin);
			App::uses($model, $plugin . '.Model');
			$model = new $model;
			$props = array();
			foreach ($model->schema() as $k => $p) {
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
	protected function _submission($answerId) {
		return $this->AnswerSubmission->submit($answerId);
	}

	protected function _submissionCount($answerId) {
		//get the user id
		$uid = $this->Session->read('Auth.User.id');
		$conditions = array(
			'answer_id' => $answerId,
			'creator_id' => $uid
		);
		//		$count = 0;
		//		if($this->AnswerSubmission->hasAny($conditions)) {
		//			$count = $this->AnswerSubmission->field('count', array($conditions));
		//		}
		return $this->AnswerSubmission->find('count', $conditions);
	}

/**
 * Used to display a form using requestAction in the default layout.
 *
 * @param {id}			The form id to call.
 * @return {string} 	Rendered From View
 * @throws NotFoundException
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
		$this->set('showtitle', false);
		$html = $this->render('view');
	}

	protected function _checkSubmissions($answer) {
		$submit = true;
		//If user is guest skip this step
		if ($this->Session->read('Auth.User.user_role_id') != __SYSTEM_GUESTS_USER_ROLE_ID) {
			//Check the user submissions
			$count = $this->_submissionCount($form['Answer']['id']);
			$message = '';
			if ($count != 0) {
				if ($count == 1) {
					$message = __('You have already submitted this form');
				} else {
					$message = __('You have already submitted this form ' . $count . ' times');
				}
			}
			$allowedCount = $form['Answer']['allowed_user_submissions'];
			if ($allowedCount != 0 || !empty($allowedCount)) {
				if ($count >= $allowedCount) {
					$message = __('You have submitted the form the max amount of times');
					$submit = false;
				}
			}
			if (!empty($message)) {
				$this->Session->setFlash($message, 'default', array(), 'formmessage');
			}
		}
		return $submit;
	}

/**
 * Quick Function for CSV downloads of all form submissions
 *
 * @codingStandardsIgnoreStart
 * @todo there is a better way to to this, See new code in submissions
 * @codingStandardsIgnoreEnd
 */
	public function answersubmissions($id = false) {
		if ($id) {
			$answers = $this->Answer->AnswerAnswer->find('all', array('conditions' => array('answer_id' => $id)));
			$cleanarray = array();
			if (!empty($answers)) {
				$index = 0;
				$prev = 0;
				$firstfield = $answers[0]['AnswerAnswer']['form_input_name'];
				foreach ($answers as $i => $v) {
					//$next = strtotime($answers[$i]['AnswerAnswer']['created']);00NA0000001uEF4
					if ($answers[$i]['AnswerAnswer']['form_input_name'] == $firstfield) {
						$index++;
					}
					//$prev = $next;
					if (!key_exists($index, $cleanarray)) {
						$cleanarray[$index] = array('date_created' => date('Y-M-d H:i:s', strtotime($answers[$i]['AnswerAnswer']['created'])));
					}
					if (!key_exists($answers[$i]['AnswerAnswer']['form_input_name'], $cleanarray[$index])) {
						$cleanarray[$index][$answers[$i]['AnswerAnswer']['form_input_name']];
					}
					$cleanarray[$index][$answers[$i]['AnswerAnswer']['form_input_name']] = unserialize($answers[$i]['AnswerAnswer']['value']);
				}
			}
			//One More Loop to set the indexes
			// $indexedarr = array();
			// foreach($cleanarray as $item) {
			// $indexedarr[] = $item;
			// }
			$this->set('answers', $cleanarray);
		}
	}

	protected function _flattenArrays($data) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$data[$key] = serialize($value);
			}
		}
		return $data;
	}

}

if (!isset($refuseInit)) {
	class AnswersController extends AppAnswersController {
	}

}
