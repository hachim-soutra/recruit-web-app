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
<section class="cms-pagemain our-news-block bg-light-red">
	<div class="container">
		<div class="title-block mb-4">
           	 <h1>Terms of Use</h1>
         </div>
    	{!!$settings->term_of_use!!}
    </div>
</section>
@endsection
@section('myscript')
@endsection

