<?php //debug($this->here); ?>

<div class="row-fluid">
	<h3><?php echo $form['Answer']['title']; ?></h3>
	<?php
		echo $this->Form->create('Answers.Answer', array('url' => array('plugin' => 'answers', 'controller' => 'answers', 'action' => 'formProcess')));
		echo $this->Form->hidden('Answer.id', array('value' => $form['Answer']['id']));
		echo $this->Form->hidden('Answer.redirect', array('value' => $form['Answer']['success_url']));
		echo $this->Form->hidden('Answer.message', array('value' => $form['Answer']['success_message']));
		echo $form['Answer']['content'];
		echo $this->Form->submit('Submit');
		echo $this->Form->end();
	?>
</div>