<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
     /**
     * Return a paginated list of posts.
     *
     * @return PostResource
     */
    public function index()
    {
        $posts = Post::latest()
            ->paginate(20);

        return PostResource::collection($posts);
    }

    /**
     * Fetch and return the Post.
     *
     * @param Post $Post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Validate and save a new signature to the database.
     *
     * @param Request $request
     * @return PostResource
     */
    public function store(Request $request)
    {
        $post = $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'body' => 'required|min:3'
        ]);

        $post = Post::create($post);

        return new PostResource($post);
    }
}
