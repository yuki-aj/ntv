@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- __('You are logged in!') --}}

                    {{--$msg--}}
                    
                    @if(isset($customer))
                    {{$customer->id}}<br>
                    {{$customer->email}}<br>
                    {{$customer->object}}<br>
                    {{$customer->source}}<br>
                    @endif
                    @if(isset($charge))<br>
                    {{$charge->id}}<br>
                    {{$charge->object}}<br>
                    {{$charge->amount}}<br>
                    {{$charge->customer}}<br>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection