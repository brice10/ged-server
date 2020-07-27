<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workflow\Workflow;
use App\Models\Persons\User;
use App\Models\APIError;

class WorkflowController extends Controller
{
    public function index(Request $req)
    {
        $data = Workflow::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);
        $data = Workflow::where($req->field, 'like', "%$req->q%")->simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    public function create(Request $req)
    {
        $data = $req->only(['description', 'user_id', 'original_file', 'treated_file']);

        $this->validate($data, [
            'description' => 'required',
            'user_id' => 'required:exists:users:id',
            'original_file' => 'required|file',
            'treated_file' => 'required|file'
        ]);

        //$req->treated_file = $req->original_file;
        //upload du fichier
        if ($file = $req->file('original_file')) {
            $filePaths = $this->uploadSingleFile($req, 'original_file', 'workflow-original-files', ['file', 'mimes:pdf']);
            $data['original_file'] = json_encode($filePaths['saved_file_path']);

            if ($file = $req->file('treated_file')) {
                $filePaths = $this->uploadSingleFileWithFileName($req, 'treated_file', $filePaths['file_name'], 'workflow-treated-files', ['file', 'mimes:pdf']);
                $data['treated_file'] = json_encode($filePaths['saved_file_path']);
            }
        }

        $workflow = new Workflow();
        $workflow->description = $data['description'];
        $workflow->original_file = $data['original_file'];
        $workflow->treated_file = $data['treated_file'];
        $workflow->status = 'PENDING';
        $workflow->user_id = $data['user_id'];
        $workflow->track_id = $this->generateTrackId();

        $workflow->save();

        return response()->json($workflow);
    }
    //generation du track-id
    public function generateTrackId() {
        $rand = -1;
        do {
            $rand = random_int(1000 , 9999);
        } while($workflow = Workflow::whereTrackId($rand)->first());
        return $rand;
    }

    public function find($id) {
        if(!$workflow = Workflow::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" WORKFLOW_NOT_FOUND");
            $apiError->setMessage("Le workflow d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        return response()->json($workflow);
    }

    public function update(Request $req, $id)
    {
        $workflow = Workflow::find($id);
        if (!$workflow) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" WORKFLOW_NOT_FOUND");
            $apiError->setMessage("le workflow d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        $data = $req->only(['description' ,'treated_file']);

        
        if (isset($data['description'])) 
            $workflow->login = $data['login'];
        if (isset($data['treated_file'])) 
            $workflow->email = $data['email'];

        $workflow->update();

        return response()->json($workflow);
    }

    public function destroy($id)
    {
        if (!$workflow = Workflow::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" WORKFLOW_NOT_FOUND");
            $apiError->setMessage("Le workflow d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $workflow->delete();

        return response()->json(null);
    }

    public function getStatus($id)
    {
        if (!$workflow = Workflow::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" WORKFLOW_NOT_FOUND");
            $apiError->setMessage("Le workflow d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        return response()->json($workflow->status);
    }

    


   // $permission_workflow = PermissionWorkflow::whereWorkflowIdAndPermissionId($workflow_id, $permission_id)->first();
   // if($permission_workflow) //creer une apiError avec code 400 badREquest
   // je cree les relations
}
