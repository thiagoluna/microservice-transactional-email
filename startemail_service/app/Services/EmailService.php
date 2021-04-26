<?php

namespace App\Services;

use App\Models\Email;
use Illuminate\Http\Request;

class EmailService
{
    public function listAll()
    {
        return Email::all();
    }

    public function store(Request $request): Email
    {
        $email = $request->all();
        return Email::create($email);
    }

    public function update($payload): void
    {
        $email = Email::find($payload->id);
        $email->status = $payload->status;
        $email->service = $payload->service;

        $email->update();
    }
}
