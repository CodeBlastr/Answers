<?php //debug(get_defined_constants()); ?>

<div id="formBuilder">
<div class="container">
  <div class="row clearfix">
    <!-- Building Form. -->
    <div class="span6">
      <div class="clearfix">
        <h2>Your Form</h2>
        <hr>
        <div id="build">
          <form id="target" class="form-horizontal">
          </form>
        </div>
      </div>
    </div>
    <!-- / Building Form. -->

    <!-- Components -->
    <div class="span6">
      <h2>Drag & Drop components</h2>
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
    </div>
    <!-- / Components -->

  </div>

</div> <!-- /container -->
</div>

<hr />
<div class="container">
	<div class="row clearfix">
		<div class="span2">
			<ul id="formOptionNav" class="nav nav-stacked">
  				<li class="active">
   					 <a href="#FormOptions">Form Options</a>
  				</li>
  				<li><a href="#SendEmail">Send Email</a></li>
  				<li><a href="#AutoResponse">Auto Response</a></li>
  				<li><a href="#ContactOptions">Save to Contacts</a></li>
			</ul>
		</div>
		
		<div class="span10">	
		<?php echo $this->Form->create('Answer'); ?>
		<div id="formOptionContent">
			
			<?php	
				echo '<div id="FormOptions" class="active">';
					echo $this->Form->input('Answer.plugin', array('type' => 'hidden', 'value' => 'Answers'));
					echo $this->Form->input('Answer.title', array('label' => 'Form Name', 'type' => 'text', 'required' => true));
					echo $this->Form->label('Choose where the form saves');
					echo $this->Form->select('Answer.model', $models, array('id' => 'modelSelect', 'required' => true, 'empty' => '-- Choose --'));
					echo '<div id="actionSelect"></div>';
					echo $this->Form->input('Answer.content', array('label' => false, 'type' => 'textarea', 'id' => 'render'));
					echo $this->Form->input('Answer.content_json', array('label' => false, 'type' => 'textarea', 'id' => 'renderJson'));
			    	echo $this->Form->label('Success Message');
			    	echo $this->Form->textArea('Answer.success_message', array('class' => 'clearfix'));
					echo $this->Form->label('Redirect');
					echo $this->Form->select('redirect', $urls, array('id' => 'redirectSelect', 'required' => true, 'empty' => false));
					echo $this->Form->input('Answer.success_url', array('type' => 'text', 'label' => 'url', 'div' => array('id' => 'urlSelect', 'style' => 'display:none;')));
			    	echo $this->Form->input('Answer.allowed_user_submissions', array('label' => 'Allowed User Submissions', 'type' => 'text'));
					echo '<p>If set to 0 users can submit unlimited times, if guest can submit form this has no effect</p>';
					echo $this->Element('forms/alias', array('formId' => '#AnswerAddForm', 'nameInput' => '#AnswerTitle', 'prefix' => 'form/')); // must have the alias behavior attached to work
			    echo '</div>';
				
				
				echo '<div id="SendEmail">';
				    echo '<label class="checkbox">';
				    echo $this->Form->checkbox('Answer.send_email', array('hiddenField' => false));
				    echo 'Send Email</label>';
					echo '<div id="responseEmailDiv" style="display:none;">';
					echo $this->Form->label('Emails (seperated by a comma)');
					echo $this->Form->text('Answer.response_email');
				    echo $this->Form->label('Email Subject');
				    echo $this->Form->text('Answer.response_subject');
					echo $this->Form->label('Email Body');
					echo $this->Form->textArea('Answer.response_body');
					echo '<div class="token-choices"></div>';
				echo '</div></div>';
				
				
				echo '<div id="AutoResponse">';
					echo '<label class="checkbox">';
				    echo $this->Form->checkbox('Answer.auto_respond', array('hiddenField' => false));
				    echo 'Auto Respond</label>';
					echo '<div id="autoEmailDiv" style="display:none;">';
					echo $this->Form->label('Auto Response Subject');
					echo $this->Form->text('Answer.auto_subject');
				    echo $this->Form->label('Auto Response Body');
				    echo $this->Form->textArea('Answer.auto_body'); 
					echo '<div class="token-choices"></div>';
					echo '</div>';
				echo '</div>';
				
				
				echo '<div id="ContactOptions">';
					echo '<p>Contact Options not available yet</p>';
				echo '</div>';
			?>
			</div>
			
			<?php
				echo '<div class="row-fluid">';
				    	echo $this->Form->submit('Save Form', array('class' => 'btn pull-right'));
				    	echo $this->Form->end();
				echo '</div>';
			?>
		</div>
	</div>
