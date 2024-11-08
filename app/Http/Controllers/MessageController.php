<?php
namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
            'project_id' => 'required|exists:projects,id',
        ]);

        $message = Message::create($request->all());
        return response()->json($message, 201);
    }

    public function index($project_id): JsonResponse
    {
        $messages = Message::where('project_id', $project_id)->get();
        return response()->json($messages, 200);
    }

    public function listAll(): JsonResponse
    {
        $messages = Message::all();
        return response()->json($messages, 200);
    }
}
