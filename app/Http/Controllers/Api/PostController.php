<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Event;
use App\PostCategory;
class PostController extends Controller
{
     /**
     * Return a paginated list of posts.
     *
     * @return PostResource
     */
    public function index()
    {
        $posts = Post::with('postCategory','event')->latest('id')->get();
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
        return Post::findOrFail($id);
        // return new PostResource($post);
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
        $post->save();
        if($post->post_type_id === 1){
            $event = $request->post_id != null ? Event::where('post_id', $post->id)->firstOrFail() : new Event;
            $event->address = $request->event['address'];
            $event->map_lat = $request->event['map_lat'];
            $event->map_lng = $request->event['map_lng'];
            $event->start = $request->event['start'];
            $event->end = $request->event['end'];
            $event->post_id = $post->id;
            $event->save();
        }
        return new PostResource($post);
        
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
