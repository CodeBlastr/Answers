<?php echo !isset($showtitle) ? __('<h3>%s</h3>', $form['Answer']['title']) : null; ?>
<?php if($this->Session->read('Message.formmessage')): ?>
	<div class="alert alert-warning alert-dismissable">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Warning!</strong> <?php echo $this->Session->flash('formmessage'); ?>
	</div>
<?php endif; ?>
<?php if($submit) : ?>
	<?php echo $this->Form->create('Answers.Answer', array('url' => array('plugin' => 'answers', 'controller' => 'answers', 'action' => 'formProcess'), 'secure' => false)); ?>
	<?php echo $this->Form->hidden('Answer.id', array('value' => $form['Answer']['id'])); ?>
	<?php echo $this->Form->hidden('Answer.redirect', array('value' => $form['Answer']['success_url'])); ?>
	<?php echo $this->Form->hidden('Answer.message', array('value' => $form['Answer']['success_message'])); ?>
	<?php echo $form['Answer']['content']; ?>
	<?php echo $this->Form->submit($submitButtonText, array('class' => 'btn submit-button')); ?>
	<?php echo $this->Form->end(); ?>
<?php else : ?>
	<div class="submit"><input class="btn btn-primary" type="button" value="Back" onclick="window.history.back()"></div>
<?php endif; ?>
<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array( array(
	'heading' => 'Forms',
	'items' => array(
		$this->Html->link(__('Edit'), array('action' => 'edit', $form['Answer']['id'])),
		$this->Html->link(__('Add'), array('action' => 'add')),
		$this->Html->link(__('List'), array('action' => 'index')),
		$this->Html->link(__('Download CSV'), array('action' => 'AnswersSubmissions', $form['Answer']['id'] . '.csv'))
	)
))));
