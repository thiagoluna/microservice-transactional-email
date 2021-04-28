<?php

namespace App\Services;

use App\Events\EmailStoredEvent;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailService
{
    public function listAll()
    {
        return Email::orderBy('id', 'DESC')->get();
    }

    public function store($data): bool
    {
        $email = Email::create($data);

        if ($email) {
            $email->content = $data['content'];
            event(new EmailStoredEvent($email));

            return true;
        }

        return false;
    }

    public function update($payload): void
    {
        $email = Email::find($payload->id);
        $email->status = $payload->status;
        $email->service = $payload->service;

        $email->update();
    }

    public function validateEmail($email): bool
    {
        $validator = Validator::make(
            ['email' => $email],
            ['email' => 'email']
        );

        if ($validator->fails())
            return false;

        return true;
    }
}
