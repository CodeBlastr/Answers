<?php

App::uses('AnswersAppModel', 'Answers.Model');

class AnswerStep extends AnswersAppModel {
	
	public $name = 'AnswerStep';
	
	public $belongsTo = array(
        'Answer' => array(
            'className'    => 'Answer',
            'foreignKey'   => 'answer_id'
        )
    );
	
}