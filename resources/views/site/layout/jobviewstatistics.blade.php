<div style="margin:10px 0;"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ $jobid }}</div>

<script>
jQuery(document).ready(function($) {
	
	setTimeout(function() {
		
		$.post( "{{ route('job-statistics-hits') }}", 
			{ 
				jobid: {{ $jobid }}, IP: "<?php echo $_SERVER['REMOTE_ADDR']; ?>", _token: "{{ csrf_token() }}"
			}
		).done(function(response) {
			console.log('job-statistics-hits');
			console.log(response);
		}, 'json');
			
		/*
		$.ajax({
            url: ,
            method: 'POST',
            data: { 'jobid' },
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success:function(response) {
                console.log(response);
            },
            error: function(response) {
				console.log(response);
            }
        });
		*/
		
	}, 3000);
});
</script>