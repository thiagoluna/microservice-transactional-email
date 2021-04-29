<?php


namespace App\Services;


use SendGrid\Mail\Mail;

class SendGridService
{
    public function sendEmail($payload)
    {
        $id             = $payload->id;
        $nameTo         = $payload->name;
        $emailTo        = $payload->email;
        $subject        = $payload->subject;
        $content        = $payload->content;

        $email = new Mail();
        $email->setFrom(env('SENDGRID_FROM_EMAIL'), env('SENDGRID_FROM_NAME'));
        $email->setSubject($subject);
        $email->addContent(
            "text/html", $content
        );
        $email->addTo($emailTo, $nameTo);

        $sendgrid = new \SendGrid(env('SENDGRID_KEY'));
        try {
            $response = $sendgrid->send($email);
        } catch (\Exception $e) {
            echo 'Caught exception: ' .  $e->getMessage() . "\n";
        }

        $payload            = [];
        $payload['id']      = $id;
        $payload['name']    = $nameTo;
        $payload['email']   = $emailTo;
        $payload['subject'] = $subject;
        $payload['content'] = $content;
        $payload['service'] = 'SendGrid';
        $payload['status']  = $response->statusCode();

        return $payload;
    }
}
