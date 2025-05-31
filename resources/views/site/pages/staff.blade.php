@extends('site.layout.app')
@section('title', 'About Us')
@section('mystyle')
@endsection
@section('content')
<!-- banner-block -->	
<div class="post-resume-one">
	<div class="container">
		<div class="bd-block">
			<div class="row">
				{!! 'satff' !!}
			</div>
		</div>
	</div>
</div>
@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
</script>
@endsection
