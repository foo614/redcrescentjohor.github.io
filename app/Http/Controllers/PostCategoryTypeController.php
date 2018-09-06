<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostCategoryType;

class PostCategoryTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post_category_types = PostCategoryType::all();
        return view('post_category_types.index',compact('post_category_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post_category_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
        $category = new PostCategoryType;
        $category->name = $request->name;
        $category->save();

        $notification = array(
            'message' => $category->name.' is created',
            'title' => 'Add post category type',
            'alert-type' => 'success'
        );
        return redirect()->route('post_category_types.index')
            ->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('post_category_types');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post_category_type = PostCategoryType::findOrFail($id);
        return view('post_category_types.edit', compact('post_category_type'));
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
        $this->validate($request, [
            'name'=>'required|max:100',
        ]);

        $category = PostCategoryType::findOrFail($id);
        $category->name = $request->input('name');
        $category->save();

        $notification = array(
            'message' => $category->name.' is updated',
            'title' => 'Edit post category type',
            'alert-type' => 'success'
        );    
        return redirect()->route('post_category_types.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = PostCategoryType::findOrFail($id);
        $category->delete();

        $notification = array(
            'message' => $category->name.' is deleted',
            'title' => 'Delete post category type',
            'alert-type' => 'success'
        );
        return redirect()->route('post_category_types.index')
            ->with($notification);
    }
}
