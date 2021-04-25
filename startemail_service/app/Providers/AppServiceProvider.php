<?php

namespace App\Providers;

use App\Models\Email;
use App\Observers\KafkaEmailObserver;
use Illuminate\Support\ServiceProvider;
use PHPEasykafka\Broker;
use PHPEasykafka\BrokerCollection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Register KafkaBrokerCollection to get data from brokers throw dependencies injection of Laravel
         * Return all Brokers tha are running in the app
         */
        $this->app->bind("KafkaBrokerCollection", function () {
            $broker = new Broker(env("KAFKA_HOST","kafka"), env("KAFKA_PORT","9092"));
            $kafkaBrokerCollection = new BrokerCollection();
            $kafkaBrokerCollection->addBroker($broker);
            return $kafkaBrokerCollection;
        });

        /**
         * Register Topic Settings
         */
        $this->app->bind("KafkaTopicConfig", function () {
            return [
                'topic' => [
                    'auto.offset.reset' => 'largest'
                ],
                'consumer' => [
                    'enable.auto.commit' => "true",
                    'auto.commit.interval.ms' => "100",
                    'offset.store.method' => 'broker'
                ]
            ];
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Email::observe(KafkaEmailObserver::class);
    }
}
