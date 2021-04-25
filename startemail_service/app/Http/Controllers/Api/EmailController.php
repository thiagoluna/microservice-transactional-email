<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class EmailController extends Controller
{
    private $topicConf;
    private $brokerCollection;
    private $producer;

    public function __construct(ContainerInterface $container)
    {
        $this->topicConf = $container->get("KafkaTopicConfig");
        $this->brokerCollection = $container->get("KafkaBrokerCollection");

        $this->producer = new KafkaProducer(
            $this->brokerCollection,
            "emails",
            $this->topicConf
        );
    }

    public function sendEmail(Request $request)
    {
        $email = $request->all();

        $email = Email::create($email);
        $email->sentFromEmail = $request->get('sentFromEmail');
        $email->sentFromName = $request->get('sentFromName');
        $email->content = $request->get('content');

        $this->producer->produce($email->toJson());

        return response()->json(['success' => 'Email saved'], 201);
    }

    public function listEmail()
    {
        $emails = Email::All();

        return response()->json($emails);
    }
}
