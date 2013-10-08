<div class="row-fluid">
	<?php if(!isset($showtitle)): ?>
	<h3><?php echo $form['Answer']['title']; ?></h3>
	<?php endif; ?>
	
	<?php if($this->Session->read('Message.formmessage')): ?>
		<div class="alert">
  			<button type="button" class="close" data-dismiss="alert">&times;</button>
  			<strong>Warning!</strong> <?php echo $this->Session->flash('formmessage'); ?>
		</div>
	<?php endif; ?>
	
	
	<?php
	  if($submit) {
	  	$submitButtonText = !empty($form['Answer']['submit_button_text']) ? $form['Answer']['submit_button_text'] : 'Submit';
		echo $this->Form->create('Answers.Answer', array('url' => array('plugin' => 'answers', 'controller' => 'answers', 'action' => 'formProcess')));
		echo $this->Form->hidden('Answer.id', array('value' => $form['Answer']['id']));
		echo $this->Form->hidden('Answer.redirect', array('value' => $form['Answer']['success_url']));
		echo $this->Form->hidden('Answer.message', array('value' => $form['Answer']['success_message']));
		echo $form['Answer']['content'];
		echo $this->Form->submit($submitButtonText, array('class' => 'btn btn-primary'));
		echo $this->Form->end();
	  }else {
	  	echo '<script>function goBack(){window.history.back()}</script>';
		echo '<div class="submit"><input class="btn btn-primary" type="button" value="Back" onclick="goBack()"></div>';
	  }
	?>
</div>

<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('Edit'), array('action' => 'edit', $form['Answer']['id']), array('class' => 'edit')),
			$this->Html->link(__('Add'), array('action' => 'add'), array('class' => 'add')),
			$this->Html->link(__('List'), array('action' => 'index'), array('class' => 'index')),
			$this->Html->link(__('Download CSV'), array('action' => 'answersubmissions', $form['Answer']['id'].'.csv')),
			)
		),
	))); ?>