<script type="text/javascript">
	"use strict";
	function storyType(e, type){
        $('.file-type-story').removeClass('border');
        $('.text-type-story').removeClass('border');
        

        if(type == 'file-type-story'){
            $('.text-story-form').hide();
            $('.text-story-submit').hide();
            $('.file-story-form').show();
            $('.file-story-submit').show();
        }else{
            $('.file-story-form').hide();
            $('.file-story-submit').hide();
            $('.text-story-form').show();
            $('.text-story-submit').show();
        }
        $('.'+type).addClass('border');
    }

    function selectColor(color){
        $('.bg-color-input-field').val(color);
        $('.color-input-field').val('fff');
        $('.input-prev-text').css({backgroundColor: '#'+color});
        $('.input-prev-text').css({color: '#fff'});
    }

   "use strict";

function createStory(formClass){

    // 1) TEXT STORY: normal submit, no progressbar
    if (formClass === 'text-story-form') {
        $('.text-story-form')[0].submit();
        return;
    }

    // 2) FILE STORY: AJAX + circular progress bar
    if (formClass === 'file-story-form') {

        // Validate file selected
        let fileInput = document.getElementById('file-story-input');
        if (!fileInput || !fileInput.files.length) {
            alert('Please select a photo or video first.');
            return;
        }

        let form      = $('.file-story-form')[0];
        let formData  = new FormData(form);

        let progressWrap = $('#uploadProgress');
        let progressBar  = $('#uploadProgress .fg');
        let percentText  = $('#uploadProgress .percent');
        let percentLabel = $('#uploadProgress .percent-label');
        let linearBar    = $('#uploadProgress .progress-bar');
        let container    = $('.circular-progress');

        // Reset and show progress
        progressWrap.removeClass('d-none');
        container.removeClass('success error');
        progressBar.css('stroke-dashoffset', '164'); // Circumference for r=26
        percentText.text('0%');
        percentLabel.text('0%');
        linearBar.css('width', '0%').attr('aria-valuenow', 0);

        // Hide preview during upload to focus on progress
        $('#story-preview-container').addClass('d-none');

        $.ajax({
            url: "<?php echo route('create_story'); ?>",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,

            xhr: function () {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (e) {
                    if (e.lengthComputable) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        
                        // Sync circular bar (offset calculation for r=26)
                        let offset = (1 - (percent / 100)) * 164;
                        progressBar.css('stroke-dashoffset', offset);
                        
                        // Sync linear bar
                        linearBar.css('width', percent + '%').attr('aria-valuenow', percent);
                        
                        // Sync labels with "X% upload"
                        percentText.text(percent + '%');
                        percentLabel.text(percent + '% upload');
                    }
                });
                return xhr;
            },

            success: function () {
                container.addClass('success');
                progressBar.css('stroke-dashoffset', '0');
                percentText.text('✓');
                percentLabel.text('100% uploaded');
                $('.upload-status-label').text('Success! ');

                // Reload after short delay
                setTimeout(function () {
                    window.location.reload();
                }, 1200);
            },

            error: function (xhr) {
                container.addClass('error');
                percentText.text('✕');
                $('#story-preview-container').removeClass('d-none');
                
                let errorMsg = "Upload failed. ";
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else if(xhr.status == 413) {
                    errorMsg += "File is too large for the server.";
                }
                
                $('.status-text').text(errorMsg).addClass('text-danger');
                console.error("Story Upload Error:", xhr.responseText);
            }
        });
    }
}

// File Preview Logic
$(document).on('change', '#file-story-input', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        const fileType = file.type;
        const fileName = file.name;

        $('#uploadFileName').text(fileName);
        $('#story-preview-container').removeClass('d-none');
        $('.file-story-form textarea').removeClass('d-hidden'); // Show description for files too

        reader.onload = function(e) {
            let html = '';
            if (fileType.startsWith('image/')) {
                html = `<img src="${e.target.result}" alt="Preview">`;
            } else if (fileType.startsWith('video/')) {
                html = `
                    <video muted loop autoplay>
                        <source src="${e.target.result}" type="${fileType}">
                        Your browser does not support the video tag.
                    </video>`;
            }
            $('#story-preview-content').html(html);
        };
        reader.readAsDataURL(file);
    }
});

