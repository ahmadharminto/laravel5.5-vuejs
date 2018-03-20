<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\ChatEvent;
use App\Http\Models\User;
use Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send(Request $request)
    {
        $user = User::find(Auth::id());
        $this->saveToSession($request);
        event(new ChatEvent($request->message, $user->username));
    }

    public function saveToSession(Request $request)
    {
        session()->put('chat', $request->chat);
    }

    public function messages(Request $request)
    {
        return response()->json(session('chat'));
    }

    public function destroySession(Request $request)
    {
        session()->forget('chat');
    }
}
