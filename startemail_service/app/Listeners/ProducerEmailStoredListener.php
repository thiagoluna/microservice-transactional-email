<?php

namespace App\Listeners;

use App\Events\EmailStoredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class ProducerEmailStoredListener
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

    /**
     * Handle the event.
     *
     * @param  EmailStoredEvent  $event
     * @return void
     */
    public function handle(EmailStoredEvent $event)
    {
        $email = $event->getEmail();

        //Push email email to 'emails' Topic
        $this->producer->produce($email->toJson());
        $logMessage = "Email ID {$email->id} published to queue in emails Topic";
        Log::channel('publisher')->info($logMessage);
    }
}
