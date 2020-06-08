@extends('layouts.app')
{{-- This is from older version
@section('content')
    <h1>Create Post</h1>
    {!! Form::open(['action' => 'PostsController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Title'])}}
        </div>
        <div class="form-group">
            {{Form::label('body', 'Body')}}
            {{Form::textarea('body', '', ['id' => 'summary-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text'])}}
        </div>
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection
--}}

{{-- verion 7 --}}
@section('content')
    <h1>Create Post</h1>
    <form method="POST" action="/posts" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input name="title" type="text" class="form-control" placeholder="Title">
        </div>
        <div class="form-group">
            <label for="body">Body</label>
            <textarea name="body" class="form-control" id="summary-ckeditor" placeholder="Body Text"></textarea>
        </div>
        <div class="form-group">
            <input type="file" name="cover_image" id="cover_image">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection