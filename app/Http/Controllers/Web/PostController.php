<?php

namespace App\Http\Controllers\Web;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Web\StorePostRequest;
use App\Http\Requests\Web\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        $postsQuery = Post::query()->orderBy('created_at', 'desc');

        if ($query) {
            $postsQuery->where('title', 'like', '%' . $query . '%');
        }

        $posts = $postsQuery->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        Auth::user()->posts()->create($request->all());

        return redirect()->route('posts');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {

        $post = Post::findOrFail($id);
        if ($this->checkAuthor($post)) {
            $post->update($request->all());
        } else {
            return redirect()->route('posts');
        }

        return redirect()->route('posts');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if ($this->checkAuthor($post)) {
            $post->delete();
        }

        return redirect()->route('posts');
    }

    public function checkAuthor($post)
    {
        return auth()->user()->id === $post->user_id;
    }
}
