@extends('site.layout.app')
@section('title', 'Privacy Policy')
@section('mystyle')
<style>
	/*.bd-block{
		width: 66% !important;
	}*/
</style>
@endsection
@section('content')
<section class="cms-pagemain our-news-block bg-light-red">
	<div class="container">
		<div class="title-block mb-4">
           	 <h1>Privacy Policy</h1>
         </div>
    {!!$settings->privacy_policy!!}
      </div>
</section>
 
@endsection
@section('myscript')
@endsection
