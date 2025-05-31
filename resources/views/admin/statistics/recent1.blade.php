@extends('admin.layout.app')

@section('title', "Recent Job Applications")

@section('mystyle')

@endsection

@section('content')

<section class="content-header">

    <h1>

        Recent Job Applications

    </h1>

    <ol class="breadcrumb">

        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>

        <li class="">Reports</li>

		<li class="active">Recent Job Applications</li>

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
							<label for="appdate">Application Date</label>
							&nbsp;&nbsp;
							<input type="date" max="<?php echo date('Y-m-d')?>" value="{{ @$appdate }}" class="form-control" id="appdate" name="appdate" />
						</div>

						<button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

				<div class="col-md-3 col-xs-12"></div>
				
                <div class="col-md-4 col-xs-12">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control pull-right" placeholder="Search by Job Title, Employer, Candidate name or email...." value="{{ @$keyword }}">
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

					@if(count($data) > 0)

                    <table class="table table-hover">

                        <tr>

                            <th style="width:60px;text-align:center;">SNo</th>
							
							<th>Date</th>

                            <th>Job Title</th>
                            
							<th>Employer</th>
							
							<th>Candidate Name</th>
							
							<th>Candidate Email</th>
							
							<th>Candidate Phone</th>

							<!--<th style="text-align:center;">Details</th>-->
                        </tr>
						
                        @forelse ($data as $d)

                            <tr>

								<td style="text-align:center;">{{ $loop->iteration }}</td>
								
								<td style="_text-align:center;">{{ $d->created_at }}</td>

								<td>
									<!--{{ $d->job_id }}-->
									<a target="_blank" href="{{ route('admin.job.show', $d->job_id) }}">{{ $d->job_title }}</a>
								</td>
								
								<td>{{ $d->company_name }}</td>
								
								<td>
									<a target="_blank" href="{{ route('admin.candidate.show', $d->candidate_id) }}">{{ $d->candidate_name }}</a>
								</td>
								
								<td>{{ $d->candidate_email }}</td>
								<td>{{ $d->candidate_mobile }}</td>

								<!--<td><?php print_r($d); ?></td>-->
							</tr>

                        @empty

                            <tr>

                                <td>No Record Found!</td>

                            </tr>

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

    

    $('select#provider_id').on('change', function() {

        $('select#employer_id').val(this.value);

        //alert( this.value );

    });

    

    $('select#employer_id').on('change', function() {

        $('select#provider_id').val(this.value);

        //alert( this.value );

    });

    

    //alert('hi');

});

</script>



@endsection