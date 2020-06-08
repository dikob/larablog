@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="/posts/create" class="btn btn-primary mb-1">Create Post</a>

                    @if (count($posts)>0)
                    <h3>Your Blog Posts</h3>
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                            <tr>
                                <td>{{$post->title}}</td>
                                <td><a href="/posts/{{$post->id}}/edit" class="btn btn-secondary">Edit</a></td>
                                <td>
                                    <form method="POST" action="/posts/{{$post->id}}" class="float-right">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn btn-danger" value="Delete" />
                                    </form>
                                </td>
                            </tr>    
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="mt-3">You don't have any post!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
