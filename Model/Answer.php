<?php
App::uses('AnswersAppModel', 'Answers.Model');

class AppAnswer extends AnswersAppModel {

	public $name = 'Answer';

	public $actsAs = array(
		'Copyable',
		'Optimizable'
	);

	public $hasMany = array(
		'AnswersSubmission' => array(
			'className' => 'Answers.AnswersSubmission',
			'foreignKey' => 'answer_id',
			'dependent' => true,
		),
		'AnswersResult' => array(
			'className' => 'Answers.AnswersResult',
			'foreignKey' => 'answer_id',
			'dependent' => true,
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

/**
 * Process method
 * 
 * @param 
 * @param
 * @throws Exception
 */
	public function process($form, $answers) {
		//If id is false check the id propery
		if ($form['Answer']['id'] == null && isset($this->id)) {
			$id = $this->id;
		} else {
			$id = $form['Answer']['id'];
		}
		if (empty($id)) {
			throw new Exception(__('No Form Id'));
		}
		//Send Auto Responders
		if ($form['Answer']['auto_respond'] == 1 && !empty($form['Answer']['auto_email'])) {
			$this->_sendAutoResponseMail($form, $answers);
		}
		//Send Emails if set
		if ($form['Answer']['send_email'] == 1 && !empty($form['Answer']['response_email'])) {
			$this->_sendResponseMail($form, $answers);
		}
		return true;
	}

/**
 * Send Emails to notifiees
 */
	protected function _sendResponseMail($form, $answers) {
		if (!empty($form['Answer']['response_email'])) {
			$addresses = explode(',', str_replace(' ', '', $form['Answer']['response_email']));
			$message['html'] = $this->_replaceTokens($this->_cleanAnswers($answers), $form['Answer']['response_body']);
			$subject = $form['Answer']['response_subject'];
			foreach ($addresses as $address) {
				$this->__sendMail($address, $subject, $message);
			}
		}
	}

/**
 * Send Autoresponses
 */
	protected function _sendAutoResponseMail($form, $answers) {
		$answers = $this->_cleanAnswers($answers);
		//debug($answers);break;
		$emailto = $answers[$form['Answer']['auto_email']];
		$userEmail = CakeSession::read('Auth.User.email');
		if (!empty($userEmail) && empty($emailto)) {
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
	protected function _cleanAnswers($answers, $unserialize=false) {
		$arr = array();
		foreach ($answers as $answer) {
			if($unserialize) {
				$arr[$answer['form_input_name']] = unserialize($answer['value']);
			}else {
				$arr[$answer['form_input_name']] = $answer['value'];
			}
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
	protected function _replaceTokens($arr, $str) {
		foreach ($arr as $token => $value) {
			$token = '*| ' . $token . ' |*';
			$str = str_replace($token, $value, $str);
		}
		return $str;
	}
	
	public function cleanUnpackAnswers($answers) {
		return $this->_cleanAnswers($answers, true);
	}
	

}

if (!isset($refuseInit)) {
	class Answer extends AppAnswer {
	}
}
