<?php
	echo $this->Form->label('Select an action');
	echo $this->Form->select('Answer.action', $actions, array('required' => true, 'empty' => false));
?>