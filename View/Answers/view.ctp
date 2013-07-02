<?php //debug($this->here); ?>

<div class="row-fluid">
	<h3><?php echo $form['Answer']['title']; ?></h3>
	
	<?php if($this->Session->read('Message.formmessage')): ?>
		<div class="alert">
  			<button type="button" class="close" data-dismiss="alert">&times;</button>
  			<strong>Warning!</strong> <?php echo $this->Session->flash('formmessage'); ?>
		</div>
	<?php endif; ?>
	
	
	<?php
	  if($submit) {
		echo $this->Form->create('Answers.Answer', array('url' => array('plugin' => 'answers', 'controller' => 'answers', 'action' => 'formProcess')));
		echo $this->Form->hidden('Answer.id', array('value' => $form['Answer']['id']));
		echo $this->Form->hidden('Answer.redirect', array('value' => $form['Answer']['success_url']));
		echo $this->Form->hidden('Answer.message', array('value' => $form['Answer']['success_message']));
		echo $form['Answer']['content'];
		echo $this->Form->submit('Submit');
		echo $this->Form->end();
	  }else {
	  	echo '<script>function goBack(){window.history.back()}</script>';
		echo '<div class="submit"><input class="btn btn-primary" type="button" value="Back" onclick="goBack()"></div>';
	  }
	?>
</div>