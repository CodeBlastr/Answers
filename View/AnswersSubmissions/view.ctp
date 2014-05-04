<?php
if (!empty($data['User']['id'])) {
	$submittedBy = $this->Html->link($data['User']['full_name'], array(
		'plugin' => 'users',
		'controller' => 'users',
		'action' => 'view',
		$data['User']['id']
	));
} else {
	$submittedBy = '(guest)';
}
?>

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
		<td>
			<a title="View info about this IP" href="http://whatismyipaddress.com/ip/<?php echo $data['AnswersSubmission']['from_ip'] ?>" target="_blank">
				<?php echo $data['AnswersSubmission']['from_ip'] ?>
				<span class="glyphicon glyphicon-new-window"></span>
			</a>
		</td>
	</tr>
</table>

<table class="table hover">
	<thead>
		<tr>
			<th>form field</th>
			<th>submitted value</th>
		</tr>
	</thead>
<?php foreach ($data['AnswersResult'] as $answer) : ?>
	<tr>
		<td><?php echo $answer['form_input_name'] ?></td>
		<td><?php
				if(strpos($answer['form_input_name'], 'submitted_files') !== false) {
					$val = unserialize($answer['value']);
					$ext = explode('.', $val);
					$ext = strtolower(array_pop($ext));
					if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'bmp' || $ext == 'png') {
						echo "<img width='200' height='200' src='/{$val}' />";
					}else {
						echo $this->Html->link($val, '/'.$val);
					}
				}else {
					echo unserialize($answer['value']);
				}?>
		</td>

	</tr>
<?php endforeach; ?>
</table>
