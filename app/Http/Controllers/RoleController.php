<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use App\Http\Resources\Role as RoleResource;
use App\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::paginate(15);
        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $exists = DB::table('roles')
        ->where('name', $name)->exists();

        if ($exists) {
            return response()->json(['error' => true, 'message' => 'This role exists already']);
        }

        $role = new Role;
        $role->name = $name;
        
        if ($role->save()) {
            return new RoleResource($role);
        }
    }
}
