<?php

namespace App\Http\Controllers\person;

use App\Http\Controllers\Controller;
use App\Models\person\Permission;
use Illuminate\Http\Request;
use App\Models\APIError;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *@author Nguedia Daniela
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {       
        $data = Permission::simplePaginate($req->has('limit') ? $req->limit : 15);
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

        $this->validate($data, [
            'name' => 'required|unique:permissions'
        ]);

        $permission = new Permission();
        $permission->name = $data['name'];
        if(isset($data['description']))
            $permission->description = $data['description'];
        if(isset($data['display_name']))
            $permission->display_name = $data['display_name'];

        $permission->save();

        return response()->json($permission);
    }

    public function find($id)
    {
        $permission = new Permission();
        if (!$permission = Permission::find($id)) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("PERMISSION_NOT_FOUND");
            $apiError->setMessage("la permission d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        return response()->json($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\person\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $data = $req->all();
        $permission = new Permission();
        if (!$permission = Permission::find($id)) {
            $apiError = new APIError();
            $apiError->setStatus('404');
            $apiError->setCode("PERMISSION_NOT_FOUND");
            $apiError->setMessage("la permission d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
       
        if (isset($data['name'])) $permission->name = $data['name'];
        if (isset($data['description'])) $permission->description = $data['description'];
        if (isset($data['display_name'])) $permission->display_name = $data['display_name'];
        $permission->update();

        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\person\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            $apiError = new APIError();
            $apiError->setStatus('404');
            $apiError->setCode("PERMISSION_NOT_FOUND");
            $apiError->setMessage("la permission d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $permission->delete();
        return response()->json(null);
    }
}