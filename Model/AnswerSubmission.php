<?php
App::uses('AnswersAppModel', 'Answers.Model');

class AnswerSubmission extends AnswersAppModel {
	
	public $name = 'AnswerSubmission';

	public $hasMany = array(
		'AnswerAnswer' => array(
			'className' => 'Answers.Answer',
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

}