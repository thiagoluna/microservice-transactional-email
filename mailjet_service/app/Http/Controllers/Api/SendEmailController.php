<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mailjet\Resources;

class SendEmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $nameTo         = $request->get('name');
        $emailTo        = $request->get('email');
        $subject        = $request->get('subject');
        $content        = $request->get('content');
        $sentFromEmail   = $request->get('sentFromEmail');
        $sentFromName    = $request->get('sentFromName');

        $mj = new \Mailjet\Client(env('MAILJET_KEY'),env('MAILJET_SECRET'),true,['version' => 'v3']);
        $body_v31 = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $sentFromEmail,
                        'Name'  => $sentFromName
                    ],
                    'To' => [
                        [
                            'Email' => $emailTo,
                            'Name'  => $nameTo
                        ]
                    ],
                    'Subject'   => $subject,
                    'TextPart'  => $content,
                    'HTMLPart'  => $content
                ]
            ]
        ];

        $body = [
            'FromEmail' => $sentFromEmail,
            'FromName' => $sentFromName,
            'Recipients' => [
                [
                    'Email' => $emailTo,
                    'Name' => $nameTo
                ]
            ],
            'Subject'    => $subject,
            'Text-part'  => $content,
            'Html-part'  => $content
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());

        $email = $response->getData();
        $id = $email['Sent'][0]['MessageID'];
        $id = preg_replace("/[^0-9]/", "", $id);

        $response = $mj->get(Resources::$Message, ['id' => $id]);
        $response->success() && var_dump($response->getData());

        $email = $response->getData();

        return response()->json(['success' => "Email sent. CodeStatus: {$email[0]['Status']}"], 200);
    }
}
