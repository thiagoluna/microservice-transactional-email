<?php


namespace App\Kafka;


use App\Services\SendGridService;
use PHPEasykafka\KafkaConsumerHandlerInterface;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class EmailHandler implements KafkaConsumerHandlerInterface
{
    private $topicConf;
    private $brokerCollection;
    private $producer;
    private $sendGridService;

    public function __construct(ContainerInterface $container, SendGridService $sendGridService)
    {
        $this->sendGridService = $sendGridService;

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

        $statusSentEmail = $this->sendGridService->sendEmail($payload);

        //$consumer->commit();

        $this->producer->produce(json_encode($statusSentEmail));
    }
}
