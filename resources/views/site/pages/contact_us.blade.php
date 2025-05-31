@extends('site.layout.app')
@section('title', 'Terms Of Use')
@section('mystyle')
<style>
	/*.bd-block{
		width: 66% !important;
	}*/
</style>
@endsection
@section('content')
	<section class="contactmain our-news-block bg-light-red">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4 mb-4">
					<div class="content-widget tac-crxpo align-items-start">
		                <div class="number mt-1"><i class="fa fa-envelope"></i></div>
						<div class="box-content">
							<h5>E-MAIL</h5>
							@if($settings->contact_email != '')
							<p><a href="mailto:{{$settings->contact_email}}">{{$settings->contact_email}}</a></p>
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4 mb-4">
					<div class="content-widget tac-crxpo align-items-start">
		                <div class="number mt-1"><i class="fa fa-phone"></i></div>
						<div class="box-content">
							<h5>PHONE</h5>
							@if($settings->mobile_no != '')
							<p><a href="tel:{{$settings->mobile_no}}">01 215 0518
							</a></p>
							@endif
							@if($settings->alt_mobaile_no != '')
							<p><a href="tel:{{$settings->alt_mobaile_no}}">087 646 3175</a></p>
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4 mb-4">
					<div class="content-widget tac-crxpo align-items-start">
		                <div class="number mt-1"><i class="fa fa-map-marker"></i></div>
						<div class="box-content">
							<h5>ADDRESS</h5>
							<p>61 Lower Kilmacud Road, Stillorgan, Co. Dublin, A94 A2F7, Ireland</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<iframe class="mape-main" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d4770.135098874851!2d-6.20172!3d53.288325!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4867091ddab2842f%3A0x9c3bdbf708f9cbea!2sProvidence%20House%2C%20Lower%20Kilmacud%20Rd%2C%20Stillorgan%2C%20Dublin%2C%20Ireland!5e0!3m2!1sen!2sus!4v1661420799168!5m2!1sen!2sus" width="100%" height="" style="border:0;" allowfullscreen="" loading="lazy">
					</iframe>
				</div>
				<div class="col-lg-6">
					<div class="contactform-main">
						<h2>Got A Questions</h2>
						<form name="contact-form" action="{{ route('contact_us.store') }}" method="POST" id="contact-form" class="bd_contform" >
							@csrf
							<div class="form-group">
								<input type="text" class="form-control" id="name" name="name" required placeholder="Name *">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="email" name="email" placeholder="Email *" required>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone No *" required>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Subject *"  name="subject" id="subject" required>
							</div>
							<div class="form-group">
								<textarea placeholder="Your Message *" name="sms_body" id="sms_body" required></textarea>
							</div>
							<div class="form-group mb-0">
								<input class="btn btton-bllu" type="submit" value="Submit"/>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="newsmain">
		<div class="container">
			

		</div>
	</section>
@endsection
@section('myscript')
<script>
AOS.init({
		duration: 1000
	});
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	$(document).ready(function () {
		var auth_status = 0;
		toastr.options = {
		"closeButton": true,
		"debug": false,
		"newestOnTop": false,
		"progressBar": true,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}

	function printErrorMsg(error) {
		$.each(error, function (key, value) {
			$('#' + key).addClass('is-invalid');
			$('#' + key).parent().append(
				'<div class="invalid-feedback">' +
				value +
				'</div>');

		});
	}

});

function printError(errorData) {
	$.each(errorData, function(key, value) {
		if (key === 'roles') {
			$('.role').addClass("is-invalid");
			$('.role').parent().parent().append('<div class="text-danger role-error">' + value[0] + '</div>');
		}else {
			$("#" + key).addClass("is-invalid");
			$("#" + key).parent().append('<div class="invalid-feedback">' + value[0] + '</div>');
		}
	});
}


</script>
<script>
	$(document).ready(function () {
		$('#contact-form').on('submit', function (e) {
			e.preventDefault();
			var data = new FormData(this);
			$.ajax({
				type: "POST",
				url: $(this).attr('action'),
				data: new FormData(this),
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function () {
					$(".form_group").removeClass("has-error");
					$(".invalid-feedback").remove();
				},
				complete: function () {},
				success: function (response) {
					//console.log(response);
					if (response.data.status === "validation_error") {
						printError(response.data.message);

					} else if (response.data.status === "error") {
						toastr.success(response.data.message);
					} else {
						toastr.success(response.data.message);
						location.reload();
					}
				},
				error: function (error) {
					console.log(error);

				},
			})
		});

	});
	</script>
@endsection
