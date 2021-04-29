<?php

namespace App\Listeners;

use App\Events\EmailStoredEvent;
use App\Events\FallbackEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class ProducerSecondaryServiceFallbackListener
{
    private $topicConf;
    private $brokerCollection;
    private $producer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->topicConf = $container->get("KafkaTopicConfig");
        $this->brokerCollection = $container->get("KafkaBrokerCollection");

        $this->producer = new KafkaProducer(
            $this->brokerCollection,
            "mailjet",
            $this->topicConf
        );
    }

    /**
     * Handle the event.
     *
     * @param  FallbackEvent  $event
     * @return void
     */
    public function handle(FallbackEvent $event)
    {
        $email = $event->getEmail();

        //Push email  to 'mailjet' Topic
        $this->producer->produce($email->toJson());
        $logMessage = "Email ID {$email->id} published to queue in mailjet Topic";
        Log::channel('publisher')->info($logMessage);
    }
}
