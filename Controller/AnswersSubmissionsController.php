<?php
App::uses('AnswersAppController', 'Answers.Controller');
class AppAnswersSubmissionsController extends AnswersAppController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'AnswersSubmissions';

/**
 * Uses
 *
 * @var string
 */
	public $uses = array(
		'Answers.AnswersSubmission',
		'Answers.AnswersResult',
		'Contact.Contact'
	);

	public function index($id = false) {
		if ($id) {
			$this->paginate['conditions'][] = array('AnswersSubmission.answer_id' => $id);
		}
		$this->paginate['contain'] = array(
			'Answer' => array('fields' => array(
					'id',
					'title'
				)),
			'AnswersResult',
			'User' => array('fields' => array(
					'id',
					'full_name'
				))
		);
		$this->paginate['order'] = array('AnswersSubmission.created' => 'DESC');
		$this->AnswersSubmission->recursive = 0;
		$this->set('submissions', $this->paginate());
		$this->set('answer_id', $id);
	}
	
	public function csv($id = null) {
		$findargs = array();
		if (!empty($id)) {
			$findargs['conditions'][] = array('AnswersSubmission.answer_id' => $id);
		}
		$findargs['contain'] = array(
				'Answer' => array('fields' => array(
						'id',
						'title'
				)),
				'AnswersResult',
				'User' => array('fields' => array(
						'id',
						'full_name'
				))
		);
		$findargs['order'] = array('AnswersSubmission.created' => 'DESC');
		$this->AnswersSubmission->recursive = 0;
		$data = $this->AnswersSubmission->find('all', $findargs);
		foreach ($data as $i => $dat) {
			$this->request->data[$i] = $dat;
			$this->request->data[$i]['AnswersResult'] = $this->AnswersSubmission->Answer->cleanUnpackAnswers($dat['AnswersResult']);
		}
	}

	public function view($id) {
		if (empty($id)) {
			$this->Session->setFlash('Invalid request');
			$this->redirect($this->referer());
		}
		$data = $this->AnswersSubmission->find('first', array(
			'conditions' => array('AnswersSubmission.id' => $id),
			'contain' => array(
				'AnswersResult',
				'User' => array('fields' => array(
						'id',
						'full_name'
					))
			)
		));
		$this->set('data', $data);
	}

	public function edit($id) {
		if (empty($id)) {
			$this->Session->setFlash('Invalid request');
			$this->redirect($this->referer());
		}
		
		if(!empty($this->request->data)) {
			if($this->AnswersSubmission->saveAll($this->request->data)) {
				$this->Session->setFlash('Submission Saved');
			}else {
				$this->Session->setFlash('Submission Not Saved');
			}
		}		
		$this->request->data = $this->AnswersSubmission->find('first', array(
			'conditions' => array('AnswersSubmission.id' => $id),
			'contain' => array(
				'AnswersResult',
				'User' => array('fields' => array(
						'id',
						'full_name'
					))
			)
		));
		
	}

/**
 * Delete method
 * @todo this needs to be POST only
 *
 * @param string $id
 */
	public function delete($id = null) {
		// if (!$this->request->is('post')) {
			// throw new MethodNotAllowedException();
		// }
		$this->AnswersSubmission->id = $id;
		if (!$this->AnswersSubmission->exists()) {
			throw new NotFoundException(__('Invalid property'));
		}
		if ($this->AnswersSubmission->delete()) {
			$this->Session->setFlash(__('Deleted'), 'flash_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Error deleting, please try again.'), 'flash_warning');
		$this->redirect(array('action' => 'index'));
	}

}

if (!isset($refuseInit)) {
	class AnswersSubmissionsController extends AppAnswersSubmissionsController {
	}

}
