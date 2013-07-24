<?php

/**
 * Extension Code
 * $refuseInit = true; require_once(ROOT.DS.'app'.DS.'Plugin'.DS.'Answers'.DS.'Model'.DS.'AnswerAnswer.php');
 */

App::uses('AnswersAppModel', 'Answers.Model');

class _AnswerAnswer extends AnswersAppModel {
	
	public $name = 'AnswerAnswer';
	
}

if (!isset($refuseInit)) {
	class AnswerAnswer extends _AnswerAnswer {}
}