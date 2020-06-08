<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use to delete a file in the public\storage folder
use Illuminate\Support\Facades\Storage;
use App\Post;
// this can be use to access auth::user in edit() method below
//use Illuminate\Support\Facades\Auth;
//use DB; use this only when not using eloquent; meaning when you're using plane sql query

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     * With access control for some pages
     * Meaning if you're not authorize
     * You can only view the blogs
     * But won't be able to delete, edit or update them
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$posts = DB::select('select * from posts'); use this when not using eloquent
        // possible syntax
        //$posts = Post::all();
        //$posts = Post::where('title', 'Post Two')->get(); using where clause
        //$posts = Post::orderBy('title', 'desc')->take(1)->get(); use this when using limit
        //$posts = Post::orderBy('title', 'desc')->get();
        $posts = Post::orderBy('created_at', 'desc')->paginate(3); // adding pagination; check the post.index page and find {{$posts->links()}}
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle File Upload
        if ($request->hasFile('cover_image')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store with timestamp to make sure no duplicate name upload
            // that would cause overwriting existing similar filename
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }else {
            $fileNameToStore = 'noimage.jpg';
        }

        // create a post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);
        // redirect if post does not exist yet
        if (!isset($post)) {
            return redirect('/posts')->with('error', 'Unauthorized Access');
        }
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::find($id);
        // make sure that only the current user can edit his/her own posts
        // if not will redirect them to /post
        //if ((Auth::user()->id != $post->user_id)) { option 1
        if (!isset($post->user_id) || (auth()->user()->id != $post->user_id)) {
            return redirect('/posts')->with('error', 'Unauthorized Access');
            exit;
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        // create a post; this should be here to address the delete previous post's image
        $post = Post::find($id);

        // Handle File Upload
        if ($request->hasFile('cover_image')) {

            // Need to delete previous post's image to avoid duplicate images
            if ($post->cover_image != 'noimage.jpg') {
                // Delete image
                Storage::delete('public/cover_images/' . $post->cover_image);
            }

            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store with timestamp to make sure no duplicate name upload
            // that would cause overwriting existing similar filename
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if ($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        // make sure that only the current user can delete his/her own posts
        // if not will redirect them to /post
        // if ((Auth::user()->id != $post->user_id)) { option 1
        if ((auth()->user()->id != $post->user_id)) {
            return redirect('/posts')->with('error', 'Unauthorized Access');
            exit;
        }

        if ($post->cover_image != 'noimage.jpg') {
            // Delete image
            Storage::delete('public/cover_images/' . $post->cover_image);
        }

        $post->delete();

        return redirect('/posts')->with('success', 'Post Removed');
    }
}
