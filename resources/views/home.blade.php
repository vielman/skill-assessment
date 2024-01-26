@extends('layouts.app')

@section('content')
<div class="container">
    @if (Auth::user()->is_admin)
        <users-component />
    @else
        <quote-component userid="{{ Auth::user()->id }}"/>
    @endif
    
</div>
@endsection
