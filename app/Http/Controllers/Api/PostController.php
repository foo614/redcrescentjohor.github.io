<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\EventResource;
use App\Event;
use App\PostCategory;
use Illuminate\Support\Facades\Storage;
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
        // Post::findOrFail($id);
        return new PostResource(Post::findOrFail($id));
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
        \Log::info($request->all());
        if($request->get('cover_img'))
        {
            $image = $request->get('cover_img');
            $image_name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('cover_img'))->save(public_path('img/').$image_name);
        }
        $post->name = $request->name;
        $post->body = $request->body ? $request->body : '';
        $post->post_type_id = $request->post_type_id;
        $post->status = $request->status;
        if($request->get('cover_img'))
            $post->cover_img = $image_name;
        $post->save();
        if($post->post_type_id === 1){
            $event = $request->post_id != null ? Event::where('post_id', $post->id)->firstOrFail() : new Event;
            $event->address = $request->address;
            $event->map_lat = $request->map_lat;
            $event->map_lng = $request->map_lng;
            $event->start = $request->start === "Invalid date" ? null : $request->start;
            $event->end = $request->end === "Invalid date" ? null : $request->end;
            $event->post_id = $post->id;
            $event->save();
        }
        return new PostResource([$post, $event]);
        
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

    public function showEvent()
    {
        $posts = Post::with('event')->where('post_type_id', 1)->get();
        return EventResource::collection($posts);
    }

    public function showUpcomingEvent()
    {
        $posts = Post::with('event')->where('post_type_id', 1)->whereHas('event', function ($query){
            $query->where('start', '>=', Carbon::now());
        })->get()->sortBy('event.start');;
        return EventResource::collection($posts);
    }
}
