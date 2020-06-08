@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-primary">Go back</a>
    <h1>{{$post->title}}</h1>
    <img style="width: 100%" src="/storage/cover_images/{{$post->cover_image}}" />
    <br/><br/>
    <div>
        {!!$post->body!!}
    </div>
    <hr>
    <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>

    @if(!Auth::guest())
        @if(Auth::user()->id == $post->user_id)
        <hr>
        <a href="/posts/{{$post->id}}/edit" class="btn btn-primary">Edit</a>
        
        {{-- This is from older version
        {!! Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'float-right']) !!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!! Form::close() !!}
        --}}
        
        <form method="POST" action="/posts/{{$post->id}}" class="float-right">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="Delete" />
        </form>
        @endif
    @endif

@endsection