<script type="text/javascript">
	"use strict";
	function alert_message(message) {
		if (typeof $.toast === 'function') {
			$.toast({
				content: message,
				position: "bottom-left"
			});
		} else {
			console.log("Toast message: " + message);
		}
	}
</script>

@php
    $message_keys = ['success_message', 'info_message', 'error_message'];
@endphp

@foreach($message_keys as $key)
    @if($message = session($key))
        <script>
            "use strict";
            $(document).ready(function() {
                alert_message({!! json_encode($message) !!});
            });
        </script>
        @php session()->forget($key); @endphp
    @endif
@endforeach