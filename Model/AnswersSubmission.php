<?php
App::uses('AnswersAppModel', 'Answers.Model');

class AppAnswersSubmission extends AnswersAppModel {

	public $name = 'AnswersSubmission';
	
	public $useTable = 'answer_submissions';

	public $hasMany = array(
		'AnswersResult' => array(
			'className' => 'Answers.AnswersResult',
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
		'Answer' => array(
			'className' => 'Answers.Answer'
		),
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
		$submissionCount = 0;
		if($uid) {
              	$conditions = array('answer_id' => $answerId, 'creator_id' => $uid);
			$submissionCount = $this->find('count', array($conditions));
		}
		$AnswersSubmission = array('AnswersSubmission' => array(
			'answer_id' => $answerId,
			'count' => $submissionCount + 1,
			'from_ip' => $_SERVER['REMOTE_ADDR'],
		));
		return $this->save($AnswersSubmission);
	}

}

if (!isset($refuseInit)) {
	class AnswersSubmission extends AppAnswersSubmission {
	}
}