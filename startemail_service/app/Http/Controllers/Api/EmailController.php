<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailRequest;
use App\Services\EmailService;


class EmailController extends Controller
{
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendEmail(EmailRequest $request)
    {
        $data = $request->all();
        $result = $this->emailService->store($data);

        if ($result)
            return response()->json(['success' => 'Email saved and sent to queue.'], 201);

        return response()->json(['error' => 'Email Not saved and Not Sent to queue'], 500);
    }

    public function listAll()
    {
        $emails = $this->emailService->listAll();
        return response()->json($emails);
    }
}
