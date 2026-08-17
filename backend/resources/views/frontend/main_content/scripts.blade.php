<script type="text/javascript">
	"use strict";
	
	// Shared submit logic called by both Enter key and Send button
	function _doSubmitComment(input, parent_id, post_id, comment_id, type) {
		var description = $(input).val().trim();
		if (!description) return;

		// Disable input + button while sending
		$(input).prop('disabled', true);
		var $btn = $(input).closest('.comment-input-wrap').find('.send-btn');
		$btn.prop('disabled', true).html('<i class="fa fa-circle-notch fa-spin"></i>');

		$.ajax({
			type: 'get',
			url: '{{url("/post_comment")}}',
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
			data: { description:description, parent_id:parent_id, post_id:post_id, comment_id:comment_id, type:type },
			success: function(response){
				$(input).val('').prop('disabled', false);
				$btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i>');

				if (parent_id == 0) {
					$('#comments' + post_id).prepend(response);
				} else {
					$('#child_comments' + parent_id).prepend(response);
				}

				// Update comment count without extra network call (just increment)
				var $cnt = $('#post_comment_count' + post_id);
				var cur = parseInt($cnt.text()) || 0;
				$cnt.text(cur + 1);
			},
			error: function(){
				$(input).prop('disabled', false);
				$btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i>');
			}
		});
	}

	// Called by onkeypress on input
	function postComment(e, parent_id, post_id, comment_id, type) {
		var key = window.event.keyCode;
		if (key === 13) {
			_doSubmitComment(e, parent_id, post_id, comment_id, type);
		}
	}

	// Called by clicking the Send button
	function submitComment(btn, parent_id, post_id, comment_id, type) {
		var input = $(btn).closest('.comment-input-wrap').find('input, textarea').get(0);
		_doSubmitComment(input, parent_id, post_id, comment_id, type);
	}

	// Emoji SVG map for instant optimistic UI
	var _reactSvg = {
		like:  '{{asset("storage/images/like.svg")}}',
		liked: '{{asset("storage/images/liked.svg")}}',
		love:  '{{asset("storage/images/love.svg")}}',
		haha:  '{{asset("storage/images/haha.svg")}}',
		sad:   '{{asset("storage/images/sad.svg")}}',
		angry: '{{asset("storage/images/angry.svg")}}'
	};
	var _reactLabel = { like:'Liked', love:'Loved', haha:'Haha', sad:'Sad', angry:'Angry' };
	var _reactColor  = { like:'like-color', love:'love-color', haha:'sad-color', sad:'sad-color', angry:'angry-color' };

	function myReact(type, react, requestType, postId, responseType) {
		@if(!auth()->check())
			window.location.href = '{{ route("login") }}';
			return;
		@endif
		responseType = responseType || null;

		// --- OPTIMISTIC UI: update BOTH top summary AND bottom button instantly ---
		if (requestType === 'update' || requestType === 'toggle') {
			// 1) Bottom Like button
			var $myBtn = $('#my_post_reacts' + postId);
			var colorClass = _reactColor[react] || 'like-color';
			var activeSvg  = (react === 'like') ? _reactSvg.liked : _reactSvg[react];
			var label      = _reactLabel[react] || 'Like';
			$myBtn.html('<div class="' + colorClass + '"><img class="w-17px mt--4px" src="' + activeSvg + '" alt=""> ' + label + '</div>');

			// 2) Top reaction summary (emoji icons + count)
			var $topReacts = $('#post_reacts' + postId);
			// Parse current count from the .react-count span
			var curCount = parseInt($topReacts.find('.react-count').text()) || 0;
			// If first reaction (was 0) bump to 1; otherwise keep same (may be toggling)
			var newCount = (curCount === 0) ? 1 : curCount;
			// Build a simple summary: one emoji icon + count
			var summarySvg = (react === 'like') ? _reactSvg.liked : _reactSvg[react];
			$topReacts.html(
				'<div class="post-react d-flex align-items-center">' +
				'<ul class="react-icons"><li><img class="w-22px" src="' + summarySvg + '" alt=""></li></ul>' +
				'<span class="react-count">' + newCount + '</span>' +
				'</div>'
			);
		}

		$.ajax({
			type: 'post',
			url: '{{url("/my_react")}}',
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
			data: { type:type, react:react, request_type:requestType, post_id:postId, response_type:responseType },
			success: function(response){
				if (responseType == 'number') {
					$('#reactNumber' + postId + ' .appendNumber').html(response);
				} else {
					// Server confirms: update both sections with accurate data
					var parts = response.split('<hr>');
					$('#post_reacts'    + postId).html(parts[0]);
					$('#my_post_reacts' + postId).html(parts[1]);
				}
			}
		});
	}


	function myCommentReact(react, requestType, commentId){
		@if(!auth()->check())
			window.location.href = '{{ route("login") }}';
			return;
		@endif
		// Optimistic UI for comment reactions
		var colorClass = _reactColor[react] || 'like-color';
		var svg = (react === 'like') ? _reactSvg.liked : _reactSvg[react];
		var label = _reactLabel[react] || 'Like';
		$('#my_comment_reacts' + commentId).html('<div class="' + colorClass + '"><img class="reaction-icon-small" src="' + svg + '"> ' + label + '</div>');

		$.ajax({
			type: 'get',
			url: '{{url("/my_comment_react")}}',
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
			data: { react:react, request_type:requestType, comment_id:commentId },
			success: function(response){
				var parts = response.split('<hr>');
				$('#comment_reacts'    + commentId).html(parts[0]);
				$('#my_comment_reacts' + commentId).html(parts[1]);
			}
		});
	}

	function loadMoreComments(e, postId, parent_id, total_comments,type){
		if(parent_id == 0){
			var total_loaded_comments = $('#comments'+postId+' > li').length;
		}else{
			var total_loaded_comments = $('#child_comments'+parent_id+' > li').length;
		}

		$.ajax({
			type: 'get',
			url: '{{url("/load_post_comments")}}',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
			},
			data: {
				post_id:postId,
				parent_id:parent_id,
				total_loaded_comments:total_loaded_comments,
				type:type
			},
			success: function(response){
				if(parent_id == 0){
					$('#comments'+postId).append(response);

					total_loaded_comments = $('#comments'+postId+' > li').length;
					if(total_comments <= total_loaded_comments){
						$(e).hide();
					}
				}else{
					$('#child_comments'+parent_id).append(response);

					total_loaded_comments = $('#child_comments'+parent_id+' > li').length;
					if(total_comments <= total_loaded_comments){
						$(e).hide();
					}
				}
			}
		});
	}

	function post_privacy(privacy, e, postPrivacyDroupdownId, inputId){
        $('#'+inputId).val(privacy);
        $('#'+postPrivacyDroupdownId).html($(e).html());
    }

    function tagPeople(user_id, user_name){

        if($('#taggedUsers > #taggedUserLabel'+user_id).length > 0){
            $('#taggedUsers > #taggedUserLabel'+user_id).remove();
            $('#taggedUsers > #taggedUserId'+user_id).remove();
        }else{
            var label = '<a class="ms-2 my-2" id="taggedUserLabel'+user_id+'" onclick="tagPeople('+user_id+')" href="javascript:void(0)">'+user_name+'</a>';
            var inputField = '<input id="taggedUserId'+user_id+'" value="'+user_id+'" type="hidden" name="tagged_users_id[]">';

            $('#taggedUsers').append(label+inputField);
        }
    }

 $(document).on('click', '[data-tab]', function () {
    let tabId = $(this).data('tab');

    $('.post-inner').removeClass('current');
    $('#' + tabId).addClass('current');
});


