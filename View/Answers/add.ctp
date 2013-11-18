<div id="formBuilder">
	<div class="container">
		<div class="row clearfix">
			<!-- Building Form. -->
			<div class="span12" style="padding:20px;">
				<div class="clearfix">
					<h2>Your Form</h2>
					<hr>
					<div id="build">
						<form id="target" class="form-horizontal"></form>
					</div>
				</div>
			</div>
			<!-- / Building Form. -->
		</div>
	</div>
	<!-- /container -->

	<div id="formComponents">
		<a class="btn" id="hideComponents">&times;</a>
		<div class="compcon">
			<!-- Components -->
			<h2>Drag &amp; Drop components</h2>
			<hr>
			<div class="tabbable">
				<ul class="nav nav-tabs" id="navtab">
					<!-- Tab nav -->
				</ul>
				<form class="form-horizontal" id="components">
					<fieldset>
						<div class="tab-content">
							<!-- Tabs of snippets go here -->
						</div>
					</fieldset>
				</form>
			</div>
			<!-- / Components -->
		</div>

	</div>
	<!-- /formComponents -->
</div>

<hr />
<div class="container">
	<div class="row clearfix">
		<div class="span2">
			<ul id="formOptionNav" class="nav nav-stacked">
				<li class="active">
					<a href="#FormOptions">Form Options</a>
				</li>
				<li>
					<a href="#SendEmail">Send Email</a>
				</li>
				<li>
					<a href="#AutoResponse">Auto Response</a>
				</li>
				<li>
					<a href="#ContactOptions">Save to Contacts</a>
				</li>
			</ul>
		</div>

		<div class="span10">
			<?php echo $this->Form->create('Answer'); ?>
			<div id="formOptionContent">
				<div id="FormOptions" class="active">
					<?php echo $this->Form->input('Answer.id', array('type' => 'hidden')); ?>
					<?php echo $this->Form->input('Answer.plugin', array('type' => 'hidden', 'value' => 'Answers')); ?>
					<?php echo $this->Form->input('Answer.title', array('label' => 'Form Name',	'type' => 'text', 'required' => true)); ?>
					<?php echo $this->Form->label('Choose where the form saves'); ?>
					<?php echo $this->Form->select('Answer.model', $models, array('id' => 'modelSelect', 'required' => true, 'empty' => '-- Choose --')); ?>
					<div id="actionSelect"></div>
					<?php echo $this->Form->input('Answer.content', array('label' => false, 'type' => 'textarea', 'id' => 'render')); ?>
					<?php echo $this->Form->input('Answer.content_json', array('label' => false, 'type' => 'textarea', 'id' => 'renderJson')); ?>
					<?php echo $this->Form->label('Success Message'); ?>
					<?php echo $this->Form->textArea('Answer.success_message', array('class' => 'clearfix')); ?>
					<?php echo $this->Form->label('Redirect'); ?>
					<?php echo $this->Form->select('redirect', $urls, array('id' => 'redirectSelect', 'required' => true, 'empty' => false)); ?>
					<?php echo $this->Form->input('Answer.success_url', array('type' => 'text',	'label' => 'url', 'div' => array('id' => 'urlSelect', 'style' => 'display:none;'))); ?>
					<?php echo $this->Form->input('Answer.allowed_user_submissions', array('label' => 'Allowed User Submissions', 'type' => 'text')); ?>
					<p>If set to 0 users can submit unlimited times, if guest can submit form this has no effect</p>
					<?php echo $this->Element('forms/alias', array('formId' => '#AnswerAddForm', 'nameInput' => '#AnswerTitle',	'prefix' => 'form/')); ?>
					<?php echo $this->Form->label('Submit Button text'); ?>
					<?php echo $this->Form->input('Answer.submit_button_text', array('label' => false, 'placeholder' => 'Submit')); ?>
				</div>
				<div id="SendEmail">
					<label class="checkbox"><?php echo $this->Form->checkbox('Answer.send_email', array('hiddenField' => false)); ?> Send Email</label>
					<div id="responseEmailDiv" style="display:none;">
						<?php echo $this->Form->label('Emails (seperated by a comma)'); ?>
						<?php echo $this->Form->text('Answer.response_email'); ?>
						<?php echo $this->Form->label('Email Subject'); ?>
						<?php echo $this->Form->text('Answer.response_subject'); ?>
						<?php echo $this->Form->label('Email Body'); ?>
						<?php echo $this->Form->textArea('Answer.response_body'); ?>
						<div class="token-choices"></div>
					</div>
				</div>
				<div id="AutoResponse">
					<label class="checkbox"><?php echo $this->Form->checkbox('Answer.auto_respond', array('hiddenField' => false)); ?> Auto Respond</label>
					<div id="autoEmailDiv" style="display:none;">
						<?php echo $this->Form->label('Auto Email Field. Please enter the id of the field for autoresponder'); ?>
						<?php echo $this->Form->text('Answer.auto_email'); ?>
						<?php echo $this->Form->label('Auto Response Subject'); ?>
						<?php echo $this->Form->text('Answer.auto_subject'); ?>
						<?php echo $this->Form->label('Auto Response Body'); ?>
						<?php echo $this->Form->textArea('Answer.auto_body'); ?>
						<div class="token-choices"></div>
					</div>
				</div>
				<div id="ContactOptions">
					<p>Contact Options not available yet</p>
				</div>
			</div>
			<div class="row-fluid">
				<?php echo $this->Form->submit('Save Form', array('class' => 'btn pull-right')); ?>
			</div>
			<div id="labelTextArea">
				<?php echo $this->Form->input('Fake.labelEditor', array('type' => 'richtext')); ?>
				<a class="btn" href="#" id="ckeditorInsert">Insert</a>
				<a class="btn" href="#" id="ckeditorCancel">Cancel</a>
				<div class="clearfix"></div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<hr />

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array( array(
			'heading' => 'Forms',
			'items' => array(
				$this->Html->link('View All Forms', array('action' => 'index')),
				$this->Html->link(__('View'), array(
					'action' => 'view',
					$this->request->data['Answer']['id']
				), array('class' => 'view'))
			)
		))));
