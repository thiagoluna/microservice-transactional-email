<?php

namespace Tests\Feature\Events;

use App\Events\EmailStoredEvent;
use App\Models\Email;
use App\Services\EmailService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailService = new EmailService();
    }

    public function test_event_pub_email_to_kafka()
    {
        $email = [
            'name' => 'Takeaway',
            'email' => 'contact@email.com',
            'subject' => 'Email Test',
            'content' => 'Content Test',
        ];

        Event::fake();
        $this->emailService->store($email);

        Event::assertDispatched(EmailStoredEvent::class);
    }
}
