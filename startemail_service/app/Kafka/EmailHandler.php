<?php


namespace App\Kafka;


use App\Services\EmailService;
use PHPEasykafka\KafkaConsumerHandlerInterface;

class EmailHandler implements KafkaConsumerHandlerInterface
{

    public function __invoke(\RdKafka\Message $message, \RdKafka\KafkaConsumer $consumer)
    {
        //echo $message->payload;
        //print_r(json_decode($message->payload));
        $payload = json_decode($message->payload);

        $emailService = new EmailService($payload);
        $emailService->update();
        $consumer->commit();
    }
}
