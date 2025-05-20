<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageReply;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('replies')->latest()->get();
        return view('admin.messages.index', compact('messages'));
    }

    public function reply(Request $request, Message $message)
    {
        // Yeni yanıtı kaydet
        $message->replies()->create([
            'reply' => $request->reply
        ]);

        // Mesajı yanıtlandı olarak işaretle
        $message->update([
            'is_replied' => true
        ]);

        return redirect()->back()->with('success', 'Yanıt başarıyla gönderildi!');
    }

    public function show(Message $message)
    {
        $message->load('replies');
        
        return response()->json([
            'message' => $message->message,
            'subject' => $message->subject,
            'name' => $message->name,
            'email' => $message->email,
            'replies' => $message->replies->map(function($reply) {
                return [
                    'reply' => $reply->reply,
                    'date' => $reply->created_at->format('d.m.Y H:i')
                ];
            })
        ]);
    }
}
