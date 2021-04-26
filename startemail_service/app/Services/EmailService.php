<?php

namespace App\Services;

use App\Models\Email;

class EmailService
{
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function update()
    {
        $email = Email::find($this->payload->id);
        $email->status = $this->payload->status;
        $email->service = $this->payload->service;

        $email->update();
    }
}
