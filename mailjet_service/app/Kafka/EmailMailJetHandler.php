<?php


namespace App\Kafka;


use App\Services\MailjetService;
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
        $payload = json_decode($message->payload);
        print_r($payload);

        $statusSentEmail = $this->mailjetService->SendEmail($payload);

        //Produce messagoe in Status Topic
        $this->producer->produce(json_encode($statusSentEmail));
    }
}
