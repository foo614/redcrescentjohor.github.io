<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostCategory;

class PostCategoryController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.postCategory');
    }
}