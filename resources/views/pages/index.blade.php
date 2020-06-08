@extends('layouts.app')
@section('content')
    <div class="jumbotron text-center">
        <h1>{{$title}}</h1>
        <p>This is the Laravel application from the "Laravel from Scratch" Youtube series</p>
        @guest
            <p><a href="/login" class="btn btn-primary btn-large" role="button">Login</a> <a href="/register" class="btn btn-success btn-large" role="button">Register</a></p>
        @else
            <p><a href="/dashboard" class="btn btn-primary btn-large" role="button">Dashboard</a></p>
        @endguest
    </div>
@endsection