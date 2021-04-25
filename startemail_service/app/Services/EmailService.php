<?php

namespace App\Services;

use App\Models\Email;

class EmailService
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function update()
    {
        $email = Email::find($this->data->id);

        $email->status = $this->data->status;
        $email->save();
    }
}
