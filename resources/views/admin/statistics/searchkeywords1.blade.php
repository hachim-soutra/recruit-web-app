@extends('admin.layout.app')

@section('title', "Search Keywords")

@section('mystyle')

@endsection

@section('content')

<section class="content-header">

    <h1>Search Keywords</h1>

    <ol class="breadcrumb">

        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>

        <li class="">Reports</li>

		<li class="active">Search Keywords</li>

    </ol>

</section>



<!-- =============Main Page====================== -->

<section class="content">

    <div class="box">

        <div class="row">

            <div class="box-body">
                <div class="col-md-5 col-xs-12">
                    <form class="form-inline" method="GET" action="">

						<div class="form-group">
							<label for="appdate">Year</label>
							&nbsp;&nbsp;
							<input type="date" max="<?php echo date('Y-m-d')?>" value="{{ @$appdate }}" class="form-control" id="appdate" name="appdate" disabled />
						</div>

						<button type="submit" class="btn btn-primary"  disabled>Submit</button>
                    </form>
                </div>

				<div class="col-md-3 col-xs-12"></div>
				
                <div class="col-md-4 col-xs-12">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control pull-right" placeholder="keyword" value="{{ @$keyword }}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-12">

            <div class="box">

				<!--

                <div class="box-header">

                    <h3 class="box-title">Candidates List</h3>

                </div>

				-->

                <div class="box-body table-responsive no-padding">
					
					<div style="display:none;">{{ $SQL }}</div>

					@if(count($data) > 0)

                    <table class="table table-hover">
                        <tr>
							<th style="width:150px;">Keywords</th>
							
							<td class="num text-center">Jan</td>
							<td class="num text-center">Feb</td>
							<td class="num text-center">Mar</td>
							<td class="num text-center">Apr</td>
							<td class="num text-center">May</td>
							<td class="num text-center">Jun</td>
							<td class="num text-center">Jul</td>
							<td class="num text-center">Aug</td>
							<td class="num text-center">Sep</td>
							<td class="num text-center">Oct</td>
							<td class="num text-center">Nov</td>
							<td class="num text-center">Dec</td>
							
							<th class="num text-center"><strong>TOTAL</strong></th>
						</tr>
						
                        @forelse ($data as $r)
						
							<tr>
							
								<td>{{ $r->keywords }}</td>
								
								<td class="num text-center">{{ $r->jan_total }}</td>
								<td class="num text-center">{{ $r->feb_total }}</td>
								<td class="num text-center">{{ $r->mar_total }}</td>
								<td class="num text-center">{{ $r->apr_total }}</td>
								<td class="num text-center">{{ $r->may_total }}</td>
								<td class="num text-center">{{ $r->jun_total }}</td>
								<td class="num text-center">{{ $r->jul_total }}</td>
								<td class="num text-center">{{ $r->aug_total }}</td>
								<td class="num text-center">{{ $r->sep_total }}</td>
								<td class="num text-center">{{ $r->oct_total }}</td>
								<td class="num text-center">{{ $r->nov_total }}</td>
								<td class="num text-center">{{ $r->dec_total }}</td>
								
								<td class="num text-center"><strong>{{ $r->total }}</strong></td>
							
							</tr>
						
						
						@empty

                            <tr><td colspan="14">No Record Found!</td></tr>

                        @endforelse

                    </table>

					@endif

                    <div class="mt-5">

                        

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



@endsection



@section('myscript')

<script>

jQuery(document).ready(function($) {

 
    //alert('hi');

});
</script>

@endsection