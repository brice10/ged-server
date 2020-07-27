<?php

namespace App\Http\Controllers\person;

use App\Http\Controllers\Controller;
use App\Models\person\RoleUser;
use App\Models\person\User;
use App\Models\person\Role;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {       
        $data = RoleUser::simplePaginate($req->has('limit') ? $req->limit : 15);
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

        $roleUser = RoleUser::whereUserIdAndRoleId($user_id, $role_id)->first();
        if($roleUser){
            $apiError = new APIError();
            $apiError->setStatus("400");
            $apiError->setCode("BAD_REQUEST");
            $apiError->setMessage("cette relation existe deja");
            return response()->json($apiError, 400);
        } 
       $this->validate($data, [
            'user_id' => 'required',
            'role_id' => 'required'
        ]);

        $roleUser->$user_id = $data['user_id'];
        $roleUser->$role_id = $data['role_id'];

        $roleUser->save();

        return response()->json($roleUser);
    }
    
    public function find($user_id,$role_id)
    {   
       if (!$roleUser = RoleUser::find($user_id,$role_id)->first()) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("ROLE_USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $user_id n'a pas le role d'id $role_id ");
            return response()->json($apiError, 404);
        }
        return response()->json($roleUser);
    }
}
