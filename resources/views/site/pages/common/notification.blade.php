@extends('site.layout.app')
@section('title', 'Notifications')
@section('mystyle')
<style>
    .pagination{
        display: flex;
        justify-content: center;
        width: 58%;
        margin-left: 21%;
    }
    .toplt {
        background-color: #ed1c24;
        border-radius: 5px;
        margin: 2px 4px 4px 2px;
        width: 35%;
        text-align: center;
        height: 30px;
        display: flow-root;
        justify-content: center;
        color: white;
        padding-top: 5px !important;
    }
</style>
@endsection
@section('content')
<!-- banner-block -->	

<!-- post-resume-block -->
	<div class="login-block">
		<div class="container">
			<div class="bd-block">
				<div class="row">   
                    <div class="section-titel text-center mb-4 mb-lg-5 w-100">
                        <h4 class="text-center">Notifications</h4>
                    </div>
				</div>
					<!-- item lt-->
					@forelse($data['notification'] as $n)
                    <div class="row" style="justify-content: center;padding: 7px;">
                        <div class="col-lg-7" style="border-radius: 10px;">
                            <div class="item-col">
                                <!--top-->
                                <div class="top" style="padding: 0 0 15px;">
                                    <div class="lt toplt"><i class="fa fa-briefcase"></i> {{ $n->title }}</div>
                                </div>
                                <!--top-->
                                <!-- middle -->
                                <div class="middle">
                                    <div class="gt"><i class="fa fa-user-o"></i> {{ $n->sender->name }} - <i class="fa fa-user"></i> {{ $n->receiver->name }}</div>
                                    <div class="lt"><i class="fa fa-calendar"></i> {{ date("M j, Y", strtotime($n->created_at)); }}</div>
                                </div>
                                <!-- middle -->
                                <div class="bottom">  
                                    {{str_replace('.', ' ', $n->body)}}.
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <h3 class="text-center" style="color:red;width: 100%;">No Data Found.</h3>
                    @endforelse
                    {{ $data['notification']->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
					<!-- item -->
				</div>
			</div>
		</div>
	</div>
<!-- post-resume-block -->

@endsection
@section('myscript')
<script>
	const APP_URL = '<?= env('APP_URL')?>';
</script>
@endsection