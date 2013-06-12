<?php
App::uses('AnswersAppModel', 'Answers.Model');

class Answer extends AnswersAppModel {
	
	public $name = 'Answer';
	
	public $actsAs = array('Copyable');
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	 
	public $validate = array(
		'title' => array(
			'message' => 'Please Enter a Title',
			'allowEmpty' => false,
			'required' => true,
		),
		'method' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			//'message' => 'Your custom message here',
			//'allowEmpty' => false,
			//'required' => false,
			//'last' => false, // Stop validation after this rule
			//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'content' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			//'message' => 'Your custom message here',
			//'allowEmpty' => false,
			//'required' => false,
			//'last' => false, // Stop validation after this rule
			//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
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

}