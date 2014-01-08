<?php
App::uses('AnswersAppModel', 'Answers.Model');
class AppAnswerAnswer extends AnswersAppModel {

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
		if(isset($this->data['AnswerAnswer']['value']['tmp_name'])) {
			$result = $this->uploadFiles('submitted_files', array($this->data['AnswerAnswer']['value']));
			if(isset($result['errors'])) {
				throw new Exception($result['errors'][0]);
			}
			if(isset($result['urls'])) {
				$this->data['AnswerAnswer']['value'] = $result['urls'][0];
			}else {
				throw new Exception('Something went wrong');
			}
		}
		$this->data['AnswerAnswer']['value'] = serialize($this->data['AnswerAnswer']['value']);
		return true;
	}

	public function afterFind($results, $primary = false) {
		if (is_array($results['AnswerAnswer'])) {
			foreach ($results['AnswerAnswer'] as $key => $value) {
				if ($key == 'value') {
					$results['AnswerAnswer'][$key] = unserialize($value);
				}
			}
		} else {
			$results['AnswerAnswer']['value'] = unserialize($results['AnswerAnswer']['value']);
		}
		return $results;
	}
	
	/**
	 * uploads files to the server
	 * @params:
	 *		$folder 	= the folder to upload the files e.g. 'img/files'
	 *		$formdata 	= the array containing the form files
	 *		$itemId 	= id of the item (optional) will create a new sub folder
	 * @return:
	 *		will return an array with the success of each file upload
	 */
	function uploadFiles($folder, $formdata, $itemId = null) {
		// setup dir names absolute and relative
		$folder_url = ROOT.DS.SITE_DIR.DS.'Locale'.DS.'View'.DS.WEBROOT_DIR.DS.$folder;
		$rel_url = $folder;
		// create the folder if it does not exist
		if(!is_dir($folder_url)) {
			mkdir($folder_url);
		}
			
		// if itemId is set create an item folder
		if($itemId) {
			// set new absolute folder
			$folder_url = WWW_ROOT.$folder.'/'.$itemId; 
			// set new relative folder
			$rel_url = $folder.'/'.$itemId;
			// create directory
			if(!is_dir($folder_url)) {
				mkdir($folder_url, 755);
			}
		}
		
		// list of permitted file types, this is only images but documents can be added
		$permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');
		
		// loop through and deal with the files
		foreach($formdata as $file) {
			// replace spaces with underscores
			$filename = str_replace(' ', '_', $file['name']);
			// assume filetype is false
			$typeOK = false;
			// check filetype is ok
			foreach($permitted as $type) {
				if($type == $file['type']) {
					$typeOK = true;
					break;
				}
			}
			// if file type ok upload the file
			if($typeOK) {
				// switch based on error code
				switch($file['error']) {
					case 0:
						// check filename already exists
						if(!file_exists($folder_url.DS.$filename)) {
							// create full filename
							$full_url = $folder_url.DS.$filename;
							$url = $rel_url.'/'.$filename;
							// upload the file
							$success = rename($file['tmp_name'], $full_url);
						} else {
							// create unique filename and upload file
							ini_set('date.timezone', 'Europe/London');
							$now = date('Y-m-d-His');
							$full_url = $folder_url.DS.$now.$filename;
							$url = $rel_url.'/'.$now.$filename;
							$success = rename($file['tmp_name'], $full_url);
						}
						// if upload was successful
						if($success) {
							// save the url of the file
							$result['urls'][] = $url;
						} else {
							$result['errors'][] = "Error uploaded $filename. Please try again.";
						}
						break;
					case 3:
						// an error occured
						$result['errors'][] = "Error uploading $filename. Please try again.";
						break;
					default:
						// an error occured
						$result['errors'][] = "System error uploading $filename. Contact webmaster.";
						break;
				}
			} elseif($file['error'] == 4) {
				// no file was selected for upload
				$result['nofiles'][] = "No file Selected";
			} else {
				// unacceptable file type
				$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
			}
		}
		return $result;
		}
	
}

if (!isset($refuseInit)) {
	class AnswerAnswer extends AppAnswerAnswer {
	}

}
