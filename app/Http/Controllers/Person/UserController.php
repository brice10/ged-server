<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person\User;
use App\Models\APIError;

class UserController extends Controller
{
    public function index(Request $req)
    {
        $data = User::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);
        $data = User::where($req->field, 'like', "%$req->q%")->simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    public function create(Request $req)
    {
        $data = $req->except('avatar');

        $this->validate($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'login' => 'required|unique:users',
            'email' => ['required', 'email', Rule::unique('users', 'email')]
        ]);
       
        $data['password'] = bcrypt($data['password']);
        $data['avatar'] = '';
        //upload image
        if ($file = $req->file('avatar')) {
            $filePaths = $this->uploadSingleFile($req, 'avatar', 'users-avatar', ['file', 'mimes:jpg,png,gif']);
            $data['avatar'] = json_encode($filePaths);
        }
        $user = new User();
        $user->login = $data['login'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->gender = $data['gender'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        if (isset($data['avatar'])) 
            $user->avatar = $data['avatar'];
        if (isset($data['birth_date'])) 
            $user->birth_date = $data['birth_date'];
        if (isset($data['birth_place'])) 
            $user->birth_place = $data['birth_place'];
        $user->save();

        return response()->json($user);
    }

    public function find($id) {
        if(!$user = User::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $user->permissions;
        $user->roles;
        return response()->json($user);
    }

    public function update(Request $req, $id)
    {
        $user = User::find($id);
        if (!$user) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        $data = $req->except('avatar');

        if (isset($data['password']) && strlen($data['password']) >= 4) {
            $data['password'] = bcrypt($data['password']);
        }

        //upload image
        if ($file = $req->file('avatar')) {
            $filePaths = $this->uploadMultipleFiles($req, 'avatar', 'users-avatar', ['file', 'mimes:jpg,png,gif']);
            $data['avatar'] = json_encode($filePaths);
        }

        if (isset($data['login'])) 
            $user->login = $data['login'];
        if (isset($data['email'])) 
            $user->email = $data['email'];
        if (isset($data['password'])) 
            $user->password = $data['password'];
        if (isset($data['gender'])) 
            $user->gender = $data['gender'];
        if (isset($data['first_name'])) 
            $user->first_name = $data['first_name'];
        if (isset($data['last_name'])) 
            $user->last_name = $data['last_name'];
        if (isset($data['birth_date'])) 
            $user->birth_date = $data['birth_date'];
        if (isset($data['birth_place'])) 
            $user->birth_place = $data['birth_place'];
        if (isset($data['avatar'])) 
            $user->avatar = $data['avatar'];
        if (isset($data['tel']))
             $user->tel = $data['tel'];

        $user->update();

        return response()->json($user);
    }

    public function destroy($id)
    {
        if (!$user = User::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $user->delete();

        return response()->json(null);
    }

   // $permission_user = PermissionUser::whereUserIdAndPermissionId($user_id, $permission_id)->first();
   // if($permission_user) //creer une apiError avec code 400 badREquest
   // je cree les relations
}
