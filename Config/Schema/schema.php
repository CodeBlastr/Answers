<?php
class AppAnswersSchema extends CakeSchema {

	public $renames = array();

	public function before($event = array()) {
		App::uses('UpdateSchema', 'Model');
		$this->UpdateSchema = new UpdateSchema;
		$before = $this->UpdateSchema->before($event);
		return $before;
	}

	public function after($event = array()) {
		$this->UpdateSchema->rename($event, $this->renames);
		$this->UpdateSchema->after($event);
	}

	public $answer_answers = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'default' => null,
			'key' => 'primary'
		),
		'answer_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'answer_submission_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'form_input_name' => array(
			'type' => 'string',
			'null' => false,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'value' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'data' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'creator_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'modifier_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'created' => array(
			'type' => 'datetime',
			'null' => false,
			'default' => null
		),
		'modified' => array(
			'type' => 'datetime',
			'null' => false,
			'default' => null
		),
		'indexes' => array('PRIMARY' => array(
				'column' => 'id',
				'unique' => 1
			)),
		'tableParameters' => array(
			'charset' => 'utf8',
			'collate' => 'utf8_general_ci',
			'engine' => 'MyISAM'
		)
	);

	public $answer_submissions = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'default' => null,
			'key' => 'primary'
		),
		'answer_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'from_ip' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 50,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'count' => array(
			'type' => 'integer',
			'null' => true,
			'default' => null
		),
		'data' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'creator_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'modifier_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'created' => array(
			'type' => 'datetime',
			'null' => false,
			'default' => null
		),
		'modified' => array(
			'type' => 'datetime',
			'null' => false,
			'default' => null
		),
		'indexes' => array('PRIMARY' => array(
				'column' => 'id',
				'unique' => 1
			)),
		'tableParameters' => array(
			'charset' => 'utf8',
			'collate' => 'utf8_general_ci',
			'engine' => 'MyISAM'
		)
	);

	public $answers = array(
		'id' => array(
			'type' => 'string',
			'null' => false,
			'default' => null,
			'length' => 36,
			'key' => 'primary',
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'title' => array(
			'type' => 'string',
			'null' => false,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'merely for descriptive purposes',
			'charset' => 'utf8'
		),
		'method' => array(
			'type' => 'string',
			'null' => false,
			'default' => 'post',
			'length' => 10,
			'collate' => 'utf8_general_ci',
			'comment' => 'Valid values include post, get, file, put and delete - default post',
			'charset' => 'utf8'
		),
		'plugin' => array(
			'type' => 'string',
			'null' => false,
			'default' => null,
			'length' => 100,
			'collate' => 'utf8_general_ci',
			'comment' => 'the plugin the model is in',
			'charset' => 'utf8'
		),
		'model' => array(
			'type' => 'string',
			'null' => false,
			'default' => null,
			'length' => 100,
			'collate' => 'utf8_general_ci',
			'comment' => 'the model to save form data to',
			'charset' => 'utf8'
		),
		'action' => array(
			'type' => 'string',
			'null' => false,
			'default' => 'add',
			'length' => 10,
			'collate' => 'utf8_general_ci',
			'comment' => 'Valid values are add, edit, view - default add',
			'charset' => 'utf8'
		),
		'url' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'hidden from editing, auto created from other inputs',
			'charset' => 'utf8'
		),
		'submit_button_text' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'send_email' => array(
			'type' => 'boolean',
			'null' => false,
			'default' => '0'
		),
		'success_message' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'success_url' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'fail_message' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'fail_url' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'response_email' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'the field name which holds the email address to send a response to',
			'charset' => 'utf8'
		),
		'response_subject' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'response_body' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'auto_respond' => array(
			'type' => 'boolean',
			'null' => false,
			'default' => '0'
		),
		'auto_email' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'The name of the id containing the email field',
			'charset' => 'utf8'
		),
		'auto_subject' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'auto_body' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'content' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'content_json' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'allowed_user_submissions' => array(
			'type' => 'integer',
			'null' => false,
			'default' => '0'
		),
		'data' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'creator_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'modifier_id' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'length' => 36,
			'collate' => 'utf8_general_ci',
			'charset' => 'utf8'
		),
		'created' => array(
			'type' => 'datetime',
			'null' => false,
			'default' => null
		),
		'modified' => array(
			'type' => 'datetime',
			'null' => false,
			'default' => null
		),
		'indexes' => array('PRIMARY' => array(
				'column' => 'id',
				'unique' => 1
			)),
		'tableParameters' => array(
			'charset' => 'utf8',
			'collate' => 'utf8_general_ci',
			'engine' => 'MyISAM'
		)
	);
}
if (!isset($refuseInit)) {
	class AnswersSchema extends AppAnswersSchema {}
}



/* This is an example of how you can have and use per site config files
$refuseInit = true; 
require_once(ROOT . DS . 'app' . DS . 'Plugin' .DS. 'Answers' .DS. 'Config' . DS . 'Schema' . DS . 'schema.php');

class AnswersSchema extends AppAnswersSchema {
	
	public function __construct($options = array()) {
		// add / edit a table not in the app config file
		// order is important
		// don't forget you still have to add this to pluginize() else it will cause a loop when running auto update
		// $this->answer_delete = array(
			// 'id' => array(
				// 'type' => 'integer',
				// 'null' => false,
				// 'default' => null,
				// 'key' => 'primary'
				// ),
			// 'indexes' => array(
				// 'PRIMARY' => array('column' => 'id','unique' => 1)
				// ),
			// 'tableParameters' => array(
				// 'charset' => 'utf8',
				// 'collate' => 'utf8_general_ci',
				// 'engine' => 'MyISAM'
				// )
			// );

		parent::__construct($options);

		// add a new column to an existing table
		// order related to parent::__construct() is important
		// $this->tables['answer_answers'] = ZuhaSet::array_splice_before($this->tables['answer_answers'], array(
			// 'answer_field_delete' => array( // added column name
				// 'type' => 'string',
				// 'null' => true,
				// 'default' => null,
				// 'length' => 36,
				// 'collate' => 'utf8_general_ci',
				// 'charset' => 'utf8'
				// )), 'indexes');

		// edit an existing column
		// order related to parent::__construct() is important
		// $this->tables['answer_answers']['answer_id'] = array(
				// 'type' => 'string',
				// 'null' => true,
				// 'default' => null,
				// 'length' => 44, // edited line
				// 'collate' => 'utf8_general_ci',
				// 'charset' => 'utf8'
			// );
	}
} */