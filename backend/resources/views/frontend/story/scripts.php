function createStoryForm(view_path) {
    // Show the modal first so the user sees something is happening
    $('#story-modal').modal('show');
    $('.modal-body').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div><p>Loading...</p></div>');

    $.ajax({
        type: 'POST',
        // This helper ensures the URL is correct for the Cloud environment
        url: "{{ route('load_bottom_modal') }}", 
        data: {
            view_path: view_path,
            _token: "{{ csrf_token() }}" // Essential for Cloud security
        },
        success: function(response) {
            $('.modal-body').html(response);
            // Re-initialize click events for the newly loaded HTML
        },
        error: function() {
            $('.modal-body').html('<div class="alert alert-danger">Error loading form. Please refresh.</div>');
        }
    });
}