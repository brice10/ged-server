<?php

namespace App\Http\Controllers\person;

use App\Http\Controllers\Controller;
use App\Models\person\PermissionRole;
use App\Models\person\Permission;
use App\Models\person\Role;
use Illuminate\Http\Request;
use App\Models\APIError;

class PermissionRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {       
        $data = PermissionRole::simplePaginate($req->has('limit') ? $req->limit : 15);
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
        $permissionRole = new PermissionRole();
        $permissionRole = PermissionRole::wherePermissionIdAndRoleID($permission_id, $role_id)->first();
        if($permissionRole){
            $apiError = new APIError();
            $apiError->setStatus("400");
            $apiError->setCode("BAD_REQUEST");
            $apiError->setMessage("cette relation existe deja");
            return response()->json($apiError, 400);
        } //creer une apiError avec code 400 badREquest

   // je cree les relations
       $this->validate($data, [
            'permission_id' => 'required',
            'role_id' => 'required'
        ]);

        $permissionRole->$permission_id = $data['permission_id'];
        $permissionRole->$role_id = $data['role_id'];

        $permissionRole->save();

        return response()->json($permissionRole);
    }
    
    public function find($permission_id,$role_id)
    {   
        $permissionRole = new PermissionRole();  
        if (!$permissionRole = PermissionRole::wherePermissionIdAndRoleId($permission_id,$role_id)->first()) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("PERMISSION_ROLE_NOT_FOUND");
            $apiError->setMessage("l'role d'id $role_id n'a pas la permission d'id $permission_id ");
            return response()->json($apiError, 404);
        }
        return response()->json($permissionRole);

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\person\PermissionRole  $permissionRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $permission_id, $role_id)
    {
        $data = $req->all();
        $permissionRole = new PermissionRole();
        if (!$permissionRole = PermissionRole::wherePermissionIdAndRoleId($permission_id, $role_id)) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("PERMISSION_USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $role_id n'a pas la permission d'id $permission_id ");
            return response()->json($apiError, 404);
        }
        $permissionRole->update();
        return response()->json($permissionRole);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\person\PermissionRole  $permissionRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($permission_id, $role_id)
    {
        
        $permissionRole = PermissionRole::wherePermissionIdAndRoleId($permission_id, $role_id);
        if (!$permissionRole) {
            $apiError = new APIError();
            $apiError->setStatus('404');
            $apiError->setCode("PERMISSION_ROLE_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $role_id n'a pas la permission d'id $permission_id ");
            return response()->json($apiError, 404);
        }
        $permissionRole->delete();
        return response()->json(null);
    }
}
