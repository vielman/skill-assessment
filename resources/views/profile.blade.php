@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>
                <div class="card-body">
                    <p>Name: {{ Auth::user()->name }}</p>
                    <p>Email: {{ Auth::user()->email }}</p> 
                    @if (Auth::user()->is_active == 1)
                    <p>Active: Yes</p>
                    @else
                    <p>Active: No</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
