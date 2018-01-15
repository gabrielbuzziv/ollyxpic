<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{

    public function sendContact()
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $data = request()->all();
        $data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'user_message' => $data['message']
        ];

        Mail::send('email', $data, function ($message) use ($data) {
            $message->from($data['email']);
            $message->subject($data['subject']);
            $message->replyTo($data['email']);
            $message->to('ollyxpic@gmail.com');
        });
    }
}
