<?php

namespace App\Http\Controllers\Api;

use App\Role;
use Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
     /**
     * Return a paginated list of posts.
     *
     * @return RoleResource
     */
    public function index()
    {
        $roles = Role::latest('created_at')->get();
        return RoleResource::collection($roles);
    }

    /**
     * Fetch and return the Post.
     *
     * @param Role $role
     * @return RoleResource
     */
    public function show($id)
    {
        // return new PostResource($post);
        $role = Role::findOrFail($id);
        return new RoleResource($role);
    }

    /**
     * Validate and save a new signature to the database.
     *
     * @param Request $request
     * @return RoleResource
     */
    public function store(Request $request)
    {
        $role = $request->isMethod('put') ? Role::findOrFail($request->role_id) : new Role;
        $role->name = $request->name;
        if($role->save()) {
            return new RoleResource($role);
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
        $role = Role::findOrFail($id);
        if($role->delete()) {
            return new RoleResource($role);
        }    
    }
}
