<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PostCategory;
use App\Http\Resources\PostCategoryResource;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post_categories = PostCategory::latest('created_at')->get();
        // return new PostCategoryResource($post_categories);
        return PostCategoryResource::collection($post_categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post_category = $request->isMethod('put') ? PostCategory::findOrFail($request->postCategory_id) : new PostCategory;
        $post_category->name = $request->name;
        if($post_category->save()){
            return new PostCategoryResource($post_category);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post_category = PostCategory::findOrFail($id);
        return new PostCategoryResource($post_category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post_category = PostCategory::findOrFail($id);
        if($post_category->delete()){
            return new PostCategoryResource($post_category);
        }
    }
}
