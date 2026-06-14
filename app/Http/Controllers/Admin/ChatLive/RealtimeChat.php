<?php

namespace App\Http\Controllers\Admin\ChatLive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Events\MessageSent;

class RealtimeChat extends BaseController
{
    public function index(Request $request)
    {
        try {

            $request->validate([
                'project_id' => 'required',
                'message' => 'required'
            ]);

            $result = DB::select("CALL InsertChatMessage_xx26(?, ?, ?, ?)", [
                $request->project_id,
                auth()->id() ?? 1,
                $request->message,
                'text'
            ]);

            if (!$result || count($result) == 0) {
                return response()->json([
                    'error' => 'Procedure tidak return data'
                ], 500);
            }

            $messageId = $result[0]->id ?? null;

            if (!$messageId) {
                return response()->json([
                    'error' => 'ID tidak ditemukan dari procedure'
                ], 500);
            }

            $message = DB::table('chat_messages_xx26')
                ->where('id', $messageId)
                ->first();
            
            broadcast(new MessageSent($message))->toOthers();

            return response()->json($message);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function getHistoryChat($id)
    {
        try {            
            $history = DB::select('CALL sp_get_history_chat_with_project_progress(?)', [$id]);

            return response()->json([
                'status' => 'success',
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}