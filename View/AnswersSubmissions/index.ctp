<table class="table table-hover">
	<thead>
		<tr>
			<th>date</th>
			<th>form title</th>
			<th>submitted by</th>
			<th>actions</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($submissions as $submission) : ?>
		<tr>
			<td><?php echo $submission['AnswersSubmission']['created'] ?></td>
			<td><?php echo $this->Html->link($submission['Answer']['title'], array('action' => 'index', $submission['Answer']['id'])) ?></td>
			<td><?php echo !empty($submission['User']) ? $this->Html->link($submission['User']['full_name'], array('plugin' => 'users', 'controller' => 'users', 'action' => 'view', $submission['User']['id'])) : '(guest)'; ?></td>
			<td><?php echo $this->Html->link('view', array('action' => 'view', $submission['AnswersSubmission']['id']), array('class' => 'btn btn-xs')) ?>
				<?php echo $this->Html->link('edit', array('action' => 'edit', $submission['AnswersSubmission']['id']), array('class' => 'btn btn-xs')) ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php 
// set contextual search options
$this->set('forms_search', array(
	'url' => '/answers/answersSubmissions/index',
	'inputs' => array(array(
			'name' => 'contains:data',
			'options' => array(
				'label' => '',
				'placeholder' => 'Submission Search',
				'value' => !empty($this->request->params['named']['contains']) ? substr($this->request->params['named']['contains'], strpos($this->request->params['named']['contains'], ':') + 1) : null,
			)
		))
));
