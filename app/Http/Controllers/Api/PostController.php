<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\StorePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('query');

        $postsQuery = Post::query()->orderBy('created_at', 'desc');
        if ($title) {
            $postsQuery->where('title', 'like', '%' . $title . '%');
        }

        // Get the filtered posts and return as a resource collection
        $posts = $postsQuery->get();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = Auth::user()->posts()->create($request->all());

        return response()->json([
            'message' => 'Post created successfully!',
            'data' => new PostResource($post),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ]);
        }

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ]);
        }
        if ($this->checkAuthor($post)) {
            $post->update($request->all());
        } else {
            return response()->json([
                'message' => 'no access'
            ]);
        }

        return response()->json([
            'message' => 'Post updated successfully!',
            'data' => new PostResource($post),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ]);
        }
        if ($this->checkAuthor($post)) {
            $post->delete();
        } else {
            return response()->json([
                'message' => 'no access'
            ]);
        }

        return response()->json([
            'message' => 'Post deleted successfully!',
        ]);
    }

    public function checkAuthor($post)
    {
        return Auth::user()->id === $post->user_id;
    }
}
