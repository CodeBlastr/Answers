<?php
App::uses('AnswersAppModel', 'Answers.Model');

class Answer extends AnswersAppModel {
	
	public $name = 'Answer';
	
	public $actsAs = array(
		'Copyable', 
		'Optimizable');
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	 
	// public $validate = array(
		// 'title' => array(
			// 'message' => 'Please Enter a Title',
			// 'allowEmpty' => false,
			// 'required' => true,
		// ),
		// 'method' => array(
			// 'notempty' => array(
				// 'rule' => array('notempty'),
			// //'message' => 'Your custom message here',
			// //'allowEmpty' => false,
			// //'required' => false,
			// //'last' => false, // Stop validation after this rule
			// //'on' => 'create', // Limit validation to 'create' or 'update' operations
			// ),
		// ),
	// );
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array(
		'AnswerSubmission' => array(
			'className' => 'Answer.AnswerSubmission',
			'foreignKey' => 'answer_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			),
		'AnswerAnswer' => array(
			'className' => 'Answer.AnswerAnswer',
			'foreignKey' => 'answer_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			),
		);
		
	public $methods = array(
			'post' => 'post',
			'get' => 'get',
			'file' => 'file',
			'put' => 'put',
			'delete' => 'delete'
		);
		
	public function process ($id = null, $answers) {
		
		//If id is false check the id propery
		if($id == null && isset($this->id)) {
			$id = $this->id;
		}
		if(empty($id)) {
			throw new Exception(__('No Form Id'));
		}
		
		//Get the form
		$form = $this->find('first', array(
			'conditions' => array('id' => $id),
		));
		
		//Send Emails if set
		if($form['Answer']['send_email'] == 1) {
			
			if(!empty($form['Answer']['response_email'])) {
				$addresses = explode(',', str_replace(' ', '' , $form['Answer']['response_email']));
			}else {
				throw new Exception('No Email addresses defined');
			}

			$message['html'] = $form['Answer']['response_body'];
			$from = array('info@educastic.com' => __SYSTEM_SITE_NAME);
			$subject = $form['Answer']['response_subject'];
			foreach($addresses as $address) {
				$this->__sendMail($address, $subject, $message);
			}
		}
		
	}
	
	private function _replaceTokens ($str) {
		
	}

}