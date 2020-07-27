<?php

namespace App\Http\Controllers\Messagerie;

use Illuminate\Http\Request;
use App\ChatMessage;
use App\ChatDiscussion;
use App\Models\APIError;
use App\Models\Person\User;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    
    public function getDiscussions($id)
    {
        $data = [];
        return response()->json($data, 200);
    }

    public function getNewMessages($id)
    {
        $user = Auth::user();
        return response()->json(null, 200);
    }

    public function deleteDiscussion($id) {
        
        return response()->json(200);
    }

    public function deleteMessage($id) {
        $message = ChatMessage::find($id);
        return response()->json(200);
    }

    public function newMessage(Request $request)
    {
        return response()->json(null, 200);
    }

    public function discussionMessage($id) {
        return response()->json(null, 200);
    }

}
