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
        $posts = Post::latest('id')->get();
        return PostResource::collection($posts);
    }

    /**
     * Fetch and return the Post.
     *
     * @param Post $Post
     * @return PostResource
     */
    public function show($id)
    {
        // return new PostResource($post);
        $post = Post::findOrFail($id);
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
        $post = $request->isMethod('put') ? Post::findOrFail($request->post_id) : new Post;
        $post->name = $request->name;
        $post->body = $request->body;
        $post->post_type_id = $request->post_type_id;
        $post->status = $request->status;

        // $post = Post::create($post);
        if($post->save()) {
            return new PostResource($post);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get article
        $post = Post::findOrFail($id);
        if($post->delete()) {
            return new PostResource($post);
        }
    }
}
