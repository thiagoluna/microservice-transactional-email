<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $email = $request->all();

        Email::create($email);

        return response()->json(['success' => 'Email saved'], 201);
    }

    public function listEmail()
    {
        $emails = Email::All();

        return response()->json($emails);
    }
}
