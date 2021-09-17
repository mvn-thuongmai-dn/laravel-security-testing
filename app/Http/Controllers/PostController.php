<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        if (Gate::allows('create-post')) {
            $posts = [];
        }

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::none(['create-post', 'view-create-post'])) {
            abort(403);
        }
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
        if (Gate::none(['create-post', 'view-create-post'])) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => auth()->user()->id
        ]);

        flash()->stored();
        return redirect('/posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if (Gate::any(['create-post', 'view-all-post', 'view-create-post'])) {
            abort(403);
        }
        return view('posts.show')
            ->with('post', Post::where('id', $post->id)->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if (Gate::any(['create-post', 'view-all-post', 'view-create-post'])) {
            abort(403);
        }
        return view('posts.edit')
            ->with('post', Post::where('id', $post->id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (Gate::any(['create-post', 'view-all-post', 'view-create-post'])) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        Post::where('id', $post->id)
            ->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'user_id' => auth()->user()->id
            ]);

        flash()->updated();
        return redirect('/posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (Gate::any(['create-post', 'view-all-post', 'view-create-post'])) {
            abort(403);
        }

        $post = Post::where('id', $post->id);
        $post->delete();
        flash()->deleted();
        return redirect('/posts');
    }
}
