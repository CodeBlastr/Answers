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
<?php echo $this->Form->create('Answer');
	echo $this->Form->input('Answer.model', array('type' => 'hidden', 'value' => 'Answer'));
	echo $this->Form->input('Answer.plugin', array('type' => 'hidden', 'value' => 'Answers'));
	echo $this->Form->input('Answer.title', array('type' => 'hidden'));
	echo $this->Form->input('Answer.action', array('type' => 'hidden', 'value' => 'Process'));
	echo $this->Form->input('Answer.content', array('label' => false, 'type' => 'textarea', 'id' => 'render'));
	//echo $this->From->input('')
    echo $this->Form->end('Save Form'); 
?>
<script data-main="/Answers/js/main-built.js" src="/Answers/js/lib/require.js" ></script>
  