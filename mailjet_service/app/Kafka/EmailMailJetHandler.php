<?php


namespace App\Kafka;


use App\Services\MailjetService;
use Illuminate\Support\Facades\Log;
use PHPEasykafka\KafkaConsumerHandlerInterface;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class EmailMailJetHandler implements KafkaConsumerHandlerInterface
{
    private $topicConf;
    private $brokerCollection;
    private $producer;
    private $mailjetService;

    public function __construct(ContainerInterface $container, MailjetService $mailjetService)
    {
        $this->mailjetService = $mailjetService;

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
        echo $message->payload;
        $payload = json_decode($message->payload);

        $logMessage = "Email ID {$payload->id} consumed from queue emails Topic";
        Log::channel('consumer')->info($logMessage);

        $statusSentEmail = $this->mailjetService->SendEmail($payload);

        //Push to queue status topic the status of Email sent
        $this->producer->produce(json_encode($statusSentEmail));
        $logMessage = "Email ID {$payload->id} published to queue in status Topic";
        Log::channel('publisher')->info($logMessage);
    }
}
