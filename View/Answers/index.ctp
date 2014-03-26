<?php
/**
 * Answers Index View
 *
 * The view for a list of forms.
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.answers.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */

?>

<div class="forms index">
	<h2><?php echo __('Forms'); ?></h2>
	<p> This forms plugin allows you to customize your website database.  Including system tables like project, tickets, contacts ,etc..</p>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id', 'Template Tag');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
		</tr>
		<?php foreach ($forms as $group) : ?>
		<tr>
			<td>
				<?php echo __('&#123answer: %s&#125;', $group['Answer']['id']); ?>
			</td>
			<td>
				<?php echo $group['Answer']['title']; ?>
			</td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('action' => 'view', $group['Answer']['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $group['Answer']['id'])); ?>
				<?php echo $this->Html->link(__('Copy'), array('action' => 'copy', $group['Answer']['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $group['Answer']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $group['Form']['id'])); ?>
				<?php echo $this->Html->link(__('CSV'), array('controller' => 'answers_submissions', 'action' => 'csv', $group['Answer']['id'] . '.csv')); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<?php echo $this->Element('paging'); ?>

<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('Add'), array('action' => 'add'), array('class' => 'add')),
			)
		)
	)));
