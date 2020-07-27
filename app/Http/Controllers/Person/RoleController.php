<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person\Role;
use App\Models\APIError;

class RoleController extends Controller
{
    public function index(Request $req)
    {
        $data = Role::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    //
    public function create(Request $req){

        $data = $req->except('image');

        $this->validate($data, [
            'name' => 'required|string|unique:roles'
        ]);

        $data = $req->except('image');

        //upload image
        if ($file = $req->file('image')) {
            $filePaths = $this->uploadSingleFile($req, 'image', 'role-images', ['file', 'mimes:jpg,png,gif']);
            $data['image'] = json_encode($filePaths);
        }

        $role = new Role();
        $role->name = $data["name"];
        if (isset($data['description'])) $role->description = $data["description"];
        if (isset($data['display_name'])) $role->display_name = $data["display_name"];
        if (isset($data['image'])) $role->image = $data["image"];
        $role->save();
    
        if(isset($data["permissions"])) $role->permissions()->sync($req->permissions);

        return response()->json($role);
    }

    public function update(Request $req, $id){

        $role = Role::find($id);
        if($role == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ROLE_NOT_FOUND");
            $apiError->setMessage("Role type with id " . $id . " not found");
            return response()->json($apiError, 404);
        }

        $data = $req->except('image');

        //upload image
        if ($file = $req->file('image')) {
            $filePaths = $this->uploadSingleFile($req, 'image', 'role-images', ['file', 'mimes:jpg,png,gif']);
            $data['image'] = json_encode($filePaths);
        }

        if (isset($data['name'])) $role->name = $data['name'];
        if (isset($data['description'])) $role->description = $data['description'];
        if (isset($data['display_name'])) $role->display_name = $data['display_name'];
        if (isset($data['image'])) $role->image = $data["image"];
        $role->update();

        if (isset($data['permissions'])) $role->permissions()->sync($req->permissions);

        return response()->json($role);
    }

    public function find($id) {
        $role = new Role();
        if(!$role = Role::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ROLE_NOT_FOUND");
            $apiError->setMessage("le role d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $role->permissions;
        return response()->json($role);
    }

   
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            $apiError = new APIError();
            $apiError->setStatus('404');
            $apiError->setCode("ROLE_NOT_FOUND");
            $apiError->setMessage("le role d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $role->delete();
        return response()->json(null);
    }
}
