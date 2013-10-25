<?php
App::uses('Answer', 'Answers.Model');
/**
 * Answer model test cases
 *
 * @package 	answers
 * @subpackage	answers.tests.cases.models
 */
class AnswerTestCase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Start Test callback
 *
 * @param string $method
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Answer = ClassRegistry::init('Answers.Answer');
	}

/**
 * End Test callback
 *
 * @param string $method
 * @return void
 */
	public function tearDown() {
		unset($this->Answer);
		ClassRegistry::flush();
		parent::tearDown();
	}

/**
 * Test adding a Category
 *
 * @return void
 */
	public function testAdd() {
		$this->assertTrue(true);
	}

}
