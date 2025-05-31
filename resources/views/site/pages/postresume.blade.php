@extends('site.layout.app')
@section('title', 'Resume')
@section('mystyle')

@endsection
@section('content')
<!-- banner-block -->	
<style>
	.js-example-basic-multiple {
		width: 300px !important;
	}
	.skillsubmit{
		border-radius: 25px;
		height: 32px;
		background-color: white;
		color: black;
		font-size: smaller;
	}
	.edusubmit{
		border-radius: 25px;
		height: 32px;
		background-color: white;
		color: black;
		font-size: smaller;
	}
	.langsubmit{
		border-radius: 25px;
		height: 32px;
		background-color: white;
		color: black;
		font-size: smaller;
	}
	.spantext{
		color: #eb1829 !important;
	}
</style>
<!-- post-resume-block -->
	<div class="post-resume-block">
		<div class="container">
			<div class="bd-block">

				<!-- item -->
					<div class="col-top">
						<div class="figure">
							<img src="{{ asset('frontend/images/icon11.png') }}">
						</div>
						<div class="text">
							<h4>{{ $logindata['name'] }}</h4>
							<p>{{ $logindata['email'] }}</p>
							<a href="#">Edit</a>
						</div>
					</div>
				<!-- item -->

				<!-- item -->
				<div class="item-headline">
					<h4>Headline</h4>
					<p>It's the very first thing clients see, so make it count. Stand out by describing your expertise in your own words.</p>
					<form>
						<input type="text" placeholder="Example: UI/UX Designer" name="">
					</form>
				</div>
				<!-- item -->

				<!-- item -->
				<div class="upload-block">
					<h5>Upload your CV</h5>
					<p>Upload DOCS / PDF up to 1 MB</p>
					<div class="btn-wrapper">
						<button class="btn">Upload</button>
						<input type="file" name="myfile" />
					</div>
				</div>
				<!-- item -->

				<!-- item -->
				<div class="upload-block">
					<h5>Upload your Cover Letter</h5>
					<p>Upload DOCS / PDF up to 1 MB</p>
					<div class="btn-wrapper">
						<button class="btn">Upload</button>
						<input type="file" name="myfile" />
					</div>
				</div>
				<!-- item -->

				<!-- work-experience -->
				<div class="work-experience">
					<!-- item -->
					<div class="item">
						<div class="top-text">
							<div class="txt">
								<h4>Work experience</h4>
								<span>Share details about jobs you've worked</span>
							</div>
							<div class="btn-block">
								<a href="#">Add</a>
							</div>
						</div>

						<!---->
						<div class="col-sec">
							<div class="top-sec">
								<a href="#"></a>
								<p>Junior web & graphic designer</p>
							</div>
							<div class="b-txt">
								<p>TCS</p>
								<p>Sept 11 - Nov- 16</p>
							</div>
						</div>
						<!---->

						<!---->
						<div class="col-sec">
							<div class="top-sec">
								<a href="#"></a>
								<p>Junior web & graphic designer</p>
							</div>
							<div class="b-txt">
								<p>Webel Technology</p>
								<p>Sept 11 - Nov- 16</p>
							</div>
						</div>
						<!---->
					</div>
					<!-- item -->
					<!-- skills  -->
					<!-- item -->
					<div class="item">
						<div class="top-text">
							<div class="txt">
								<h4>Skill</h4>
								<?php 
									$skill_ids = $str_skills = '';
									for($i = 0;$i < count($data['userSkill']);$i++):
										$str_skills .= $data['userSkill'][$i]->skill->name;
										$skill_ids .= $data['userSkill'][$i]->skill->id;
										if($i < (count($data['userSkill'])-1)):
											$str_skills .= ', ';
											$skill_ids .= ', ';
										endif;
									endfor; 
								?>
								<span class="spantext">{{ @$str_skills }}</span>
								<span>Share details about jobs you've worked</span>
							</div>
							<div class="btn-block skillbtn">
								<a href="javascript:void(0);">Add</a>
							</div>
						</div>
						<br>
						<div class="row addsection_skill">
							<div class="col-md-3" style="max-width: 21% !important;">
								<span>Add Skills</span>
							</div>
							<div class="col-md-6" style="max-width: 58% !important;">
								<select class="js-example-basic-multiple skillmultiple" name="skill[]" multiple="multiple">
									<option disabled readonly>Choose a skill</option>
									@foreach($data['skills'] as $sk)
										<option value="{{ $sk->id }}">{{ $sk->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-3" style="text-align: end;max-width: 21% !important;">
								<button type="button" class="btn btn-success skillsubmit"><i class="fa fa-send-o"></i>&nbsp;&nbsp;Submit</button>
							</div>
						</div>
					</div>
					<!-- item -->

					<!-- item -->
					<div class="item">
						<div class="top-text">
							<div class="txt">
								<h4>Education</h4>
								<?php 
									$edu_ids = $str_edus = '';
									for($i = 0;$i < count($data['userEdu']);$i++):
										$str_edus .= $data['userEdu'][$i]->qualification->name;
										$edu_ids .= $data['userEdu'][$i]->qualification->id;
										if($i < (count($data['userEdu'])-1)):
											$str_edus .= ', ';
											$edu_ids .= ', ';
										endif;
									endfor; 
								?>
								<span class="spantext">{{ $str_edus }}</span>
								<span>Add your licenses, degrees, and certificates.</span>
							</div>
							<div class="btn-block edubtn">
								<a href="javascript:void(0);">Add</a>
							</div>
						</div>
						<br>
						<div class="row addsection_edu">
							<div class="col-md-3" style="max-width: 21% !important;">
								<span>Add Education</span>
							</div>
							<div class="col-md-6" style="max-width: 58% !important;">
								<select class="js-example-basic-multiple edumultiple" name="education[]" multiple="multiple">
									<option disabled readonly>Choose a skill</option>
									@foreach($data['qualification'] as $q)
										<option value="{{ $q->id }}">{{ $q->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-3" style="text-align: end;max-width: 21% !important;">
								<button type="button" class="btn btn-success edusubmit"><i class="fa fa-send-o"></i>&nbsp;&nbsp;Submit</button>
							</div>
						</div>
					</div>
					<!-- item -->

					<!-- item -->
					<div class="item">
						<div class="top-text">
							<div class="txt">
								<h4>Languages</h4>
								<span>Add your Known Languages.</span>
							</div>
							<div class="btn-block langbtn">
								<a href="javascript:void(0);">Add</a>
							</div>
						</div>
						<br>
						<div class="row addsection_lang">
							<div class="col-md-3" style="max-width: 21% !important;">
								<span>Add Languages</span>
							</div>
							<div class="col-md-6" style="max-width: 58% !important;">
								<select class="js-example-basic-multiple langmultiple" name="language[]" multiple="multiple">
									<option disabled readonly>Choose a skill</option>
									@foreach($data['language'] as $q)
										<option value="{{ $q->id }}">{{ $q->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-3" style="text-align: end;max-width: 21% !important;">
								<button type="button" class="btn btn-success langsubmit"><i class="fa fa-send-o"></i>&nbsp;&nbsp;Submit</button>
							</div>
						</div>
						<!---->
					</div>
					<!-- item -->
				</div>
				<!-- work-experience -->
			</div>
		</div>
	</div>
<!-- post-resume-block -->

@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
	$(document).ready(function() {
		skillselect2();
		eduselect2();
		langselect2();
	});
	function skillselect2(){
		$('.skillmultiple').select2({placeholder: "Select your skills."});
		$('.addsection_skill').hide(); 
		$('.skillbtn').html('<a href="javascript:void(0);">Add</a>');
		$('.skillbtn').removeAttr('onclick', 'plusSkillcollapse()');
		$('.skillbtn').on('click', function(){
			$('.addsection_skill').show();
			$(this).html('<a href="javascript:void(0);">collapse</a>');
			$(this).attr('onclick', 'plusSkillcollapse()');
			setSkillsValue();
		});
	}
	function eduselect2(){
		$('.edumultiple').select2({placeholder: "Select your Education."});
		$('.addsection_edu').hide(); 
		$('.edubtn').html('<a href="javascript:void(0);">Add</a>');
		$('.edubtn').removeAttr('onclick', 'plusEducollapse()');
		$('.edubtn').on('click', function(){
			$('.addsection_edu').show();
			$(this).html('<a href="javascript:void(0);">collapse</a>');
			$(this).attr('onclick', 'plusEducollapse()');
			setEdusValue();
		});
	}
	function langselect2(){
		$('.langmultiple').select2({placeholder: "Select your Langcation."});
		$('.addsection_lang').hide(); 
		$('.langbtn').html('<a href="javascript:void(0);">Add</a>');
		$('.langbtn').removeAttr('onclick', 'plusLangcollapse()');
		$('.langbtn').on('click', function(){
			$('.addsection_lang').show();
			$(this).html('<a href="javascript:void(0);">collapse</a>');
			$(this).attr('onclick', 'plusLangcollapse()');
			// setLangsValue();
		});
	}
	function plusSkillcollapse(){
		$('.addsection_skill').hide();
		$('.skillmultiple').val('').trigger('change.select2'); 
		$('.skillbtn').removeAttr('onclick', 'plusSkillcollapse()');
		$('.skillbtn').html('<a href="javascript:void(0);">Add</a>');
	}
	function plusEducollapse(){
		$('.addsection_edu').hide();
		$('.edumultiple').val('').trigger('change.select2'); 
		$('.edubtn').removeAttr('onclick', 'plusEducollapse()');
		$('.edubtn').html('<a href="javascript:void(0);">Add</a>');
	}
	function plusLangcollapse(){
		$('.addsection_lang').hide();
		$('.langmultiple').val('').trigger('change.select2'); 
		$('.langbtn').removeAttr('onclick', 'plusLangcollapse()');
		$('.langbtn').html('<a href="javascript:void(0);">Add</a>');
	}
	$('.skillsubmit').on('click', function(){
		var skills=[]; 
		$('select[name="skill[]"] option:selected').each(function() {
			skills.push($(this).val());
		});
		let ajax_url = APP_URL+'/resume-ajax';
		let data = {_token: '{{ csrf_token() }}', skills: skills, queryfor: 'skill', userid: "{{ $logindata['id'] }}"};
		let response = commomAjax(ajax_url, data, 'Skills Added.');
		if(response){
			skills=[];
		}
	});
	$('.edusubmit').on('click', function(){
		var edus=[]; 
		$('select[name="education[]"] option:selected').each(function() {
			edus.push($(this).val());
		});
		let ajax_url = APP_URL+'/resume-ajax';
		let data = {_token: '{{ csrf_token() }}', edus: edus, queryfor: 'education', userid: "{{ $logindata['id'] }}"};
		let response = commomAjax(ajax_url, data, 'Education Qualification Added.');
		if(response){
			edus=[];
		}
	});
	$('.langsubmit').on('click', function(){
		var langs=[]; 
		$('select[name="language[]"] option:selected').each(function() {
			langs.push($(this).val());
		});
		let ajax_url = APP_URL+'/resume-ajax';
		let data = {_token: '{{ csrf_token() }}', langs: langs, queryfor: 'language', userid: "{{ $logindata['id'] }}"};
		let response = commomAjax(ajax_url, data, 'Languages Added.');
		if(response){
			langs=[];
		}
	});
	function setSkillsValue(){
		let skillsid = '[{{$skill_ids}}]';
		$(".skillmultiple").select2().val(eval(skillsid)).trigger('change.select2');
	}
	function setEdusValue(){
		let edusid = '[{{$edu_ids}}]';
		$(".edumultiple").select2().val(eval(edusid)).trigger('change.select2');
	}
	/* common ajax */
	function commomAjax(ajax_url, data, msg){
		let ajaxresponse = false;
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: data,
			success: function(res){
				if(res == true){
					toastr.success(msg);
					if(data.queryfor == 'skill'){
						plusSkillcollapse();
					}
					if(data.queryfor == 'education'){
						plusEducollapse();
					}
					if(data.queryfor == 'language'){
						plusLangcollapse();
					}
				}
				ajaxresponse = true;
			},
			error: function(err){
				console.log(err);
				ajaxresponse = false;
			}
		});
		return ajaxresponse;
	}
</script>
@endsection
