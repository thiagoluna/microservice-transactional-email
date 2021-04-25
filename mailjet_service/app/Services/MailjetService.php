<?php


namespace App\Services;


use Mailjet\Resources;

class MailjetService
{
    public function SendEmail($payload)
    {
        $id             = $payload->id;
        $nameTo         = $payload->name;
        $emailTo        = $payload->email;
        $subject        = $payload->subject;
        $content        = $payload->content;

        $body = [
            'FromEmail' => env('MAILJET_FROM_EMAIL'),
            'FromName' => env('MAILJET_FROM_NAME'),
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

        $mj = new \Mailjet\Client(env('MAILJET_KEY'),env('MAILJET_SECRET'),true,['version' => 'v3']);
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());

        $email = $response->getData();
        $id_message = $email['Sent'][0]['MessageID'];
        $id_message = preg_replace("/[^0-9]/", "", $id_message);

        sleep(15);
        $response = $mj->get(Resources::$Message, ['id' => $id_message]);

        $email = $response->getData();
        $status = ($email[0]['Status']);

        $payload            = [];
        $payload['id']      = $id;
        $payload['status']  = $status;
        $payload['service'] = 'Mailjet';

        return $payload;
    }
}
