<?php


namespace App\Kafka;


use App\Services\EmailService;
use PHPEasykafka\KafkaConsumerHandlerInterface;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;
use SendGrid\Mail\Mail;

class EmailHandler implements KafkaConsumerHandlerInterface
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
            "status",
            $this->topicConf
        );
    }

    public function __invoke(\RdKafka\Message $message, \RdKafka\KafkaConsumer $consumer)
    {
        $payload = json_decode($message->payload);

        $id             = $payload->id;
        $name           = $payload->name;
        $emailTo        = $payload->email;
        $subject        = $payload->subject;
        $content        = $payload->content;
        $sentFromEmail  = $payload->sentFromEmail;
        $sentFromName   = $payload->sentFromName;

        $email = new Mail();
        $email->setFrom($sentFromEmail, $sentFromName);
        $email->setSubject($subject);
        $email->addContent(
            "text/html", $content
        );
        $email->addTo($emailTo, $name);

        $sendgrid = new \SendGrid(env('SENDGRID_KEY'));
        try {
            $response = $sendgrid->send($email);
        } catch (\Exception $e) {
            echo 'Caught exception: ' .  $e->getMessage() . "\n";
        }

        //$consumer->commit();
        $payload = [];
        $payload['id'] = $id;
        $payload['status'] = $response->statusCode();

        $this->producer->produce(json_encode($payload));
    }
}
