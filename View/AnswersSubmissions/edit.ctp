<?php
if (!empty($data['User'])) {
	$submittedBy = $this->Html->link($data['User']['full_name'], array(
		'plugin' => 'users',
		'controller' => 'users',
		'action' => 'view',
		$data['User']['id']
	));
} else {
	$submittedBy = '(guest)';
}
$data = $this->request->data;

?>

<?php echo $this->Form->create('AnswersSubmission'); ?>
<?php echo $this->Form->hidden('AnswersSubmission.id'); ?>
<table>
	<tr>
		<th>Submitted by:</th>
		<td><?php echo $submittedBy ?></td>
	</tr>
	<tr>
		<th>Date:</th>
		<td><?php echo $data['AnswersSubmission']['created'] ?></td>
	</tr>
	<tr>
		<th>IP address:</th>
		<td><?php echo $data['AnswersSubmission']['from_ip'] ?></td>
	</tr>
</table>

<table class="table hover">
	<thead>
		<tr>
			<th>form field</th>
			<th>submitted value(s)</th>
		</tr>
	</thead>
<?php foreach ($data['AnswersResult'] as $i => $answer) : ?>
	<tr>
		<td><?php echo $answer['form_input_name'] ?><?php echo $this->Form->hidden('AnswersResult.'.$i.'.id')?></td>
		<td><?php 
				$value = unserialize($answer['value']);
				if(is_array($value)) {
					foreach ($value as $j => $v) {
						echo $this->Form->input('AnswersResult.'.$i.'.value.'.$j, array('label' => false, 'type' => 'text', 'value' => $v));
					}
				}else {
					echo $this->Form->input('AnswersResult.'.$i.'.value', array('label' => false, 'type' => 'text', 'value' => $value));
				}
		?></td>
	</tr>
<?php endforeach; ?>
</table>
<?php echo $this->Form->submit('Save', array('class' => 'btn btn-primary')); ?>
<?php echo $this->Html->link('Cancel', array('action' => 'index'), array('class' => 'btn btn-default')); ?>
<?php echo $this->Form->end(); ?>
