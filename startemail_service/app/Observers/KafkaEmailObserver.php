<?php

namespace App\Observers;

use App\Models\Email;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class KafkaEmailObserver
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
     * Handle the email "created" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function created(Email $email)
    {
        $email->content = request('content');

        //Push email data to 'emails' Topic
        $this->producer->produce($email->toJson());

    }

    /**
     * Handle the email "updated" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function updated(Email $email)
    {
        //
    }

    /**
     * Handle the email "deleted" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function deleted(Email $email)
    {
        //
    }

    /**
     * Handle the email "restored" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function restored(Email $email)
    {
        //
    }

    /**
     * Handle the email "force deleted" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function forceDeleted(Email $email)
    {
        //
    }
}
