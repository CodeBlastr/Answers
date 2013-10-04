<?php
App::uses('AnswersAppModel', 'Answers.Model');

/**
 * Extension Code
 * $refuseInit = true; require_once(ROOT.DS.'app'.DS.'Plugin'.DS.'Answers'.DS.'Model'.DS.'Answer.php');
 */

class _Answer extends AnswersAppModel {
	
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
			'className' => 'Answers.AnswerSubmission',
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
			'className' => 'Answers.AnswerAnswer',
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
		
	public $belongsTo = array(
		'Creator' => array(
			'className' => 'Users.User',
			'foreignKey' => 'creator_id'
		),
		'Modifier' => array(
			'className' => 'Users.User',
			'foreignKey' => 'modifier_id'
		)
	);
		
	public $methods = array(
			'post' => 'post',
			'get' => 'get',
			'file' => 'file',
			'put' => 'put',
			'delete' => 'delete'
		);
		
	public function process ($form, $answers) {
		
		//If id is false check the id propery
		if($form['Answer']['id'] == null && isset($this->id)) {
			$id = $this->id;
		} else {
			$id = $form['Answer']['id'];
		}
		if(empty($id)) {
			throw new Exception(__('No Form Id'));
			return false;
		}
		//Send Auto Responders
		if($form['Answer']['auto_respond'] == 1 && !empty($form['Answer']['auto_email'])) {
			$this->_sendAutoResponseMail($form, $answers);
		}
			
		//Send Emails if set
		if($form['Answer']['send_email'] == 1 && !empty($form['Answer']['response_email'])) {
			
			$this->_sendResponseMail($form, $answers);
		}
		return true;
		
	}

/**
 * Send Emails to notifiees
 */
	
	protected function _sendResponseMail ($form, $answers) {
		if(!empty($form['Answer']['response_email'])) {
			$addresses = explode(',', str_replace(' ', '' , $form['Answer']['response_email']));
			$message['html'] = $this->_replaceTokens($this->_cleanAnswers($answers), $form['Answer']['response_body']);
			$subject = $form['Answer']['response_subject'];
			foreach($addresses as $address) {
				$this->__sendMail($address, $subject, $message);
			}
		}	
	}
	
	/**
	 * Send Autoresponses
	 */
	
	protected function _sendAutoResponseMail ($form, $answers) {
			
			$answers = $this->_cleanAnswers($answers);
			//debug($answers);break;
			$emailto = $answers[$form['Answer']['auto_email']];

			$userEmail = CakeSession::read('Auth.User.email');
			if ( !empty($userEmail) && empty($emailto)) {
				$emailto = $userEmail;
			}
			
			$message['html'] = $this->_replaceTokens($answers, $form['Answer']['auto_body']);
			$subject = $form['Answer']['auto_subject'];

			$this->__sendMail($emailto, $subject, $message);
	}
	 
	
	/**
	 * Clean the answer array for string replacement
	 * @param {$answers} array of $answers
	 * @return array keyed by input => value
	 */
	
	protected function _cleanAnswers ($answers) {
		$arr = array();
		foreach($answers as $answer) {
			$arr[$answer['form_input_name']] = $answer['value'];
		}
		return $arr;
	}
	
	/**
	 * Token Replacement Function
	 * Tokens should be in the format *| value |*
	 * @param $arr array of replacement keyed by token
	 * @param $str string to replace tokens in
	 * @return returns string with tokens replaced
	 */
	protected function _replaceTokens ($arr, $str) {
		foreach($arr as $token => $value) {
			$token = '*| '.$token. ' |*';
			$str = str_replace($token, $value, $str);
		}
		return $str;
	}

}

if (!isset($refuseInit)) {
	class Answer extends _Answer {}
}
