<?php
App::uses('AnswersAppModel', 'Answers.Model');

class AppAnswerSubmission extends AnswersAppModel {

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
 * Constructor
 */
	public function __construct($id = false, $table = null, $ds = null) {
		if (CakePlugin::loaded('Media')) {
			$this->actsAs[] = 'Media.MediaAttachable';
		}
		parent::__construct($id, $table, $ds);
	}

/**
 * Submission function - Create a sumbission if exists, else creates one
 * Increments the Submission Count
 *
 * @param $answerID -  The Answer(Form) ID
 * @return the count
 */
	public function submit($answerId) {
		$uid = CakeSession::read('Auth.User.id');
		$conditions = array('answer_id' => $answerId, 'creator_id' => $uid);
			$submissionCount = $this->find('count', array($conditions));
			$answerSubmission = array('AnswerSubmission' => array(
				'answer_id' => $answerId,
				'count' => 0,
				'from_ip' => $_SERVER['REMOTE_ADDR'],
			));
		$this->save($answerSubmission);
		return $submissionCount + 1;
	}

}

if (!isset($refuseInit)) {
	class AnswerSubmission extends AppAnswerSubmission {
	}
}