function removeStoryFiles() {
    $('#file-story-input').val('');
    $('#story-preview-container').addClass('d-none');
    $('#story-preview-content').html('');
    $('#uploadFileName').text('');
    $('.file-story-form textarea').addClass('d-hidden').val('');
}



    function story_privacy(privacy, e){
        $('.story_privacy').val(privacy);
        $('#privacyDroupdownBtn').html($(e).html());
    }

    
	$(document).ready(function(){
		var owl = $('#storiesSection');
		owl.on('translate.owl.carousel', function(event) {
			var offSet = (event.item.count-1);
			loadStory(offSet);
		})

		function loadStory(offSet){
			var url = "<?php echo url('/stories'); ?>/"+offSet;
			$.ajax({
				type: 'get',
				url: url,
				success: function(response){
					if(response.length > 3){
						const myArray = response.split('<div class="devider"></div>');

						myArray.forEach(myFunction);
						 
						function myFunction(item, index) {
							if(item.length > 3){
								$('#storiesSection').owlCarousel().trigger('add.owl.carousel', [jQuery('<div class="owl-item">' + item + '</div>')]);
								$('#storiesSection').trigger('refresh.owl.carousel');
							}
						}
					}

				}
			});
		}
	});


	function loadStoryDetailsOnModal(story_id){
		$('#story-modal').modal('show');
		
		var url = "<?php echo url('/story_details'); ?>/"+story_id;
		$.ajax({
			type: 'get',
			url: url,
			success: function(response){
				if(response){
					$('#story-modal .modal-body').html(response);
				}
				$('.timeline-carousel').owlCarousel({
			        loop: false,
			        autoplay:false,
			        autoplayHoverPause:true,
			        margin: 0,
			        dots: false,
			        nav: true,
			        responsiveClass: true,
			        responsive: {
			        	0: {
			                items: 1,
			            },
			            300: {
			                items: 1,
			            },
			            400: {
			                items: 2,
			            },
			            550: {
			                items: 3,
			            },
			            600: {
			                items: 3,
			            },
			            1000: {
			                items: 3,
			            }
			        }
			    });

			    $('.story-gallery').owlCarousel({
			        loop: false,
			        autoplay:false,
			        autoplayHoverPause:true,
			        margin: 10,
			        dots: false,
			        nav: true,
			        items: 1,
			    });

			    $('.st-child-gallery').owlCarousel({
			        loop: true,
			        autoplay:true,
			        autoplayHoverPause:true,
			        margin: 10,
			        dots: true,
			        nav: false,
			        items: 1,
			    });
			}
		});
	}

	function loadSingleStoryDetailsOnModal(story_id, e){
		$('.story-entry.active').removeClass('active');
		$(e).addClass('active');
		
		var url = "<?php echo url('/single_story_details'); ?>/"+story_id;
		$.ajax({
			type: 'get',
			url: url,
			success: function(response){
				if(response){
					$('#stg-wrap-story-gallery').remove();
					$('#story-modal .modal-body').append(response);
				}

			    $('.story-gallery').owlCarousel({
			        loop: false,
			        video: true,
			        autoplay:false,
			        autoplayHoverPause:true,
			        margin: 10,
			        dots: false,
			        nav: true,
			        items: 1,
			    });

			    $('.st-child-gallery').owlCarousel({
			        loop: true,
			        video: true,
			        autoplay:true,
			        autoplayHoverPause:true,
			        margin: 10,
			        dots: true,
			        nav: false,
			        items: 1,
			    });
			}
		});
	}

	$(function(){
		$('#storiesSection').removeClass('invisible');
	});
</script>
