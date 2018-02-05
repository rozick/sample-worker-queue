<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\QueueClass;

class MessageController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function sendMessage(Request $request) {
        $data = $request->input('data');
        $message_type = $request->input('message_type');
        $category = $request->input('category');
        $queue = new QueueClass();
        $queue->createItem($data, $message_type, $category);
        
        return response()->json($queue->numberOfItems());
    }

    public function reserveMessage(Request $request) {
        $message_type = $request->input('message_type'); 
        $category = $request->input('category'); 
        $reserver_name = $request->input('reserver_name');
        $queue = new QueueClass();
        $queue->reserveItem($message_type, $category, $reserver_name);
        if (!$queue->canReserve) {
            return response()->json(['error' => 'you cannot reserve this item'], 403);
        }
        
        return response()->json($queue->reserved_item);
    }

    public function deleteMessage($item_id) {
        $queue = new QueueClass();
        $queue->deleteItem($item_id);
    }
}