function addFeelingActivity(id, title, icon, iconExt) {

    let iconHtml = '';

    if (['png','jpg','jpeg','ico'].includes(iconExt)) {
        iconHtml = `<img src="/storage/images/${icon}" style="width:20px">`;
    } else {
        iconHtml = `<i class="${icon}"></i>`;
    }

    // already selected → remove
    if ($('#feeling_and_activity_id').val() == id) {
        $('#feeling_and_activities').html('');
        $('#feeling_and_activity_id').remove();
        return;
    }

    let html = `
        <span class="badge bg-light text-dark p-2">
            ${iconHtml} <b>${title}</b>
            <a href="javascript:void(0)" onclick="removeFeeling()" class="ms-2 text-danger">×</a>
        </span>
        <input type="hidden" id="feeling_and_activity_id"
               name="feeling_and_activity_id" value="${id}">
    `;

    $('#feeling_and_activities').html(html);
}

function removeFeeling() {
    $('#feeling_and_activities').html('');
    $('#feeling_and_activity_id').remove();
}



    

    var timer = 0;
    function searchFriendsForTagging(e, showOn){

    	$('.suggestions-loaging-bar').removeClass('d-none');

        var searchValue = $(e).val();
        
        clearTimeout(timer);
        timer = setTimeout(function () {
            $.ajax({
				type: 'get',
				url: '{{url("/search_friends_for_tagging")}}',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
				},
				data:{'search_value':searchValue},
				success: function(response){
					$(showOn).html(response);
					if(!$('.suggestions-loaging-bar').hasClass('d-none')){
			    		$('.suggestions-loaging-bar').addClass('d-none');
			    	}
				}
			});
        } ,1000);
    }

    function confirmLiveStreaming(){
    	$('.alert-modal.custom-modal').addClass('custom-modal-show');
    	$('#alertContinueLink').attr('onclick', 'startLiveStreaming()');
    }
	
    function startLiveStreaming(){
    	$('#post_type').val('live_streaming');

    	setTimeout(function(){
    		$('#createPostForm').submit();
    	}, 500);
    }



	window.isSubmittingPost = false;

	// AJAX Post Submission with Progress Bar
	$(document).on('submit', '#createPostForm', function(e) {
	    e.preventDefault();

        // Frontend Duplicate Guard
        if (window.isSubmittingPost) {
            console.warn("Blocked duplicate post submission request");
            return;
        }
        window.isSubmittingPost = true;

	    var form = $(this);
	    var formData = new FormData(this);

        // Add UUID to form data to prevent duplicate backend processing
        var submissionId = 'post_' + new Date().getTime() + '_' + Math.random().toString(36).substr(2, 9);
        formData.append('submission_id', submissionId);

        // Disable publish button and show spinner
        var $submitBtn = form.find('button[type="submit"]');
        var originalBtnHtml = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Publishing...');

	    // Progress elements
	    let progressWrap = $('#uploadProgress');
	    let progressBar  = $('#uploadProgress .fg');
	    let percentText  = $('#uploadProgress .percent');
	    let percentLabel = $('#uploadProgress .percent-label');
	    let linearBar    = $('#uploadProgress .progress-bar');
	    let container    = $('.circular-progress');

	    // Reset and show progress if files are present
	    if ($('#multiFileUploader')[0].files.length > 0) {
	        progressWrap.removeClass('d-none');
	        container.removeClass('success error');
	        progressBar.css('stroke-dashoffset', '164');
	        percentText.text('0%');
	        percentLabel.text('0%');
	        linearBar.css('width', '0%').attr('aria-valuenow', 0);
	        
	        // Hide preview during upload
	        $('#media-preview-container').addClass('d-none');
	    }

	    $.ajax({
	        url: form.attr('action'),
	        type: 'POST',
	        data: formData,
	        processData: false,
	        contentType: false,
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
	        },
	        xhr: function () {
	            let xhr = new window.XMLHttpRequest();
	            xhr.upload.addEventListener("progress", function (evt) {
	                if (evt.lengthComputable) {
	                    let percent = Math.round((evt.loaded / evt.total) * 100);
	                    
	                    // Sync circular bar
	                    let offset = (1 - (percent / 100)) * 164;
	                    progressBar.css('stroke-dashoffset', offset);
	                    
	                    // Sync linear bar
	                    linearBar.css('width', percent + '%').attr('aria-valuenow', percent);
	                    
	                    // Sync labels
	                    percentText.text(percent + '%');
	                    percentLabel.text(percent + '% ' + "{{get_phrase('completed')}}");
	                }
	            }, false);
	            return xhr;
	        },
	        success: function (response) {
                window.isSubmittingPost = false;
                $submitBtn.prop('disabled', false).html(originalBtnHtml);

                var res = typeof response === 'string' ? (typeof response === 'string' ? JSON.parse(response) : response) : response;

                if (res.validationError || res.alertMessage === "Duplicate submission detected." || res.alertMessage === "Your post is already being processed. Please wait.") {
                    distributeServerResponse(res);
                    return;
                }

	            if ($('#multiFileUploader')[0].files.length > 0) {
	                container.addClass('success');
	                progressBar.css('stroke-dashoffset', '0');
	                percentText.text('✓');
	                percentLabel.text('100% ' + "{{get_phrase('completed')}}");
	                $('.upload-status-label').text("{{get_phrase('Success!')}} ");
	            }
	            
                resetCreatePostForm();
                distributeServerResponse(res);
                

	        },
	        error: function (xhr) {
                window.isSubmittingPost = false;
                $submitBtn.prop('disabled', false).html(originalBtnHtml);

	            container.addClass('error');
	            percentText.text('✕');
	            $('#media-preview-container').removeClass('d-none');
	            
	            let errorMsg = "{{get_phrase('Upload failed.')}} ";
	            if(xhr.responseJSON && xhr.responseJSON.message) {
	                errorMsg += xhr.responseJSON.message;
	            }
	            
	            $('.status-text').text(errorMsg).addClass('text-danger');
	            console.error("Post Upload Error:", xhr.responseText);
	        }
	    });
	});


	function copyToClipboard(id) {
        var el = document.getElementById(id);
        var text = el ? el.value : '';
        if (!text) return;

        if (navigator.clipboard && window.isSecureContext) {
            // Modern async clipboard API (works on HTTPS / localhost)
            navigator.clipboard.writeText(text).then(function() {
                alert_message("{{get_phrase('Link Copied')}}");
            }).catch(function() {
                _fallbackCopy(text);
            });
        } else {
            _fallbackCopy(text);
        }
    }

    function _fallbackCopy(text) {
        // Fallback for HTTP or older browsers
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try {
            document.execCommand('copy');
            alert_message("{{get_phrase('Link Copied')}}");
        } catch(e) {
            alert_message("{{get_phrase('Could not copy link')}}");
        }
        document.body.removeChild(ta);
    }


	// share module jquery
	
	$('#timelinePostBtn').click(function() {
		$('#timelinePostBtn').addClass('active-own-button');
		$('#groupPostButton').removeClass('active-own-button');
		$('#messageSendButton').removeClass('active-own-button');

		$('#timeline-content-area').removeClass('d-none');
		$('#timeline-content-area').addClass('d-block');
		$('#message-content-area').addClass('d-none');
		$('#group-content-area').addClass('d-none');

		$('#ShareButton').removeClass('d-none');
		$('#ShareButton').addClass('d-block');
	});

	$('#messageSendButton').click(function() {
		$('#messageSendButton').addClass('active-own-button');
		$('#groupPostButton').removeClass('active-own-button');
		$('#timelinePostBtn').removeClass('active-own-button');

		$('#message-content-area').removeClass('d-none');
		$('#message-content-area').addClass('d-block');
		$('#group-content-area').addClass('d-none');
		$('#timeline-content-area').addClass('d-none');
		$('#ShareButton').addClass('d-none');
		$('#ShareButton').removeClass('d-block');
	});


	$('#groupPostButton').click(function() {
		$('#groupPostButton').addClass('active-own-button');
		$('#messageSendButton').removeClass('active-own-button');
		$('#timelinePostBtn').removeClass('active-own-button');

		$('#group-content-area').removeClass('d-none');
		$('#group-content-area').addClass('d-block');
		$('#message-content-area').addClass('d-none');
		$('#timeline-content-area').addClass('d-none');

		$('#ShareButton').addClass('d-none');
		$('#ShareButton').removeClass('d-block');
	});

	function resetCreatePostForm() {
	    // Hide bootstrap modal reliably
        var modalEl = document.getElementById('createPost');
        if (modalEl) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    $('#createPost').modal('hide');
                }
            } else {
                $('#createPost').modal('hide');
            }
        }
        
        // Force cleanup just in case
        $('#createPost').removeClass('show');
        $('#createPost .btn-close').trigger('click');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('overflow', '').css('padding-right', '');
	    
	    // Reset the form
	    if ($('#createPostForm').length > 0) {
	        $('#createPostForm')[0].reset();
	    }
	    
	    // Reset the text area if simple text
	    $('#post_article').val('');
	    
	    // Clear uploader & preview
	    $('#multiFileUploader').val('');
	    $('#media-preview-container').addClass('d-none');
	    $('#media-preview-content').html('');
	    $('#uploadFileName').text('');
	    $('#uploadProgress').addClass('d-none');
	    
	    // Reset circular/linear upload progress
	    let progressBar  = $('#uploadProgress .fg');
	    let percentText  = $('#uploadProgress .percent');
	    let percentLabel = $('#uploadProgress .percent-label');
	    let linearBar    = $('#uploadProgress .progress-bar');
	    let container    = $('.circular-progress');
	    
	    if(container.length > 0) {
	        container.removeClass('success error');
	    }
	    if(progressBar.length > 0) {
	        progressBar.css('stroke-dashoffset', '164');
	    }
	    if(percentText.length > 0) {
	        percentText.text('0%');
	    }
	    if(percentLabel.length > 0) {
	        percentLabel.text('0%');
	    }
	    if(linearBar.length > 0) {
	        linearBar.css('width', '0%').attr('aria-valuenow', 0);
	    }
	}

	// When modal is hidden manually by close button or clicking outside
	$(document).on('hidden.bs.modal', '#createPost', function () {
	    resetCreatePostForm();
	});

</script>