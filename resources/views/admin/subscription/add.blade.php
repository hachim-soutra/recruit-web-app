@extends('admin.layout.app')

@section('title', 'Manual Stripe Virement')

@section('mystyle')
    <style>
        .copy-link-button {
            cursor: pointer;
            font-size: 1.3rem;
            color: #EB1829;
        }

        .copy-link-button:hover {
            text-decoration: underline;
            color: #dd4b39;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>Add Manual Subscription (Virement)</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manual Virement</li>
        </ol>
    </section>



    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-warning">
                        @foreach ($errors->all() as $error)
                            <span class="d-block">{{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                <div class="box">
                    <div class="box-body">
                        <form action="{{ route('admin.stripe.virement') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="email">Customer Email</label>
                                    <input type="email" name="email" class="form-control" required placeholder="Email" value="{{ old('email') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="plan_id">Select Plan</label>
                                    <select name="plan_id" id="plan_id" class="form-control" required>
                                        <option value="">-- Choose a Plan --</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->plan->title }} ({{ $plan->price }} $) ({{ $plan->plan->job_number }} job slot{{ $plan->plan->job_number > 1 ? 's' : '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="Subscription description" value="{{ old('description') }}">
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Generate Payment</button>
                        </form>
                    </div>
                </div>

                @if (session('instructions'))
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Bank Transfer Instructions</h3>
                        </div>
                        <div class="box-body">
                            @php
                                $instructions = session('instructions');
                            @endphp
                            @if(isset($instructions['iban']) && $instructions['iban'])
                                <p><strong>IBAN:</strong> {{ $instructions['iban']['account_number'] ?? 'N/A' }}</p>
                                <p><strong>BIC:</strong> {{ $instructions['iban']['bic'] ?? 'N/A' }}</p>
                            @endif
                            <p><strong>Reference:</strong> {{ $instructions['reference'] ?? 'N/A' }}</p>
                            <p><strong>Amount:</strong> {{ number_format(($instructions['amount_remaining'] ?? 0) / 100, 2) }} {{ $instructions['currency'] ?? 'EUR' }}</p>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                <strong>Important:</strong> Please use the exact reference number when making the bank transfer to ensure proper payment processing.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('myscript')
    {{-- You can include any specific scripts here --}}
@endsection
