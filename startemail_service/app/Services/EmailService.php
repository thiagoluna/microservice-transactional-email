<?php

namespace App\Services;

use App\Events\EmailStoredEvent;
use App\Events\FallbackEvent;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmailService
{
    public function listAll()
    {
        return Email::orderBy('id', 'DESC')->paginate(7);
    }

    public function store($data): bool
    {
        $email = Email::create($data);

        if ($email) {
            $email->content = $data['content'];
            //Push email to 'sendgrid' Topic
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

        //Fallback to a secondary service
        if ($payload->status == '500' || $payload->status == '503') {

            //Push email  to queue 'mailjet' Topic (secondary service)
            event(new FallbackEvent($email));

            $email->status = 'Fallback to Service 2';
        }

        //Update database - Frontend will show this info
        $email->update();

        $logMessage = "Email ID {$email->id} consumed from queue status Topic;service:{$email->service};status:{$email->status}";
        Log::channel('consumer')->info($logMessage);
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
