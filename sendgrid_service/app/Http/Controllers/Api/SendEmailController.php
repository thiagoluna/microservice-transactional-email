<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SendGrid\Mail\Mail;

class SendEmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $name           = $request->get('name');
        $emailTo        = $request->get('email');
        $subject        = $request->get('subject');
        $content        = $request->get('content');
        $setFromEmail   = $request->get('setFromEmail');
        $setFromName    = $request->get('setFromName');

        $email = new Mail();
        $email->setFrom($setFromEmail, $setFromName);
        $email->setSubject($subject);
        $email->addContent(
            "text/html", $content
        );
        $email->addTo($emailTo, $name);

        $sendgrid = new \SendGrid(env('SENDGRID_KEY'));
        try {
            $response = $sendgrid->send($email);
            echo 'StatusCode: ' . $response->statusCode() . "\n";
            print $response->body() . "\n";
        } catch (\Exception $e) {
            echo 'Caught exception: ' .  $e->getMessage() . "\n";
        }
    }
}