</div>

<hr />

<style>
	
</style>
 
<script type="text/javascript">
//<![CDATA[

var tokens = [];

$(document).ready(function () {
	
	$('#formOptionContent').children('div').not('.active').hide('slow');
	
	if($('#redirectSelect').val() == 'url') {
		$('#urlSelect').show();
	}
	if($('#AnswerSendEmail').is(':checked')) {
		$('#responseEmailDiv').show();
	}
	if($('#AnswerAutoRespond').is(':checked')) {
		$('#autoEmailDiv').show();
	}
	$("#modelSelect").bind("change", function (event) {
		$.ajax({
  			type: "POST",
  			url: "/answers/answers/getActions",
  			data: { plugin: $(this).val() }
				}).done(function( returnhtml ) {
  				$('#actionSelect').html(returnhtml);
			});
		return false;
		});
	$('#redirectSelect').bind("change", function(event) {
		if($(this).val() == 'url') {
			$('#urlSelect').show('fast');
			$('#AnswerSuccessUrl').val('');
		}else {
			$('#urlSelect').hide();
			$('#AnswerSuccessUrl').val($(this).val());
		}
	});
	
	$('#AnswerSendEmail').bind("change", function(event) {
		if($(this).is(":checked")) {
			$('#responseEmailDiv').show('fast');
		}else{
			$('#AnswerResponseEmail').val();
			$('#responseEmailDiv').hide();
		}		
	});
	
	$('#AnswerAutoRespond').bind("change", function(event) {
		if($(this).is(":checked")) {
			$('#autoEmailDiv').show('fast');
		}else{
			$('#autoEmailDiv').hide();
		}		
	});
	
	$('#target').on('change', '.popover-content #id', function(e) {
		var id = $(this).val();
		id = id.replace(" ", "_");
		$(this).val(id);
		var index = $.inArray(id, tokens);
		if(index != -1) {
			tokens[index] = id;
		}else {
			tokens.push(id);
		}
		refreshToken();
	});
	
	$('#formOptionNav li a').bind('click', function(e) {
		//hide all
		$('#formOptionContent').children('div').hide('slow');
		
		var pane = $(this).attr('href');
		$(pane).show('slow');
	});
	
	$('.token-choices').on('click', 'a', function(e) {
		e.preventDefault();
		var el = $(this).closest('.token-choices').prev('textarea').prop('id');
		var token = $(this).data('token');
		token = '*| '+token+' |*';
		repTokens(token,el);
		
	});
	
	//Below is code not used yet
	
	// $("#target").on("click", '#id', function(event){
  		// var model = $('#modelSelect').val();
  		// console.log(model);
  		// if(model !== 'Answer') {
  			// var item = $(this);
	  		// item.css('display', 'none');
		  		// $.ajax({
		  			// type: "POST",
		  			// url: "/answers/answers/getColumns",
		  			// data: { plugin: $('#modelSelect').val() }
						// }).done(function( returnhtml ) {
		  				// item.before(returnhtml);
					// });
			// }
		// return false;
	// });
// 	
	// $('#propertySelect').bind("change", function (event) {
		// $(this).next('#id').val($(this.val()));
	// });
	
});

function refreshToken() {
	var choices = '<ul class="token-nav">';
	for(var i=0 ; i<tokens.length ; i++) {
		choices += '<li><a href="#" data-token="'+tokens[i]+'">'+tokens[i]+'</a></li>';
	}
	choices += '</ul>'
	$('.token-choices').html(choices);
}

function repTokens(textValue, id) {
        //Get textArea HTML control 
        var txtArea = document.getElementById(id);
        
        //IE
        if (document.selection) {
            txtArea.focus();
            var sel = document.selection.createRange();
            sel.text = textValue;
            return;
        }
        
        //Firefox, chrome, mozilla
        else if (txtArea.selectionStart || txtArea.selectionStart == '0') {
            var startPos = txtArea.selectionStart;
            var endPos = txtArea.selectionEnd;
            var scrollTop = txtArea.scrollTop;
            txtArea.value = txtArea.value.substring(0, startPos) + textValue + txtArea.value.substring(endPos, txtArea.value.length);
            txtArea.focus();
            txtArea.selectionStart = startPos + textValue.length;
            txtArea.selectionEnd = startPos + textValue.length;
        }
        else {
            txtArea.value += textArea.value;
            txtArea.focus();
        }
}
//]]>
</script>