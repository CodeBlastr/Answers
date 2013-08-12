<?php
App::uses('AnswersAppModel', 'Answers.Model');

class AnswerSubmission extends AnswersAppModel {
	
	public $name = 'AnswerSubmission';

	public $hasMany = array(
		'AnswerAnswer' => array(
			'className' => 'Answers.AnswerAnswer',
			'foreignKey' => 'answer_submission_id',
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
		'Answer',
		'User' => array(
			'foreignKey' => 'creator_id'
		)
	);


	/**
	 * submission function - Create a sumbission if exists, else creates one
	 * Increments the Submission Count
	 *
	 * @param $answerID -  The Answer(Form) ID
	 * @return the count
	 */
	public function submit ($answerId) {
		//get the user id
		$uid = CakeSession::read('Auth.User.id');
		$conditions = array('answer_id' => $answerId, 'creator_id' => $uid);

			$submissionCount = $this->find('count', array($conditions));

			$answerSubmission = array('AnswerSubmission' => array(
				'answer_id' => $answerId,
				'count' => 0,
				'from_ip' => $_SERVER['REMOTE_ADDR'],
			));

		//increment count
		//$answerSubmission['AnswerSubmission']['count'] = $answerSubmission['AnswerSubmission']['count'] + 1;
		$this->save($answerSubmission);

		return $submissionCount + 1;
	}

}