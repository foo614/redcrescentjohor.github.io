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
use Analytics;
use Spatie\Analytics\Period;
class PostController extends Controller
{
     /**
     * Return a paginated list of posts.
     *
     * @return PostResource
     */
    public function index()
    {
        $collection = collect(Post::with('postCategory','event')->where('status', 1)->latest('id')->get())->map(function($post, $key){
            $visitorsData = Analytics::performQuery(Period::years(3), 'ga:pageviews', ['dimensions' => 'ga:pagePathLevel2', 'filters' => 'ga:pagePath=~/news-stories/;ga:pagePath!~fbclid']);
            $visitorsData = collect($visitorsData['rows'] ?? [])->map(function(array $dateRow){
                return [
                    'id' =>(int)trim($dateRow[0], "/"),
                    'total_view' => (int) $dateRow[1],
                ];
            });
            return[
                'id' => $post->id,
                'title' =>  $post->title,
                'cover_img' => $post->cover_img,
                'body' => $post->body,
                'status' => $post->status,
                'post_type_id' => $post->post_type_id ? $post->post_type_id : null,
                'post_category' => $post->postCategory ? $post->postCategory : null,
                'event' => $post->event ? $post->event : null,
                'created_at' => $post->created_at->format('F d, Y'),
                'from_now' => $post->created_at->diffForHumans(),
                'page_view' =>  $visitorsData->firstWhere('id', $post->id),
            ];
        });
        return $collection;
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
        if(! \File::exists(public_path('img/'.$request->get('cover_img'))))
        {
            $image = $request->get('cover_img');
            $image_name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('cover_img'))->save(public_path('img/').$image_name);
        }

        $post->title = $request->title;
        $post->body = $request->body ? $request->body : '';
        $post->post_type_id = $request->post_type_id;
        $post->status = $request->status;
        if(! \File::exists(public_path('img/'.$request->get('cover_img'))))
            $post->cover_img = $image_name;
        else if($request->get('cover_img') === null)
            $post->cover_img = null;
        $post->save();
        if($post->post_type_id === 1){
            $event = $request->post_id != null ? Event::where('post_id', $post->id)->firstOrFail() : new Event;
            $event->address = $request->address;
            $event->map_lat = $request->map_lat;
            $event->map_lng = $request->map_lng;
            // $event->start = !$request->start ? null : $request->start.= ':00';
            // $event->end = !$request->end ? null : $request->end.= ':00';
            $event->start = !$request->start ? null : $request->start;
            $event->end = !$request->end ? null : $request->end;
            $event->place_id = $request->place_id;
            $event->formatted_address = $request->formatted_address;
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

    public function latestEventDisplayHomePage()
    {
        $posts = Post::where('post_type_id', 4)->latest()->take(2)->get();
        return $posts;
    }
}
