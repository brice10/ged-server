<?php

namespace App\Http\Controllers\person;

use App\Http\Controllers\Controller;
use App\Models\person\PermissionUser;
use App\Models\person\Permission;
use App\Models\person\User;
use Illuminate\Http\Request;

class PermissionUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {       
        $data = PermissionUser::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $data = $req->all();
        $Permission_user = new PermissionUser();
        $Permission_user = PermissionUser::whereUserIdAndPermissionUser($user_id, $permission_id)->first();
        if($Permission_user)
        {
            $apiError = new APIError();
            $apiError->setStatus("400");
            $apiError->setCode("BAD_REQUEST");
            $apiError->setMessage("cette relation existe deja");
            return response()->json($apiError, 400);
        } 
       $this->validate($data, [
            'permission_id' => 'required',
            'user_id' => 'required'
        ]);

        $Permission_user->$permission_id = $data['permission_id'];
        $Permission_user->$user_id = $data['user_id'];

        $Permission_user->save();

        return response()->json($Permission_user);
    }
    
    public function find($user_id, $permission_id)
    { 
        if (!$permissionUser = PermissionUser::whereUserIdAndPermissionId($user_id, $permission_id)->first()) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("PERMISSION_USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $user_id n'a pas la permission d'id $permission_id ");
            return response()->json($apiError, 404);
        }
        return response()->json($permissionUser);
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\person\PermissionUser  $permissionUser
     * @return \Illuminate\Http\Response
     */


    
}

