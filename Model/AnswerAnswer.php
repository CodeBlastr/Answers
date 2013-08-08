<?php

/**
 * Extension Code
 * $refuseInit = true; require_once(ROOT.DS.'app'.DS.'Plugin'.DS.'Answers'.DS.'Model'.DS.'AnswerAnswer.php');
 */

App::uses('AnswersAppModel', 'Answers.Model');

class _AnswerAnswer extends AnswersAppModel {
	
	public $name = 'AnswerAnswer';
	
	public $belongsTo = array(
		'Creator' => array(
            'className' => 'Users.User',
            'foreignKey' => 'creator_id'
        ),
		'Modifier' => array(
            'className' => 'Users.User',
            'foreignKey' => 'modifier_id'
        ),
        'Answer' => array(
			'className' => 'Answers.Answer',
            'foreignKey' => 'answer_id'
		),
        'AnswerSubmission' => array(
			'className' => 'Answers.AnswerSubmission',
            'foreignKey' => 'answer_sumbmission_id'
		)
	);
	
	public function beforeSave($options = array()) {
		
		$this->data['AnswerAnswer']['value'] = serialize($this->data['AnswerAnswer']['value']);
		
		return true;
	}
	
	public function afterFind($results, $primary = false) {
		
		if(is_array($results['AnswerAnswer'])) {
			foreach ($results['AnswerAnswer'] as $key => $value) {
				if($key == 'value') {
					$results['AnswerAnswer'][$key] = unserialize($value);
				}
			}
		}else {
			$results['AnswerAnswer']['value'] = unserialize($results['AnswerAnswer']['value']);
		}
		
		return $results;
	}
	
}

if (!isset($refuseInit)) {
	class AnswerAnswer extends _AnswerAnswer {}
}