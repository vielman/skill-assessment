@extends('layouts.app')

@section('content')
<div class="container">
    <quote-component userid="{{ Auth::user()->id }}"/>
</div>
@endsection
