
var tokens = [];
var labeleditor;
var targetel;

$(document).ready(function () {
	
	labeleditor = CKEDITOR.instances['FakeLabelEditor'];
	
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
	
	$('#target').on('click', '#label', function(e) {
		var offset = $(this).offset();
		targetel = $(this);
		$('.adv-label-editor').remove();
		$(this).after('<a href="#" class="adv-label-editor">Advanced</a>');
		$('#labelTextArea').css('left', offset.left+$(this).width()).css('top', offset.top+$(this).height());
		
		$('#labelTextArea').on('click', '#ckeditorInsert', function() {
			targetel.val(labeleditor.getData());
			$('#labelTextArea').css('display', 'none');
			labeleditor.setData('');
		});
		$('#target').on('click', '.adv-label-editor', function() {
			labeleditor.setData(targetel.val());
			$('#labelTextArea').css('display', 'block');
		});
	});
	
	$('#labelTextArea').on('click', '#ckeditorCancel', function(e){
			labeleditor.setData('');
			console.log(labeleditor.getData());
			$('#labelTextArea').css('display', 'none');
			$('#labelTextArea').off('click', '#ckeditorInsert', function(){});
			$('#target').off('click', '.adv-label-editor', function(){});
	});
	
	$(document).on('click', '#tokenRefresh', function(e) {
		getTokens();
	});
	
	$('#hideComponents').click(function(e) {
		if($('#formComponents').hasClass('closed')) {
			$('#formComponents').animate({
				right: '0px'
			});
			$('#formComponents').removeClass('closed');
		}else {
			$('#formComponents').animate({
				right: '-540px'
			});
			$('#formComponents').addClass('closed');
		}
		
	});
	
	//Populates the token array when page loads
	getTokens();
	
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

function getTokens() {
	tokens = [];
	$('#target .component .control-group label').each(function(e) {
		var id = $(this).prop('for');
		tokens.push(id);
	});
	refreshToken();
}

function refreshToken() {
	var choices = '<ul class="token-nav">';
	for(var i=0 ; i<tokens.length ; i++) {
		choices += '<li><a href="#" data-token="'+tokens[i]+'">'+tokens[i]+'</a></li>';
	}
	choices += '</ul>';
	choices += '<a href="#" id="tokenRefresh">Refresh Tokens</a>';
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
