@extends('layouts.app')
@section('title', 'Home')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Bem-vindo!</h4>
    </div>
    <div class="card-body">
        <a href="{{ route('prepare.login') }}">Login</a>
    </div>
</div>
@endsection
